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

if (isset($_GET['ToolbarMenu_ButtonMenuClick'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ToolbarMenu_ButtonMenuClick($parametros));
}

if (isset($_GET['ListCliente_LostFocus'])) {
    $ListClienteText = $_POST['ListClienteText'];
    echo json_encode($controlador->ListCliente_LostFocus($ListClienteText));
}

if (isset($_GET['DCCliente_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCCliente_LostFocus($parametros));
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
                //$Total = $data['Total'];
                //$Abono = $data['Abono'];
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                $totales = $this->Totales_CxC_Abonos($datos['AdoQuery'], $Opcion);
                $Total = $totales['LabelFacturado'];
                $Abono = $totales['LabelAbonado'];
                break;
            case "Protestado":
                $data = $this->Cheques_Protestados($parametros);
                //$Total = $data['Total'];
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                $totales = $this->Totales_CxC_Abonos($datos['AdoQuery'], $Opcion);
                $Total = $totales['LabelFacturado'];
                break;
            case "Retenciones_NC":
                $data = $this->Abonos_Facturas(true, $parametros);
                //$Total = $data['Total'];
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                break;
            case "Por_Buses":
                $Opcion = 12;
                if ($CheqCxC == 1)
                    $PorCxC = true;
                $datos = $this->modelo->Por_Buses($parametros['DCCliente']);
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
                break;
            case "Base_Access":
                break;
            case "Base_MySQL":
                break;
            case "Buscar_Malla":
                return array('DCCliente' => $this->modelo->Buscar_Malla(), 'idBtn' => $idBtn);
        }

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format($Total - $Abono, 2, '.', ',');


        return array(
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
            'tbl' => $datos['datos'],
            'num_filas' => $datos['num_filas'],
            'idBtn' => $idBtn,
            'Opcion' => $Opcion,
        );
    }

    function Abonos_Facturas($Ret_NC, $parametros)
    {
        $Opcion = 6;
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqCxC = $parametros['CheqCxC'];
        $PorCxC = false;
        if ($CheqCxC == 1) {
            $PorCxC = true;
        }

        $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion, true);

        $sSQL = $this->modelo->Abonos_Facturas($tipoConsulta, $MBFechaI, $MBFechaF, $Ret_NC);

        //$Total = 0;
        //$label_abonado = number_format($Total, 2, '.', ',');
        //$label_facturado = "0.00";
        //$label_saldo = "0.00";

        return array(
            'datos' => $sSQL,
            //'Total' => $Total,
            'Opcion' => $Opcion
        );
    }

    function Cheques_Protestados($parametros)
    {
        $Total = 0;
        $Opcion = 7;

        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];

        $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion);
        $sSQL = $this->modelo->Cheques_Protestados($tipoConsulta, $MBFechaI, $MBFechaF);

        return array(
            'datos' => $sSQL,
            //'Total' => $Total,
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
                $LabelFacturado = $Total;
                $LabelAbonado = $Abono;
                $LabelSaldo = $Saldo;
                break;
            case 7:
                $LabelFacturado = $Total;
                $LabelAbonado = $Abono;
                $LabelSaldo = $Saldo;
                break;
            case 9:
            case 10:
                $Abono = $Total - $Saldo;
                $LabelFacturado = $Total;
                $LabelAbonado = $Abono;
                $LabelSaldo = $Saldo;
                break;
        }
        return array(
            'LabelFacturado' => $LabelFacturado,
            'LabelAbonado' => $LabelAbonado,
            'LabelSaldo' => $LabelSaldo
        );

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

        $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion);
        $sSQL = $this->modelo->Historico_Facturas($tipoConsulta, $FechaFin);
        return array(
            'datos' => $sSQL,
            'Total' => $Total,
            'Abono' => $Abono,
            'Saldo' => $Saldo,
            'Opcion' => $Opcion
        );
    }

    function TipoDeConsulta($parametros, $Opcion, $val = false)
    {
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

        return $this->Tipo_De_Consulta($paramAdd, $val);
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

    function ToolbarMenu_ButtonMenuClick($parametros)
    {
        $ButtonMenu = $parametros['idBtnMenu'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $ListCliente = $parametros['ListCliente'];
        $idBtnMenu = $parametros['idBtnMenu'];


        FechaValida($MBFechaI);
        FechaValida($MBFechaF);
        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);
        $CheqCxC = $parametros['CheqCxC'];

        if ($CheqCxC == 1) {
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
        $label_facturado = 0;
        $label_abonado = 0;
        $label_saldo = 0;
        switch ($idBtnMenu) {
            case "Resumen_Prod":
                $data = $this->Resumen_Productos($parametros, $FechaIni, $FechaFin);
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                break;
            case "Resumen_Prod_Meses":
                $PorCantidad = $parametros['PorCantidad'];
                $datos = $this->modelo->Resumen_Prod_Meses($FechaIni, $FechaFin, $PorCantidad, $MBFechaF);
                $Opcion = 16;
                break;
            case "ResumenVentCost":
                $data = $this->Resumen_Ventas_Costos($FechaIni, $FechaFin, $parametros);
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                break;
            case "Resumen_Ventas_Vendedor":
                $Opcion = 15;
                $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion);
                $datos = $this->modelo->Resumen_Ventas_Vendedor($FechaIni, $FechaFin, $tipoConsulta);
                break;
            case "Ventas_x_Cli":
                $data = $this->Ventas_Cliente($parametros, $FechaIni, $FechaFin);
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                break;
            case "Ventas_Cli_x_Mes":
                $data = $this->Ventas_Clientes_Por_Meses($FechaIni, $FechaFin, $parametros['FA'], $MBFechaF);
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                break;
            case "VentasxProductos":
                $data = $this->Ventas_Productos($parametros, $FechaIni, $FechaFin);
                $datos = $data['datos'];
                $Opcion = $data['Opcion'];
                ;
                break;
            case "Ventas_ResumidasxVendedor":
                $datos = $this->modelo->Ventas_Resumidas_x_Vendedor($FechaIni, $FechaFin);
                $Total = 0;
                if (count($datos['AdoQuery']) > 0) {
                    foreach ($datos['AdoQuery'] as $fila) {
                        $Total += $fila["Cantidad"];
                    }
                }
                $label_facturado = number_format($Total, 2, ',', '.');
                $Opcion = 17;
                break;
            case "SMAbonos_Anticipados":
                $data = $this->SMAbonos_Anticipados();
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
        return array(
            'label_facturado' => $label_facturado,
            'label_abonado' => $label_abonado,
            'label_saldo' => $label_saldo,
            'tbl' => $datos['datos'],
            'num_filas' => $datos['num_filas'],
            'idBtnMenu' => $idBtnMenu,
            'Opcion' => $Opcion,
        );
    }


    function SMAbonos_Anticipados()
    {
        $Opcion = 20;

    }
    function Ventas_Productos($parametros, $FechaIni, $FechaFin)
    {
        $Opcion = 8;

        $Con_Costeo = $parametros['Con_Costeo'];
        $Si_No = $parametros['Si_No'];
        $CodigoInv = $parametros['CodigoInv'];

        $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion, true);
        $tipoConsulta2 = $this->TipoDeConsulta($parametros, $Opcion);

        $sSQL = $this->modelo->Ventas_Productos($FechaIni, $FechaFin, $Si_No, $Con_Costeo, $CodigoInv, $tipoConsulta, $tipoConsulta2);

        $Total = 0;
        $Abono = 0;
        $Saldo = 0;
        
        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL["AdoQuery"] as $record) {
                if ($Si_No) {
                    $Saldo += $record["Costos"];
                }
                $Total += $record["Total"];
            }
        }

        $labelFacturado = number_format($Total, 2, '.', ',');
        $labelAbonado = number_format($Abono, 2, '.', ',');
        $labelSaldo = number_format($Saldo, 2, '.', ',');

        return array(
            'datos' => $sSQL,
            'Opcion' => $Opcion,
            'LabelFacturado' => $labelFacturado,
            'LabelAbonado' => $labelAbonado,
            'LabelSaldo' => $labelSaldo
        );
    }

    function Ventas_Clientes_Por_Meses($FechaIni, $FechaFin, $FA, $MBFechaF)
    {
        $Opcion = 14;
        $sSQL = $this->modelo->Ventas_Clientes_Por_Meses($FechaIni, $FechaFin, $FA, $MBFechaF);
        $Total = 0;
        $Abono = 0;
        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL['AdoQuery'] as $fila) {
                $Total += $fila["Total"];
            }
        }
        $labelFacturado = number_format($Total, 2, '.', ',');
        $labelAbonado = number_format($Abono, 2, '.', ',');
        $labelSaldo = number_format($Total - $Abono, 2, '.', ',');

        return array(
            'datos' => $sSQL,
            'Opcion' => $Opcion,
            'LabelFacturado' => $labelFacturado,
            'LabelAbonado' => $labelAbonado,
            'LabelSaldo' => $labelSaldo
        );
    }



    function Ventas_Cliente($parametros, $FechaIni, $FechaFin)
    {
        $Opcion = 4;
        $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion, true);
        $sSQL = $this->modelo->Ventas_Cliente($FechaIni, $FechaFin, $tipoConsulta);
        $Total = 0;
        $Abono = 0;
        if (count($sSQL['AdoQuery']) > 0) {
            foreach ($sSQL['AdoQuery'] as $fila) {
                $Total += $fila["Ventas"];
                $Abono += $fila["I_V_A"];
            }
        }
        $labelFacturado = number_format($Total, 2, '.', ',');
        $labelAbonado = number_format($Abono, 2, '.', ',');
        $labelSaldo = number_format($Total - $Abono, 2, '.', ',');

        return array(
            'datos' => $sSQL,
            'Opcion' => $Opcion,
            'LabelFacturado' => $labelFacturado,
            'LabelAbonado' => $labelAbonado,
            'LabelSaldo' => $labelSaldo
        );

    }

    function Resumen_Ventas_Costos($FechaIni, $FechaFin, $parametros)
    {
        $Opcion = 5;
        $Con_Costeo = $parametros['Con_Costeo'];
        $Si_No = $parametros['Si_No'];
        $DescItem = $parametros['DescItem'];

        $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion, true);
        $sSQL = $this->modelo->Resumen_Ventas_Costos($FechaIni, $FechaFin, $Con_Costeo, $Si_No, $DescItem, $tipoConsulta);

        return array(
            'datos' => $sSQL,
            //'Total' => $Total,
            //'Abono' => $Abono,
            'Opcion' => $Opcion
        );
    }

    function Resumen_Productos($parametros, $FechaIni, $FechaFin)
    {
        $Opcion = 3;
        $tipoConsulta = $this->TipoDeConsulta($parametros, $Opcion);
        $sSQL = $this->modelo->Resumen_Productos($tipoConsulta, $FechaIni, $FechaFin);
        $Total = 0;
        $Abono = 0;

        $label_facturado = number_format($Total, 2, '.', ',');
        $label_abonado = number_format($Abono, 2, '.', ',');
        $label_saldo = number_format($Total - $Abono, 2, '.', ',');

        return array(
            'datos' => $sSQL,
            'Total' => $Total,
            'Abono' => $Abono,
            'Opcion' => $Opcion
        );
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
        $DCClienteVal = $parametros['DCClienteVal'];
        $ListClienteVal = $parametros['ListClienteVal'];
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
            switch ($ListClienteVal) {
                case "Codigo":
                    $FA['CodigoC'] = $DCClienteVal;
                    break;
                case "CI_RUC":
                    if ($cliente['CI_RUC'] == $DCClienteVal) {
                        $FA['CodigoC'] = $cliente['Codigo'];
                        $FA['Cliente'] = $cliente['Cliente'];
                        $FA['CI_RUC'] = $cliente['CI_RUC'];
                    }
                    break;
                case "Ciudad":
                    $FA['CiudadC'] = $DCClienteVal;
                    break;
                case "Cliente":
                    if ($cliente['Cliente'] == $DCClienteVal) {
                        $FA['CodigoC'] = $cliente['CodigoC'];
                        $FA['Cliente'] = $cliente['Cliente'];
                    }
                    break;
                case "Vendedor":
                    if ($cliente['Cliente'] == $DCClienteVal) {
                        $FA['Cod_Ejec'] = $cliente['Codigo'];
                    }
                    break;
                case "Grupo":
                    $FA['Grupo'] = $DCClienteVal;
                    break;
                case "Factura":
                    $FA['TC'] = SinEspaciosIzq($DCClienteVal);
                    $FA['Serie'] = MidStrg($DCClienteVal, 4, 6);
                    $FA['Factura'] = intval(SinEspaciosDer($DCClienteVal));
                    //$LblPatronBusqueda_Caption = "P A T R O N   D E   B U S Q U E D A:" . PHP_EOL .
                    //$ListCliente . " = " . $FA['TC'] . ": " . $FA['Serie'] . "-" . $FA['Factura'];
                    break;
                case "Serie":
                    $FA['Serie'] = $DCClienteVal;
                    break;
                case "Autorizacion":
                    $FA['Autorizacion'] = $DCClienteVal;
                    break;
                case "Forma_Pago":
                    $FA['Forma_Pago'] = $DCClienteVal;
                    break;
                case "Plan_Afiliado":
                    //
                    break;
                case "Tipo Documento":
                    $FA['TC'] = $DCClienteVal;
                    break;
                case "Marca":
                    $DescItem = SinEspaciosIzq($DCClienteVal);
                    break;
                case "DescItem":
                    $Cod_Marca = $DCClienteVal;
                    break;
                case "Producto":
                    $CodigoInv = trim(SinEspaciosIzq($DCClienteVal));
                    $Producto = trim(substr($DCClienteVal, strlen($CodigoInv) + 1));
                    break;
            }
        }
        /*if ($ListCliente !== "Factura") {
            //$LblPatronBusqueda_Caption = "P A T R O N   D E   B U S Q U E D A:" . PHP_EOL .
                                         //$ListCliente . " = " . $DCCliente;
        }*/
        return array('Cod_Marca' => $Cod_Marca, 'DescItem' => $DescItem, 'CodigoInv' => $CodigoInv, 'FA' => $FA);
    }
}
?>