<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/FCierre_CajaM.php');
/**
 * 
 */

$controlador = new FCierre_CajaC();
if(isset($_GET['Form_Activate']))
{
	echo json_encode($controlador->Form_Activate());
}else
if(isset($_GET['Diario_CajaInicio']))
{
   echo json_encode($controlador->Diario_CajaInicio($_POST));
}else
if(isset($_GET['Productos_Cierre_Caja']))
{
   echo json_encode($controlador->Productos_Cierre_Caja($_POST));
}else
if(isset($_GET['Mayorizar_Inventario']))
{
   echo json_encode($controlador->Mayorizar_Inventario($_POST));
}else
if(isset($_GET['Actualizar_Abonos_Facturas']))
{
   echo json_encode($controlador->Actualizar_Abonos_Facturas($_POST));
}else
if(isset($_GET['Actualizar_Datos_Representantes']))
{
   echo json_encode($controlador->Actualizar_Datos_Representantes($_POST));
}else
if(isset($_GET['Grabar_Asientos_Facturacion']))
{
   echo json_encode($controlador->Grabar_Asientos_FacturacionC($_POST));
}else
if(isset($_GET['VerificandoErrores']))
{
   echo json_encode($controlador->VerificandoErrores($_POST));
}else
if(isset($_GET['FechasdeCierre']))
{
   echo json_encode($controlador->FechasdeCierre($_POST));
}

class FCierre_CajaC
{
    private $CierreCajaM;
    
    function __construct()
    {
       $this->CierreCajaM = new  FCierre_CajaM();     
    }

    function Form_Activate()
    {
        $_SESSION['FCierre_Caja']['CtaDeAnticipos'] = Leer_Seteos_Ctas("Cta_Anticipos_Clientes");
        $Valor_Retorno = Leer_Campo_Empresa("Cierre_Vertical");
        $_SESSION['FCierre_Caja']['FormaCierre'] = (is_null($Valor_Retorno) || empty($Valor_Retorno))?G_NINGUNO:$Valor_Retorno;

        $AdoAsiento1 = $this->CierreCajaM->IniciarAsientosDe($Trans_No = 97); // CxC
        $AdoAsiento = $this->CierreCajaM->IniciarAsientosDe($Trans_No = 96); // Abonos
        //TODO LS definir donde se usa AdoAsiento1 AdoAsiento en vista o solo en back
        $Co = new stdClass();
        $Co->TP = G_COMPDIARIO;
        $Co->Numero = 0;
        $Co->RUC_CI = G_NINGUNO;
        $Co->CodigoB = G_NINGUNO;
        $Co->Cotizacion = 0;
        $Co->Beneficiario = G_NINGUNO;
        $Co->Concepto = "";
        $Co->Efectivo = 0;
        $Co->Total_Banco = 0;
        $Co->Item = $_SESSION['INGRESO']['item'];
        //TODO LS este co creo que no va aqui

        $ModificarComp = false; //TODO LS definir si declarar
        $CopiarComp = false; //TODO LS definir si declarar
        $NuevoComp = true; //TODO LS definir si declarar

        $sSQL = "SELECT CONCAT(CONVERT(NVARCHAR, Codigo), Space(5), Cuenta) As NomCuenta " .
            "FROM Catalogo_Cuentas " .
            "WHERE TC = 'BA' " .
            "AND DG = 'D' " .
            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "ORDER BY Codigo ";
        $AdoCtaBanco = $this->CierreCajaM->SelectDB($sSQL);

        $sSQL = "SELECT CONCAT(Nombre_Completo, ' - ', Codigo) As Cajero " .
            "FROM Accesos " .
            "WHERE Ok <> 0 " . //TODO LS pendiente validar si si es 0
            "ORDER BY Nombre_Completo ";
        $AdoClientes = $this->CierreCajaM->SelectDB($sSQL); //TODO definir si se usara o no

        $_SESSION['FCierre_Caja']['NuevoDiario'] = false;

        return compact('AdoAsiento1','AdoAsiento','AdoCtaBanco','AdoClientes');
    }

    function Diario_CajaInicio($parametros)
    {
        extract($parametros);
        $FechaSistema = date('Y-m-d H:i:s');
        //Progreso_Iniciar_Errores();
        $_SESSION['FCierre_Caja']['ErrorFacturas'] = "";
        $_SESSION['FCierre_Caja']['ErrorInventario'] = "";
        //control_procesos("F", "Cierre Diarios de Caja");
        $_SESSION['FCierre_Caja']['Presentar_Inventario'] = False;
        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);
        $_SESSION['FCierre_Caja']['FA']["Fecha_Corte"] = $FechaSistema;
        $_SESSION['FCierre_Caja']['FA']["Fecha_Desde"] = $MBFechaI;
        $_SESSION['FCierre_Caja']['FA']["Fecha_Hasta"] = $MBFechaF;

        //---------------------------------------------------------------------------------------
        //Enceramos para realizar la primer parte del cierre de Abonos, NC, Cruce de Cuentas, etc
        //---------------------------------------------------------------------------------------
        $AdoAsiento1 = $this->CierreCajaM->IniciarAsientosDe($Trans_No = 97); // CxC
        $AdoAsiento = $this->CierreCajaM->IniciarAsientosDe($Trans_No = 96); // Abonos

        return compact('AdoAsiento1','AdoAsiento');
    }

    function Productos_Cierre_Caja($parametros)
    {
        extract($parametros);
        Productos_Cierre_Caja_SP($MBFechaI, $MBFechaF);
        return ["rps" => true];
    }

    function Mayorizar_Inventario()
    {
        return ["rps" => mayorizar_inventario_sp()];
    }

    function Actualizar_Abonos_Facturas($parametros)
    {
        return Actualizar_Abonos_Facturas_SP($_SESSION['FCierre_Caja']['FA'], true, true);
    }

    function Actualizar_Datos_Representantes($parametros)
    {
        Actualizar_Datos_Representantes_SP($_SESSION['INGRESO']['Mas_Grupos']);
        return ["rps" => true];
    }

    function Grabar_Asientos_FacturacionC($parametros)
    {
        return $this->Grabar_Asientos_Facturacion(G_NORMAL,$parametros);
    }

    function VerificandoErrores($parametros)
    {   
        extract($parametros);
        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
        Presenta_Errores_Facturacion_SP($MBFechaI, $MBFechaF);
        $Trans_No = 96;
        $SQL2 = "SELECT * " .
                "FROM Asiento " .
                "WHERE Item = '" . $NumEmpresa . "' " .
                "AND T_No = " . $Trans_No . " " .
                "AND CodigoU = '" . $CodigoUsuario . "' " .
                "ORDER BY A_No ";
        $AdoAsiento = $this->CierreCajaM->SelectDB($SQL2);

        $Trans_No = 97;
        $SQL2 = "SELECT * " .
                "FROM Asiento " .
                "WHERE Item = '" . $NumEmpresa . "' " .
                "AND T_No = " . $Trans_No . " " .
                "AND CodigoU = '" . $CodigoUsuario . "' " .
                "ORDER BY A_No ";
        $AdoAsiento1 = $this->CierreCajaM->SelectDB($SQL2);

        return compact('AdoAsiento','AdoAsiento1');

    }

    function FechasdeCierre($parametros)
    {
        extract($parametros);
        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];

        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);

        $sSQL = "SELECT Fecha " .
                "FROM Facturas " .
                "WHERE Item = '" . $NumEmpresa . "' " .
                "AND Periodo = '" . $Periodo_Contable . "' " .
                "AND TC <> 'OP' " .
                "AND Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "UNION " .
                "SELECT Fecha " .
                "FROM Trans_Abonos " .
                "WHERE Item = '" . $NumEmpresa . "' " .
                "AND Periodo = '" . $Periodo_Contable . "' " .
                "AND TP <> 'OP' " .
                "AND Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "GROUP BY Fecha " .
                "ORDER BY Fecha ";
                $AdoCierres = $this->CierreCajaM->SelectDB($sSQL); //TODO LS asignar DGCierres
                //$DGCierres->Caption = "Dias Cierres";

                // Resumen de abonos anticipados de Clientes
        $sSQL = "SELECT CC.Cuenta, C.Cliente, TS.Fecha, TS.TP, TS.Numero, TS.Creditos, T.Cta AS Contra_Cta, TS.Cta " .
                "FROM Trans_SubCtas AS TS, Transacciones AS T, Catalogo_Cuentas AS CC, Clientes AS C " .
                "WHERE TS.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
                "AND TS.Item = '" . $NumEmpresa . "' " .
                "AND TS.Periodo = '" . $Periodo_Contable . "' " .
                "AND TS.T <> 'A' " .
                "AND TS.Cta = '" . $_SESSION['FCierre_Caja']['CtaDeAnticipos'] . "' " .
                "AND TS.Creditos > 0 " .
                "AND TS.Periodo = T.Periodo " .
                "AND TS.Periodo = CC.Periodo " .
                "AND TS.Item = T.Item " .
                "AND TS.Item = CC.Item " .
                "AND TS.TP = T.TP " .
                "AND TS.Numero = T.Numero " .
                "AND T.Cta = CC.Codigo " .
                "AND TS.Codigo = C.Codigo " .
                "AND TS.Cta <> T.Cta " .
                "ORDER BY T.Cta, C.Cliente, TS.Fecha, TS.TP, TS.Numero ";
                $AdoAnticipos = $this->CierreCajaM->SelectDB($sSQL);

        return compact('AdoCierres','AdoAnticipos');
    }


    function Grabar_Asientos_Facturacion($TipoConsulta, $parametros){
        extract($parametros);
        $AdoDBAux = [];
        $VentasDia = false;
        $Ctas_Catalogo = "";

        $Cta_Propinas = "";
        $Cta_CajaG = "";
        $Cta_Desc = "";
        $Cta_Desc2 = "";
        $Cta_IVA = "";
        $Cta_Servicio = "";
        $Autorizacion = "01234567899";

        $TextoImprimio = "";
        $ErrorTemp = "";
        $Total_Vaucher = 0;
        $T_No = 0;
        $NoMes = 0;
        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];
        $CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
        $error = false;

        $Trans_No = 96;
        $Ctas_Catalogo = "";
        $Beneficiario = G_NINGUNO;
        $FechaValida = FechaValida($MBFechaI);
        if($FechaValida["ErrorFecha"]){
            return ['error' => true, "mensaje" =>$FechaValida["MsgBox"]];
        }
        $FechaValida = FechaValida($MBFechaF);
        if($FechaValida["ErrorFecha"]){
            return ['error' => true, "mensaje" =>$FechaValida["MsgBox"]];
        }

        $ErrorInventario = "";
        $Total_Vaucher = 0;
        $Total_Propinas = 0;
        $VentasDia = false;
        $FechaIni = BuscarFecha($MBFechaI);
        $FechaFin = BuscarFecha($MBFechaF);
        $Fecha_Vence = $MBFechaF;

        //"Verificando Cuentas involucradas"
        //Listado de los tipos de abonos
        $sSQL = "SELECT TA.TP,TA.Fecha,C.CI_RUC As COD_BANCO,C.Cliente,TA.Serie,TA.Autorizacion,TA.Factura,TA.Banco,TA.Cheque,TA.Abono," .
        "TA.Comprobante,TA.Cta,TA.Cta_CxP,TA.CodigoC,C.Ciudad,C.Plan_Afiliado As Sectorizacion," .
        "A.Nombre_Completo As Ejecutivo,Recibo_No As Orden_No " .
        "FROM Trans_Abonos As TA, Clientes C, Accesos As A " .
        "WHERE TA.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
        "AND TA.TP NOT IN ('OP') " .
        "AND TA.T <> 'A' " .
        "AND TA.Item = '" . $NumEmpresa . "' " .
        "AND TA.Periodo = '" . $Periodo_Contable . "' " .
        "AND TA.CodigoC = C.Codigo " .
        "AND TA.Cod_Ejec = A.Codigo ";

        if ($CheqCajero == 1) {
            $sSQL .= "AND TA.CodigoU = '" . rtrim($DCBenef) . "' "; //TODO LS validar que llega en $DCBenef
        }

        if ($CheqOrdDep == 1) {
            $sSQL .= "ORDER BY TA.Fecha,TA.TP,TA.Cta,TA.Banco,C.Cliente,TA.Factura ";
        } else {
            $sSQL .= "ORDER BY TA.Fecha,TA.TP,TA.Cta,C.Cliente,TA.Banco,TA.Factura ";
        }

        $AdoCxC = $this->CierreCajaM->SelectDB($sSQL);
        //$AdoCxC = $DGCxC//TODO LS definir uso

        // Listado de las CxC Clientes
        $sSQL = "SELECT F.TC,F.Fecha,C.Cliente,F.Serie,F.Autorizacion,F.Factura,F.IVA As Total_IVA,F.Descuento," .
                "F.Descuento2,F.Servicio,F.Propina,F.Total_MN,F.Saldo_MN,F.Cta_CxP,C.Ciudad,C.Plan_Afiliado As Sectorizacion," .
                "A.Nombre_Completo As Ejecutivo " .
                "FROM Facturas F,Clientes C,Accesos As A " .
                "WHERE F.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "AND F.TC NOT IN ('OP') " .
                "AND F.T <> 'A' " .
                "AND F.Item = '" . $NumEmpresa . "' " .
                "AND F.Periodo = '" . $Periodo_Contable . "' " .
                "AND F.CodigoC = C.Codigo " .
                "AND F.Cod_Ejec = A.Codigo ";

        if ($CheqCajero == 1) {
            $sSQL .= "AND F.CodigoU = '" . SinEspaciosDer($DCBenef) . "' ";
        }

        $sSQL .= "ORDER BY F.TC,F.Fecha,F.Cta_CxP,F.Factura,C.Cliente ";

        $AdoVentas = $this->CierreCajaM->SelectDB($sSQL); //TODO LS asignaar tambien en $DGVentas

        $Combos = G_NINGUNO; //TODO LS donde USO
        $FechaFinal = BuscarFecha("31/12/" .date('Y', strtotime($MBFechaF)));
        $ContCtas = 0; //TODO LS donde USO
        $Total = 0; //TODO LS donde USO

        switch ($TipoConsulta) {
            case G_PROCESADO:
                $_SESSION['FCierre_Caja']['NuevoDiario'] = false;
                break;
            case G_NORMAL:
                $_SESSION['FCierre_Caja']['NuevoDiario'] = true;
                break;
        }

        // ================================
        // Iniciamos los asientos contables
        // ================================
        // Asientos de Abonos de todas las cuentas con sus CxC
        //$Progreso_Barra->Mensaje_Box = "Totalizando Abonos";
        $sSQL = "SELECT TA.TP, TA.Cta, TA.Cta_CxP, SUM(TA.Abono) As TAbono " .
                "FROM Trans_Abonos As TA, Clientes C, Accesos As A " .
                "WHERE TA.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "AND TA.TP NOT IN ('OP') " .
                "AND TA.T <> 'A' " .
                "AND TA.Item = '" . $NumEmpresa . "' " .
                "AND TA.Periodo = '" . $Periodo_Contable . "' " .
                "AND TA.CodigoC = C.Codigo " .
                "AND TA.Cod_Ejec = A.Codigo ";
        if ($CheqCajero == 1) {
            $sSQL = $sSQL . "AND TA.CodigoU = '" . SinEspaciosDer($DCBenef) . "' ";
        }
        $sSQL = $sSQL . "GROUP BY TA.TP, TA.Cta, TA.Cta_CxP ";
        $AdoDBAux = $this->CierreCajaM->SelectDB($sSQL);

        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                Insertar_Ctas_Cierre_SP($fields["Cta"], $fields["TAbono"], $Trans_No);
                Insertar_Ctas_Cierre_SP($fields["Cta_CxP"], -($fields["TAbono"]), $Trans_No);
                $Total = $Total + number_format($fields["TAbono"], 2, '.', '');
            }
        }
        $LabelCheque = number_format($Total, 2, '.', ',');
        $ContSC = 1;

        $sSQL = "SELECT TA.Cta,TA.Tipo_Cta,C.Cliente,TA.CodigoC,TA.Fecha,TA.TP,TA.Serie,TA.Factura,TA.Abono 
                 FROM Trans_Abonos TA 
                 JOIN Clientes C ON TA.CodigoC = C.Codigo
                 JOIN Accesos A ON TA.Cod_Ejec = A.Codigo
                 WHERE TA.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                 AND TA.TP NOT IN ('OP')
                 AND TA.Tipo_Cta IN ('C','P')
                 AND TA.T <> 'A'
                 AND TA.Item = '" . $NumEmpresa . "'
                 AND TA.Periodo = '" . $Periodo_Contable . "'";
        if ($CheqCajero == 1) {
            $sSQL .= " AND TA.CodigoU = '" . rtrim($DCBenef) . "'";
        }
        $sSQL .= " ORDER BY TA.Cta,TA.Tipo_Cta,C.Cliente,TA.CodigoC,TA.Fecha,TA.TP,TA.Serie,TA.Factura";

        $AdoDBAux = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fila) {
                // Verificamos si es cta de submodulos
                switch ($fila['Tipo_Cta']) {
                    case in_array($fila['Tipo_Cta'], array('C', 'P')):
                          SetAdoAddNew("Asiento_SC");
                          SetAdoFields("Codigo", $fila["CodigoC"]);
                          SetAdoFields("Beneficiario", $fila["Cliente"]);
                          SetAdoFields("TM", "1");
                          SetAdoFields("DH", "1");
                          SetAdoFields("Valor", number_format($fila["Abono"], 2, '.', ''));
                          SetAdoFields("FECHA_V", $fila["Fecha"]);
                          SetAdoFields("TC", $fila["Tipo_Cta"]);
                          SetAdoFields("Cta", $fila["Cta"]);
                          SetAdoFields("Serie", $fila["Serie"]);
                          SetAdoFields("Factura", $fila["Factura"]);
                          SetAdoFields("T_No", $Trans_No);
                          SetAdoFields("SC_No", $ContSC);
                          SetAdoUpdate();
                        $ContSC++;
                    break;
                }
            }
        }

        //Totalizamos las Propinas
        $sSQL = "SELECT F.TC, SUM(F.Propina) As Total_Propina " .
                "FROM Facturas F, Clientes C " .
                "WHERE F.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "AND F.TC NOT IN ('OP') " .
                "AND F.T <> 'A' " .
                "AND F.Item = '" . $NumEmpresa . "' " .
                "AND F.Periodo = '" . $Periodo_Contable . "' " .
                "AND F.CodigoC = C.Codigo ";
        if ($CheqCajero == 1) {
            $sSQL = $sSQL . "AND F.CodigoU = '" . SinEspaciosDer($DCBenef) . "' ";
        }
        $sSQL = $sSQL . "GROUP BY F.TC ";
        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                $Total_Propinas = $Total_Propinas + $fields["Total_Propina"];
                Insertar_Ctas_Cierre_SP($Cta_Propinas, -$fields["Total_Propina"], $Trans_No); //TODO LS de donde sale  Cta_Propinas
            }
        }
        Insertar_Ctas_Cierre_SP($Cta_CajaG, $Total_Propinas,$Trans_No); //TODO LS de donde sale  Cta_CajaG

        //Totalizamos las Liquidacion de Compras Debe
        $sSQL = "SELECT F.Cta_CxP, C.T, SUM(F.Total_MN) As Total_LC " .
            "FROM Facturas F, Clientes C " .
            "WHERE F.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
            "AND F.TC = 'LC' " .
            "AND F.T <> 'A' " .
            "AND F.Item = '" . $NumEmpresa . "' " .
            "AND F.Periodo = '" . $Periodo_Contable . "' " .
            "AND F.CodigoC = C.Codigo ";
        if ($CheqCajero == 1) {
            $sSQL = $sSQL . "AND F.CodigoU = '" . SinEspaciosDer($DCBenef) . "' ";
        }
        $sSQL = $sSQL . "GROUP BY F.Cta_CxP, C.T ";
        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL); //"LC Debe"
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                Insertar_Ctas_Cierre_SP($fields["Cta_CxP"], $fields["Total_LC"], $Trans_No);
            }
        }

        //Totalizamos las Liquidacion de Compras Haber
        $sSQL = "SELECT Cta_Venta, SUM(Total+Total_IVA) As Total_D_LC " .
        "FROM Detalle_Factura " .
        "WHERE Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
        "AND Periodo = '" . $Periodo_Contable . "' " .
        "AND Item = '" . $NumEmpresa . "' " .
        "AND T <> '" . G_ANULADO . "' " .
        "AND TC = 'LC' ";
        if ($CheqCajero == 1) {
            $sSQL .= "AND CodigoU = '" . SinEspaciosDer($DCBenef) . "' ";
        }
        $sSQL .= "GROUP BY Cta_Venta " .
        "ORDER BY Cta_Venta ";
        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL); //"LC Haber"
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                Insertar_Ctas_Cierre_SP($fields["Cta_Venta"], -($fields["Total_D_LC"]), $Trans_No);
            }
        }

        // Asiento de Entrada y Salida de Inventario por NC
        $sSQL = "SELECT Cta_Inv, Contra_Cta, SUM(Valor_Total) As dValor_Total " .
                "FROM Trans_Kardex " .
                "WHERE Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "AND Item = '" . $NumEmpresa . "' " .
                "AND Entrada > 0 " .
                "AND SUBSTRING(Detalle,1,3) = 'NC:' " .
                "GROUP BY Cta_Inv, Contra_Cta " .
                "ORDER BY Cta_Inv, Contra_Cta ";

        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                Insertar_Ctas_Cierre_SP($fields["Cta_Inv"], $fields["dValor_Total"], $Trans_No);
                Insertar_Ctas_Cierre_SP($fields["Contra_Cta"], -$fields["dValor_Total"], $Trans_No);
            }
        }

        // Asiento de Voucher por cobrar contra el banco para tarjeta de Crédito
        $Total_Vaucher = 0;
        $sSQL = "SELECT CP.Detalle, SUM(TA.Abono) As Total_TJ " .
        "FROM Trans_Abonos As TA, Catalogo_Cuentas As CC, Ctas_Proceso As CP " .
        "WHERE TA.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
        "AND TA.Periodo = '" . $Periodo_Contable . "' " .
        "AND TA.Item = '" . $NumEmpresa . "' " .
        "AND TA.Tipo_Cta = 'TJ' " .
        "AND MidStrg(CP.Detalle,1,7) = 'Voucher' " .
        "AND TA.Cta = CC.Codigo " .
        "AND TA.Cta = CP.Codigo " .
        "AND TA.Periodo = CC.Periodo " .
        "AND TA.Periodo = CP.Periodo " .
        "AND TA.Item = CC.Item " .
        "AND TA.Item = CP.Item " .
        "GROUP BY CP.Detalle " .
        "ORDER BY CP.Detalle ";
        $sSQL = CompilarSQL($sSQL);
        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                if ($Codigo != $fields["Detalle"]) {
                    $Codigo2 = SinEspaciosDer($Codigo);
                    $Codigo = TrimStrg(MidStrg($Codigo, 1, strlen($Codigo) - strlen($Codigo2)));
                    $Codigo1 = SinEspaciosDer($Codigo);
                    if ($Total_Vaucher > 0) {
                        Insertar_Ctas_Cierre_SP($Codigo1, $Total_Vaucher, $Trans_No);
                        Insertar_Ctas_Cierre_SP($Codigo2, -$Total_Vaucher, $Trans_No);
                    }
                    $Codigo = $fields["Detalle"];
                    $Total_Vaucher = 0;
                }
                $Total_Vaucher = $Total_Vaucher + $fields["Total_TJ"];
            }
            $Codigo2 = SinEspaciosDer($Codigo);
            $Codigo = TrimStrg(MidStrg($Codigo, 1, strlen($Codigo) - strlen($Codigo2)));
            $Codigo1 = SinEspaciosDer($Codigo);
            if ($Total_Vaucher > 0) {
                Insertar_Ctas_Cierre_SP($Codigo1, $Total_Vaucher, $Trans_No);
                Insertar_Ctas_Cierre_SP($Codigo2, -$Total_Vaucher, $Trans_No);
            }
        }

        // Enceramos para realizar la segunda parte del cierre de las CxC el segundo asiento
        // =============================================================================
        $Trans_No = 97;
        $sSQL = "SELECT DF.Cta_Venta, F.SubCta, CS.TC, CS.Detalle, SUM(F.SubTotal) As TSubTotal " .
            "FROM Facturas As F, Detalle_Factura As DF, Catalogo_SubCtas As CS " .
            "WHERE F.Item = '" . $NumEmpresa . "' " .
            "AND F.Periodo = '" . $Periodo_Contable . "' " .
            "AND F.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
            "AND F.SubCta <> '.' " .
            "AND F.T <> 'A' " .
            "AND F.Item = DF.Item " .
            "AND F.Item = CS.Item " .
            "AND F.Periodo = DF.Periodo " .
            "AND F.Periodo = CS.Periodo " .
            "AND F.TC = DF.TC " .
            "AND F.Factura = DF.Factura " .
            "AND F.SubCta = CS.Codigo ";
        if ($CheqCajero == 1) {
            $sSQL .= "AND F.CodigoU = '" . rtrim($DCBenef) . "' ";
        }
        $sSQL .= "GROUP BY DF.Cta_Venta, F.SubCta, CS.TC, CS.Detalle ";
        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                // Verificamos si es cta de submodulos
                switch ($fields["TC"]) {
                    case "I":
                    case "CC":
                        SetAdoAddNew("Asiento_SC");
                        SetAdoFields("Codigo", $fields["SubCta"]);
                        SetAdoFields("Beneficiario", $fields["Detalle"]);
                        SetAdoFields("TM", "1");
                        SetAdoFields("DH", "2");
                        SetAdoFields("Valor", number_format($fields["TSubTotal"], 2, '.', ''));
                        SetAdoFields("TC", $fields["TC"]);
                        SetAdoFields("Cta", $fields["Cta_Venta"]);
                        SetAdoFields("T_No", $Trans_No);
                        SetAdoFields("SC_No", $ContSC);
                        SetAdoUpdate();
                        $ContSC = $ContSC + 1;
                        break;
                }
            }
        }

        // Asientos de CxC Efectivo
        $total = 0;
        $sSQL = "SELECT TC, Cta_CxP, SUM(IVA) As T_IVA, SUM(Descuento) As T_Descuento, SUM(Descuento2) As T_Descuento2, SUM(Servicio) As T_Servicio, SUM(Total_MN) As T_Total_MN " .
                "FROM Facturas " .
                "WHERE Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "AND TC NOT IN ('OP','LC') " .
                "AND T <> 'A' " .
                "AND Item = '" . $NumEmpresa . "' " .
                "AND Periodo = '" . $Periodo_Contable . "' ";
        if ($CheqCajero == 1) {
          $sSQL .= "AND CodigoU = '" . SinEspaciosDer($DCBenef->Text) . "' ";
        }
        $sSQL .= "GROUP BY TC, Cta_CxP " .
                "ORDER BY TC, Cta_CxP ";
        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
            Insertar_Ctas_Cierre_SP($fields["Cta_CxP"], $fields["T_Total_MN"], $Trans_No);
            Insertar_Ctas_Cierre_SP($Cta_Desc, $fields["T_Descuento"], $Trans_No); //TODO LS de donde sale la variable $Cta_Desc
            Insertar_Ctas_Cierre_SP($Cta_Desc2, $fields["T_Descuento2"], $Trans_No); //TODO LS de donde sale la variable $Cta_Desc2
            Insertar_Ctas_Cierre_SP($Cta_IVA, -$fields["T_IVA"], $Trans_No);
            Insertar_Ctas_Cierre_SP($Cta_Servicio, -$fields["T_Servicio"], $Trans_No);
            $total = $total + number_format($fields["T_Total_MN"], 2, '.', '');
          }
        }
        $LabelAbonos = number_format($total, 2, '.', ',');
        // Abrimos espacios para el asiento
        $Total = 0;
        $TotalIngreso = 0;
        // Asiento Ventas del dia de una sola cuenta
        $sSQL = "SELECT TC, Cta_Venta, SUM(Total) AS T_Total " .
            "FROM Detalle_Factura " .
            "WHERE Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
            "AND Periodo = '" . $Periodo_Contable . "' " .
            "AND Item = '" . $NumEmpresa . "' " .
            "AND T <> '" . G_ANULADO . "' " .
            "AND TC NOT IN ('OP','LC') ";
        if ($CheqCajero == 1) {
            $sSQL .= "AND CodigoU = '" . SinEspaciosDer($DCBenef) . "' ";
        }
        $sSQL .= "GROUP BY TC, Cta_Venta " .
            "ORDER BY TC, Cta_Venta ";

        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                Insertar_Ctas_Cierre_SP($fields["Cta_Venta"], -$fields["T_Total"], $Trans_No);
                $Total = $Total + $fields["T_Total"];
            }
        }

        // Asiento de Entrada y Salida de Inventario por NC
        $sSQL = "SELECT Cta_Inv, Contra_Cta, SUM(Valor_Total) As dValor_Total " .
                "FROM Trans_Kardex " .
                "WHERE Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                "AND Item = '" . $NumEmpresa . "' " .
                "AND Salida > 0 " .
                "AND LEN(TC) = 2 " .
                "AND LEN(Serie) = 6 " .
                "AND Factura > 0 " .
                "GROUP BY Cta_Inv, Contra_Cta " .
                "ORDER BY Cta_Inv, Contra_Cta ";
        $AdoDBAux  = $this->CierreCajaM->SelectDB($sSQL);
        if (count($AdoDBAux) > 0) {
            foreach ($AdoDBAux as $key => $fields) {
                Insertar_Ctas_Cierre_SP($fields["Contra_Cta"], $fields["dValor_Total"], $Trans_No);
                Insertar_Ctas_Cierre_SP($fields["Cta_Inv"], -$fields["dValor_Total"], $Trans_No);
            }
        }

        if (!empty($ErrorInventario)) {//TODO LS donde se define este valor $ErrorInventario
            $TextoImprimio .= "Warning: Falta de Ingresar Entrada Inicial de los siguientes producto(s):" . PHP_EOL;
            $TextoImprimio .= $ErrorInventario . PHP_EOL;
        }

        // Totalizamos los dos asientos para ver descuadres
        $Trans_No = 96;
        $Debe = 0;
        $Haber = 0;
        $Ln_No = 0;
        $SQL2 = "SELECT CODIGO, CUENTA, PARCIAL_ME, DEBE, HABER, CHEQ_DEP, DETALLE, EFECTIVIZAR, CODIGO_C, CODIGO_CC, T_No, A_No, TC, ID "
               . "FROM Asiento "
               . "WHERE Item = '" . $NumEmpresa . "' "
               . "AND T_No = " . $Trans_No . " "
               . "AND CodigoU = '" . $CodigoUsuario . "' "
               . "ORDER BY CODIGO,DEBE DESC,HABER ";
        $DGAsiento  = $AdoAsiento= $this->CierreCajaM->SelectDB($SQL2);
        if (count($DGAsiento) > 0) {
            foreach ($DGAsiento as $key => $fields) {
                $Debe = $Debe + $fields["DEBE"];
                $Haber = $Haber + $fields["HABER"];
                $fields["A_No"] = $Ln_No;
                $Ln_No = $Ln_No + 1;
                //$DGAsiento->UpdateBatch(); //TODO LS esto significa wue toca hacer un update en Asiento de A_No??
            }
        }
        $LabelDebe = number_format($Debe, 2, '.', ',');
        $LabelHaber = number_format($Haber, 2, '.', ',');
        $LblDiferencia = number_format(($Debe - $Haber), 2, '.', ',');

        $Trans_No = 97;
        $Debe = 0; $Haber = 0; $Ln_No = 0;
        $SQL2 = "SELECT CODIGO, CUENTA, PARCIAL_ME, DEBE, HABER, CHEQ_DEP, DETALLE, EFECTIVIZAR, CODIGO_C, CODIGO_CC, T_No, A_No, TC 
                 FROM Asiento 
                 WHERE Item = '" . $NumEmpresa . "' 
                 AND T_No = " . $Trans_No . " 
                 AND CodigoU = '" . $CodigoUsuario . "' 
                 ORDER BY CODIGO,DEBE DESC,HABER ";
        $AdoAsiento1  = $this->CierreCajaM->SelectDB($SQL2);
        if (count($AdoAsiento1) > 0) {
            foreach ($AdoAsiento1 as $key => $fields) {
                $Debe = $Debe + $fields["DEBE"];
                $Haber = $Haber + $fields["HABER"];
                $Ln_No = $Ln_No + 1;
            }
            //$AdoAsiento1->Recordset->UpdateBatch(); //TODO LS que se supone que se actualiza aqui
        }

        $LabelDebe1 = number_format($Debe, 2, ',', '.');
        $LabelHaber1 = number_format($Haber, 2, ',', '.');
        $LblDiferencia1 = number_format($Debe - $Haber, 2, ',', '.');
        if ($MBFechaI == $MBFechaF) {
            $LblConcepto = "Cierre Diario de Caja de Abonos del " . $MBFechaI . ", Diario No. ?";
            $LblConcepto1 = "Cierre Diario de Caja de CxC del " . $MBFechaI . ", Diario No. ?";
        } else {
            $LblConcepto = "Cierre Diario de Caja de Abonos del " . $MBFechaI . " al " . $MBFechaF . ", Diario No. ?";
            $LblConcepto1 = "Cierre Diario de Caja de CxC del " . $MBFechaI . " al " . $MBFechaF . ", Diario No. ?";
        }

        // Listado de Facturas anuladas
        $total = 0;
        $sql = "SELECT F.T,F.TC,F.Fecha,C.Cliente,F.Factura,F.IVA As Total_IVA,F.Total_MN,F.Cta_CxP 
                FROM Facturas F, Clientes C 
                WHERE F.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
                AND F.T = 'A' 
                AND F.Item = '".$NumEmpresa."' 
                AND F.Periodo = '".$Periodo_Contable."' 
                AND F.TC <> 'OP'";
        if ($CheqCajero == 1) {
            $sql .= "AND F.CodigoU = '" . SinEspaciosDer($DCBenef->Text) . "' ";
        }
        $sql .= "AND F.CodigoC = C.Codigo 
                 ORDER BY F.TC,F.Fecha,F.Cta_CxP,C.Cliente,F.Factura";
        $AdoFactAnul  = $this->CierreCajaM->SelectDB($sql); //tdl asignar a DGFactAnul
        // REPORTES DE AUDITORIA TRANSACCIONALES (S.R.I.)
        if ($MBFechaI == $MBFechaF) {//TODO LS de donde sale la variable $Autorizacion
            $DGSRI = "Autorización No. {$Autorizacion}, Listado de Facturas del {$MBFechaI}";
        } else {
            $DGSRI = "Autorización No. {$Autorizacion}, Listado de Facturas del {$MBFechaI} al {$MBFechaF}";
        }

        $Codigo = strval($_SESSION['INGRESO']['porc'] * 100);
        $sSQL = "SELECT F.TC,F.T,F.RUC_CI,F.TB,F.Razon_Social,F.Fecha,F.Hora,A.Nombre_Completo AS Usuario," .
                "F.Autorizacion,F.Serie,F.Factura AS Secuencial,F.Con_IVA AS Base_" . $Codigo . ",F.Sin_IVA AS Base_0," .
                "F.Descuento,F.Descuento2,(F.SubTotal - F.Descuento - F.Descuento2) AS Sub_Total, F.IVA AS IVA_" . $Codigo . ",F.Servicio,F.Total_MN AS TOTAL,Serie_R," .
                "Secuencial_R,F.Autorizacion_R,Total_Ret_Fuente,Total_Ret_IVA_B,Total_Ret_IVA_S,C.Contacto AS Referencia,C.CI_RUC AS COD_BANCO " .
                "FROM Facturas F, Clientes C, Accesos AS A " .
                "WHERE F.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
                "AND F.TC NOT IN ('C','P','OP','LC','DO') " .
                "AND F.Item = '" . $NumEmpresa . "' " .
                "AND F.Periodo = '" . $Periodo_Contable . "' " .
                "AND F.CodigoC = C.Codigo " .
                "AND F.CodigoU = A.Codigo ";
        if ($CheqCajero == 1) {
            $sSQL .= "AND F.CodigoU = '" . SinEspaciosDer($DCBenef) . "' ";
        }
        $sSQL .= "ORDER BY F.Factura,F.TC,F.Fecha,F.Cta_CxP,C.Cliente ";
        $AdoSRI  = $this->CierreCajaM->SelectDB($sSQL); //TODO LS asignar tambien a DGSRI

        $Total_Con_IVA = 0;
        $Total_Sin_IVA = 0;
        $Total_Desc = 0;
        $Total_Desc2 = 0;
        $Total_IVA = 0;
        $Total_Servicio = 0;
        $Total = 0;
        if (count($AdoSRI) > 0) {
            foreach ($AdoSRI as $key => $fields) {
                if ($fields["T"] != G_ANULADO) {
                    $Total_Con_IVA += $fields["Base_" . $Codigo];
                    $Total_Sin_IVA += $fields["Base_0"];
                    $Total_Desc += $fields["Descuento"];
                    $Total_Desc2 += $fields["Descuento2"];
                    $Total_IVA += $fields["IVA_" . $Codigo];
                    $Total_Servicio += $fields["Servicio"];
                    $Total += $fields["TOTAL"];
                }
            }
        }

        // Convert numeric values to a string formatted with commas and two decimal places
        $LblConIVA = number_format($Total_Con_IVA, 2, '.', ',');
        $LblSinIVA = number_format($Total_Sin_IVA, 2, '.', ',');
        $LblDescuento = number_format($Total_Desc + $Total_Desc2, 2, '.', ',');
        $LblIVA = number_format($Total_IVA, 2, '.', ',');
        $LblServicio = number_format($Total_Servicio, 2, '.', ',');
        $LblTotalFacturado = number_format($Total, 2, '.', ',');

        // SQL query to retrieve data from database and display in a grid
        $sSQL = "SELECT Codigo, Producto, SUM(Cantidad) AS CANTIDADES, SUM(Total) AS SUBTOTALES, SUM(Total_IVA) AS SUBTOTAL_IVA, Cta_Venta " .
                "FROM Detalle_Factura " .
                "WHERE Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
                "AND Item = '" . $NumEmpresa . "' " .
                "AND Periodo = '" . $Periodo_Contable . "' " .
                "AND T <> '" . G_ANULADO . "' " .
                "GROUP BY Codigo, Producto, Cta_Venta " .
                "UNION " .
                "SELECT '-x-' AS Codigo, 'TOTAL DE VENTAS' AS Producto, SUM(Cantidad) AS CANTIDADES, SUM(Total) AS SUBTOTALES, SUM(Total_IVA) AS SUBTOTAL_IVA, '' AS Cta_Venta " .
                "FROM Detalle_Factura " .
                "WHERE Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
                "AND Item = '" . $NumEmpresa . "' " .
                "AND Periodo = '" . $Periodo_Contable . "' " .
                "AND T <> '" . G_ANULADO . "' " .
                "ORDER BY Codigo, Producto ";

        $AdoProductos = $this->CierreCajaM->SelectDB($sSQL); ////TODO LS asignar a DGProductos

        $sSQL = "SELECT TK.TC As Doc, TK.Codigo_Inv, CP.Producto, 0 As Entradas, SUM(TK.Salida) As Salidas, AVG(TK.Costo) As Costos, " .
        "(SUM(TK.Salida) * AVG(TK.Costo)) As Totales, TK.Cta_Inv, TK.Contra_Cta, TK.CodBodega, CP.Unidad, COUNT(TK.TC) As Cant_Doc " .
        "FROM Trans_Kardex As TK, Catalogo_Productos As CP " .
        "WHERE TK.Item = '" . $NumEmpresa . "' " .
        "AND TK.Periodo = '" . $Periodo_Contable . "' " .
        "AND TK.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
        "AND LEN(TK.TC) = 2 " .
        "AND LEN(TK.Serie) = 6 " .
        "AND TK.Factura > 0 " .
        "AND TK.Salida > 0 ";
        if ($CheqCajero == 1) {
            $sSQL .= "AND TK.CodigoU = '" . rtrim($DCBenef) . "' ";
        }
        $sSQL .= "AND TK.Item = CP.Item " .
                 "AND TK.Periodo = CP.Periodo " .
                 "AND TK.Codigo_Inv = CP.Codigo_Inv " .
                 "GROUP BY TK.TC, TK.Codigo_Inv, CP.Producto, TK.Cta_Inv, TK.Contra_Cta, TK.CodBodega, CP.Unidad ";
        $sSQL .= "UNION " .
                 "SELECT 'NC' As Doc, TK.Codigo_Inv, CP.Producto, SUM(TK.Entrada) As Entradas, 0 As Salidas, AVG(TK.Costo) As Costos, " .
                 "(SUM(TK.Entrada) * AVG(TK.Costo)) As Totales, TK.Cta_Inv, TK.Contra_Cta, TK.CodBodega, CP.Unidad, COUNT(TK.TC) As Cant_Doc " .
                 "FROM Trans_Kardex As TK, Catalogo_Productos As CP " .
                 "WHERE TK.Item = '" . $NumEmpresa . "' " .
                 "AND TK.Periodo = '" . $Periodo_Contable . "' " .
                 "AND TK.Fecha BETWEEN '" . $FechaIni . "' and '" . $FechaFin . "' " .
                 "AND LEN(TK.TC) = 2 " .
                 "AND LEN(TK.Serie) = 6 " .
                 "AND TK.Factura > 0 " .
                 "AND TK.Entrada > 0 ";
        if ($CheqCajero == 1) {
            $sSQL .= "AND TK.CodigoU = '" . rtrim($DCBenef) . "' ";
        }
        $sSQL = $sSQL .
               "AND TK.Item = CP.Item " .
               "AND TK.Periodo = CP.Periodo " .
               "AND TK.Codigo_Inv = CP.Codigo_Inv " .
               "GROUP BY TK.Codigo_Inv, CP.Producto, TK.Cta_Inv, TK.Contra_Cta, TK.CodBodega, CP.Unidad " .
               "ORDER BY Doc, TK.Codigo_Inv, CP.Producto, TK.Cta_Inv, TK.Contra_Cta, TK.CodBodega, CP.Unidad ";
        //$SQLDec = "Costos " . strval($Dec_Costo) . "|."; //TODO LS no se que signifca este fragmentp
        $AdoInv = $this->CierreCajaM->SelectDB($sSQL); //TODO LS asignar tbm a DGInv

        //podria retornarse pero parce no ser necesario
        /*
            $AdoDBAux
        */
        return compact('error','AdoCxC','AdoVentas', 'AdoAsiento','AdoAsiento1','AdoFactAnul','AdoProductos','AdoInv','TextoImprimio','LabelDebe', 'LabelHaber', 'LblDiferencia','LabelDebe1','LabelHaber1','LblDiferencia1','LblConcepto','LblConcepto1','DGSRI', 'AdoSRI', 'LblConIVA', 'LblSinIVA', 'LblDescuento', 'LblIVA', 'LblServicio', 'LblTotalFacturado','LabelCheque', 'LabelAbonos' ) ;
    }

}