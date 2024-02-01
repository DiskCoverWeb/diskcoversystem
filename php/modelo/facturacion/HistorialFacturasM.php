<?php
/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * FECHA CREACION : 30/01/2024
 * FECHA MODIFICACION : 20/02/2024
 * DESCIPCION : Clase que se encarga de manejar el Historial de Facturas
 */
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");
@session_start();

class HistorialFacturasM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function CheqAbonos_Click()
    {
        $sSQL = "SELECT (TA.Cta + ' - ' + CC.Cuenta) As NomCxC 
                FROM Trans_Abonos As TA, Catalogo_Cuentas As CC 
                WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CC.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND CC.Periodo = TA.Periodo 
                AND CC.Item = TA.Item 
                AND CC.Codigo = TA.Cta 
                GROUP BY Cta,Cuenta  
                ORDER BY Cta ";

        return $this->db->datos($sSQL);
    }

    function CheqCxC_Click()
    {
        $sSQL = "SELECT (F.Cta_CxP + ' - ' + CC.Cuenta) As NomCxC 
                FROM Facturas As F,Catalogo_Cuentas As CC 
                WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CC.Item = F.Item 
                AND CC.Codigo = F.Cta_CxP 
                GROUP BY F.Cta_CxP,Cuenta 
                ORDER BY F.Cta_CxP ";

        return $this->db->datos($sSQL);
    }

    function Historico_Facturas($FechaFin)
    {
        print_r('HOLA MUNDO');
        $sSQL = "SELECT C.Cliente, F.T, F.Serie, F.Factura, F.Fecha, Fecha_V, F.Total_MN As Total, F.Total_Efectivo, F.Total_Banco,
                F.Total_Ret_Fuente, F.Total_Ret_IVA_B, F.Total_Ret_IVA_S, F.Otros_Abonos, F.Total_Abonos,F.Saldo_Actual, 
                F.Fecha_C As Abonado_El, F.CodigoC, C.CI_RUC, F.TC, F.Autorizacion, C.Grupo, A.Nombre_Completo As Ejecutivo, C.Ciudad, 
                C.Plan_Afiliado As Sectorizacion, F.Cta_CxP, C.EMail, C.EMail2, C.EMailR, C.Representante 
                FROM Facturas As F, Clientes As C, Accesos As A 
                WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.Fecha <= '" . $FechaFin . "' 
                Tipo_De_Consulta()
                AND F.CodigoC = C.Codigo 
                AND F.Cod_Ejec = A.Codigo 
                ORDER BY C.Cliente, F.Serie, F.Factura, F.Fecha";

        return $this->db->datos($sSQL);
    }


}
?>