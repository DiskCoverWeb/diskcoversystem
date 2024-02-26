<?php
include(dirname(__DIR__, 2) . '/modelo/inventario/registro_beneficiarioM.php');

$controlador = new registro_beneficiarioC();

if (isset($_GET['LlenarSelect'])) {
    $valores = $_POST['valores'];
    echo json_encode($controlador->LlenarSelect($valores));
}

if (isset($_GET['LlenarDatosCliente'])) {
    $query = '';
    $query = isset($_GET['q']) ? $_GET['q'] : '';
    echo json_encode($controlador->LlenarDatosCliente($query));
}

if (isset($_GET['seleccionarClienteConRUCVisc'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->seleccionarClienteConRUCVisc($parametros['RUC'],$parametros['Cliente']));
}

class registro_beneficiarioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new registro_beneficiarioM();
    }

    function LlenarSelect($valores)
    {
        foreach ($valores as $valor) {
            $datos = $this->modelo->LlenarSelect($valor);
            if (empty($datos)) {
                $datos = "No se encontraron datos para mostrar";
            }
            $resultado = array(
                "valor" => $valor,
                "datos" => $datos
            );
            $resultados[] = $resultado;
        }
        return $resultados;
    }

    function LlenarDatosCliente($query)
    {
        $datos = $this->modelo->LlenarDatosCliente($query);
        $res = array();
        $clientes = array();
        $rucs = array();
        foreach ($datos as $valor) {
            $clientes[] = array('id' => $valor['Cliente'], 'text' => $valor['Cliente']);
            $rucs[] = array('id' => $valor['CI_RUC'], 'text' => $valor['CI_RUC']); 
        }
        return array('clientes' => $clientes, 'rucs' => $rucs); 
    }

    function seleccionarClienteConRUCVisc($RUC,$Cliente){
        //print_r($Cliente);
        return $this->modelo->seleccionarClienteConRUCVisc($RUC,$Cliente);
    }



}
?>