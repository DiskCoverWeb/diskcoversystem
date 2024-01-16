<?php
include(dirname(__DIR__, 2) . '/modelo/facturacion/FRecaudacionBancosCxCM.php');

$controlador = new FRecaudacionBancosCxCC();
if (isset($_GET['Form_Activate'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Form_Activate($parametros));
}

if (isset($_GET['DCEntidad'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCEntidad());
}

if (isset($_GET['DCGrupoI_DCGrupoF'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCGrupoI_DCGrupoF());
}

if (isset($_GET['DCBanco'])) {
    //$parametros = $_POST['parametros'];
    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['FechaValida'])) {
    echo json_encode(FechaValida($_POST['fecha']));
}

if (isset($_GET['AdoAux'])) {
    echo json_encode($controlador->AdoAux());
}

if (isset($_GET['AdoProducto'])) {
    echo json_encode($controlador->AdoProducto());
}

if (isset($_GET['LeerCampoEmpresa'])) {
    echo json_encode(Leer_Campo_Empresa($_POST['campo']));
}

if (isset($_GET['MBFechaFLostFocus'])) {
    echo json_encode($controlador->MBFechaF_LostFocus($_POST['fecha']));
}

if (isset($_GET['LeerSeteosCtas'])) {
    echo json_encode(Leer_Seteos_Ctas());
}

if (isset($_GET['GenerarPichincha'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Generar_Pichincha($parametros));
}


class FRecaudacionBancosCxCC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new FRecaudacionBancosCxCM();
    }

    function DCGrupoI_DCGrupoF()
    {
        $datos = $this->modelo->SelectCombo_DCGrupoI_DCGrupoF();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array('Grupo' => $value['Grupo']);
            }
            return $list;
        }
        return $list;
    }

    function DCBanco()
    {
        $datos = $this->modelo->SelectDB_Combo_DCBanco();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array(
                    'Codigo' => $value['Codigo'],
                    'NomCuenta' => $value['NomCuenta']
                );
            }
            return $list;
        }
        return $list;
    }

    function DCEntidad()
    {
        $datos = $this->modelo->SelectDB_Combo_DCEntidad();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array(
                    'Descripcion' => $value['Descripcion'],
                    'Abreviado' => $value['Abreviado']
                );
            }
            return $list;
        }
        return $list;
    }

    function AdoAux()
    {
        $datos = $this->modelo->AdoAux();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $key => $value) {
                $list[] = array(
                    'Cta_Cobrar' => $value['CxC'],
                    'CxC_Clientes' => $value['Concepto'],
                    'Individual' => $value['Individual'],
                    'TipoFactura' => $value['Fact'],
                );
            }
            return $list;
        }
        return $list;
    }

    function AdoProducto()
    {
        $datos = $this->modelo->Select_AdoProducto();
        $list = array();
        if (count($datos) > 0) {
            foreach ($datos as $value) {
                $list[] = array('Codigo_Inv' => $value['Codigo_Inv']);
            }
            return $list;
        }
        return $list;
    }

    function MBFechaF_LostFocus($fecha)
    {
        $datos = $this->modelo->MBFechaF_LostFocus();
        if (count($datos) > 0) {
            print_r($fecha);
            $this->modelo->MBFechaF_LostFocusUpdate($fecha);
        }
    }
    /*
        function FechaValida($MBFechaF){
            $FechaValida = FechaValida($MBFechaF);
            if ($FechaValida["ErrorFecha"]) {
                return ['error' => true, "mensaje" => $FechaValida["MsgBox"]];
            }        
            return ['error' => false, "mensaje" => "--"];
        }*/

    function Enviar_Rubros($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $CheqMatricula = $parametros['CheqMatricula'];

        $Cont = 0;
        $CaptionTemp = '';
        $Costo_Banco = 0.0;
        $Tabulador = '';

        $SumaBancos = 0;        
        FechaValida($MBFechaI);
        FechaValida($MBFechaF);
        $TipoDoc = ($CheqMatricula == 1) ? "0" : "1";

        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = Ninguno; //
        $FechaFinal = $MBFechaF;
        $FechaTexto = date('Y-m-d H:i:s');
        $FechaTexto1 = date('m/d/Y', strtotime($MBFechaI));
        $Mifecha = BuscarFecha($MBFechaI);
        $MiMes = sprintf("%02d", date('m', strtotime($MBFechaI)));
        $FechaFin = BuscarFecha($MBFechaF);
        $TextoImprimio = "";

        Eliminar_Nulos_SP("Facturas"); 

        $this->modelo-> Query1EnviarRubros();
        $this->modelo-> Query2EnviarRubros($parametros);
        //Detalle de las Facturas Emitidas del mes     
        $this->modelo-> Query3EnviarRubros($parametros);   
    }

    function Generar_Pichincha($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoPendiente = $parametros['AdoPendiente'];
        $Cta_Bancaria = $parametros['Cta_Bancaria'];
        $AuxNumEmp = "";
        $DiaV = 0;
        $MesV = 0;
        $AñoV = 0;
        $CamposFile = array();

        $Mifecha = BuscarFecha($MBFechaI);
        $MiMes = sprintf("%02d", date("m", strtotime($MBFechaI)));
        $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaI));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        //$Cta_Cobrar = $Ninguno;
        $FechaSistema = date('Y-m-d H:i:s');
        $FechaTexto = $FechaSistema;

        // Comenzamos a generar el archivo: SCRECXX.TXT
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;
        $SumaBancos = 0;
        $NumFileFacturas = fopen(strtoupper(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/SCREC" . date("m", strtotime($MBFechaI)) . ".TXT"), "w");

        if (count($AdoPendiente) > 0) {
            foreach ($AdoPendiente as $row) {
                $Contador = $Contador + 1;
                $CodigoCli = $row["CodigoC"];
                $NombreCliente = Sin_Signos_Especiales($row["Cliente"]);
                $Factura_No = $row["Factura"];
                $SerieFactura = $row["Serie"];
                $Total = $row["Saldo_MN"];
                $Saldo = $row["Saldo_MN"] * 100;
                $CodigoP = $row["CI_RUC"];
                $CodigoC = $CodigoC . str_pad("", abs(4 - strlen($CodigoC)), " ");
                $DireccionCli = Sin_Signos_Especiales($row["Direccion"]);
                $Codigo3 = SinEspaciosDer($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer($DireccionCli));
                $Codigo1 = $row["Anio_Mes"];

                if (strlen($row["Actividad"]) < 3) {
                    if ($Tipo_Carga == 1) {
                        // Tipo Gualaceo
                        fwrite($NumFileFacturas, "CO\t");
                        fwrite($NumFileFacturas, $CodigoC . "\t");
                        fwrite($NumFileFacturas, "USD\t");
                        fwrite($NumFileFacturas, $Saldo . "\t");
                        fwrite($NumFileFacturas, "REC\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        if ($CheqMatricula == 1) {
                            $Codigo4 = "MATRICULAS DE " . substr(MesesLetras(date("n", strtotime($MBFechaI))), 0, 3) . "-" . date("Y", strtotime($MBFechaI));
                        } else {
                            $Codigo4 = "PENSION ACUMULADA DE " . substr(MesesLetras(date("n", strtotime($MBFechaI))), 0, 3) . "-" . date("Y", strtotime($MBFechaI));
                        }
                        fwrite($NumFileFacturas, strtoupper(UCaseStrg($Codigo4)) . "\t");
                        fwrite($NumFileFacturas, "N\t");
                        fwrite($NumFileFacturas, sprintf("%010d", $CodigoC) . "\t");
                        fwrite($NumFileFacturas, substr($NombreCliente, 0, 40) . "\t");
                    } else {
                        // Tipo General
                        fwrite($NumFileFacturas, "CO\t");
                        fwrite($NumFileFacturas, $Cta_Bancaria . "\t");
                        fwrite($NumFileFacturas, $Contador . "\t");
                        fwrite($NumFileFacturas, sprintf("%010d", $Factura_No) . "\t");
                        fwrite($NumFileFacturas, $CodigoP . "\t");
                        fwrite($NumFileFacturas, "USD\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                        fwrite($NumFileFacturas, "REC\t");
                        fwrite($NumFileFacturas, "10\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "0\t");
                        fwrite($NumFileFacturas, "R\t");
                        fwrite($NumFileFacturas, $RUC . "\t");
                        fwrite($NumFileFacturas, substr($Codigo1, 5, 2) . " " . substr($NombreCliente, 0, 37) . "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, substr($Codigo1, 5, 2) . "\t");

                        if ($CheqMatricula == 1) {
                            $Codigo4 = $row["Grupo"] . " Matricula ";
                        } else {
                            $Codigo4 = $row["Grupo"] . " Pension ";
                        }
                        $Codigo4 = $Codigo4 . str_repeat(" ", 26 - strlen($Codigo4)) . "$SerieFactura $Codigo1";
                        fwrite($NumFileFacturas, $Codigo4 . "\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                        $SumaBancos = $SumaBancos + $row["Saldo_MN"];
                    }
                }
            }
        }
        fclose($NumFileFacturas);

        //Comenzamos a generar el archivo: SCCOB.TXT
        $mes = date('m', strtotime($MBFechaI));
        $anio = intval(substr(date('Y', strtotime($MBFechaI)), 1, 3));
        $dia = "15";
        $NumFileFacturas = fopen(strtoupper(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/SCCOB" . $mes . ".TXT"), 'w');
        if (count($AdoPendiente) > 0) {
            foreach ($AdoPendiente as $row) {
                $Contador = $Contador + 1;
                $CodigoCli = $row["CodigoC"];
                $NombreCliente = Sin_Signos_Especiales($row["Cliente"]);
                $Factura_No = $Factura_No + 1;
                $Total = $row["Saldo_MN"];
                $Saldo = $row["Saldo_MN"] * 100;
                $CodigoP = $row["CI_RUC"];
                $DireccionCli = Sin_Signos_Especiales($row["Direccion"]);
                $Codigo3 = SinEspaciosDer($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer($DireccionCli));
                $Codigo1 = sprintf("%02d", intval(substr($GrupoNo, 0, 1)));

                if (strlen($row["Actividad"]) >= 3) {
                    fwrite($NumFileFacturas, "CO\t");
                    fwrite($NumFileFacturas, $Cta_Bancaria . "\t");
                    fwrite($NumFileFacturas, $Contador . "\t");
                    fwrite($NumFileFacturas, sprintf("%010d", $Factura_No) . "\t");
                    fwrite($NumFileFacturas, $CodigoP . "\t");
                    fwrite($NumFileFacturas, "USD\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                    fwrite($NumFileFacturas, "CTA\t");
                    fwrite($NumFileFacturas, "10\t");
                    $NumStrg = SinEspaciosIzq($row["Actividad"]);
                    if (strlen($NumStrg) == 3) {
                        fwrite($NumFileFacturas, SinEspaciosIzq($row["Actividad"]) . "\t");
                        fwrite($NumFileFacturas, SinEspaciosDer($row["Actividad"]) . "\t");
                    } else {
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                    }
                    fwrite($NumFileFacturas, "R\t");
                    fwrite($NumFileFacturas, "RUC\t");
                    fwrite($NumFileFacturas, substr(date('m', strtotime($MBFechaI)) . " " . $NombreCliente, 0, 40) . "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, "\t");
                    fwrite($NumFileFacturas, $mes . "\t");
                    fwrite($NumFileFacturas, "Pensión Acumulada\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                }
            }
        }
        fclose($NumFileFacturas);
        $mensaje = "SE GENERARON LOS SIGUIENTES ARCHIVOS:" . PHP_EOL . PHP_EOL .
            strtoupper(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/SCREC" . $mes . ".TXT") . PHP_EOL . PHP_EOL .
            strtoupper(dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/SCCOB" . $mes . ".TXT") . PHP_EOL . PHP_EOL .
            "Valor Total a Recaudar USD " . number_format($SumaBancos, 2, '.', ',');

        return array('res' => 'Ok', 'mensaje' => $mensaje);
    }



}

?>