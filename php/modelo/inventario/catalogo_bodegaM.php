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

    function GuardarConcepto($parametros)
    {
        $sql = "INSERT INTO Catalogo_Proceso(DC, Cmds, Proceso, TP, Nivel) 
                VALUES ('" . $parametros['tipo'] . "', '" . $parametros['codigo'] . "', '" . $parametros['concepto'] . "', 'CATE', '0')";
        return $this->db->datos($sql);
    }
}

?>