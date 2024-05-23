<?php 
    require_once(dirname(__DIR__, 2) . "/db/db1.php");
    @session_start();

    class InscVoluntariosM{
        private $db;

        function __construct(){
            $this->db = new db();
        }

        function getCatalogoForm(){
            $sql = "SELECT * 
            FROM Catalogo_Auditor1
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
            ORDER BY Codigo"; //TODO: Cambiar Catalogo_Auditor1
            return $this->db->datos($sql);
        }
    }
?>