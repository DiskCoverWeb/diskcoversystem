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
        //SelectDB_Combo DCCxC, AdoCxC, sSQL, "NomCxC"
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
        //SelectDB_Combo DCCxC, AdoCxC, sSQL, "NomCxC"
        return $this->db->datos($sSQL);
    }

    function Historico_Facturas($FechaFin)
    {
        $sSQL = "SELECT C.Cliente, F.T, F.Serie, F.Factura, F.Fecha, Fecha_V, F.Total_MN As Total, F.Total_Efectivo, F.Total_Banco,
                F.Total_Ret_Fuente, F.Total_Ret_IVA_B, F.Total_Ret_IVA_S, F.Otros_Abonos, F.Total_Abonos,F.Saldo_Actual, 
                F.Fecha_C As Abonado_El, F.CodigoC, C.CI_RUC, F.TC, F.Autorizacion, C.Grupo, A.Nombre_Completo As Ejecutivo, C.Ciudad, 
                C.Plan_Afiliado As Sectorizacion, F.Cta_CxP, C.EMail, C.EMail2, C.EMailR, C.Representante 
                FROM Facturas As F, Clientes As C, Accesos As A 
                WHERE F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.Fecha <= '" . $FechaFin . "' 
                " . Tipo_De_Consulta() . "
                AND F.CodigoC = C.Codigo 
                AND F.Cod_Ejec = A.Codigo 
                ORDER BY C.Cliente, F.Serie, F.Factura, F.Fecha";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        return $this->db->datos($sSQL);
    }

    function Ventas_Productos($Si_No, $FechaIni, $FechaFin, $Con_Costeo, $CodigoInv)
    {
        $sSQL = "SELECT F.T, CL.Cliente, F.TC As Doc, F.Serie, F.Factura, F.Fecha, F.Codigo, F.Producto, F.Mes, F.Cantidad, F.Total, 0 As Total_NC, 
                (Total_Desc+Total_Desc2) As Descuento, (F.Total-Total_Desc-Total_Desc2) As SubTotal, C.Marca, 
                C.Desc_Item As Parte, F.Lote_No, F.Fecha_Fab, F.Fecha_Exp, C.Reg_Sanitario, F.Serie_No + $Con_Costeo";

        if ($Si_No) {
            $sSQL .= ",F.Precio, Valor_Compra As Costos";
        }

        $sSQL .= "FROM Detalle_Factura As F, Catalogo_Productos As C, Clientes As CL 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'         
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . Tipo_De_Consulta(null, null, true) . "
                AND C.INV <> 0 
                AND F.T <> '" . G_ANULADO . "' ";

        if ($CodigoInv != G_NINGUNO) {
            $sSQL .= "AND C.Codigo_Inv = '" . $CodigoInv . "' ";
        }

        $sSQL .= "AND F.Item = C.Item 
                AND F.Periodo = C.Periodo 
                AND F.Codigo = C.Codigo_Inv 
                AND F.CodigoC = CL.Codigo 
                UNION ALL 
                SELECT F.T, CL.Cliente, F.TP As Doc, F.Serie, F.Factura, F.Fecha, F.Cta As Codigo, (F.Banco + ' - ' + F.Cheque) AS Producto_Aux, 
                F.Mes, 1 As Cantidad, 0 As Total, -F.Abono As Total_NC, 0 As Descuento, -F.Abono As SubTotal, '.' As Marca, 
                '.' As Parte, '.' As Lote_No, F.Fecha As Fecha_Fab, F.Fecha As Fecha_Exp, '.' As Reg_Sanitario, '.' As Serie_No ";

        if ($Si_No) {
            $sSQL .= ", 0 As Precio, 0 As Costos ";
        }

        $sSQL .= "FROM Trans_Abonos AS F, Clientes AS CL 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND F.Banco = '" . G_ANULADO . "'
                " . Tipo_De_Consulta() . "
                AND F.T <> '" . G_ANULADO . "'
                AND F.CodigoC = CL.Codigo
                ORDER BY Doc, F.Factura, F.Fecha";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        return $this->db->datos($sSQL);
    }

    function Abonos_Facturas($FechaIni, $FechaFin, $SQL_Server, $Ret_NC)
    {
        $PorCxC = true;
        $DGQueryCaption = "ABONOS DE FACTURAS";
        for ($IDMes = 1; $IDMes <= 12; $IDMes++) {
            if ($SQL_Server) {
                $sSQL = "UPDATE Trans_Abonos 
                    SET Mes = DF.Mes, Mes_No = DF.Mes_No 
                    FROM Trans_Abonos AS TA, Detalle_Factura AS DF ";
            } else {
                $sSQL = "UPDATE Trans_Abonos AS TA, Detalle_Factura AS DF 
                    SET TA.Mes = DF.Mes, TA.Mes_No = DF.Mes_No ";
            }

            $sSQL .= "WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND TA.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TA.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND DF.T <> 'A' 
                AND MONTH(TA.Fecha) = '" . $IDMes . "' 
                AND TA.Item = DF.Item 
                AND TA.Periodo = DF.Periodo 
                AND TA.Factura = DF.Factura 
                AND TA.Serie = DF.Serie 
                AND TA.Autorizacion = DF.Autorizacion 
                AND TA.CodigoC = DF.CodigoC ";

            Ejecutar_SQL_SP($sSQL);
        }
        $Total = 0;

        if ($Ret_NC) {
            $sSQL = "SELECT F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, 
                F.Autorizacion, F.Serie_NC, Secuencial_NC, F.Autorizacion_NC, F.Base_Imponible, F.Porc, C.Representante As Razon_Social, 
                F.Cta, F.Cta_CxP 
                FROM Trans_Abonos As F, Clientes C 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . Tipo_De_Consulta(true) . "
                AND SUBSTRING(F.Banco, 1, 9) = 'RETENCION' 
                AND F.CodigoC = C.Codigo 
                UNION 
                SELECT F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, 
                F.Autorizacion, F.Serie_NC, Secuencial_NC, F.Autorizacion_NC, F.Base_Imponible, F.Porc, C.Representante As Razon_Social, 
                F.Cta, F.Cta_CxP 
                FROM Trans_Abonos As F, Clientes C 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . Tipo_De_Consulta(true) . "
                AND F.Banco = 'NOTA DE CREDITO' 
                AND F.CodigoC = C.Codigo 
                ORDER BY F.Banco, F.Cheque, C.Cliente, F.Factura, F.Fecha ";
        } else {
            $sSQL = "SELECT F.TP, F.Fecha, C.Cliente, F.Serie, F.Factura, F.Banco, F.Cheque, F.Abono, F.Mes, F.Comprobante, 
                F.Autorizacion, F.Serie_NC, Secuencial_NC, F.Autorizacion_NC, C.Representante As Razon_Social, F.Cta, 
                F.Cta_CxP 
                FROM Trans_Abonos As F, Clientes C 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                " . Tipo_De_Consulta(true) . "
                AND F.CodigoC = C.Codigo 
                ORDER BY C.Cliente, F.Factura, F.Fecha, F.Banco ";
        }
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        return $this->db->datos($sSQL);
        //$LabelAbonado = number_format($Total, 2, '.', ',');
        //$LabelFacturado = "0.00";
        //$LabelSaldo = "0.00";
        //$DGQueryVisible = true;
    }

    function Recibo_Abonos_Anticipados($FechaIni, $FechaFin, $Co)
    {
        $sSQL = "SELECT C.Cliente, C.Email, C.Email2, C.CI_RUC, TS.Cta, TS.Fecha, TS.TP, TS.Numero, TS.Creditos As Abono, Co.Concepto 
                FROM Trans_SubCtas AS TS, Comprobantes AS Co, Clientes AS C 
                WHERE TS.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND TS.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND TS.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND TS.TP = '" . $Co['TP'] . "' 
                AND TS.Numero = '" . $Co['Numero'] . "'
                AND TS.T <> 'A' 
                " . Tipo_De_Consulta() . "
                AND TS.Item = Co.Item 
                AND TS.Periodo = Co.Periodo 
                AND TS.TP = Co.TP 
                AND TS.Numero = Co.Numero 
                AND TS.Codigo = C.Codigo 
                ORDER BY C.Cliente, TS.Cta, TS.Fecha, TS.TP, TS.Numero";
        //Select_Adodc AdoFacturas, sSQL
        return $this->db->datos($sSQL);
    }

    function Abonos_Anticipados($FechaIni, $FechaFin)
    {
        $sSQL = "SELECT TA.TP, F.Serie, F.Autorizacion, F.Fecha, F.Factura, TA.Fecha AS Fecha_Abono, TA.Abono 
            FROM Facturas AS F, Trans_Abonos AS TA 
            WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
            AND F.Item = '" . $_SESSION['INGRESO']['item'] . "'  
            AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND F.Item = TA.Item 
            AND F.Periodo = TA.Periodo 
            AND F.TC = TA.TP 
            AND F.Serie = TA.Serie 
            AND F.Autorizacion = TA.Autorizacion 
            AND F.Factura = TA.Factura 
            AND F.Fecha > TA.Fecha
            ORDER BY TA.Fecha, F.Factura";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        return $this->db->datos($sSQL);
    }

    function Abonos_Erroneos($FechaIni, $FechaFin, $SQL_Server)
    {
        $sSQL = "UPDATE Trans_Abonos 
                SET X = 'E' 
                WHERE Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";

        Ejecutar_SQL_SP($sSQL);

        if ($SQL_Server) {
            $sSQL = "UPDATE Trans_Abonos 
                    SET X = '.' 
                    FROM Trans_Abonos AS TA, Facturas AS F ";
        } else {
            $sSQL = "UPDATE Trans_Abonos AS TA, Facturas AS F 
                    SET TA.X = '.' ";
        }

        $sSQL .= "WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND F.Item = TA.Item 
                AND F.Periodo = TA.Periodo 
                AND F.TC = TA.TP 
                AND F.Serie = TA.Serie 
                AND F.Autorizacion = TA.Autorizacion 
                AND F.Factura = TA.Factura 
                AND F.CodigoC = TA.CodigoC";

        Ejecutar_SQL_SP($sSQL);

        $sSQL = "SELECT TP, Serie, Autorizacion, Fecha, Factura, Abono, CodigoC
                FROM Trans_Abonos 
                WHERE Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND X = 'E' 
                AND TP <> 'CB' 
                ORDER BY Fecha, Factura";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        return $this->db->datos($sSQL);
    }

    function Resumen_Productos($FechaIni, $FechaFin)
    {
        $sSQL = "SELECT C.Cliente, SUM(F.Cantidad) AS Cant_Prod, CP.Producto, F.Codigo, SUM(F.Total_IVA) AS IVA, 
                SUM(F.Total) AS Ventas, SUM(F.Cantidad*CP.Gramaje/1000) AS Kilos, CP.Gramaje 
                FROM Clientes AS C, Detalle_Factura AS F, Catalogo_Productos AS CP 
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                " . Tipo_De_Consulta(null, null, true) . "
                AND F.CodigoC = C.Codigo 
                AND F.Item = CP.Item 
                AND F.Periodo = CP.Periodo 
                AND F.Codigo = CP.Codigo_Inv 
                GROUP BY C.Cliente, F.Codigo, F.CodigoC, CP.Producto, CP.Gramaje 
                ORDER BY C.Cliente, F.Codigo, F.CodigoC, CP.Producto, CP.Gramaje";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        return $this->db->datos($sSQL);
    }

    function Ventas_Cliente($FechaIni, $FechaFin)
    {
        $sSQL = "SELECT C.Cliente, F.TC, COUNT(F.CodigoC) AS Cant_Fact, SUM(F.Total) AS Ventas, 
                SUM(F.Total_IVA) AS I_V_A, SUM(F.Total + F.Total_IVA) AS Total_Facturado 
         FROM Detalle_Factura AS F, Clientes AS C 
         WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
           AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
           AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
           " . Tipo_De_Consulta(null, null, true) . "
           AND F.CodigoC = C.Codigo 
         GROUP BY C.Cliente, F.TC 
         ORDER BY SUM(F.Total + F.Total_IVA) DESC, C.Cliente";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        return $this->db->datos($sSQL);
    }

    function Resumen_Prod_Meses($FechaIni, $FechaFin, $SQL_Server, $PorCantidad, $MBFechaF)
    {
        $sSQL = "UPDATE Catalogo_Productos 
                SET X = '.' 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND TC = 'P' ";

        Ejecutar_SQL_SP($sSQL);

        if ($SQL_Server) {
            $sSQL = "UPDATE Catalogo_Productos
                    SET X = 'X' 
                    FROM Catalogo_Productos As CP, Detalle_Factura As DF ";
        } else {
            $sSQL = "UPDATE Catalogo_Productos As CP, Detalle_Factura As DF
                    SET CP.X = 'X' ";
        }

        $sSQL .= "WHERE DF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                AND CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND CP.TC = 'P' 
                AND CP.Item = DF.Item 
                AND CP.Periodo = DF.Periodo 
                AND CP.Codigo_Inv = DF.Codigo ";

        Ejecutar_SQL_SP($sSQL);

        $Nom_Mes = [
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        ];

        $sSQLx = implode(",", $Nom_Mes) . ",Total";

        $sSQL = "DELETE FROM Saldo_Diarios
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND TP = 'RPXM'";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "INSERT INTO Saldo_Diarios (TC, Codigo_Aux, Item, CodigoU, TP, Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, 
                Agosto, Septiembre, Octubre, Noviembre, Diciembre, Total) 
                SELECT TC, Codigo_Inv, '" . $_SESSION['INGRESO']['item'] . "' AS Itemx, '" . $_SESSION['INGRESO']['CodigoU'] . "' AS CodigoUs, 'RPXM' AS TPs,
                0 AS Enerox, 0 AS Febrerox, 0 AS Marzox, 0 AS Abrilx, 0 AS Mayox, 0 AS Juniox, 0 AS Juliox, 
                0 AS Agostox, 0 AS Septiembrex, 0 AS Octubrex, 0 AS Noviembrex, 0 AS Diciembrex, 0 AS Totalx 
                FROM Catalogo_Productos 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND X = 'X'";

        Ejecutar_SQL_SP($sSQL);

        $sSQL = "SELECT * " .
            "FROM Saldo_Diarios " .
            "WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' " .
            "AND TP = 'RPXM'";
        //Select_Adodc AdoQuery1, sSQL
        for ($NoMes = 1; $NoMes <= 12; $NoMes++) {
            $sSQL = "UPDATE Saldo_Diarios ";
            if ($PorCantidad) {
                $sSQL .= "SET " . $Nom_Mes[$NoMes] . " = (SELECT SUM(Cantidad) ";
            } else {
                $sSQL .= "SET " . $Nom_Mes[$NoMes] . " = (SELECT SUM(Total-Total_Desc-Total_Desc2) ";
            }
            $sSQL .= "FROM Detalle_Factura AS F
                    WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                    AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND F.T <> '" . G_ANULADO . "' 
                    AND MONTH(F.Fecha) = " . $NoMes . " 
                    AND F.Codigo = Saldo_Diarios.Codigo_Aux 
                    AND F.Item = Saldo_Diarios.Item) 
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                    AND TP = 'RPXM'";
            Ejecutar_SQL_SP($sSQL);

            $sSQL = "UPDATE Saldo_Diarios 
                    SET " . $Nom_Mes[$NoMes] . " = 0 
                    WHERE " . $Nom_Mes[$NoMes] . " IS NULL 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sSQL);
        }

        $sSQLx = "Total=" . implode("+", $Nom_Mes);

        $sSQL = "UPDATE Saldo_Diarios 
                SET " . $sSQLx . " 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND TP = 'RPXM'";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "DELETE FROM Saldo_Diarios
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND Total = 0 
                AND TP = 'RPXM'";
        Ejecutar_SQL_SP($sSQL);

        $sSQLx = "";
        for ($NoMes = 1; $NoMes <= date("n", strtotime($MBFechaF)); $NoMes++) {
            $sSQLx .= ",SD." . $Nom_Mes[$NoMes];
        }

        $sSQL = "SELECT SD.Codigo_Aux AS Codigos, CP.Producto, CP.Unidad " . $sSQLx . ",SD.Total
            FROM Saldo_Diarios AS SD, Catalogo_Productos AS CP 
            WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND SD.CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND SD.TP = 'RPXM' 
            AND SD.Codigo_Aux = CP.Codigo_Inv 
            AND SD.Item = CP.Item 
            ORDER BY SD.Total DESC, CP.Producto, SD.Codigo_Aux";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
    }

    function Ventas_Clientes_Por_Meses($SQL_Server, $FechaIni, $FechaFin, $FA, $MBFechaF)
    {
        $sSQL = "UPDATE Clientes
                SET X = '.' 
                WHERE FA <> " . intval(false);
        Ejecutar_SQL_SP($sSQL);

        //$DGQuery->Visible = false;

        if ($SQL_Server) {
            $sSQL = "UPDATE C 
                    SET X = 'X', FA = 1
                    FROM Clientes AS C, Facturas AS F ";
        } else {
            $sSQL = "UPDATE Clientes AS C, Facturas AS F
                    SET C.X = 'X' ";
        }

        $sSQL .= "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND C.FA <> " . intval(false) . " 
                AND C.Codigo = F.CodigoC ";
        Ejecutar_SQL_SP($sSQL);

        $Nom_Mes = [
            1 => "Enero",
            2 => "Febrero",
            3 => "Marzo",
            4 => "Abril",
            5 => "Mayo",
            6 => "Junio",
            7 => "Julio",
            8 => "Agosto",
            9 => "Septiembre",
            10 => "Octubre",
            11 => "Noviembre",
            12 => "Diciembre"
        ];

        $sSQLx = implode(",", $Nom_Mes) . ",Total";

        $sSQL = "DELETE * 
                FROM Saldo_Diarios 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND TP = 'VCXM' ";

        Ejecutar_SQL_SP($sSQL);

        $sSQL = "INSERT INTO Saldo_Diarios (Cta, CodigoC, Item, CodigoU, TP, Enero, Febrero, Marzo, Abril, Mayo, Junio, Julio, 
                Agosto, Septiembre, Octubre, Noviembre, Diciembre, Total) 
                SELECT Cod_Ejec, Codigo,'" . $_SESSION['INGRESO']['item'] . "' AS Itemx,'" . $_SESSION['INGRESO']['CodigoU'] . "' AS CodigoUs,'VCXM' AS TPs,
                0 AS Enerox, 0 AS Febrerox, 0 AS Marzox, 0 AS Abrilx, 0 AS Mayox, 0 AS Juniox, 0 AS Juliox, 
                0 AS Agostox, 0 AS Septiembrex, 0 AS Octubrex, 0 AS Noviembrex, 0 AS Diciembrex, 0 AS Totalx 
                FROM Clientes 
                WHERE FA <> 0
                AND X = 'X' ";

        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQL .= "AND Cod_Ejec = '" . $FA['Cod_Ejec'] . "' ";
        }
        if (strlen($FA['CodigoC']) > 1) {
            $sSQL .= "AND Codigo = '" . $FA['CodigoC'] . "' ";
        }
        if (strlen($FA['Grupo']) > 1) {
            $sSQL .= "AND Grupo = '" . $FA['Grupo'] . "' ";
        }
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "SELECT * 
                FROM Saldo_Diarios 
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                AND TP = 'VCXM'";
        //Select_Adodc AdoQuery1, sSQL

        for ($NoMes = 1; $NoMes <= 12; $NoMes++) {
            $sSQL = "UPDATE Saldo_Diarios 
                    SET " . $Nom_Mes[$NoMes] . " = (SELECT SUM(Total_MN-IVA) 
                    FROM Facturas AS F 
                    WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                    AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                    AND F.T <> '" . G_ANULADO . "' 
                    AND MONTH(F.Fecha) = " . $NoMes . " 
                    AND F.CodigoC = Saldo_Diarios.CodigoC 
                    AND F.Item = Saldo_Diarios.Item) 
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                    AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                    AND TP = 'VCXM'";
            Ejecutar_SQL_SP($sSQL);

            $sSQL = "UPDATE Saldo_Diarios 
                    SET " . $Nom_Mes[$NoMes] . " = 0 
                    WHERE " . $Nom_Mes[$NoMes] . " IS NULL 
                    AND Item = '" . $_SESSION['INGRESO']['item'] . "'";
            Ejecutar_SQL_SP($sSQL);
        }

        $sSQLx = "Total=" . implode("+", $Nom_Mes);

        $sSQL = "UPDATE Saldo_Diarios
                SET " . $sSQLx . "
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND TP = 'VCXM' ";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "UPDATE Saldo_Diarios 
            SET Grupo_No = RP.Cod_Ejec 
            FROM Saldo_Diarios As SD, Catalogo_Rol_Pagos As RP 
            WHERE RP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND RP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
            AND SD.Cta = RP.Codigo 
            AND SD.Item = RP.Item ";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "DELETE * 
            FROM Saldo_Diarios 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND Total = 0 
            AND TP = 'VCXM' ";
        Ejecutar_SQL_SP($sSQL);

        $sSQL = "SELECT * 
            FROM Saldo_Diarios 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
            AND TP = 'VCXM' ";
        //Select_Adodc AdoQuery1, sSQL

        $sSQLx = "";
        for ($NoMes = 1; $NoMes <= date("n", strtotime($MBFechaF)); $NoMes++) {
            $sSQLx .= ",SD." . $Nom_Mes[$NoMes];
        }

        $sSQL = "SELECT SD.Grupo_No AS Ejecutivo, C.Grupo, C.Cliente 
                " . $sSQLx . ", SD.Total, SD.Diferencia AS Promedio 
                FROM Saldo_Diarios AS SD, Clientes AS C 
                WHERE SD.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND SD.CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' ";

        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQL .= "AND SD.Cta = '" . $FA['Cod_Ejec'] . "' ";
        }
        if (strlen($FA['CodigoC']) > 1) {
            $sSQL .= "AND SD.Codigo = '" . $FA['CodigoC'] . "' ";
        }

        $sSQL .= "AND SD.TP = 'VCXM'
            AND SD.CodigoC = C.Codigo 
            ORDER BY SD.Total DESC, SD.Grupo_No, C.Grupo, C.Cliente";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
    }

    function Resumen_Ventas_Costos($FechaFin, $Con_Costeo, $Si_No, $FechaIni, $DescItem)
    {

        $sSQL = "SELECT * 
                FROM Catalogo_Productos 
                WHERE TC = 'P' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                ORDER BY Codigo_Inv ";
        //Select_Adodc AdoHistoria, sSQL

        $sSQL = "SELECT * 
                FROM Trans_Kardex 
                WHERE Fecha <= '" . $FechaFin . "'
                AND T <> 'A' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                RDER BY Codigo_Inv,Fecha,ID ";
        //Select_Adodc AdoQuery, sSQL

        $sSQL = "SELECT F.Codigo, CP.Producto, SUM(F.Cantidad) AS Cant_Prod, SUM(F.Total) AS Ventas, 
                SUM(F.Cantidad*CP.Gramaje/1000) AS Kilos, CP.Desc_Item " . $Con_Costeo;

        if ($Si_No) {
            $sSQL .= ", AVG(F.Precio) AS PVP, Valor_Compra AS Costos ";
        }

        $sSQL .= "FROM Detalle_Factura AS F, Catalogo_Productos AS CP, Clientes AS C
                WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                AND CP.INV <> 0 
                " . Tipo_De_Consulta(null, null, true) . " ";

        if ($DescItem != G_NINGUNO) {
            $sSQL .= "AND CP.Desc_Item = '" . $DescItem . "' ";
        }

        $sSQL .= "AND F.Item = CP.Item 
                AND F.Periodo = CP.Periodo 
                AND F.Codigo = CP.Codigo_Inv 
                AND F.CodigoC = C.Codigo ";

        if ($Si_No) {
            if ($DescItem != G_NINGUNO) {
                $sSQL .= "GROUP BY CP.Desc_Item, F.Codigo, CP.Valor_Compra ";
            } else {
                $sSQL .= "GROUP BY F.Codigo, CP.Valor_Compra, CP.Producto, CP.Desc_Item ";
            }
        } else {
            if ($DescItem != G_NINGUNO) {
                $sSQL .= "GROUP BY CP.Desc_Item, F.Codigo, CP.Producto ";
            } else {
                $sSQL .= "GROUP BY F.Codigo, CP.Producto, CP.Desc_Item ";
            }
        }

        if ($DescItem != G_NINGUNO) {
            $sSQL .= "ORDER BY CP.Desc_Item, F.Codigo, SUM(F.Total) DESC ";
        } else {
            $sSQL .= "ORDER BY F.Codigo, SUM(F.Total) DESC, CP.Producto ";
        }
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
    }

    function Resumen_Ventas_Vendedor($FechaIni, $FechaFin)
    {
        $sSQL = "SELECT C.Grupo,C.Cliente, F.Fecha, TA.Fecha As Fecha_A, F.Serie, TA.Factura, CONVERT(Money,TA.Abono/(1+F.Porc_IVA)) As Abonos, 
         DATEDIFF(day,F.Fecha,TA.Fecha) As Dias_T, A.Nombre_Completo 
         FROM Clientes As C, Facturas As F, Trans_Abonos As TA, Accesos As A 
          WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
          AND TA.Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND TA.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
          AND NOT SUBSTRING(TA.Banco,1,9) IN ('RETENCION','NOTA DE C') 
          " . Tipo_De_Consulta() . "
          AND C.Codigo = F.CodigoC 
          AND A.Codigo = F.Cod_Ejec 
          AND F.Item = TA.Item 
          AND F.Periodo = TA.Periodo 
          AND F.TC = TA.TP 
          AND F.Serie = TA.Serie 
          AND F.Autorizacion = TA.Autorizacion 
          AND F.Factura = TA.Factura 
          AND F.CodigoC = TA.CodigoC 
          ORDER BY C.Grupo,C.Cliente,F.Serie,F.Factura ";
        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True
        //Opcion = 15
    }

    function Ventas_Resumidas_x_Vendedor($FechaIni, $FechaFin)
    {
        $sSQL = "UPDATE Accesos 
            SET Cuota_Venta = 1 
            WHERE Cuota_Venta = 0 ";
        Ejecutar_SQL_SP($sSQL);

        $sSQLGrupo = "SELECT A.Cod_Ejec, A.Nombre_Completo AS Nombre_Vendedor, C.Grupo, CC.Cuenta, 
                    SUM(F.SubTotal - F.Descuento - F.Descuento2) AS Cantidad, ' ' AS Cuota 
              FROM Facturas AS F, Catalogo_Cuentas AS CC, Accesos AS A, Clientes AS C 
              WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
              AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
              AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
              AND F.T <> '" . G_ANULADO . "' 
              AND A.Codigo = F.Cod_Ejec 
              AND C.Codigo = F.CodigoC 
              AND F.Item = CC.Item 
              AND F.Periodo = CC.Periodo 
              AND F.Cta_CxP = CC.Codigo 
              GROUP BY C.Grupo, A.Cod_Ejec, A.Nombre_Completo, A.Cuota_Venta, CC.Cuenta ";

        $sSQLSubTotal = "SELECT A.Cod_Ejec, ' ' AS Nombre_Vendedor, ' ' AS Grupo, 'SUBTOTAL VENDEDOR' AS Cuenta, 
                         SUM(F.SubTotal - F.Descuento - F.Descuento2) AS Cantidad, 
                         CONCAT(ROUND((SUM(F.SubTotal - F.Descuento - F.Descuento2) / A.Cuota_Venta) * 100), '%') AS Cuota 
                 FROM Facturas AS F, Catalogo_Cuentas AS CC, Accesos AS A, Clientes AS C 
                 WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
                 AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                 AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
                 AND F.T <> '" . G_ANULADO . "' 
                 AND A.Codigo = F.Cod_Ejec 
                 AND C.Codigo = F.CodigoC 
                 AND F.Item = CC.Item 
                 AND F.Periodo = CC.Periodo 
                 AND F.Cta_CxP = CC.Codigo 
                 GROUP BY A.Cod_Ejec, A.Cuota_Venta ";

        $sSQL = "$sSQLGrupo 
         UNION 
         $sSQLSubTotal 
         ORDER BY A.Cod_Ejec, A.Nombre_Completo DESC, C.Grupo, CC.Cuenta";

        //Select_Adodc_Grid DGQuery, AdoQuery, sSQL, , , True;
    }

    function CxC_Tiempo_Credito($Mifecha, $FechaIni, $FechaFin, $FA)
    {
        $sSQL = "UPDATE Facturas 
            SET Venc_0_60=0,Venc_61_90=0,Venc_91_120=0,Venc_121_360=0,Venc_mas_360=0 
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";
        Ejecutar_SQL_SP($sSQL);

        $intervalos = [
            ['Venc_0_60', 0, 60],
            ['Venc_61_90', 61, 90],
            ['Venc_91_120', 91, 120],
            ['Venc_121_360', 121, 360],
            ['Venc_mas_360', 361, PHP_INT_MAX]
        ];

        foreach ($intervalos as $intervalo) {
            list($campo, $minDias, $maxDias) = $intervalo;

            $sSQL = "UPDATE Facturas " .
                "SET " . $campo . " = Saldo_MN " .
                "WHERE DATEDIFF(DAY,Fecha, '" . $Mifecha . "') BETWEEN " . $minDias . " and " . $maxDias . " " .
                "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
                "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
                "AND T = '" . G_PENDIENTE . "' ";
            Ejecutar_SQL_SP($sSQL);
        }

        $sSQLV = "SELECT A.Nombre_Completo AS Nombre_Vendedor, C.Cliente AS Clientes, YEAR(F.Fecha) AS Año, MONTH(F.Fecha) AS Mes, " .
            "SUM(Venc_0_60) AS _0_60, " .
            "SUM(Venc_61_90) AS _61_90, " .
            "SUM(Venc_91_120) AS _61_120, " .
            "SUM(Venc_121_360) AS _121_360, " .
            "SUM(Venc_mas_360) AS _mas_360, " .
            "SUM(Venc_0_60 + Venc_61_90 + Venc_91_120 + Venc_121_360 + Venc_mas_360) AS Saldo_Total, " .
            "SUM(F.Total_MN) AS Total_Facturado " .
            "FROM Facturas AS F, Accesos AS A, Clientes AS C " .
            "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND F.T = '" . G_PENDIENTE . "' ";
        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQLV .= "AND C.Cod_Ejec = '" . $FA['Cod_Ejec'] . "' ";
        }
        $sSQLV .= "AND A.Codigo = F.Cod_Ejec " .
            "AND C.Codigo = F.CodigoC " .
            "GROUP BY A.Nombre_Completo, C.Cliente, YEAR(F.Fecha), MONTH(F.Fecha) ";

        $sSQLT = "SELECT A.Nombre_Completo AS Nombre_Vendedor, 'zz" . str_repeat(" ", 40) . "SUBTOTALES' AS Clientes, " . date('Y', strtotime(FechaSistema())) . " AS Año, " . date('n', strtotime(FechaSistema())) . " AS Mes, " .
            "SUM(Venc_0_60) AS T_Venc_0_60, " .
            "SUM(Venc_61_90) AS T_Venc_61_90, " .
            "SUM(Venc_91_120) AS T_Venc_61_90, " .
            "SUM(Venc_121_360) AS T_Venc_121_360, " .
            "SUM(Venc_mas_360) AS T_Venc_mas_360, " .
            "SUM(Venc_0_60 + Venc_61_90 + Venc_91_120 + Venc_121_360 + Venc_mas_360) AS Saldo_Total, " .
            "SUM(F.Total_MN) AS Total_Facturado " .
            "FROM Facturas AS F, Accesos AS A, Clientes AS C " .
            "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND F.T = '" . G_PENDIENTE . "' ";

        if (strlen($FA['Cod_Ejec']) > 1) {
            $sSQLT .= "AND C.Cod_Ejec = '" . $FA['Cod_Ejec'] . "' ";
        }
        $sSQLT .= "AND A.Codigo = F.Cod_Ejec " .
            "AND C.Codigo = F.CodigoC " .
            "GROUP BY A.Nombre_Completo ";

        $sSQL = $sSQLV . "UNION " . $sSQLT . "ORDER BY A.Nombre_Completo, Clientes ";
        //Select_Adodc_Grid($DGQuery, $AdoQuery, $sSQL);
    }

    function Cheques_Protestados($FechaIni, $FechaFin)
    {
        $sSQL = "SELECT F.TP,F.Fecha,C.Cliente,F.Factura,F.Banco,F.Cheque,F.Abono,F.Comprobante,F.Cta,F.Cta_CxP 
        FROM Trans_Abonos AS F,Clientes C 
        WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' 
        AND F.Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND F.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' 
        " . Tipo_De_Consulta() . "
        AND F.CodigoC = C.Codigo 
        AND F.Protestado <> 0 
        ORDER BY C.Cliente,F.Factura,F.Fecha,F.Banco ";
        //Select_Adodc_Grid($DGQuery, $AdoQuery, $sSQL, null, null, true);
    }

    function CheqIngreso_Click()
    {
        $sSQL = "SELECT (DF.Cta_Venta +' - '+ CC.Cuenta) AS NomCxC " .
            "FROM Detalle_Factura AS DF, Catalogo_Cuentas AS CC " .
            "WHERE CC.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND CC.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND CC.Periodo = DF.Periodo " .
            "AND CC.Item = DF.Item " .
            "AND CC.Codigo = DF.Cta_Venta " .
            "GROUP BY DF.Cta_Venta, CC.Cuenta " .
            "ORDER BY DF.Cta_Venta";
        //SelectDB_Combo DCCxC, AdoCxC, sSQL, "NomCxC"
    }

    function ListCliente_LostFocus($caso, $Modulo)
    {
        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];
        $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];

        switch ($caso) {
            case "Autorizacion":
                $sSQL = "SELECT Autorizacion 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "'
                        AND Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY Autorizacion " .
                    "ORDER BY Autorizacion DESC ";
                $Nombre_Campo = "Autorizacion";
                break;
            case "Serie":
                $sSQL = "SELECT Serie 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "'
                        AND Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY Serie " .
                    "ORDER BY Serie ";
                $Nombre_Campo = "Serie";
                break;
            case "Codigo":
                $sSQL = "SELECT Codigo, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                    GROUP BY Codigo 
                    ORDER BY Codigo ";
                $Nombre_Campo = "Codigo";
                break;
            case "CI_RUC":
                $sSQL = "SELECT CI_RUC, Cliente, Codigo, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                    GROUP BY CI_RUC, Cliente, Codigo 
                    ORDER BY CI_RUC ";
                $Nombre_Campo = "CI_RUC";
                break;
            case "Cliente":
                $sSQL = "SELECT F.CodigoC, Cliente, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC
                    GROUP BY F.CodigoC, Cliente 
                    ORDER BY Cliente ";
                $Nombre_Campo = "Cliente";
            case "Grupo/Zona":
                $sSQL = "SELECT C.Grupo, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                    GROUP BY C.Grupo 
                    ORDER BY C.Grupo ";
                $Nombre_Campo = "Grupo";
                break;
            case "Vendedor":
                $sSQL = "SELECT C.Codigo, C.Cliente, COUNT(CR.Codigo) AS Fact_Proc 
                        FROM Clientes AS C, Catalogo_Rol_Pagos AS CR 
                        WHERE CR.Item = '" . $NumEmpresa . "' 
                        AND CR.Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND CR.Codigo = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = CR.Codigo 
                        GROUP BY C.Codigo, C.Cliente 
                        ORDER BY Cliente ";
                $Nombre_Campo = "Cliente";
                break;
            case "Ciudad":
                $sSQL = "SELECT Ciudad, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                        GROUP BY Ciudad 
                        ORDER BY Ciudad ";
                $Nombre_Campo = "Ciudad";
                break;
            case "Factura":
                $sSQL = "SELECT CONCAT(TC, ' ', Serie, ' ', CAST(Factura AS VARCHAR)) AS TipoFactura 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND TC NOT IN ('C','P') ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY TC, Serie, Factura 
                    ORDER BY TC, Serie, Factura DESC ";
                $Nombre_Campo = "TipoFactura";
                break;
            case "Forma_Pago":
                $sSQL = "SELECT Forma_Pago 
                        FROM Facturas 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND TC NOT IN ('C','P') ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "GROUP BY Forma_Pago 
                    ORDER BY Forma_Pago ";
                $Nombre_Campo = "Forma_Pago";
                break;
            case "Tipo Documento":
                $sSQL = "SELECT TC, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' 
                        AND TC NOT IN ('C','P') ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                        GROUP BY TC 
                        ORDER BY TC ";
                $Nombre_Campo = "TC";
                break;
            case "Plan_Afiliado":
                $sSQL = "SELECT Plan_Afiliado, COUNT(Factura) AS Fact_Proc 
                        FROM Clientes AS C, Facturas AS F 
                        WHERE F.Item = '" . $NumEmpresa . "' 
                        AND F.Periodo = '" . $Periodo_Contable . "' 
                        AND LENGTH(C.Plan_Afiliado) > 3 
                        AND TC NOT IN ('C','P') ";
                if ($Modulo == "EJECUTIVOS")
                    $sSQL .= "AND F.Cod_Ejec = '" . $CodigoUsuario . "' ";
                $sSQL .= "AND C.Codigo = F.CodigoC 
                        GROUP BY Plan_Afiliado 
                        ORDER BY Plan_Afiliado ";
                $Nombre_Campo = "Plan_Afiliado";
                break;
            case "Cuenta_No":
                $sSQL = "SELECT Cuenta_No
                        FROM Clientes_Datos_Extras 
                        WHERE Item = '" . $NumEmpresa . "' 
                        GROUP BY Cuenta_No 
                        ORDER BY Cuenta_No ";
                $Nombre_Campo = "Cuenta_No";
                break;
            case "Producto":
                $sSQL = "SELECT CONCAT(Codigo_Inv, ' - ', Producto) AS Codigos 
                        FROM Catalogo_Productos 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        ORDER BY Codigo_Inv ";
                $Nombre_Campo = "Codigos";
                break;
            case "DescItem":
                $sSQL = "SELECT Desc_Item 
                        FROM Catalogo_Productos 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND Desc_Item <> '" . G_NINGUNO . "' 
                        GROUP BY Desc_Item 
                        ORDER BY Desc_Item ";
                $Nombre_Campo = "Desc_Item";
                break;
            case "Marca":
                $sSQL = "SELECT CONCAT(CodMar, ' - ', Marca) AS NomMarca 
                        FROM Catalogo_Marcas 
                        WHERE Item = '" . $NumEmpresa . "' 
                        AND Periodo = '" . $Periodo_Contable . "' 
                        AND CodMar <> '" . G_NINGUNO . "' 
                        ORDER BY Marca ";
                $Nombre_Campo = "NomMarca";
                break;
            default:
                $sSQL = "SELECT Codigo, Cliente 
                        FROM Clientes 
                        WHERE Codigo = '-' ";
                $Nombre_Campo = "Cliente";
                break;
            //SelectDB_Combo DCCliente, AdoCliente, sSQL, Nombre_Campo
        }
    }



}

?>