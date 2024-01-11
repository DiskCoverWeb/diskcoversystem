<?php
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
}
?>