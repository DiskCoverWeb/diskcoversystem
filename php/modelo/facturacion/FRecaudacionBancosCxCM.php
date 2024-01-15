<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
@session_start();

class FRecaudacionBancosCxCM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function SelectCombo_DCGrupoI_DCGrupoF()
    {
        $sql = "SELECT Grupo, COUNT(Grupo) AS Cantidad 
                FROM Clientes 
                WHERE FA != 0";

        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
        }

        $sql .= "GROUP BY Grupo ORDER BY Grupo ";

        return $this->db->datos($sql);
    }

    function AdoAux()
    {
        $sql = "SELECT * 
                FROM Catalogo_Lineas 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TL != 0
                ORDER BY Codigo, CxC";

        return $this->db->datos($sql);
    }
    function Select_AdoProducto()
    {
        $sql = "SELECT * 
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P'
                ORDER BY Codigo_Inv";

        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCBanco()
    {
        $sql = "SELECT Codigo + '  ' + Cuenta AS NomCuenta, Codigo
                FROM Catalogo_Cuentas 
                WHERE TC = 'BA' 
                AND DG = 'D' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";

        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCEntidad()
    {
        $sql = "SELECT Descripcion, Abreviado, ID
                FROM Tabla_Referenciales_SRI
                WHERE Tipo_Referencia = 'BANCOS Y COOP'
                AND Abreviado <> '.' 
                AND TFA != 'False'
                ORDER BY Descripcion";

        return $this->db->datos($sql);
    }

    function MBFechaF_LostFocus()
    {
        $sql = "SELECT *
                FROM Fechas_Balance
                WHERE Detalle = 'Deuda Pendiente'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";

        return $this->db->datos($sql);
    }

    function MBFechaF_LostFocusUpdate($fecha)
    {
        "UPDATE Fechas_Balance
        SET Fecha_Inicial = '" . $fecha . "', Fecha_Final = '" . $fecha . "',
        WHERE Detalle = 'Deuda Pendiente'
        AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND Periodo =  '" . $_SESSION['INGRESO']['periodo'] . "' ";
        
    }





}
?>