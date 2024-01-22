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
    $fecha = $_POST['fecha'];
    echo json_encode($controlador->MBFechaF_LostFocus($fecha));
}

if (isset($_GET['LeerSeteosCtas'])) {
    echo json_encode(Leer_Seteos_Ctas());
}

if (isset($_GET['EnviarRubros'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Enviar_Rubros($parametros));
}

if (isset($_GET['RecibirAbonos'])) {
    $MBFechaI = $_POST['MBFechaI'];
    $MBFechaF = $_POST['MBFechaF'];
    $TxtOrden = $_POST['TxtOrden'];
    $DCEntidad = $_POST['DCEntidad'];

    $parametros = array(
        'MBFechaI' => $MBFechaI,
        'MBFechaF' => $MBFechaF,
        'TxtOrden' => $TxtOrden,
        'DCEntidad' => $DCEntidad,
    );

    if (isset($_FILES['archivoBanco']) && $_FILES['archivoBanco']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivoBanco'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/BANCO/ABONOS/";
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $nombreArchivoDestino = $carpetaDestino . basename($archivo['name']);
        if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
            $parametros['NombreArchivo'] = $nombreArchivoDestino;
            echo json_encode($controlador->Recibir_Abonos($parametros));
        } else {
            echo json_encode(array("response" => 0, "message" => "Error al subir el archivo"));
        }
    }
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
            $this->modelo->MBFechaF_LostFocusUpdate($fecha);
        }
    }

    function Enviar_Rubros($parametros)
    {
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        //$Tipo_Carga = $parametros['Tipo_Carga'];        
        $CheqMatricula = $parametros['CheqMatricula'];
        $Cta_Bancaria = SinEspaciosDer($parametros['DCBanco']);
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");

        /** 
         * $DCGrupoI = $parametros['DCGrupoI'];
         * $DCGrupoF = $parametros['DCGrupoF'];
         * $CheqRangos = $parametros['$CheqRangos']; 
         */

        $Cont = 0;
        $CaptionTemp = '';
        $Costo_Banco = 0.0;
        $Tabulador = '';

        $SumaBancos = 0;
        FechaValida($MBFechaI);
        FechaValida($MBFechaF);
        $TipoDoc = ($CheqMatricula == 1) ? "0" : "1";

        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaFinal = $MBFechaF;
        $FechaTexto = date('Y-m-d H:i:s');
        $FechaTexto1 = date('m/d/Y', strtotime($MBFechaI));
        $Mifecha = BuscarFecha($MBFechaI);
        $MiMes = sprintf("%02d", date('m', strtotime($MBFechaI)));
        $FechaFin = BuscarFecha($MBFechaF);
        $TextoImprimio = "";

        Eliminar_Nulos_SP("Facturas");

        $this->modelo->Query1EnviarRubros();
        $query2 = $this->modelo->Query2EnviarRubros($parametros);
        $AdoPendiente = array();
        if (count($query2) > 0) {
            foreach ($query2 as $value) {
                $AdoPendiente[] = array(
                    'Grupo' => $value['Grupo'],
                    'Cliente' => $value['Cliente'],
                    'Anio_Mes' => $value['Anio_Mes'],
                    'Serie' => $value['Serie'],
                    'Factura' => $value['Factura'],
                    'CI_RUC' => $value['CI_RUC'],
                    'CodigoC' => $value['CodigoC'],
                    'Actividad' => $value['Actividad'],
                    'Direccion' => $value['Direccion'],
                    'Fecha' => $value['Fecha'],
                    'Saldo_MN' => $value['Saldo_MN'],
                    'Total_MN' => $value['Total_MN']
                );
            }
        }
        //Detalle de las Facturas Emitidas del mes     
        $query3 = $this->modelo->Query3EnviarRubros($parametros);
        $AdoDetalle = array();
        if (count($query3) > 0) {
            foreach ($query3 as $value) {
                $AdoDetalle[] = array(
                    'Fecha' => $value['Fecha'],
                    'Cliente' => $value['Cliente'],
                    'Grupo' => $value['Grupo'],
                    'RUC' => $value['CI_RUC'],
                    'Direccion' => $value['Direccion'],
                    'Item_Banco' => $value['Item_Banco'],
                    'Desc_Item' => $value['Desc_Item']
                );
            }
        }
        //Facturas Emitidas del mes
        $query4 = $this->modelo->Query4EnviarRubros($parametros);
        $AdoFactura = array();
        if (count($query4) > 0) {
            foreach ($query4 as $value) {
                $AdoFactura[] = array(
                    'CodigoC' => $value['CodigoC'],
                    'Actividad' => $value['Actividad'],
                    'Cliente' => $value['Cliente'],
                    'Grupo' => $value['Grupo'],
                    'CI_RUC' => $value['CI_RUC'],
                    'Direccion' => $value['Direccion'],
                    'Saldo_Pend' => $value['Saldo_Pend']
                );
            }
        }
        //Facturas Emitidas del mes
        $query5 = $this->modelo->Query5EnviarRubros($parametros);
        $AdoAux = array();
        if (count($query5) > 0) {
            foreach ($query5 as $value) {
                $AdoAux[] = array(
                    'Fecha' => $value['Fecha'],
                    'Cliente' => $value['Cliente'],
                    'Grupo' => $value['Grupo'],
                    'RUC' => $value['CI_RUC'],
                    'Direccion' => $value['Direccion'],
                    'Casilla' => $value['Casilla']
                );
            }
        }

        switch ($parametros['DCEntidad']) {

            case "PICHINCHA":
                $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaF)));
                $parametros = array(
                    'MBFechaI' => $MBFechaI,
                    'MBFechaF' => $MBFechaF,
                    'AdoPendiente' => $AdoPendiente,
                    'Cta_Bancaria' => $Cta_Bancaria,
                    'Tipo_Carga' => $Tipo_Carga,
                    'CheqMatricula' => $CheqMatricula,
                );
                //print_r($parametros2['Tipo_Carga']);
                $res = $this->Generar_Pichincha($parametros);
                break;

            /*case "BGR_EC":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_BGR_EC();
                break;
            case "INTERNACIONAL":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Internacional();
                break;
            case "BOLIVARIANO":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Bolivariano();
                break;
            case "PACIFICO":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Pacifico();
                break;
            case "PRODUBANCO":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Produbanco();
                break;
            case "GUAYAQUIL":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Guayaquil();
                break;
            case "COOPJEP":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Coop_Jep();
                break;
            default:
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                echo "No está definido este Banco";
                break;*/
        }

        // Facturas Emitidas del mes
        // Generacion del Resumen de la facturacion del mes
        $Tabulador = ";";
        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        $RutaGeneraFile = strtoupper($directorioBase . "RESUMEN_MES_" . substr(mesesLetras(date('m')), 0, 3) . "-" . date('Y') . "_" . $Cta_Bancaria . ".csv");
        $NumFileFacturas = fopen($RutaGeneraFile, "w");
        $Contador = 0;
        $FechaTexto = buscarFecha($MBFechaI);

        if (count($AdoPendiente) > 0) {
            fwrite($NumFileFacturas, "No." . $Tabulador);
            fwrite($NumFileFacturas, "GRUPO" . $Tabulador);
            fwrite($NumFileFacturas, "CODIGO" . $Tabulador);
            fwrite($NumFileFacturas, "BENEFICIARIO" . $Tabulador);
            fwrite($NumFileFacturas, "DETALLE" . $Tabulador);
            fwrite($NumFileFacturas, "AÑO-MES" . $Tabulador);
            fwrite($NumFileFacturas, "SERIE" . $Tabulador);
            fwrite($NumFileFacturas, "FACTURA No" . $Tabulador);
            fwrite($NumFileFacturas, "TOTAL FACTURA" . $Tabulador);
            fwrite($NumFileFacturas, "SALDO FACTURA" . $Tabulador);
            fwrite($NumFileFacturas, "\n");

            foreach ($AdoPendiente as $valor) {
                $Contador++;
                $Grupo_No = $valor["Grupo"];
                $Codigo = $valor["CodigoC"];
                $CodigoCli = $valor["CI_RUC"];
                $NombreCliente = Sin_Signos_Especiales($valor["Cliente"]);
                $Codigo1 = Sin_Signos_Especiales($valor["Direccion"]);
                $Codigo2 = $valor["Anio_Mes"];
                $SerieFactura = "'" . $valor["Serie"];
                $Factura_No = $valor["Factura"];
                $Total_Factura = $valor["Total_MN"];
                $Total_Pagar = $valor["Saldo_MN"];
                $Total = $Total_Factura - $Total_Pagar;

                // Empieza la trama por Alumno
                fwrite($NumFileFacturas, $Contador . $Tabulador);
                fwrite($NumFileFacturas, $Grupo_No . $Tabulador);
                fwrite($NumFileFacturas, $CodigoCli . $Tabulador);
                fwrite($NumFileFacturas, $NombreCliente . $Tabulador);
                fwrite($NumFileFacturas, $Codigo1 . $Tabulador);
                fwrite($NumFileFacturas, $Codigo2 . $Tabulador);
                fwrite($NumFileFacturas, $SerieFactura . $Tabulador);
                fwrite($NumFileFacturas, $Factura_No . $Tabulador);
                fwrite($NumFileFacturas, $Total_Factura . $Tabulador);
                fwrite($NumFileFacturas, $Total_Pagar . $Tabulador);
                fwrite($NumFileFacturas, "\n");
            }
        }

        fclose($NumFileFacturas);
        return $res;
    }

    function Recibir_Abonos($parametros)
    {
        //print_r($parametros);
        $OrdenValida = false;
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        //$Archivo = $parametros['Archivo'];
        $TextoBanco = $parametros['DCEntidad'];
        $TxtOrden = $parametros['TxtOrden'];
        //$Label4 = G_NINGUNO;
        FechaValida($MBFechaI);
        FechaValida($MBFechaF);

        $NombreArchivo = $parametros['NombreArchivo'];
        $RutaGeneraFile = strtoupper($NombreArchivo);
        
        $Contador = 0;
        $CantCampos = 0;
        $TotalIngreso = 0;
        $Separador = G_NINGUNO;
        $Orden_Pago = G_NINGUNO;
        $OrdenValida = false;
        $CamposFile = [];

        $NumFile = fopen($RutaGeneraFile, "r");
        if ($NumFile) {
            while (!feof($NumFile)) {
                $Cod_Field = fgets($NumFile);
                if ($Separador == G_NINGUNO) {
                    if (strpos($Cod_Field, "\t") !== false) {
                        $Separador = "\t";
                    }
                }
                if ($Contador == 0) {
                    while (strlen($Cod_Field) > 2) {
                        $No_Hasta = strpos($Cod_Field, $Separador);
                        $CampoTemp = trim(substr($Cod_Field, 0, $No_Hasta - 1));
                        $CamposFile[] = ['Campo' => "C" . sprintf("%02d", count($CamposFile)), 'Valor' => $CampoTemp];

                        switch ($TextoBanco) {
                            case "PICHINCHA":
                                if ($CantCampos == 14 && $TxtOrden == $CampoTemp) {
                                    $Orden_Pago = $CampoTemp;
                                    $OrdenValida = true;
                                }
                                break;
                            default:
                                $OrdenValida = true;
                                break;
                        }
                        $Cod_Field = trim(substr($Cod_Field, $No_Hasta + 1));
                    }
                }
                $Contador++;
            }
            fclose($NumFile);
        }

        if (!$OrdenValida) {
            $mensaje = "La información del archivo no pertenece a la Orden No. " . $TxtOrden . " registrada del Banco, vuelva a seleccionar el documento correcto.";
            return array('res' => 'Error', 'mensaje' => $mensaje);
        }

        return array('res' => 'OK', 'mensaje' => 'AUN EN DESARROLLO.');
    }

    function Generar_Pichincha($parametros)
    {
        //print_r($parametros['AdoPendiente']);
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        $AdoPendiente = $parametros['AdoPendiente'];
        $Cta_Bancaria = $parametros['Cta_Bancaria'];
        $Tipo_Carga = $parametros['Tipo_Carga'];
        $CheqMatricula = $parametros['CheqMatricula'];

        $AuxNumEmp = "";
        $DiaV = 0;
        $MesV = 0;
        $AñoV = 0;
        $CamposFile = array();

        $Mifecha = BuscarFecha($MBFechaI);
        $MiMes = sprintf("%02d", date("m", strtotime($MBFechaI)));
        $FechaFin = BuscarFecha(date("Y-m-t", strtotime($MBFechaI)));  //$FechaFin = BuscarFecha(UltimoDiaMes($MBFechaI));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaSistema = date('Y-m-d H:i:s');
        $FechaTexto = $FechaSistema;

        // Comenzamos a generar el archivo: SCRECXX.TXT
        $EsComa = true;
        $Estab = false;
        $Contador = 0;
        $Factura_No = 0;
        $SumaBancos = 0;

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/BANCO/FACTURAS/";
        // Verificar si el directorio base existe, si no, crearlo
        if (!is_dir($directorioBase)) {
            mkdir($directorioBase, 0777, true);
        }
        $NumFileFacturas = fopen(strtoupper($directorioBase . "SCREC" . date("m", strtotime($MBFechaI)) . ".TXT"), "w");

        if (count($AdoPendiente) > 0) {
            foreach ($AdoPendiente as $valor) {
                $Contador = $Contador + 1;
                $CodigoCli = $valor["CodigoC"];
                $NombreCliente = Sin_Signos_Especiales($valor["Cliente"]);
                $Factura_No = $valor["Factura"];
                $SerieFactura = $valor["Serie"];
                $Total = $valor["Saldo_MN"];
                $Saldo = $valor["Saldo_MN"] * 100;
                $CodigoP = $valor["CI_RUC"];
                $CodigoC = strval(intval($valor['CI_RUC']));
                $CodigoC .= str_pad('', max(0, 4 - strlen($CodigoC)), ' ');
                $DireccionCli = Sin_Signos_Especiales($valor["Direccion"]);
                $Codigo3 = SinEspaciosDer2($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                $Codigo1 = $valor["Anio_Mes"];

                if (strlen($valor["Actividad"]) < 3) {
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
                        fwrite($NumFileFacturas, strtoupper($Codigo4) . "\t");
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
                        fwrite($NumFileFacturas, $_SESSION['INGRESO']['RUC'] . "\t");
                        fwrite($NumFileFacturas, substr($Codigo1, 5, 2) . " " . substr($NombreCliente, 0, 37) . "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, "\t");
                        fwrite($NumFileFacturas, substr($Codigo1, 5, 2) . "\t");

                        if ($CheqMatricula == 1) {
                            $Codigo4 = $valor["Grupo"] . " Matricula ";
                        } else {
                            $Codigo4 = $valor["Grupo"] . " Pension ";
                        }
                        $Codigo4 = $Codigo4 . str_repeat(" ", 26 - strlen($Codigo4)) . "$SerieFactura $Codigo1";
                        fwrite($NumFileFacturas, $Codigo4 . "\t");
                        fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                        $SumaBancos = $SumaBancos + $valor["Saldo_MN"];
                    }
                }
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);

        //Comenzamos a generar el archivo: SCCOB.TXT
        $mes = date('m', strtotime($MBFechaI));
        $anio = intval(substr(date('Y', strtotime($MBFechaI)), 1, 3));
        $dia = "15";
        $NumFileFacturas = fopen(strtoupper($directorioBase . "SCCOB" . $mes . ".TXT"), 'w');
        if (count($AdoPendiente) > 0) {
            foreach ($AdoPendiente as $valor) {
                $Contador = $Contador + 1;
                $CodigoCli = $valor["CodigoC"];
                $NombreCliente = Sin_Signos_Especiales($valor["Cliente"]);
                $Factura_No = $Factura_No + 1;
                $Total = $valor["Saldo_MN"];
                $Saldo = $valor["Saldo_MN"] * 100;
                $CodigoP = $valor["CI_RUC"];
                $DireccionCli = Sin_Signos_Especiales($valor["Direccion"]);
                $Codigo3 = SinEspaciosDer2($DireccionCli);
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim(SinEspaciosDer2($DireccionCli));
                //$Codigo1 = sprintf("%02d", intval(substr($GrupoNo, 0, 1))); //FALTA VARIABLE

                if (strlen($valor["Actividad"]) >= 3) {
                    fwrite($NumFileFacturas, "CO\t");
                    fwrite($NumFileFacturas, $Cta_Bancaria . "\t");
                    fwrite($NumFileFacturas, $Contador . "\t");
                    fwrite($NumFileFacturas, sprintf("%010d", $Factura_No) . "\t");
                    fwrite($NumFileFacturas, $CodigoP . "\t");
                    fwrite($NumFileFacturas, "USD\t");
                    fwrite($NumFileFacturas, sprintf("%013d", $Saldo) . "\t");
                    fwrite($NumFileFacturas, "CTA\t");
                    fwrite($NumFileFacturas, "10\t");
                    $NumStrg = SinEspaciosIzq($valor["Actividad"]);
                    if (strlen($NumStrg) == 3) {
                        fwrite($NumFileFacturas, SinEspaciosIzq($valor["Actividad"]) . "\t");
                        fwrite($NumFileFacturas, SinEspaciosDer($valor["Actividad"]) . "\t");
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
                fwrite($NumFileFacturas, "\n");
            }
        }
        fclose($NumFileFacturas);
        $mensaje =
            strtoupper("SCREC" . $mes . ".TXT") . PHP_EOL . PHP_EOL .
            strtoupper("SCCOB" . $mes . ".TXT") . PHP_EOL . PHP_EOL .
            "Valor Total a Recaudar USD " . number_format($SumaBancos, 2, '.', ',');

        return array('res' => 'Ok', 
        'mensaje' => $mensaje, 
        'textoBanco' => 'PICHINCHA', 
        'Nombre1'=> 'SCREC' . $mes . ".TXT",
        'Nombre2'=> 'SCCOB' . $mes . ".TXT");
    }
    
}
?>