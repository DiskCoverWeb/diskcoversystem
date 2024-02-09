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

if (isset($_GET['Form_Activate'])) {
    echo json_encode($controlador->Form_Activate());
}

if (isset($_GET['ToolBarMenu_ButtonClick'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ToolBarMenu_ButtonClick($parametros));
}

if (isset($_GET['ListCliente_LostFocus'])) {
    $ListClienteText = $_POST['ListClienteText'];
    echo json_encode($controlador->ListCliente_LostFocus($ListClienteText));
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
        $FA = $parametros['FA'];
        $idBtn = $parametros['idBtn'];

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

        if ($CheqCxC == 1)
            $PorCxC = true;

        if ($ListCliente == "Todos") {
            $FA['TC'] = G_NINGUNO;
            $FA['Serie'] = G_NINGUNO;
            $FA['Factura'] = 0;
        }

        $Total = 0;
        $Abono = 0;
        $data = array();
        $Opcion = 0;

        switch ($idBtn) {
            case "Imprimir":
                Impresiones();
                break;
            case "Facturas":
                Actualizar_Abonos_Facturas_SP($FA);
                $data = $this->Historico_Facturas($parametros);
                $Total = $data['Total'];
                $Abono = $data['Abono'];
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                $this->Totales_CxC_Abonos($datos['AdoQuery'], $Opcion);
                break;
            case "Protestado":
                $data = $this->Cheques_Protestados($parametros);                
                $Total = $data['Total'];
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                $this->Totales_CxC_Abonos($datos['AdoQuery'], $Opcion);
                break;
            case "Retenciones_NC":
                //Actualizar_Abonos_Facturas( FA);
                $data = $this->Abonos_Facturas(true, $parametros);
                $Total = $data['Total'];
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                break;
            case "Por_Buses":
                //Por_Buses($DCCliente());
                break;
            case "Listado_Tarjetas":
                $datos = $this->modelo->Listado_Tarjetas();
                break;
            case "CxC_Clientes":
                Actualizar_Abonos_Facturas_SP($FA);
                Listado_Facturas_Por_Meses(True);
                break;
            case "Listar_Por_Meses":
                Listado_Facturas_Por_Meses(False);
                break;
            case "Estado_Cuenta_Cliente":
                if ($ListCliente == "Todos") {
                    $FA['CodigoC'] = "Todos";
                }
                $fechaSistema = FechaSistema();
                $fechaSistema = date("d/m/Y", strtotime($fechaSistema));
                //Reporte_Cartera_Clientes_SP(PrimerDiaMes($MBFechaI), UltimoDiaMes($fechaSistema), $FA['CodigoC']);  //ERROR
                $datos = $this->modelo->Estado_Cuenta_Cliente();
                if ($datos['num_filas']) {
                    $Total = 0;
                    $Abono = 0;
                    foreach ($datos['AdoQuery'] as $fila) {
                        $Total += $fila["Cargos"];
                        $Abono += $fila["Abonos"];
                    }
                }
                $Opcion = 19;
                break;
            case "Listados_Medidor":
                //Listados_Medidor
                break;
            case "Base_Access":
                //Listar_Base_Externa
                break;
            case "Base_MySQL":
                //Leer_Datos_MySQL
                //Listar_Base_MySQL
                break;
            case "Buscar_Malla":
                return array('DCCliente' => $this->modelo->Buscar_Malla(), 'idBtn' => $idBtn);
        }

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format(($Total - $Abono), 2, '.', ',');

        return array(
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
            'tbl' => $datos['datos'],
            'num_filas' => $datos['num_filas'],
            'idBtn' => $idBtn,
            'Opcion' => $Opcion,
        );
        //return $parametros;
    }

    function Abonos_Facturas($Ret_NC,$parametros){
        $Opcion = 6;
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqCxC = $parametros['CheqCxC'];
        $PorCxC = false;        
        if ($CheqCxC == 1) {
            $PorCxC = true;
        }

        $paramAdd = array(
            'ListCliente' => $parametros['ListCliente'],
            'DCCliente' => $parametros['DCCliente'],
            'DCCxC' => $parametros['DCCxC'],
            'OpcPend' => $parametros['OpcPend'],
            'OpcAnul' => $parametros['OpcAnul'],
            'OpcCanc' => $parametros['OpcCanc'],
            'CheqCxC' => $parametros['CheqCxC'],
            'CheqIngreso' => $parametros['CheqIngreso'],
            'CheqAbonos' => $parametros['CheqAbonos'],
            'DescItem' => $parametros['DescItem'],
            'Cod_Marca' => $parametros['Cod_Marca'],
            'Opcion' => $Opcion,
        );

        $tipoConsulta = $this->Tipo_De_Consulta($paramAdd, true);
        $sSQL = $this->modelo->Abonos_Facturas($tipoConsulta, $MBFechaI, $MBFechaF, $Ret_NC);

        $Total = 0;
        $label_abonado = number_format($Total, 2, '.', ',');
        $label_facturado = "0.00";
        $label_saldo = "0.00";

        return array(
            'datos' => $sSQL,
            'Total' => $Total,
            'Opcion' => $Opcion
        );
    }

    function Cheques_Protestados($parametros)
    {
        $Total = 0;
        $Opcion = 7;

        $paramAdd = array(
            'ListCliente' => $parametros['ListCliente'],
            'DCCliente' => $parametros['DCCliente'],
            'DCCxC' => $parametros['DCCxC'],
            'OpcPend' => $parametros['OpcPend'],
            'OpcAnul' => $parametros['OpcAnul'],
            'OpcCanc' => $parametros['OpcCanc'],
            'CheqCxC' => $parametros['CheqCxC'],
            'CheqIngreso' => $parametros['CheqIngreso'],
            'CheqAbonos' => $parametros['CheqAbonos'],
            'DescItem' => $parametros['DescItem'],
            'Cod_Marca' => $parametros['Cod_Marca'],
            'Opcion' => $Opcion,
        );

        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];

        $tipoConsulta = $this->Tipo_De_Consulta($paramAdd);
        $sSQL = $this->modelo->Cheques_Protestados($tipoConsulta, $MBFechaI, $MBFechaF);

        return array(
            'datos' => $sSQL,
            'Total' => $Total,
            'Opcion' => $Opcion
        );
    }

    public function Totales_CxC_Abonos($AdoQuery, $Opcion)
    {
        $Total = 0;
        $Abono = 0;
        $Saldo = 0;
        foreach ($AdoQuery as $record) {
            if ($record['T'] != G_ANULADO) {
                switch ($Opcion) {
                    case 1:
                        $Total += $record['Total'];
                        $Abono += $record['Total_Abonos'];
                        break;
                    case 6:
                    case 7:
                        $Total += $record['Abono'];
                        break;
                    case 9:
                    case 10:
                        $Total += $record['Total_MN'];
                        $Saldo += $record['Saldo_MN'];
                        break;
                }
            }
        }
        switch ($Opcion) {
            case 1:
                $Saldo = $Total - $Abono;
                $LabelFacturado = number_format($Total, 2);
                $LabelAbonado = number_format($Abono, 2);
                $LabelSaldo = number_format($Saldo, 2);
                break;
            case 7:
                $LabelFacturado = number_format($Total, 2);
                $LabelAbonado = number_format($Abono, 2);
                $LabelSaldo = number_format($Saldo, 2);
                break;
            case 9:
            case 10:
                $Abono = $Total - $Saldo;
                $LabelFacturado = number_format($Total, 2);
                $LabelAbonado = number_format($Abono, 2);
                $LabelSaldo = number_format($Saldo, 2);
                break;
        }
    }

    function Listado_Tarjetas()
    {
        $sSQL = $this->modelo->Listado_Tarjetas();
        return array('datos' => $sSQL);
    }

    function Historico_Facturas($parametros)
    {
        $Opcion = 1;
        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);

        $PorCxC = false;

        if ($parametros['CheqCxC'] == 1) {
            $PorCxC = true;
        }

        $Total = 0;
        $Abono = 0;
        $Saldo = 0;
        $paramAdd = array(
            'ListCliente' => $parametros['ListCliente'],
            'DCCliente' => $parametros['DCCliente'],
            'DCCxC' => $parametros['DCCxC'],
            'OpcPend' => $parametros['OpcPend'],
            'OpcAnul' => $parametros['OpcAnul'],
            'OpcCanc' => $parametros['OpcCanc'],
            'CheqCxC' => $parametros['CheqCxC'],
            'CheqIngreso' => $parametros['CheqIngreso'],
            'CheqAbonos' => $parametros['CheqAbonos'],
            'DescItem' => $parametros['DescItem'],
            'Cod_Marca' => $parametros['Cod_Marca'],
            'Opcion' => $Opcion,
        );

        $tipoConsulta = $this->Tipo_De_Consulta($paramAdd);

        $sSQL = $this->modelo->Historico_Facturas($tipoConsulta, $FechaFin);
        return array(
            'datos' => $sSQL,
            'Total' => $Total,
            'Abono' => $Abono,
            'Saldo' => $Saldo,
            'Opcion' => $Opcion
        );
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

        return array(
            'ListCliente' => $ListCliente,
            'FA' => $FA,
            'CodigoInv' => $CodigoInv,
            'Cod_Marca' => $Cod_Marca,
            'DescItem' => $DescItem
        );
    }

    function ListCliente_LostFocus($ListClienteText)
    {
        return $this->modelo->ListCliente_LostFocus($ListClienteText);
    }

    function Ventas_Productos()
    {
        $Opcion = 8;
        // echo "Ventas_Productos"; // Puedes descomentar esta línea si necesitas un equivalente a MsgBox en PHP
        $Si_No = false;
        $Con_Costeo = " ";
        $Mensajes = "Reporte Con Costeo ";
        $Titulo = "Formulario de Confirmación";

        $ClaveAdministrador = false; //

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

    function Tipo_De_Consulta($paramAdd, $Opcion_TP = false, $Opcion_Email = false, $Opcion_DF = false)
    {
        $ListCliente = $paramAdd['ListCliente'];
        $DCCliente = $paramAdd['DCCliente'];
        $DCCxC = $paramAdd['DCCxC'];
        $OpcPend = $paramAdd['OpcPend'];
        $OpcAnul = $paramAdd['OpcAnul'];
        $OpcCanc = $paramAdd['OpcCanc'];
        $Opcion = $paramAdd['Opcion'];
        $CheqCxC = $paramAdd['CheqCxC'];
        $CheqIngreso = $paramAdd['CheqIngreso'];
        $CheqAbonos = $paramAdd['CheqAbonos'];
        $DescItem = $paramAdd['DescItem'];
        $Cod_Marca = $paramAdd['DescItem'];

        $SQL3X = '';
        $Patron_Busqueda = $DCCliente;

        if ($Patron_Busqueda == '') {
            $Patron_Busqueda = G_NINGUNO;
        }

        $Cta_Cobrar = trim(SinEspaciosIzq($DCCxC));

        if ($OpcPend) {
            if ($Opcion > 0) {
                switch ($Opcion) {
                    case 1:
                        $SQL3X .= "AND F.Saldo_Actual <> 0 AND F.T <> 'A' ";
                        break;
                    case 2:
                        $SQL3X .= "AND F.T = 'P' ";
                        break;
                    case 9:
                    case 10:
                    case 13:
                    case 14:
                        $SQL3X .= "AND F.Saldo_MN <> 0 ";
                        break;
                }
            } else {
                $SQL3X .= "AND F.T = " . G_PENDIENTE . " ";
            }
        } elseif ($OpcCanc) {
            $SQL3X .= "AND F.T = " . G_CANCELADO . " ";
        } elseif ($OpcAnul) {
            $SQL3X .= "AND F.T = " . G_ANULADO . " ";
        }

        switch ($ListCliente) {
            case "Codigo":
                $SQL3X .= "AND C.Codigo = '$Patron_Busqueda' ";
                break;
            case "Grupo/Zona":
                $SQL3X .= "AND C.Grupo = '$Patron_Busqueda' ";
                break;
            case "CI_RUC":
                $SQL3X .= "AND C.CI_RUC = '$Patron_Busqueda' ";
                break;
            case "Cliente":
                $LongStrg = strlen($Patron_Busqueda);
                $SQL3X .= "AND C.Cliente LIKE '$Patron_Busqueda%' ";
                break;
            case "Vendedor":
                $LongStrg = strlen($Patron_Busqueda);
                $SQL3X .= "AND A.Nombre_Completo LIKE '$Patron_Busqueda%' ";
                break;
            case "Ciudad":
                $SQL3X .= "AND C.Ciudad = '$Patron_Busqueda' ";
                break;
            case "Factura":
                $SQL3X .= "AND F.Factura = " . intval($Patron_Busqueda) . " ";
                break;
            case "Serie":
                $SQL3X .= "AND F.Serie = '$Patron_Busqueda' ";
                break;
            case "Autorizacion":
                $SQL3X .= "AND F.Autorizacion = '$Patron_Busqueda' ";
                break;
            case "Forma_Pago":
                $SQL3X .= "AND F.Forma_Pago = '$Patron_Busqueda' ";
                break;
            case "Plan_Afiliado":
                $SQL3X .= "AND C.Plan_Afiliado = '$Patron_Busqueda' ";
                break;
            case "Tipo Documento":
                if ($Opcion_TP) {
                    $SQL3X .= "AND F.TP = '$Patron_Busqueda' ";
                } else {
                    $SQL3X .= "AND F.TC = '$Patron_Busqueda' ";
                }
                $TipoFactura = $Patron_Busqueda;
                break;
        }

        if ($DescItem != G_NINGUNO) {
            //$SQL3X .= "AND MidStrg(F.Codigo,1," . strlen($Codigo) . ") = '$Codigo' ";
        }
        if ($Cod_Marca != G_NINGUNO) {
            $SQL3X .= "AND F.CodMarca = '$Cod_Marca' ";
        }
        if ($CheqCxC) {
            $SQL3X .= "AND F.Cta_CxP = '$Cta_Cobrar' ";
        }
        if ($CheqIngreso && $Opcion_DF) {
            $SQL3X .= "AND F.Cta_Venta = '$Cta_Cobrar' ";
        }
        if ($CheqAbonos) {
            if ($Opcion_Email) {
                $SQL3X .= "AND TA.Cta = '$Cta_Cobrar' ";
            } else {
                $SQL3X .= "AND F.Cta = '$Cta_Cobrar' ";
            }
        }

        return $SQL3X;
    }

    function DCCliente_LostFocus($parametros)
    {
        $FA = $parametros['FA'];
        $DCClienteText = $parametros['DCClienteText'];
        $ListClienteText = $parametros['ListClienteText'];
        $DCCClienteText = $parametros['DCCClienteText'];
        $DCCliente = $parametros['DCCliente'];
        $AdoCliente = $DCCliente;

        $FA['Cod_Ejec'] = G_NINGUNO;
        $FA['CodigoC'] = G_NINGUNO;
        $FA['Cliente'] = G_NINGUNO;
        $FA['CI_RUC'] = G_NINGUNO;
        $FA['Grupo'] = G_NINGUNO;
        $FA['CiudadC'] = G_NINGUNO;
        $FA['Autorizacion'] = G_NINGUNO;
        $FA['Forma_Pago'] = G_NINGUNO;
        $FA['TC'] = G_NINGUNO;
        $FA['Serie'] = G_NINGUNO;
        $FA['Factura'] = 0;
        $CodigoInv = G_NINGUNO;
        $Cod_Marca = G_NINGUNO;
        $DescItem = G_NINGUNO;

        foreach ($AdoCliente as $cliente) {
            switch ($ListClienteText) {
                case "Codigo":
                    $FA['CodigoC'] = $DCClienteText;
                    break;
                case "CI_RUC":
                    if ($cliente['CI_RUC'] == $DCClienteText) {
                        $FA['CodigoC'] = $cliente['Codigo'];
                        $FA['Cliente'] = $cliente['Cliente'];
                        $FA['CI_RUC'] = $cliente['CI_RUC'];
                    }
                    break;
                case "Ciudad":
                    $FA['CiudadC'] = $DCClienteText;
                    break;
                case "Cliente":
                    if ($cliente['Cliente'] == $DCClienteText) {
                        $FA['CodigoC'] = $cliente['CodigoC'];
                        $FA['Cliente'] = $cliente['Cliente'];
                    }
                    break;
                case "Vendedor":
                    if ($cliente['Cliente'] == $DCClienteText) {
                        $FA['Cod_Ejec'] = $cliente['Codigo'];
                    }
                    break;
                case "Grupo":
                    $FA['Grupo'] = $DCClienteText;
                    break;
                case "Factura":
                    $FA['TC'] = SinEspaciosIzq($DCClienteText);
                    $FA['Serie'] = MidStrg($DCClienteText, 4, 6);
                    $FA['Factura'] = intval(SinEspaciosDer($DCClienteText));
                    //$LblPatronBusqueda_Caption = "P A T R O N   D E   B U S Q U E D A:" . PHP_EOL .
                    //$ListCliente . " = " . $FA['TC'] . ": " . $FA['Serie'] . "-" . $FA['Factura'];
                    break;
                case "Serie":
                    $FA['Serie'] = $DCClienteText;
                    break;
                case "Autorizacion":
                    $FA['Autorizacion'] = $DCClienteText;
                    break;
                case "Forma_Pago":
                    $FA['Forma_Pago'] = $DCClienteText;
                    break;
                case "Plan_Afiliado":
                    //
                    break;
                case "Tipo Documento":
                    $FA['TC'] = $DCClienteText;
                    break;
                case "Marca":
                    $DescItem = SinEspaciosIzq($DCClienteText);
                    break;
                case "DescItem":
                    $Cod_Marca = $DCClienteText;
                    break;
                case "Producto":
                    $CodigoInv = trim(SinEspaciosIzq($DCClienteText));
                    $Producto = trim(substr($DCClienteText, strlen($CodigoInv) + 1));
                    break;
            }
        }
        /*if ($ListCliente !== "Factura") {
            //$LblPatronBusqueda_Caption = "P A T R O N   D E   B U S Q U E D A:" . PHP_EOL .
                                         //$ListCliente . " = " . $DCCliente;
        }*/
        return array('Cod_Marca' => $Cod_Marca, 'DescItem' => $DescItem, 'FA' => $FA);
    }
}
?>