<?php
include(dirname(__DIR__,2).'/modelo/inventario/catalogo_bodegaM.php');

$controlador = new catalogo_bodegaC();

if (isset($_GET['GuardarProducto'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarProducto($parametros));
}

if (isset($_GET['ListaProductos'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaProductos($parametros));
}

if (isset($_GET['EliminarProducto'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EliminarProducto($parametros));
}

if (isset($_GET['EditarProducto'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->EditarProducto($parametros));
}

if (isset($_GET['ListaEliminar'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->ListaEliminar($parametros));
}

if (isset($_GET['ListaTipo'])) {
    echo json_encode($controlador->ListaTipoProcesosGenerales());
}

class catalogo_bodegaC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new catalogo_bodegaM();
    }

    function GuardarProducto($parametros)
    {    
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $datos = $this->modelo->GuardarProducto($parametros); 
            Eliminar_Nulos_SP("Catalogo_Proceso");            
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron agregar los datos.');
        }                     
    }

    function ListaProductos($parametros)
    {    
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $datos = $this->modelo->ListaProductos($parametros);             
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }                     
    }

    function EliminarProducto($parametros) 
    {        
        try {
            if($parametros[0]['Nivel'] == '00') $parametros[0]['Nivel'] = '0';
            $this->modelo->EliminarProducto($parametros);
            return array('status' => '200', 'msj' => 'Se elimino correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron eliminar los datos.');
        }
    }

    function EditarProducto($parametros)
    {
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $this->modelo->EditarProducto($parametros);            
            return array('status' => '200', 'msj' => 'Se actualizo correctamente');
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron editar los datos.');
        }   
    }

    function ListaEliminar($parametros)
    {    
        try {
            if($parametros['nivel'] == '00') $parametros['nivel'] = '0';
            $datos = $this->modelo->ListaEliminar($parametros);             
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }                     
    }

    function ListaTipoProcesosGenerales(){
        try {
            $datos = $this->modelo->ListaTipoProcesosGenerales();
            $tmp = array('TP'=>'.','Proceso'=>'Seleccione un proceso');  
            array_unshift($datos, $tmp);           
            return array('status' => '200', 'datos' => $datos);
        } catch (Exception $e) {
            return array('status' => '400', 'error' => 'No se pudieron listar los datos.');
        }
    }
}
?>