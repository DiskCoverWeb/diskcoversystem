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
    $DCBanco = $_POST['DCBanco'];
    $CheqSat = $_POST['CheqSat'];

    $parametros = array(
        'MBFechaI' => $MBFechaI,
        'MBFechaF' => $MBFechaF,
        'TxtOrden' => $TxtOrden,
        'DCEntidad' => $DCEntidad,
        'DCBanco' => $DCBanco,
        'CheqSat' => $CheqSat,
    );

    if (isset($_FILES['archivoBanco']) && $_FILES['archivoBanco']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivoBanco'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/ABONOS/";
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
                break;*/
            case "INTERNACIONAL":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Internacional();
                break;
            /*case "BOLIVARIANO":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Bolivariano();
                break;*/
            case "PACIFICO":
                $FechaFin = BuscarFecha(UltimoDiaMes($MBFechaF));
                //Generar_Pacifico();
                break;
            /*case "PRODUBANCO":
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
        $directorioBase = dirname(__DIR__, 3) . "/TEMP/FACTURAS/";
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
        $Nombre3 = "RESUMEN_MES_" . substr(mesesLetras(date('m')), 0, 3) . "-" . date('Y') . "_" . $Cta_Bancaria . ".csv";
        $res['Nombre3'] = $Nombre3;
        return $res;
    }

    function Recibir_Abonos($parametros)
    {
        //print_r($parametros);    
        $MBFechaI = $parametros['MBFechaI'];
        $MBFechaF = $parametros['MBFechaF'];
        //$Archivo = $parametros['Archivo'];
        $TextoBanco = $parametros['DCEntidad'];
        $TxtOrden = $parametros['TxtOrden'];
        $DCBanco = $parametros['DCBanco'];
        $CheqSAT = $parametros['CheqSAT'];
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
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
                        $CamposFile[$CantCampos]['Campo'] = "C" . sprintf("%02d", $CantCampos);
                        $CampoTemp = trim(substr($Cod_Field, 0, $No_Hasta));

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

        $Total_Alumnos = $Contador;
        if (!$OrdenValida) {
            $mensaje = "La información del archivo no pertenece a la Orden No. " . $TxtOrden . " registrada del Banco, vuelva a seleccionar el documento correcto.";
            return array('res' => 'Error', 'mensaje' => $mensaje);
        }

        //procedemos a borrar los abonos recibidos
        $NumFile = fopen($RutaGeneraFile, "r");

        while (!feof($NumFile)) {
            $Cod_Field = fgets($NumFile);
            // Colocamos los datos del archivo en un array de texto
            $CantCampos = 0;

            while (strlen($Cod_Field) > 2) {
                $No_Hasta = strpos($Cod_Field, $Separador);
                $CamposFile[$CantCampos]['Valor'] = trim(substr($Cod_Field, 0, $No_Hasta - 1));
                $Cod_Field = trim(substr($Cod_Field, $No_Hasta + 1));
                $CantCampos++;
            }

            // Procedemos a eliminar los abonos que se encuentran en el archivo, por si volvemos a subir
            switch ($TextoBanco) {
                case "PICHINCHA":
                    $TipoDoc = $CamposFile[7]['Valor'];
                    $TipoProc = SinEspaciosDer($TipoDoc);
                    $TA['Serie'] = SinEspaciosDer(substr($TipoDoc, 0, strlen($TipoDoc) - strlen($TipoProc)));
                    $TA['Factura'] = intval($CamposFile[35]['Valor']);
                    $TA['Recibo_No'] = sprintf("%010d", intval($CamposFile[34]['Valor']));

                    $this->modelo->EliminarAbonos($TA);
            }
        }
        fclose($NumFile);
        $FA['Serie'] = G_NINGUNO;
        $FA['TC'] = G_NINGUNO;
        $FA['Factura'] = 0;
        Actualizar_Abonos_Facturas_SP($FA);

        $AbonosAnticipados = 0;
        $Total_Dep_Confirmar = 0;
        $Trans_No = 200;
        BorrarAsientos(true);
        $SubCtaGen = Leer_Seteos_Ctas("Cta_Anticipos_Clientes");
        $Cta_Del_Banco = trim(SinEspaciosIzq($DCBanco));
        $Contrato_No = G_NINGUNO;

        $TxtFile = "";
        $Fecha_Tope = FechaSistema();
        $Total_Costo_Banco = 0;
        $TextoImprimio = "";

        // Alumnos/Clientes que están activados para generar las facturas
        $AdoClientes = $this->modelo->AlumnosClientesActivados();
        $MBFechaI = FechaValida($MBFechaI);
        $Mifecha = BuscarFecha($MBFechaI);
        $FechaTexto = $MBFechaI;
        $DiarioCaja = ReadSetDataNum('Recibo_No', True, True);

        $RutaGeneraFile = strtoupper($NombreArchivo);

        if ($RutaGeneraFile !== "") {
            $TotalIngreso = 0;
            $Contador = 0;
            $FileResp = 0;
            //establecemos los campos del archivo plano del banco
            $NumFile = fopen($RutaGeneraFile, "r");
            $Total_Alumnos = 0;
            $FechaTexto = FechaSistema();
            $TxtFile = "";

            while (!feof($NumFile)) {
                $Cod_Field = fgets($NumFile);
                $Cod_Field = str_replace('"', '', $Cod_Field);
                $TxtFile .= $Cod_Field . "\n";

                // Comenzamos la subida de los Abonos
                $CantCampos = 0;
                while (strlen($Cod_Field) > 2) {
                    $No_Hasta = strpos($Cod_Field, $Separador);
                    $CamposFile[$CantCampos]['Valor'] = trim(substr($Cod_Field, 1, $No_Hasta - 1));
                    $Cod_Field = trim(substr($Cod_Field, $No_Hasta + 1));
                    $CantCampos++;
                }

                // Actualizamos de qué alumnos vamos a ingresar el abono
                $TA['Serie'] = G_NINGUNO;
                $TA['Factura'] = 0;
                $TA['Fecha'] = FechaSistema();
                $TA['CodigoC'] = G_NINGUNO;
                $TA['Recibo_No'] = "0000000000";
                $CodigoCli = G_NINGUNO;
                $CodigoP = "0";
                $Proceso_Ok = "PROCESO OK";

                switch ($TextoBanco) {
                    case "PICHINCHA":
                        // Código específico para Pichincha
                        if ($Tipo_Carga == 1) {
                            $CodigoP = trim(strval(intval(substr($Cod_Field, 25, 19))));
                            $FechaTexto = substr($Cod_Field, 205, 2) . "/" . substr($Cod_Field, 207, 2) . "/" . substr($Cod_Field, 209, 4);
                        } else {
                            // Serie de la Factura
                            $TipoDoc = $CamposFile[7]['Valor'];
                            $TipoProc = SinEspaciosDer($TipoDoc);
                            $TipoDoc = trim(substr($TipoDoc, 0, strlen($TipoDoc) - strlen($TipoProc)));

                            $TA['Serie'] = SinEspaciosDer($TipoDoc);
                            $TA['Factura'] = intval($CamposFile[35]['Valor']);
                            $TA['CodigoC'] = $CamposFile[4]['Valor'];
                            $TA['Fecha'] = str_replace(" ", "/", $CamposFile[25]['Valor']);
                            $TA['Recibo_No'] = sprintf("%010d", intval($CamposFile[34]['Valor']));
                            $TA['Abono'] = intval($CamposFile[27]['Valor']);

                            $Proceso_Ok = trim($CamposFile[22]['Valor']);

                            if ($Proceso_Ok === "REVERSO OK") {
                                $CodigoP = str_pad(intval($CodigoP), 13, '0', STR_PAD_LEFT);
                            }
                            $CodigoP = $TA['CodigoC'];

                            // Detalle del Abono
                            if (trim($CamposFile[29]['Valor']) === "EFE") {
                                $TA['Banco'] = "PAGO EN EFECTIVO";
                                $TA['Cheque'] = "VENT.: " . str_replace(" ", "h", substr($CamposFile[26]['Valor'], 12, 5)) . "s";
                            } else {
                                $TA['Banco'] = "TRANS. " . $CamposFile[29]['Valor'] . "|" . $CamposFile[16]['Valor'];
                                $TA['Cheque'] = $CamposFile[18]['Valor'] . "-" . $CamposFile[19]['Valor'] . ": " . str_replace(" ", "h", substr($CamposFile[26]['Valor'], 12, 5)) . "s";
                            }
                        }
                        break;
                    case "BOLIVARIANO":
                        if ($CheqSAT == 1) {
                            $CodigoP = substr($Cod_Field, 13, 8);
                        } else {
                            $CodigoP = substr($Cod_Field, 0, 8);
                        }
                        if ($Total_Alumnos == 0) {
                            $FechaTexto = substr($Cod_Field, 11, 2) . "/" . substr($Cod_Field, 9, 2) . "/" . substr($Cod_Field, 5, 4);
                            $CodigoP = G_NINGUNO;
                        }
                        break;
                    case "BGR_EC":
                        if ($Tipo_Carga == 1) {
                            $CodigoP = trim(strval(intval(substr($Cod_Field, 24, 19))));
                            $FechaTexto = substr($Cod_Field, 204, 2) . "/" .
                                substr($Cod_Field, 206, 2) . "/" .
                                substr($Cod_Field, 208, 4);
                        } else {
                            $CodigoP = $CamposFile[10]['Valor'];
                            $FechaTexto = str_replace(" ", "/", $CamposFile[24]['Valor']);
                            $HoraTexto = str_replace(" ", ":", $CamposFile[25]['Valor']);
                            $CodigoB = $CamposFile[28]['Valor'] . ":" . $CamposFile[19]['Valor'] . "-" . str_replace(" ", ":", $CamposFile[25]['Valor']);
                        }
                        break;
                    case "INTERNACIONAL":
                        $CodigoP = trim(strval(intval(substr($Cod_Field, 24, 19))));
                        $FechaTexto = substr($Cod_Field, 204, 2) . "/" .
                            substr($Cod_Field, 206, 2) . "/" .
                            substr($Cod_Field, 208, 4);
                        break;
                    case "PACIFICO":
                        if ($CheqSAT) {
                            $CodigoP = $CamposFile[16]['Valor'];
                            $FechaTexto = date('d/m/Y', strtotime($CamposFile[11]['Valor']));

                        } else {
                            if ($Total_Alumnos !== 0) {
                                $CodigoP = $CamposFile[3]['Valor'];
                                $FechaTexto = substr($CamposFile[5]['Valor'], 0, 10);
                            }
                        }
                        break;
                    case "PRODUBANCO":
                        $CodigoP = $CamposFile[6]['Valor'];
                        $FechaTexto = $CamposFile[11]['Valor'];
                        $CodigoB = $CamposFile[13]['Valor'];

                        $NoAnio = intval(substr(trim($CodigoB), 0, 4));
                        if ($NoAnio <= 1900 && strtotime($FechaTexto)) {
                            $NoMeses = date('n', strtotime($FechaTexto));
                            $NoAnio = date('Y', strtotime($FechaTexto));
                            $Mes = MesesLetras($NoMeses);
                        }
                        break;
                    case "INTERMATICO":
                        $CodigoP = $CamposFile[6]['Valor'];
                        $FechaTexto = $CamposFile[0]['Valor'];
                        if (strlen($FechaTexto) > 10) {
                            $FechaTexto = FechaSistema();
                            $CodigoP = G_NINGUNO;
                        }
                        $Mifecha = $FechaTexto;
                        break;
                    case "COOPJEP":
                        $CodigoP = trim($CamposFile[15]['Valor']);
                        $FechaTexto = $CamposFile[0]['Valor'];
                        break;

                    case "CACPE":
                        $CodigoP = strval(intval($CamposFile[5]['Valor']));
                        $FechaTexto = substr($CamposFile[7]['Valor'], 3, 2) . "/" .
                            substr($CamposFile[7]['Valor'], 0, 2) . "/" .
                            substr($CamposFile[7]['Valor'], 6, 4);
                        break;
                    default:
                        $CodigoP = G_NINGUNO;
                        $TipoDoc = $CamposFile[0]['Valor'];
                        $FechaTexto = $CamposFile[1]['Valor'];
                        $SerieFactura = substr($CamposFile[2]['Valor'], 0, 3) . substr($CamposFile[2]['Valor'], 4, 3);
                        $Factura_No = intval(substr($CamposFile[2]['Valor'], 8, 10));
                        $Autorizacion = $CamposFile[3]['Valor'];

                        $AdoFactura = $this->modelo->sqlCaseElse($TipoDoc, $SerieFactura, $Autorizacion, $Factura_No);

                        if (count($AdoFactura) > 0) {
                            foreach ($AdoFactura as $valor) {
                                $CodigoP = $valor["CI_RUC"];
                                $CodigoCli = $valor["CodigoC"];
                            }
                        }
                        break;
                }

                $Si_No = true;
                if (count($AdoClientes) > 0) {
                    foreach ($AdoClientes as $valor) {
                        if (strlen($CodigoP) <= 10 && $Si_No) {
                            if ($valor['CI_RUC'] == $CodigoP) {
                                $TA['CodigoC'] = $valor['Codigo'];
                                $NombreCliente = $valor['Cliente'];
                                $FA['CodigoC'] = $TA['CodigoC'];
                                $FA['Cliente'] = $NombreCliente;
                                $FA['EmailC'] = $valor['Email'];
                                $FA['EmailC2'] = $valor['Email2'];
                                $FA['EmailR'] = $valor['EmailR'];
                                $Si_No = false;
                            } else {
                                $CodigoP = "0" . $CodigoP;
                            }
                        }
                    }
                }
                if (strlen($CodigoP) > 10) {
                    $TA['CodigoC'] = G_NINGUNO;
                }

                if ($TA['CodigoC'] != G_NINGUNO) {

                    $TotalIngreso += $TA['Abono'];
                    $AdoAbono = $this->modelo->sqlIngresarAbonos($TA);
                    $AbonosPar = $NombreCliente . " (" . $TA['CodigoC'] . "): Valor Abono: " . number_format($TA['Abono'], 2, '.', ',');
                    if (count($AdoAbono) > 0) {
                        foreach ($AdoAbono as $valor) {
                            $FA['Fecha'] = $valor['Fecha'];
                            $TA['Cta_CxP'] = $valor['Cta_CxP'];
                            $TA['Autorizacion'] = $valor['Autorizacion'];

                            SetAdoAddNew("Trans_Abonos");
                            SetAdoFields("T", G_CANCELADO);
                            SetAdoFields("TP", "FA");
                            SetAdoFields("CodigoC", $TA['CodigoC']);
                            SetAdoFields("Fecha", $TA['Fecha']);
                            SetAdoFields("Comprobante", "Orden No. " . $Orden_Pago);
                            SetAdoFields("Serie", $TA['Serie']);
                            SetAdoFields("Factura", $TA['Factura']);
                            SetAdoFields("Abono", $TA['Abono']);
                            SetAdoFields("Banco", $TA['Banco']);
                            SetAdoFields("Cheque", $TA['Cheque']);
                            SetAdoFields("Cta", $Cta_Del_Banco);
                            SetAdoFields("Cta_CxP", $TA['Cta_CxP']);
                            SetAdoFields("Autorizacion", $TA['Autorizacion']);
                            SetAdoFields("Recibo_No", $TA['Recibo_No']);
                            SetAdoUpdate();

                            // Enviar por correo electrónico el Abono receptado
                            $FA['TC'] = $TA['TP'];
                            $FA['Serie'] = $TA['Serie'];
                            $FA['Factura'] = $TA['Factura'];
                            $FA['ClaveAcceso'] = $FA['Autorizacion'];
                            $FA['Autorizacion'] = $TA['Autorizacion'];
                            $FA['Fecha_C'] = $TA['Fecha'];
                            $FA['Fecha_V'] = $TA['Fecha'];
                            $FA['Hora_FA'] = $TA['Cheque'];
                            $FA['Cliente'] = $NombreCliente;
                            $FA['Fecha_Aut'] = FechaSistema();
                            $SRI_Autorizacion['Autorizacion'] = $TA['Autorizacion'];

                            $FA['Nota'] = "Tipo de Abono" . "\t" . ": " . $TA['Banco'] . "\n" .
                                "Hora" . "\t" . "\t" . ": " . $TA['Cheque'] . "\n" .
                                "Documento" . "\t" . ": " . $TA['Recibo_No'] . "\n" .
                                "Valor Recibdo USD " . number_format($TA['Abono'], 2, '.', ',') . "\n";
                            //SRI_Enviar_Mails($FA, $SRI_Autorizacion, "AB");
                        }
                    }
                }
            }
            fclose($NumFile);
            $FA['Serie'] = G_NINGUNO;
            $FA['TC'] = G_NINGUNO;
            $FA['Factura'] = 0;
            Actualizar_Abonos_Facturas_SP($FA);

            $mensaje = "ARCHIVO DE ABONO DEL DIA: " . $FechaTexto . "\n" .
                "SE ACTUALIZARON: " . $Total_Alumnos . " ESTUDIANTES." . "\n" .
                "EL CIERRE DIARIO DE CAJA ES POR " . $_SESSION['INGRESO']['Moneda'] . " " . number_format($TotalIngreso, 2, '.', ',') . "\n" .
                "EL COSTO BANCARIO ES POR " . $_SESSION['INGRESO']['Moneda'] . " " . number_format($Total_Costo_Banco, 2, '.', ',') . "\n" .
                "OBTENIDO DEL ARCHIVO: " . "\n" . $RutaGeneraFile . "\n";
            return array('res' => 'Ok', 'mensaje' => $mensaje);
        }
        $mensaje = 'No hay archivo seleccionado';
        return array('res' => 'Error', 'mensaje' => $mensaje);
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

        $directorioBase = dirname(__DIR__, 3) . "/TEMP/FACTURAS/";
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

        return array(
            'res' => 'Ok',
            'mensaje' => $mensaje,
            'textoBanco' => 'PICHINCHA',
            'Nombre1' => 'SCREC' . $mes . ".TXT",
            'Nombre2' => 'SCCOB' . $mes . ".TXT"
        );
    }

    function Generar_Internacional($parametros)
    {

    }

    function Generar_Pacifico($parametros)
    {

    }
}
?>