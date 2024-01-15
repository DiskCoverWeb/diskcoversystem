<?php
/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 03/01/2024
    FECHA MODIFICACION: 12/01/2024
    DESCIPCION : Clase que se encarga de manejar la lógica de la pantalla de recaudacion de bancos
*/
require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FRecaudacionBancosPreFaM.php");

$controlador = new FRecaudacionBancosPreFaC();

if (isset($_GET['DCLinea'])) {
    
    echo json_encode($controlador->DCLinea());
}

if (isset($_GET['DCBanco'])) {
    
    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['DCGrupos'])) {
    
    echo json_encode($controlador->DCGrupos());
}

if (isset($_GET['DCEntidadBancaria'])) {
    
    echo json_encode($controlador->DCEntidadBancaria());
}

if (isset($_GET['MBFechaI_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->MBFechaI_LostFocus($parametros));
}

if (isset($_GET['Existe_Factura'])) {
    $parametros = $_POST['parametros'];
    echo json_encode(Existe_Factura($parametros));
}

if(isset($_GET['ReadSetDataNum'])){
    $parametros = $_POST['parametros'];
    echo json_encode(ReadSetDataNum($parametros['SQLs'], $parametros['ParaEmpresa'], $parametros['Incrementar'], $parametros['Fecha']));

}

if(isset($_GET['TextFacturaNo_LostFocus'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->TextFacturaNo_LostFocus($parametros));

}


class FRecaudacionBancosPreFaC
{

    private $modelo;

    public function __construct()
    {
        $this->modelo = new FRecaudacionBancosPreFaM();
    }

    public function DCLinea()
    {
        return $this->modelo->DCLinea();
    }

    public function DCBanco(){
        return $this->modelo->DCBanco();
    }

    public function DCGrupos(){
        
        return $this->modelo->DCGrupos();
    }

    public function DCEntidadBancaria(){
        return $this->modelo->DCEntidadBancaria();
    }

    public function MBFechaI_LostFocus($parametros){
        return $this->modelo->MBFechaI_LostFocus($parametros);
    }

    public function TextFacturaNo_LostFocus($parametros){
        if(Existe_Factura($parametros['FA'])){
            return array('response' => 1, 'Factura' => '');
        }else{
            return array ('response' => 0, 'Factura' => ReadSetDataNum($parametros['FA']['TC'] . "_SERIE_" . $parametros['FA']['Serie'], 
                                                                        True, False));
        }
    }
}
?>