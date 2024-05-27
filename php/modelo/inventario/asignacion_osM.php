<?php

require_once (dirname(__DIR__, 2) . "/db/db1.php");
require_once (dirname(__DIR__, 2) . "/funciones/funciones.php");

class asignacion_osM
{

    private $db;

    public function __construct()
    {

        $this->db = new db();

    }

    public function tipoBeneficiario($query = ''): array
    {
        $sql = "SELECT DISTINCT TOP 100 C.Codigo, C.CodigoA, C.Cliente, C.CI_RUC, CD.Fecha_Registro, CD.Envio_No,CP3.Proceso as 'Frecuencia',CD.CodigoA as CodigoACD,CP4.Proceso as'TipoEntega' ,CD.Beneficiario, CD.No_Soc, CD.Area, CD.Acreditacion,CP1.Proceso as 'AccionSocial', CD.Tipo, CD.Cod_Fam,CP2.Proceso as 'TipoAtencion', CD.Salario, CD.Descuento, CD.Evidencias, CD.Item,C.Actividad,CP.Proceso as 'TipoBene',CP.Color,CP.Picture 
            FROM Clientes as C INNER JOIN Clientes_Datos_Extras as CD ON C.Codigo = CD.Codigo 
            LEFT JOIN Catalogo_Proceso CP ON C.Actividad = CP.Cmds 
            LEFT JOIN Catalogo_Proceso CP1 ON CD.Acreditacion = CP1.Cmds 
            LEFT JOIN Catalogo_Proceso CP2 ON CD.Cod_Fam = CP2.Cmds 
            LEFT JOIN Catalogo_Proceso CP3 ON CD.Envio_No = CP3.Cmds 
            LEFT JOIN Catalogo_Proceso CP4 ON CD.CodigoA = CP4.Cmds 
            WHERE CD.Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND CD.Item = CP.Item 
            AND CD.Item = CP1.Item 
            AND CD.Item = CP2.Item 
            AND CD.Item = CP3.Item 
            AND CD.Item = CP4.Item  ";
        if ($query != '') {
            if (!is_numeric($query)) {
                $sql .= " AND C.Cliente LIKE '%" . $query . "%'";
            } else {
                $sql .= " AND C.CI_RUC LIKE '%" . $query . "%'";
            }
        }

        $sql .= " ORDER BY C.Cliente";

        // print_r($sql);die();
        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function datosExtra($consulta)
    {
        $sql = "SELECT Proceso, Cmds, TP, Color 
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CMDS IN " . $consulta . "
                ORDER BY TP";
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    function listaAsignacion($beneficiario)
    {
         $sql = "SELECT ".Full_Fields("Detalle_Factura")."
                FROM Detalle_Factura
                WHERE Item = '".$_SESSION['INGRESO']['item']."' 
                AND Periodo='".$_SESSION['INGRESO']['periodo']."'
                AND CodigoC = '".$beneficiario."'";
        try{
            return $this->db->datos($sql);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    function eliminarLinea($id)
    {
        $sql = "DELETE FROM Detalle_Factura WHERE ID = '".$id."'";
        return $this->db->String_Sql($sql);
    }



}