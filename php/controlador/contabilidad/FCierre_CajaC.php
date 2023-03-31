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
   echo json_encode($controlador->Grabar_Asientos_Facturacion($_POST));
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

        //si es necesario estos llamados aqui? tocaria ir a la vista y volver 
        $AdoAsiento1 = $this->CierreCajaM->IniciarAsientosDe($Trans_No = 97); // CxC
        $AdoAsiento = $this->CierreCajaM->IniciarAsientosDe($Trans_No = 96); // Abonos

        return compact('AdoAsiento1','AdoAsiento');
    }

    function Productos_Cierre_Caja($parametros)
    {
        extract($parametros);
        //Productos_Cierre_Caja_SP($MBFechaI, $MBFechaF);
        return true;
    }

    function Mayorizar_Inventario($parametros)
    {
        sleep(1);
        return [];
    }

    function Actualizar_Abonos_Facturas($parametros)
    {
        sleep(1);
        return [];
    }

    function Actualizar_Datos_Representantes($parametros)
    {
        sleep(1);
        return [];
    }

    function Grabar_Asientos_Facturacion($parametros)
    {
        sleep(1);
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







}