<?php
include(dirname(__DIR__, 2) . '/funciones/funciones.php');
@session_start();

class catalogo_bodegaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function GuardarProducto($parametros)
    {
        $sql = "INSERT INTO Catalogo_Proceso(DC, Cmds, Proceso, TP, Nivel, Item) 
                VALUES ('" . $parametros['tipo'] . "', '" . $parametros['codigo'] . "', '" . $parametros['concepto'] . "', 'CATEGORI', '0', '" . $_SESSION['INGRESO']['item'] . "')";
        return $this->db->datos($sql);
    }
    
    function ListaProductos()
    {
        $sql = "SELECT DC, Cmds, Proceso, TP, Nivel, Item, ID
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TP = 'CATEGORI'
                AND Nivel = '0'
                ORDER BY Cmds";
        return $this->db->datos($sql);
    }

    function EliminarProducto($parametros)
    {
        //$registrosAEliminar = $this->ListaIDsEliminar($parametros);

        foreach ($parametros as $registro) {
            $id = $registro['ID'];
            $sqlEliminar = "DELETE FROM Catalogo_Proceso 
                        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                        AND ID = '$id'
                        AND TP = 'CATEGORI'
                        AND Nivel = '0'";
            $this->db->datos($sqlEliminar);
        }
        return true;
    }

    function ListaEliminar($parametros)
    {
        $sql = "SELECT ID, Cmds, Proceso 
                FROM Catalogo_Proceso
                WHERE Cmds LIKE '" . $parametros['codigo'] . "%'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND TP = 'CATEGORI'
                AND Nivel = '0'";
        return $this->db->datos($sql);
    }

    function EditarProducto($parametros)
    {
        $sql = "UPDATE Catalogo_Proceso 
                SET DC = '" . $parametros['tipo'] . "', 
                    Cmds = '" . $parametros['codigo'] . "', 
                    Proceso = '" . $parametros['concepto'] . "' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND ID = '" . $parametros['id'] . "'
                AND TP = 'CATEGORI'
                AND Nivel = '0'";
        return $this->db->datos($sql);
    }
}

?>