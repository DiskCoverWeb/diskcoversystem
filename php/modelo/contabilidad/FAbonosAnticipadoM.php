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
                ORDER BY TC DESC";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCClientes($grupo = G_NINGUNO)
    {
        $sql = "SELECT Grupo,Codigo,Cliente,Email,Email2
                FROM Clientes
                WHERE FA <> '0'";
        if($grupo <> G_NINGUNO){
            $sql .= "AND GRUPO = '" . $grupo . "'";
        } 
            $sql .= "ORDER BY Cliente"; // <> adFalse linea 56
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

    function Select_Adodc_AdoIngCaja_Asiento_SC($sub_cta_gen, $trans_no)
    {
        $sql = "SELECT *
            FROM Asiento_SC
            WHERE TC = 'P'
            AND Cta = '" . $sub_cta_gen . "'
            AND DH = '2'
            AND TM= '1'
            AND T_No = '" . $trans_no . "'
            AND Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' ";
        return $this->db->datos($sql);
    }

    function Select_Adocdc_AdoIngCaja_Asiento($trans_no)
    {
        $sql = "SELECT *
                FROM Asiento
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                AND T_No = '" . $trans_no . "'";
        return $this->db->datos($sql);
    }

    function SelectDB_Combo_DCFactura_AdoFactura($TipoFactura, $fa_factura = false){
        $sql = "SELECT F.TC,F.Factura,F.CodigoC,F.Fecha,F.Fecha_V,F.Saldo_MN,
        F.Cta_CxP,F.Nota,F.Observacion,C.Cliente,C.Direccion,C.CI_RUC,C.Telefono,
        C.Grupo
        FROM Facturas As F, Clientes As C
        WHERE F.T = 'P'
        AND F.Item = '".$_SESSION['INGRESO']['item']."'
        AND F.TC = '" . $TipoFactura . "'
        AND F.Periodo =  '".$_SESSION['INGRESO']['periodo']."'";
        if($TipoFactura == "OP"){
            $sql .= "AND F.Factura = '" . $fa_factura . "'";
        }
        $sql .= "AND F.CodigoC = C.Codigo
                 ORDER BY F.TC,F.Factura";
        return $this->db->datos($sql);

    }

}
?>