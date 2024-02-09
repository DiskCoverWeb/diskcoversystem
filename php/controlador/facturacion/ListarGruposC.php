<?php

require_once(dirname(__DIR__, 2) . "/modelo/facturacion/ListarGruposM.php");

$controlador = new ListarGruposC();

if (isset($_GET['ActualizarDatosRepresentantes'])) {
    echo json_encode($controlador->ActualizarDatosRepresentantes());
}

if (isset($_GET['DCGrupos'])) {
    echo json_encode($controlador->DCGrupos());
}

if (isset($_GET['DCTipoPago'])) {
    echo json_encode($controlador->DCTipoPago());
}

if (isset($_GET['DCProductos'])) {
    echo json_encode($controlador->DCProductos());
}

if (isset($_GET['DCLinea'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCLinea($parametros));
}

if (isset($_GET['MBFecha_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->MBFecha_LostFocus($parametros));
}

if (isset($_GET['Listar_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Grupo($parametros));
}

if (isset($_GET['Listar_Clientes_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Clientes_Grupo($parametros));
}

if (isset($_GET['DCLinea_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCLinea_LostFocus($parametros));
}

if (isset($_GET['Listar_Deuda_por_Api'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Deuda_por_Api($parametros));
}

if (isset($_GET['Pensiones_Mensuales_Anio'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Pensiones_Mensuales_Anio($parametros));
}

if (isset($_GET['Listado_Becados'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listado_Becados($parametros));
}

if (isset($_GET['Nomina_Alumnos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Nomina_Alumnos($parametros));
}

if (isset($_GET['Resumen_Pensiones_Mes'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_Pensiones_Mes($parametros));
}

if (isset($_GET['Listar_Clientes_Email'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Clientes_Email($parametros));
}



class ListarGruposC
{

    private $modelo;

    public function __construct()
    {
        $this->modelo = new ListarGruposM();
    }

    public function Listar_Clientes_Email($parametros)
    {
        $AdoQuery = $this->modelo->Listar_Clientes_Grupo($parametros);
        $LstClientes = array();
        array_push($LstClientes, 'TODOS ' . str_repeat(" ", 85) . 'SALDO PENDIENTE');
        if ($parametros['PorGrupo'] || $parametros['PorDireccion']) {
            if (count($AdoQuery) > 0) {
                foreach ($AdoQuery as $key => $value) {
                    $sSaldo_Pendiente = number_format($value['Saldo_Pendiente'], 2, '.', ',');
                    $DeudaCliente = $value['Cliente'] . str_pad(" ", 80 - strlen($value['Cliente'])) . str_pad(" ", 15 - strlen($sSaldo_Pendiente)) . $sSaldo_Pendiente;
                    if (strlen($value['EmailR']) > 1) {
                        $DeudaCliente .= " -Email: " . $value['EmailR'];
                    } else {
                        $DeudaCliente .= " -Email: " . $value['Email'];
                    }
                    array_push($LstClientes, $DeudaCliente);
                }
            }
        }
        return array('LstClientes' => $LstClientes, 'numRegistros' => count($LstClientes));
    }

    public function Resumen_Pensiones_Mes($parametros)
    {
        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $AdoQuery = $this->modelo->Resumen_Pensiones_Mes($parametros, $FechaIni, $FechaFin);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']));
    }

    public function Nomina_Alumnos($parametros)
    {
        $AdoQuery = $this->modelo->Nomina_Alumnos($parametros);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']));
    }

    public function Pensiones_Mensuales_Anio($parametros)
    {
        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $Codigo1 = $parametros['Codigo1'];
        $Codigo2 = $parametros['Codigo2'];
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $SubTotal = 0;
        $Diferencia = 0;
        $TotalIngreso = 0;
        $ListaCampos = '.';
        $dataSP = Reporte_CxC_Cuotas_SP($Codigo1, $Codigo2, $MBFechaI, $MBFechaF, $SubTotal, $Diferencia, $TotalIngreso, $ListaCampos, $parametros['CheqResumen'], $parametros['CheqVenc']);
        $SubTotal = $dataSP['SubTotal'];
        $Diferencia = $dataSP['TotalAnticipo'];
        $TotalIngreso = $dataSP['TotalCxC'];
        $ListaCampos = $dataSP['ListaDeCampos'];
        $AdoQuery = $this->modelo->Pensiones_Mensuales_Anio($ListaCampos);
        $Caption9 = number_format($SubTotal, 2, '.', ',');
        $Caption10 = number_format($Diferencia, 2, '.', ',');
        $Caption4 = number_format($TotalIngreso, 2, '.', ',');
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']), 'Caption9' => $Caption9, 'Caption10' => $Caption10, 'Caption14' => $Caption4);
    }

    public function Listado_Becados($parametros)
    {
        $FechaIni = BuscarFecha($parametros['MBFechaI']);
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $AdoQuery = $this->modelo->Listado_Becados($parametros, $FechaIni, $FechaFin);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']));
    }

    public function Listar_Deuda_por_Api($parametros)
    {
        $FechaTope = BuscarFecha(FechaSistema());
        if ($parametros['CheqVenc'] <> 0) {
            $FechaTope = BuscarFecha($parametros['MBFechaF']);
        }
        $data = $this->modelo->Listar_Deuda_por_Api($parametros, $FechaTope);
        $Total = 0;
        if (count($data['AdoQuery']) > 0) {
            foreach ($data['AdoQuery'] as $key => $value) {
                $Total += $value['Saldo_Pendiente'];
            }
        }
        return array('tbl' => $data['datos'], 'Caption' => number_format($Total, 2, '.', ','), 'numRegistros' => count($data['AdoQuery']));
    }

    public function DCLinea_LostFocus($parametros)
    {
        $FA = variables_tipo_factura();
        if (isset($parametros['FA']) && is_array($parametros['FA'])) {
            // Fusionar los valores de $FA con los valores de $parametros['FA']
            $FA = array_merge($FA, $parametros['FA']);
        }
        $tmp = Lineas_De_CxC($FA);
        $Caption = "Linea de Facturacion: " . str_repeat(" ", 8) . $tmp['TFA']['Serie'] . "-" . sprintf("%09d", ReadSetDataNum($tmp['TFA']['TC'] . "_SERIE_" . $tmp['TFA']['Serie'], true, false));
        return array('tmp' => $tmp, 'Caption' => $Caption);
    }

    public function Listar_Grupo($parametros)
    {
        return $this->modelo->Listar_Grupo($parametros);
    }

    public function MBFecha_LostFocus($parametros)
    {
        return $this->modelo->MBFecha_LostFocus($parametros);
    }

    public function Listar_Clientes_Grupo($parametros)
    {
        $AdoQuery = $this->modelo->Listar_Clientes_Grupo($parametros);
        return array('tbl' => $AdoQuery['datos'], 'numRegistros' => count($AdoQuery['AdoQuery']));
    }

    public function DCLinea($parametros)
    {
        $datos = $this->modelo->DCLinea($parametros);
        $fecha = date('Y-m-d', strtotime($parametros['MBFechaI'] . ' + 365 days'));
        return array('datos' => $datos, 'fecha' => $fecha);
    }

    public function DCProductos()
    {
        return $this->modelo->DCProductos();
    }

    public function ActualizarDatosRepresentantes()
    {
        Actualizar_Datos_Representantes_SP($_SESSION['INGRESO']['Mas_Grupos']);
    }

    public function DCGrupos()
    {
        return $this->modelo->DCGrupos();
    }

    public function DCTipoPago()
    {
        return $this->modelo->DCTipoPago();
    }
}