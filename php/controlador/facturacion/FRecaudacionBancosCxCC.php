<?php
include(dirname(__DIR__, 2) . '/modelo/facturacion/FRecaudacionBancosCxCM.php');

$controlador = new FRecaudacionBancosCxCC();
if (isset($_GET['Form_Activate'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Form_Activate($parametros));
}

if (isset($_GET['DCEntidad'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCEntidad());
}

if (isset($_GET['DCGrupoI_DCGrupoF'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCGrupoI_DCGrupoF());
}

if (isset($_GET['DCBanco'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['FechaValida'])) {
    echo json_encode(FechaValida($_POST['fecha']));
}

if (isset($_GET['AdoAux'])) {
    echo json_encode($controlador->AdoAux());
}

if (isset($_GET['AdoProducto'])) {
    echo json_encode($controlador->AdoProducto());
}

if (isset($_GET['LeerCampoEmpresa'])) {
    echo json_encode(Leer_Campo_Empresa($_POST['campo']));
}

class FRecaudacionBancosCxCC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new FRecaudacionBancosCxCM();
    }

    function DCGrupoI_DCGrupoF()
    {
        $datos = $this->modelo->SelectCombo_DCGrupoI_DCGrupoF();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array('Grupo' => $value['Grupo']);
            }
            return $list;
        }
        return $list;
    }

    function DCBanco()
    {
        $datos = $this->modelo->SelectDB_Combo_DCBanco();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array(
                    'Codigo' => $value['Codigo'],
                    'NomCuenta' => $value['NomCuenta']
                );
            }
            return $list;
        }
        return $list;
    }

    function DCEntidad()
    {
        $datos = $this->modelo->SelectDB_Combo_DCEntidad();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array('Descripcion' => $value['Descripcion']);
            }
            return $list;
        }
        return $list;
    }

    function AdoAux()
    {
        $datos = $this->modelo->AdoAux();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                $list[] = array(
                    'Cta_Cobrar' => $value['CxC'],
                    'CxC_Clientes' => $value['Concepto'],
                    'Individual' => $value['Individual'],
                    'TipoFactura' => $value['Fact'],
                );
            }
            return $list;
        }
        return $list;
    }

    function AdoProducto()
    {
        $datos = $this->modelo->Select_AdoProducto();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array('Codigo_Inv' => $value['Codigo_Inv']);
            }
            return $list;
        }
        return $list;
    }

    /*
   function Form_Activate($parametros)
    {       
        
        FechaValida($parametros['MBFechaI']);
        FechaValida($parametros['MBFechaF']);
        $NuevoComp = True;
        $ModificarComp = False;
        $CopiarComp = False;
        //$Co.CodigoB = "";
        //$Co.Numero = 0;
        $this->DCGrupoI_DCGrupoF();
        $AdoAux = $this->AdoAux();

        //catalogo de rubros a facturar
        $AdoProducto = $this->AdoProducto();
    }*/



}

?>