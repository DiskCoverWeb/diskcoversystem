<?php
include(dirname(__DIR__, 2) . '/modelo/contabilidad/FAbonosAnticipadoM.php');

$controlador = new FAbonoAnticipadoC();

if (isset($_GET['DCCliente'])) {
    echo json_encode($controlador->DCCliente());
}

if (isset($_GET['DCBanco'])) {
    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['DCCtaAnt'])) {
    echo json_encode($controlador->DCCtaAnt());
}

if(isset($_GET['AdoIngCaja_Asiento_SC'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AdoIngCaja_Asiento_SC($parametros['sub_cta_gen'], $parametros['trans_no']));
}

if(isset($_GET['AdoIngCaja_Asiento'])){
    $trans_no = $_POST['trans_no'];
    echo json_encode($controlador->AdoIngCaja_Asiento($trans_no));
}

if(isset($_GET['AdoIngCaja_Asiento'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->AdoIngCaja_Catalogo_CxCxP($parametros['codigo_cliente'], $parametros['sub_cta_gen']));
}

class FAbonoAnticipadoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new FAbonosAnticipadoM();
    }

    /*
    Conexión del controlador con el modelo para la consulta del select con id "DCCliente"
    -Se implemento la variable "status" cuando no hay datos para el manejo en la vista
    */
    function DCCliente()
    {
        $datos = $this->modelo->SelectDB_Combo_DCClientes();
        $list = array();
        if (count($datos) > 0) { //Caso cuando encuentre datos, se añadio el "status" para manejar los datos en la vista
            foreach ($datos as $key => $value) {
                $list[] = array(
                    'Status' => '1',
                    'Grupo' => $value['Grupo'],
                    'Cliente' => $value['Cliente'],
                    'Email' => $value['Email'],
                    'Email2' => $value['Email2']
                );
            }
            return $list;
        }
        return array('Status' => '0', 'msj' => 'Datos no encontrados'); //Caso de que no encuentre datos
    }

    /*
    Conexión del controlador con el modelo para la consulta del select con id"DCBanco"
    -Se implemento la variable "status" cuando no hay datos para el manejo en la vista
    */
    function DCBanco()
    {
        $datos = $this->modelo->SelectDB_Combo_DCBanco();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                $list[] = array('NomCuenta' => $value['NomCuenta']);
            }
            return $list;
        }
        return array('Status' => '0', 'msj' => 'Datos no encontrados'); //Caso de que no encuentre datos
    }

    /*
    Conexión del controlador con el modelo para la consulta del select con id "DCCtaAnt"
    -Se implemento la variable "status" cuando no hay datos para el manejo en la vista
    */
    function DCCtaAnt()
    {
        $datos = $this->modelo->SelectDB_Combo_DCCtaAnt();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                $list[] = array('NomCuenta' => $value['NomCuenta']);
            }
            return $list;
        }
        return array('Status' => '0', 'msj' => 'Datos no encontrados'); //Caso de que no encuentre datos
    }


    /*
    Metodos que se utilizan al momento de grabar algun abono anticipado
    */
    function AdoIngCaja_Asiento_SC($sub_cta_gen, $trans_no)
    {
        $datos = $this->modelo->Select_Adodc_AdoIngCaja_Asiento_SC($sub_cta_gen, $trans_no);
        if (count($datos) > 0) {
            return $datos;
        }
        return array('Status' => '0', 'msj' => 'Datos no encontrados'); //Caso de que no encuentre datos
    }

    function AdoIngCaja_Asiento($trans_no)
    {
        $datos = $this->modelo->Select_Adocdc_AdoIngCaja_Asiento($trans_no);
        if(count($datos) > 0){
            return $datos;
        }
        return array('Status' => '0', 'msj' => 'Datos no encontrados'); //Caso de que no encuentre datos
    }

    function AdoIngCaja_Catalogo_CxCxP($codigo_cliente, $sub_cta_gen)
    {
        $datos = $this->modelo->Select_Adodc_AdoIngCaja_Catalogo_CxCxp($codigo_cliente, $sub_cta_gen);
        if(count($datos) > 0){
            return $datos;
        }
        return array('Status' => '0', 'msj' => 'Datos no encontrados'); //Caso de que no encuentre datos
    }












}
?>