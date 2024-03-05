<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

class FAsignaFactM
{
    private $db;

    public function __construct()
    {
        $this->db = new db();
    }

    public function AdoRubros(): array
    {
        try {
            $sql = "SELECT * 
                    FROM Tabla_Dias_Meses 
                    WHERE Tipo = 'M' 
                    AND No_D_M > 0 
                    ORDER BY No_D_M ";
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function DCInv(): array
    {
        $sql = "SELECT Codigo_Inv + '  ' + Producto As NomProd, *
                FROM Catalogo_Productos 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P' 
                AND LEN(Cta_Inventario) = 1 
                AND INV <> 0 
                ORDER BY Codigo_Inv";
        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}