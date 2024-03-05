<?php

require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FAsignaFactM.php");

$controlador = new FAsignaFactC();

if (isset($_GET['AdoRubros'])) {
    echo json_encode($controlador->AdoRubros());
}

if (isset($_GET['DCInv'])) {
    echo json_encode($controlador->DCInv());
}

if (isset($_GET['Listar_Rubros_Grupo'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listar_Rubros_Grupo($parametros));
}

if (isset($_GET['Command1_Click'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command1_Click($parametros));
}

class FAsignaFactC
{
    private $modelo;

    public function __construct()
    {
        $this->modelo = new FAsignaFactM();
    }

    public function AdoRubros(): array
    {
        try {
            $AdoRubros = $this->modelo->AdoRubros();
            if (count($AdoRubros) === 0) {
                throw new Exception("No se encontraron rubros");
            }
            return array("res" => "1", "datos" => $AdoRubros);
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "No se encontraron rubros", "error" => $e->getMessage());
        }
    }

    public function DCInv()
    {
        try {
            $datos = $this->modelo->DCInv();
            if (count($datos) == 0) {
                throw new Exception("No se encontraron productos");
            }
            return array("res" => "1", "datos" => $datos);
        } catch (Exception $e) {
            return array("res" => "0", "msj" => $e->getMessage());
        }
    }

    public function Listar_Rubros_Grupo($parametros){
        try{
            $datos = $this->modelo->Listar_Rubros_Grupo($parametros);
            if(count($datos['AdoRubros']) == 0){
                throw new Exception("No se encontraron rubros");
            }
            return array("res" => "1", "tbl" => $datos['datos']);
        }catch(Exception $e){
            return array("res" => "0", "msj" => $e->getMessage());
        }
    }

    public function Command1_Click($parametros)
    {
        try {
            $parametros['CodigoP'] = SinEspaciosIzq($parametros['CodigoP']);
            $this->modelo->Command1_Click($parametros);
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al ejecutar el proceso", "error" => $e->getMessage());
        }
    }
}