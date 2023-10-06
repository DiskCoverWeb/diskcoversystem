<?php

$controlador = new dayaC();

if (isset($_GET['MostrarTabla'])) {
    $option = $_POST['option'];
    echo json_encode($controlador->MostrarTabla($option));
}

class dayaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new dayaM();
    }

    function MostrarTabla($option)
    {
        $datos = array(); 

        switch ($option) {
            case 'CAT_GFN':
                $datos = $this->modelo->ConsultaCategoriaGFN($option);
                break;
            case 'CATEG_BPM':
                $datos = $this->modelo->ConsultaCategoriaBPMAlergenos($option);
                break;
            case 'CATEG_BPMT':
                $datos = $this->modelo->ConsultaCategoriaBPMTemperatura($option);
                break;
            default:
                return array('status' => '400', 'msj' => 'Opción no reconocida');
        }
        if (count($datos) > 0) {
            return array('status' => '200', 'datos' => $datos);
        }
        return array('status' => '400', 'msj' => 'Datos no encontrados');
    }
}

?>