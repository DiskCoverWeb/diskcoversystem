<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start();

class dayaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function ConsultarCategoriaClientesDatosExtras($option)
    {
        $sql = "SELECT Tipo_Dato, Codigo, Beneficiario, ID 
                FROM Clientes_Datos_Extras 
                WHERE Tipo_Dato = '" . $option . "' ";
        return $this->db->datos($sql);
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

    function ConsultarTipoIngreso()
    {
        $sql = "SELECT TP, Proceso, Cta_Debe, ID 
                FROM Catalogo_Proceso 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Nivel = 99
                ORDER BY TP";
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

    function AgregarCategoriaClientesDatosExtras($parametros)
    {
        $sql = "INSERT INTO Clientes_Datos_Extras (Tipo_Dato, Codigo, Beneficiario) 
                VALUES ('" . $parametros['tipo'] . "', '" . $parametros['codigo'] . "', '" . $parametros['beneficiario'] . "')";
        return $this->db->datos($sql);
    }

    function AgregarCategoriaGFN($parametros)
    {
        $sql = "INSERT INTO Catalogo_Proceso (TP, Proceso, Cmds) 
                VALUES ('" . $parametros['tp'] . "', '" . $parametros['proceso'] . "', '" . $parametros['cmds'] . "')";
        return $this->db->datos($sql);
    }

    function MostrarDatosPorId($id)
    {
        $sql = "SELECT Tipo_Dato, Codigo, Beneficiario, ID 
                FROM Clientes_Datos_Extras 
                WHERE ID = '" . $id . "' ";
        return $this->db->datos($sql);
    }

    function EditarCategoriaClientesDatosExtrasPorId($parametros)
    {
        $sql = "UPDATE Clientes_Datos_Extras 
                SET Tipo_Dato = '" . $parametros['tipo'] . "', 
                    Codigo = '" . $parametros['codigo'] . "', 
                    Beneficiario = '" . $parametros['beneficiario'] . "' 
                WHERE ID = '" . $parametros['id'] . "'";
        return $this->db->datos($sql);
    }

    function EliminarCategoriaClientesDatosExtrasPorId($id)
    {
        $sql = "DELETE
                FROM Clientes_Datos_Extras 
                WHERE ID = '" . $id . "' ";
        return $this->db->datos($sql);
    }
}
?>