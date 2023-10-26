<?php
include(dirname(__DIR__,2).'/modelo/inventario/catalogo_bodegaM.php');

$controlador = new catalogo_bodegaC();

if (isset($_GET['GuardarConcepto'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarConcepto($parametros));
}

class catalogo_bodegaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new catalogo_bodegaM();
    }

    function GuardarConcepto($parametros)
    {    
        try {
            $datos = $this->modelo->GuardarConcepto($parametros); 
            //Eliminar_Nulos_SP("Catalogo_Procesos");            
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron agregar los datos.');
        }                     
    }

}
?>