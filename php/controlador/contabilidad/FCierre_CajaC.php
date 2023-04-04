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
}else
if(isset($_GET['FInfoErrorShow']))
{
   echo json_encode(FInfoErrorShow($_POST));
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
        Grabar_Asientos_Facturacion(G_NORMAL,$parametros);
        return [];
    }

    function VerificandoErrores($parametros)
    {
        sleep(1);
        return [];
    }

    function FechasdeCierre($parametros)
    {
        sleep(1);
        return [];
    }


    function Grabar_Asientos_Facturacion($TipoConsulta, $parametros){
        extract($parametros);
        $AdoDBAux = [];
        $VentasDia = false;
        $Ctas_Catalogo = "";
        $ErrorTemp = "";
        $Total_Vaucher = 0;
        $T_No = 0;
        $NoMes = 0;
        $NumEmpresa = $_SESSION['INGRESO']['item'];
        $Periodo_Contable = $_SESSION['INGRESO']['periodo'];

        $Trans_No = 96;
        $Ctas_Catalogo = "";
        $Beneficiario = G_NINGUNO;
        $FechaValida = FechaValida($MBFechaI);
        $FechaValida = FechaValida($MBFechaF);

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
        $FechaFinal = BuscarFecha("31/12/" . ObtenerAnioFecha($MBFechaF,"Y-m-d"));
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
                Insertar_Ctas_Cierre_SP($fields["Cta"], $fields["TAbono"]);
                Insertar_Ctas_Cierre_SP($fields["Cta_CxP"], -($fields["TAbono"]));
                $Total = $Total + Redondear($fields["TAbono"], 2);
            }
        }
        $ContSC = 1;


        return compact('Total','AdoCxC','AdoVentas','AdoDBAux');
    }




}