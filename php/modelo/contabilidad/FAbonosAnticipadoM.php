<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
@session_start();

class FAbonosAnticipadoM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function SelectDB_Combo_DCCtaAnt() //Para DCCtaAnt
    {
        $sql = "SELECT (Codigo + ' ' + Cuenta) As NomCuenta
                FROM Catalogo_Cuentas
                WHERE TC = 'P'
                AND DG = 'D'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY TC DESC,Codigo";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCBanco()
    {
        $sql = "SELECT (Codigo + ' ' + Cuenta) as NomCuenta
                FROM Catalogo_Cuentas
                WHERE TC IN ('CJ','BA','TJ')
                AND DG = 'D'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY TC DESC,Codigo";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCTipo($fa_factura)
    {
        $sql = "SELECT TC
                FROM Facturas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'OP'
                AND Factura = '" . $fa_factura . "'
                GROUP BY TC
                GROUP BY DESC";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCClientes()
    {
        $sql = "SELECT Grupo,Codigo,Cliente,Email,Email2
                FROM Clientes
                WHERE FA <> '0' 
                ORDER BY Cliente"; // <> adFalse linea 56
        return $this->db->datos($sql);
    }

    function Select_Adodc_AdoIngCaja_Catalogo_CxCxp($codigo_cliente, $sub_cta_gen)
    {
        $sql = "SELECT *
                FROM Catalogo_CxCxp
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Codigo = '" . $codigo_cliente . "'
                AND Cta = '" . $sub_cta_gen . "'
                AND TC = 'P' ";
        return $this->db->datos($sql);
    }

    function Select_AsientoSC($codigo_usuario, $sub_cta_gen, $trans_no)
    {
        $sql = "SELECT *
            FROM Asiento_SC
            WHERE TC = 'P'
            AND Cta = '" . $sub_cta_gen . "'
            AND DH = '2'
            AND TM= '1'
            AND T_No = '" . $trans_no . "'
            AND Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND CodigoU = '" . $codigo_usuario . "' ";
        return $this->db->datos($sql);
    }

}
?>