<?php

require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FAsignaFactM.php");

$controlador = new FAsignaFactC();

if (isset($_GET['AdoRubros'])) {
    echo json_encode($controlador->AdoRubros());
}

if (isset($_GET['DCInv'])) {
    echo json_encode($controlador->DCInv());
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
}