<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");


class FRecaudacionBancosPreFaM
{
    private $db;
   

    public function __construct()
    {

        $this->db = new db();
        
    }

    public function DCLinea(){
        $sql = "SELECT *
                FROM Catalogo_Lineas
                WHERE TL <> 0
                AND Fact NOT IN ('CP','NC','LC')
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function AdoProducto(){
        $sql = "SELECT * 
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P'
                ORDER BY Codigo_Inv";
        return $this->db->datos($sql);
    }

    public function DCBanco(){
        $sql = "SELECT *
                FROM Catalogo_Cuentas
                WHERE TC = 'BA'
                AND DG = 'D' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function DCGrupos(){
        $sql = "SELECT Grupo, Count(Grupo) As Cantidad
                FROM Clientes
                WHERE FA <> 0";
        if($_SESSION['INGRESO']['Mas_Grupos']){
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        $sql .= "GROUP BY Grupo
                ORDER BY Grupo";
        return $this->db->datos($sql);
    }

    public function DCEntidadBancaria(){
        $sql = "SELECT Descripcion, Abreviado, ID
                FROM Tabla_Referenciales_SRI
                WHERE Tipo_Referencia = 'BANCOS Y COOP'
                AND Abreviado <> '.'
                AND TPFA <> '0'
                ORDER BY Descripcion";
        return $this->db->datos($sql);
    }

    public function MBFechaI_LostFocus($parametros){
        $sql = "SELECT * 
                FROM Catalogo_Lineas
                WHERE TL <> '0'
                AND '" . BuscarFecha($parametros['MBFechaI']) . "' <= Vencimiento
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact IN ('NV','FA')
                ORDER BY Codigo";
        return $this->db->datos($sql);

    }
}
?>