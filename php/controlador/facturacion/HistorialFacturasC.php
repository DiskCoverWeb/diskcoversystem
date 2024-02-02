<?php
/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * FECHA CREACION : 03/01/2024
 * FECHA MODIFICACION : 29/01/2024
 * DESCIPCION : Clase que se encarga de manejar la interfaz de la pantalla de recaudacion de bancos CxC   
 */

include(dirname(__DIR__, 2) . '/modelo/facturacion/HistorialFacturasM.php');
require_once(dirname(__DIR__, 3) . '/lib/phpmailer/enviar_emails.php');

$controlador = new HistorialFacturasC();
if (isset($_GET['CheqAbonos_Click'])) {
    echo json_encode($controlador->CheqAbonos_Click());
}

if (isset($_GET['CheqCxC_Click'])) {
    echo json_encode($controlador->CheqCxC_Click());
}

if (isset($_GET['Historico_Facturas'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Historico_Facturas($parametros));
}

if (isset($_GET['Form_Activate'])) {
    echo json_encode($controlador->Form_Activate());
}

class HistorialFacturasC
{
    private $modelo;
    private $email;

    function __construct()
    {
        $this->modelo = new HistorialFacturasM();
        //$this->email = new enviar_emails();
    }

    function CheqAbonos_Click()
    {
        return $this->modelo->CheqAbonos_Click();
    }

    function CheqCxC_Click()
    {
        return $this->modelo->CheqCxC_Click();
    }

    function ToolBarMenu_ButtonClick($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqCxC = $parametros['CheqCxC'];
        $ListCliente = $parametros['ListCliente'];

        FechaValida($MBFechaI);
        FechaValida($MBFechaF);

        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);

        $Mifecha = $FechaIni;
        $FechaTexto = $FechaFin;

        $FA['Fecha_Corte'] = $MBFechaF;
        $FA['Fecha_Desde'] = $MBFechaI;
        $FA['Fecha_Hasta'] = $MBFechaF;

        $PorCxC = false;

        if ($CheqCxC == 1) {
            $PorCxC = true;
        }

        if ($ListCliente == "Todos") {
            $FA['TC'] = G_NINGUNO;
            $FA['Serie'] = G_NINGUNO;
            $FA['Factura'] = 0;
        }

        $Total = 0;
        $Abono = 0;
        return $FA;
    }

    function Historico_Facturas($parametros)
    {
        //print_r($parametros);
        $FA = $this->ToolBarMenu_ButtonClick($parametros);
        Actualizar_Abonos_Facturas_SP($FA);

        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqCxC = $parametros['CheqCxC'];

        $Opcion = 1;
        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);

        $DGQuery = "HISTORIAL DE FACTURAS";
        $Label2 = " Facturado";
        $Label4 = " Cobrado";
        $Label3 = " Saldo";
        $PorCxC = false;

        if ($CheqCxC == 1) {
            $PorCxC = true;
        }

        $Total = 0;
        $Abono = 0;
        $Saldo = 0;

        $sSQL = $this->modelo->Historico_Facturas($FechaFin);
        $this->Totales_CxC_Abonos($Opcion, $sSQL);

        return $sSQL;
    }

    function Totales_CxC_Abonos($Opcion, $AdoQuery)
    {
        $Total = 0;
        $Abono = 0;
        $Saldo = 0;

        foreach ($AdoQuery as $valor) {
            if ($valor['T'] != G_ANULADO) {
                switch ($Opcion) {
                    case 1:
                        $Total += $valor['Total'];
                        $Abono += $valor['Total_Abonos'];
                        break;
                    case 6:
                    case 7:
                        $Total += $valor['Abono'];
                        break;
                    case 9:
                    case 10:
                        $Total += $valor['Total_MN'];
                        $Saldo += $valor['Saldo_MN'];
                        break;
                }
            }
        }

        switch ($Opcion) {
            case 1:
                $Saldo = $Total - $Abono;
                $LabelFacturado = number_format($Total, 2, '.', ',');
                $LabelAbonado = number_format($Abono, 2, '.', ',');
                $LabelSaldo = number_format($Saldo, 2, '.', ',');
                break;
            case 7:
                $LabelFacturadio = number_format($Total, 2, '.', ',');
                $LabelAbonado = number_format($Abono, 2, '.', ',');
                $LabelSaldo = number_format($Saldo, 2, '.', ',');
                break;
            case 9:
            case 10:
                $Abono = $Total - $Saldo;
                $LabelFacturado = number_format($Total, 2, '.', ',');
                $LabelAbonado = number_format($Abono, 2, '.', ',');
                $LabelSaldo = number_format($Saldo, 2, '.', ',');
                break;
        }
    }

    function Form_Activate()
    {
        $CnExterna = 0;
        $HistorialFacturas = "RESUMEN HISTORICO DE FACTURAS/NOTAS DE VENTA";
        Actualizar_Datos_Representantes_SP($_SESSION['INGRESO']['Mas_Grupos']);

        $OpcBusqueda = [
            "Cliente",
            "CI_RUC",
            "Ciudad",
            "Codigo",
            "Plan_Afiliado",
            "Tipo Documento",
            "Autorizacion",
            "Serie",
            "Factura",
            "Forma_Pago",
            "Cuenta_No",
            "Vendedor",
            "Grupo/Zona",
            "Producto",
            "DescItem",
            "Marca"
        ];

        // Ordenar el array alfabéticamente
        sort($OpcBusqueda);

        $ListCliente = array("Todos");

        foreach ($OpcBusqueda as $opcion) {
            $ListCliente[] = $opcion;
        }

        if (empty($TipoFactura)) {
            $TipoFactura = G_NINGUNO;
        }

        $FA = array(
            "Cliente" => G_NINGUNO,
            "CI_RUC" => G_NINGUNO,
            "Factura" => 0,
            "Cod_Ejec" => G_NINGUNO,
            "CodigoC" => G_NINGUNO,
            "Grupo" => G_NINGUNO,
            "CiudadC" => G_NINGUNO,
            "Autorizacion" => G_NINGUNO,
            "Forma_Pago" => G_NINGUNO,
            "TC" => G_NINGUNO,
            "Serie" => G_NINGUNO,
        );

        $CodigoInv = G_NINGUNO;
        $Cod_Marca = G_NINGUNO;
        $DescItem = G_NINGUNO;

        return array('ListCliente' => $ListCliente, 'FA' => $FA);
    }

    function Ventas_Productos()
    {
        $Opcion = 8;
        // echo "Ventas_Productos"; // Puedes descomentar esta línea si necesitas un equivalente a MsgBox en PHP
        $Si_No = false;
        $Con_Costeo = " ";
        $Mensajes = "Reporte Con Costeo ";
        $Titulo = "Formulario de Confirmación";
        
        $ClaveAdministrador=false;//

        if ($ClaveAdministrador()) {
            $Si_No = true;
        }


        $Label2 = " Ventas";
        $Label4 = "  ";
        $Label3 = "  ";
        $DGQueryCaption = "HISTORIAL DE FACTURAS Y PRODUCTOS";

        $sSQL = //$this->modelo->Historico_Facturas($Si_No, $FechaIni, $FechaFin, $Con_Costeo, $CodigoInv);

            $Total = 0;
        $Abono = 0;
        $Saldo = 0;

        foreach ($sSQL as $record) {
            if ($Si_No) {
                $Saldo += $record["Costos"];
            }
            $Total += $record["Total"];
        }

        $Label2 = "Facturado";
        $Label4 = "PVP";
        $Label3 = "Costo";

        $LabelFacturado = number_format($Total, 2, '.', ',');
        $LabelAbonado = number_format($Abono, 2, '.', ',');
        $LabelSaldo = number_format($Saldo, 2, '.', ',');

        return $sSQL;
    }

    function ToolbarMenu_ButtonMenuClick($parametros)
    {
        $ButtonMenu = $parametros['ButtonMenu'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $ListCliente = $parametros['ListCliente'];

        FechaValida($MBFechaI);
        FechaValida($MBFechaF);
        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);
        $CheqCxC = $parametros['CheqCxC'];

        if ($CheqCxC->value == 1) {
            $PorCxC = true;
        }

        $Mifecha = $FechaIni;
        $FechaTexto = $FechaFin;
        $FA['Fecha_Corte'] = $MBFechaF;
        $FA['Factura'] = 0;
        $FA['Fecha_Hasta'] = $MBFechaF;
        //$TMail->Volver_Envial = false;

        if ($ListCliente == "Todos") {
            $FA['TC'] = G_NINGUNO;
            $FA['Serie'] = G_NINGUNO;
            $FA['Factura'] = 0;
        }

        switch ($ButtonMenu->key) {
            case "Resumen_Prod":
                Resumen_Productos();
                break;
            case "Resumen_Prod_Meses":
                Resumen_Prod_Meses();
                break;
            case "ResumenVentCost":
                Resumen_Ventas_Costos();
                break;
            case "Resumen_Ventas_Vendedor":
                Resumen_Ventas_Vendedor();
                break;
            case "Ventas_x_Cli":
                Ventas_Cliente();
                break;
            case "Ventas_Cli_x_Mes":
                Ventas_Clientes_Por_Meses();
                break;
            case "VentasxProductos":
                Ventas_Productos();
                break;
            case "Ventas_ResumindasxVendedor":
                Ventas_Resumindas_x_Vendedor();
                break;
            case "SMAbonos_Anticipados":
                SMAbonos_Anticipados();
                break;
            case "Abonos_Ant":
                Abonos_Anticipados();
                break;
            case "Abonos_Erroneos":
                Abonos_Erroneos();
                break;
            case "Contra_Cta":
                Contra_Cta_Abonos();
                break;
            case "Por_Clientes":
                Tipo_Consulta_CxC("C");
                break;
            case "Por_Facturas":
                Tipo_Consulta_CxC("F");
                break;
            case "Resumen_Cartera":
                Tipo_Consulta_CxC("R");
                break;
            case "Por_Vendedor":
                Tipo_Consulta_CxC("V");
                break;
            case "Resumen_Vent_x_Ejec":
                break;
            case "CxC_Tiempo_Credito":
                CxC_Tiempo_Credito();
                break;
            case "Tipo_Pago_Cliente":
                Tipo_Pago_Cliente();
                break;
            case "Bajar_Excel":
                break;
            case "Reporte_Ventas":
                Ventas_x_Excel();
                break;
            case "Reporte_Catastro":
                Catastro_Registro_Datos_Clientes();
                break;
            case "Enviar_FA_Email":
                break;
            case "Enviar_RE_Email":
                break;
            case "Recibos_Anticipados":
                Recibo_Abonos_Anticipados();
                break;
            case "Deuda_x_Mail":
                Actualizar_Abonos_Facturas_SP($FA);
                Historico_Facturas();
                Deuda_x_Mail("FA");
                break;
        }
    }
}
?>