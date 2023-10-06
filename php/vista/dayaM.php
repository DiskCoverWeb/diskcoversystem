<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
@session_start();

class dayaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function ConsultaCategoriaGFN($option)
    {
        $sql = "SELECT TP, Proceso, Cmds, ID 
                FROM Catalogo_Proceso 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = 0 
                AND TP = ' " . $option . " ' ";
        return $this->db->datos($sql);
    }

    function ConsultaCategoriaBPMAlergenos($option)
    {
        $sql = "SELECT Tipo_Dato, Codigo, Beneficiario, ID 
                FROM Clientes_Datos_Extras 
                WHERE Tipo_Dato = '" . $option . "' ";
        return $this->db->datos($sql);
    }

    function ConsultaCategoriaBPMTemperatura($option)
    {
        $sql = "SELECT Tipo_Dato, Codigo, Beneficiario, ID 
                FROM Clientes_Datos_Extras 
                WHERE Tipo_Dato = '" . $option . "'";
        return $this->db->datos($sql);
    }

    function ConsultarTipoIngreso()
    {
        $sql = "SELECT TP, Proceso, Cta_Debe, ID 
                FROM Catalogo_Proceso 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = 99
                ORDER BY TP";
        return $this->db->datos($sql);
    }

    function ConsultarIndicadorNutricional($option)
    {
        $sql = "SELECT Tipo_Dato, Codigo, Beneficiario, ID 
                FROM Clientes_Datos_Extras 
                WHERE Tipo_Dato = '" . $option . "'";
        return $this->db->datos($sql);
    }

    function ConsultarCatalogoBodega($option)
    {
        $sql = "SELECT CodBod, Bodega, Item, Periodo, X, ID 
                FROM Catalogo_Bodegas 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $option . "'
                ORDER BY CodBod";
        return $this->db->datos($sql);
    }

}
?>