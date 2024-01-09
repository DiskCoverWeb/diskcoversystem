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
                //$list[] = array('NomCuenta' => $value['NomCuenta']);
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
                //$list[] = array('NomCuenta' => $value['NomCuenta']);
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
        $datos = $this->modelo->Select_AdoAux();
        $list = array();
        if (!empty($datos)) {
            foreach ($datos as $value) {
                $Cta_Cobrar = isset($value['CxC']) ? $value['CxC'] : null;
                $CxC_Clientes = isset($value['Concepto']) ? $value['Concepto'] : null;
                $TipoFactura = isset($value['Fact']) ? $value['Fact'] : null;

                $list[] = array(
                    'Cta_Cobrar' => $Cta_Cobrar,
                    'CxC_Clientes' => $CxC_Clientes,
                    'TipoFctura' => $TipoFactura
                );
            }
        }
        return $list;
    }

    function AdoProducto()
    {
        $datos = $this->modelo->Select_AdoProducto();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                //$list[] = array('NomCuenta' => $value['NomCuenta']);
            }
            return $list;
        }
        return $list;
    }

    function Form_Activate($parametros)
    {
        
        
        /**
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
        */
        

    }



}

?>