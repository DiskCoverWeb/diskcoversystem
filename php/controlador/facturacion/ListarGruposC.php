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



class ListarGruposC
{

    private $modelo;

    public function __construct()
    {
        $this->modelo = new ListarGruposM();
    }

    public function Listar_Deuda_por_Api($parametros)
    {
        $FechaTope = BuscarFecha(FechaSistema());
        if ($parametros['CheqVenc'] <> 'false') {
            $FechaTope = BuscarFecha($parametros['MBFechaF']);
        }
        $data = $this->modelo->Listar_Deuda_por_Api($parametros, $FechaTope);
        $Total = 0;
        if(count($data['AdoQuery']) > 0){
            foreach($data['AdoQuery'] as $key => $value){
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