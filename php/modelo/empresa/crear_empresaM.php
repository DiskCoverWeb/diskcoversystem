<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
include(dirname(__DIR__,2).'/funciones/funciones.php');
class crear_empresaM
{
    private $db;
    function __construct()
    {
        $this->db = new db();
    }

    function lista_empresas($query)
    {
        $sql = "SELECT *
        FROM Empresas WHERE Item <> '000'";
        if($query)
        {
            $sql.=" AND Empresa like '%".$query."%'";
        }
        return  $this->db->datos($sql);
    }
    
}

?>