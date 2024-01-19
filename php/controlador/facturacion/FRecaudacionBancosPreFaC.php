<?php
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 03/01/2024
    FECHA MODIFICACION: 17/01/2024
    DESCIPCION : Clase que se encarga de manejar la lógica de la pantalla de recaudacion de bancos
*/
require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FRecaudacionBancosPreFaM.php");

$controlador = new FRecaudacionBancosPreFaC();

if (isset($_GET['DCLinea'])) {

    echo json_encode($controlador->DCLinea());
}

if (isset($_GET['DCBanco'])) {

    echo json_encode($controlador->DCBanco());
}

if (isset($_GET['DCGrupos'])) {

    echo json_encode($controlador->DCGrupos());
}

if (isset($_GET['DCEntidadBancaria'])) {

    echo json_encode($controlador->DCEntidadBancaria());
}

if (isset($_GET['MBFechaI_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->MBFechaI_LostFocus($parametros));
}

if (isset($_GET['Existe_Factura'])) {
    $parametros = $_POST['parametros'];
    echo json_encode(Existe_Factura($parametros));
}

if (isset($_GET['ReadSetDataNum'])) {
    $parametros = $_POST['parametros'];
    echo json_encode(ReadSetDataNum($parametros['SQLs'], $parametros['ParaEmpresa'], $parametros['Incrementar'], $parametros['Fecha']));

}

if (isset($_GET['TextFacturaNo_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->TextFacturaNo_LostFocus($parametros));

}

if (isset($_GET['DCLinea_LostFocus'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->DCLinea_LostFocus($parametros));

}

if (isset($_GET['Command1_Click'])) {
    $FA = json_decode($_POST['FA'], true);
    $Factura_No = $_POST['Factura_No'];
    $MBFechaI = $_POST['MBFechaI'];
    $TextoBanco = $_POST['TextoBanco'];
    $parametros = array('FA' => $FA, 'Factura_No' => $Factura_No, 'MBFechaI' => $MBFechaI, 'TextoBanco' => $TextoBanco);

    if (isset($_FILES['archivoBanco']) && $_FILES['archivoBanco']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivoBanco'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/FRecaudacionBancosPreFa/";
        $nombreArchivoDestino = $carpetaDestino . basename($archivo['name']);
        if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
            $parametros['NombreArchivo'] = $nombreArchivoDestino;
            echo json_encode($controlador->Command1_Click($parametros));
        } else {
            echo json_encode(array("response" => 0, "message" => "Error al subir el archivo"));
        }
    }
}

if (isset($_GET['DatosBanco'])) {
    echo json_encode($controlador->DatosBanco());
}

if(isset($_GET['Command4_Click'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Command4_Click($parametros));
}



class FRecaudacionBancosPreFaC
{

    private $modelo;

    public function __construct()
    {
        $this->modelo = new FRecaudacionBancosPreFaM();
    }

    public function DCLinea()
    {
        return $this->modelo->DCLinea();
    }

    public function DCBanco()
    {
        return $this->modelo->DCBanco();
    }

    public function DCGrupos()
    {

        return $this->modelo->DCGrupos();
    }

    public function DCEntidadBancaria()
    {
        return $this->modelo->DCEntidadBancaria();
    }

    public function MBFechaI_LostFocus($parametros)
    {
        return $this->modelo->MBFechaI_LostFocus($parametros);
    }

    public function TextFacturaNo_LostFocus($parametros)
    {
        if (Existe_Factura($parametros['FA'])) {
            return array('response' => 1, 'Factura' => '');
        } else {
            return array(
                'response' => 0,
                'Factura' => ReadSetDataNum(
                    $parametros['FA']['TC'] . "_SERIE_" . $parametros['FA']['Serie'],
                    True,
                    False
                )
            );
        }
    }

    public function DCLinea_LostFocus($parametros)
    {
        $FA = variables_tipo_factura();
        if (isset($parametros['FA']) && is_array($parametros['FA'])) {
            // Fusionar los valores de $FA con los valores de $parametros['FA']
            $FA = array_merge($FA, $parametros['FA']);
        }
        $tmp = Lineas_De_CxC($FA);
        $tmp['TFA']['Factura'] = ReadSetDataNum($tmp['TFA']['TC'] . "_SERIE_" . $tmp['TFA']['Serie'], True, False);
        return $tmp;
    }

    public function Command1_Click($parametros)
    {
        $FA = $parametros['FA'];
        $FA = Lineas_De_CxC($FA)['TFA'];
        $FA['Nuevo_Doc'] = True;
        $TextoImprimio = "";
        $NumeroTarjeta = "";
        $CodigoEncontrado = "'.'";
        $CostoTarjeta = 0;
        if ($FA['Nuevo_Doc']) {
            $FA['Factura'] = ReadSetDataNum($FA['TC'] . "_SERIE_" . $FA['Serie'], True, False);
        }
        $parametros['Factura_No'] = $FA['Factura'];
        $this->modelo->Command1_Click_Delete_AsientoF();
        $this->modelo->Command1_Click_Delete_TablaTemporal();
        $this->modelo->Command1_Click_Update_ClientesFacturacion();
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        $Separador = ",";
        $Mifecha = BuscarFecha($parametros['MBFechaI']);
        $FechaTexto = $parametros['MBFechaI'];
        $DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
        $NombreArchivo = $parametros['NombreArchivo'];
        if ($NombreArchivo <> "") {
            Actualizar_Datos_Representantes_SP();
        }
        $RutaGeneraFile = strtoupper($NombreArchivo);
        $TotalIngreso = 0;
        $Contador = 0;
        $FileResp = 0;
        $Total_Alumnos = 0;
        $FechaTexto = FechaSistema();
        $TxtFile = "";

        /*
        Determinamos cuantos registro vamos a actualizar y
        cuantos campos tiene el archivo del Banco
        */
        $ContTAB = 0;

        $file = fopen($RutaGeneraFile, "r"); //Abrimos el archivo
        while (!feof($file)) { // Loop a travez de cada linea del archivo
            $Cod_Field = fgets($file); //Lee la linea
            $Cod_Field = str_replace('"', '', $Cod_Field); //Elimina las comillas dobles
            if ($Contador === 0) {
                $length = strlen($Cod_Field); //Determina la longitud de la linea
                for ($i = 0; $i < $length; $i++) {
                    if ($Cod_Field[$i] === "\t") {
                        $ContTAB++;
                        $Separador = "\t";
                    }
                }
            }
            $Contador++;
        }
        fclose($file); //Cierra el archivo

        $CamposFile = [];
        $FechaTexto = FechaSistema();
        for ($i = 0; $i <= $ContTAB; $i++) {
            $CamposFile[$i] = [];
            $CamposFile[$i]['Campo'] = "C" . str_pad($i, 2, "0", STR_PAD_LEFT);
        }

        //Empieza la subida
        $Contador = 0;

        $file = fopen($RutaGeneraFile, "r"); //Abrimos el archivo
        while (!feof($file)) {
            //Leemos la trama donde esta toda la informacion del archivo de recaudaciones
            $Cod_Field = fgets($file);
            $TxtFile .= $Cod_Field . "\n";
            //Comenzamos la subida de los Abonos
            for ($i = 0; $i <= $ContTAB; $i++) {
                $CamposFile[$i]['Valor'] = "";
            }
            $Cadena = $Cod_Field;
            $i = 0;
            $No_Desde = 1;
            $No_Hasta = 0;
            $Total_Reg = strlen($Cadena);
            while (strlen($Cadena) > 0) {
                $No_Hasta++;
                if (substr($Cadena, $No_Hasta - 1, 1) === $Separador) {
                    $CamposFile[$i]['Valor'] = substr($Cadena, $No_Desde - 1, $No_Hasta - 1);
                    $Cadena = substr($Cadena, $No_Hasta, strlen($Cadena));
                    $No_Desde = 1;
                    $No_Hasta = 0;
                    $i++;
                }
                if ($No_Hasta >= $Total_Reg) {
                    $CamposFile[$i]['Valor'] = $Cadena;
                    $Cadena = "";
                }
            }
            //Actualizamos de que alumnos vamos a ingresar el abono
            $NoMeses = 0;
            $NoAnio = 0;
            $Mes = G_NINGUNO;
            $CodigoInv = G_NINGUNO;
            $CodigoCli = G_NINGUNO;
            $Codigo1 = G_NINGUNO;
            $Codigo3 = G_NINGUNO;
            $Codigo4 = G_NINGUNO;
            $CodigoP = "0";

            switch ($parametros['TextoBanco']) {
                case "PICHINCHA":
                    if ($Tipo_Carga >= 1) {
                        //Los substr de php y MidStrg de VB tienen los mismos parametros pero el de VB comienza la indexacion en 1 y el de php en 0.
                        $CodigoCli = trim(strval(floatval(substr($Cod_Field, 24, 19))));
                        $FechaTexto = substr($Cod_Field, 204, 2) . "/" . substr($Cod_Field, 206, 2) . "/" . substr($Cod_Field, 208, 4);
                        $Total = floatval(substr($Cod_Field, 47, 13)) / 100;
                        $NoMeses = intval(substr($Cod_Field, 197, 2));
                        $NoAnio = intval(substr($Cod_Field, 200, 4));
                        if (intval($NoMeses) <= 0) {
                            $NoMeses = intval(substr($Cod_Field, 206, 2));
                            $NoAnio = intval(substr($Cod_Field, 208, 4));
                        }
                        $Mes = MesesLetras($NoMeses);
                        $NombreCliente = trim(substr($Cod_Field, 124, 40));
                        $CodigoInv = SinEspaciosIzq(trim(substr($Cod_Field, 164, 33)));
                        $Producto = trim(substr($Cod_Field, 164, 33));
                        $Producto = trim(substr($Producto, strlen($CodigoInv), strlen($Producto)));
                        $Cantidad = 1;
                        $Codigo3 = substr($Cod_Field, 73, 3);
                        if ($Codigo3 === "EFE") {
                            $NombreBanco = "EFECTIVO POR VENTANILLA";
                        } else {
                            $Codigo3 = substr($Cod_Field, 73, 3) . ". " . substr($Cod_Field, 80, 3) . ". ";
                            $Codigo4 = substr($Cod_Field, 91, 12);
                            $NombreBanco = $Codigo3 . "No. " . substr($Cod_Field, 91, 12);
                        }
                    } else {
                        if ($ContTAB > 40) {
                            $FechaTexto = str_replace(" ", "/", $CamposFile[25]['Valor']);
                            if (IsDate($FechaTexto)) {
                                $CodigoCli = trim($CamposFile[4]['Valor']);
                                $NombreCliente = $CamposFile[5]['Valor'];
                                $Codigo3 = $CamposFile[16]['Valor'];
                                $Codigo4 = $CamposFile[26]['Valor'];
                                $SubTotal = $CamposFile[8]['Valor'];
                                $Total = $CamposFile[8]['Valor'];
                                $Total_Letras = $CamposFile[7]['Valor'];
                                $CodigoInv = SinEspaciosIzq($Total_Letras);
                                if (is_numeric($CodigoInv)) {
                                    $NoMeses = intval(substr($Total_Letras, strlen($Total_Letras) - 7, 2));
                                    $NoAnio = SinEspaciosIzq($Total_Letras);
                                    $Mes = MesesLetras($NoMeses);
                                    $Producto = trim(substr($Total_Letras, strlen($Total_Letras), 28));
                                } else {
                                    $CodigoInv = G_NINGUNO;
                                    $NoAnio = date("Y", strtotime($FechaTexto));
                                    if (is_numeric(substr($NombreCliente, 0, 2))) {
                                        $NoAnio = 0;
                                        $NoMeses = intval(substr($NombreCliente, 0, 2));
                                        $Mes = MesesLetras($NoMeses);
                                        $NombreCliente = substr($NombreCliente, 2, strlen($NombreCliente));
                                    } else {
                                        $NoMeses = date("m", strtotime($CamposFile[25]['Valor']));
                                        $Mes = MesesLetras($NoMeses);
                                        $NombreCliente = substr($CamposFile[5]['Valor'], strlen($Mes) - 1, strlen($CamposFile[5]['Valor']) - 1);
                                    }
                                }
                                $Cantidad = 1;
                            }
                        } else {
                            $FechaTexto = $CamposFile[12]['Valor'];
                            $NoAnio = date("Y", strtotime($FechaTexto));
                            $Codigo3 = $CamposFile[16]['Valor'];
                            $Codigo4 = $CamposFile[17]['Valor'];
                            $CodigoCli = trim($CamposFile[7]['Valor']);
                            $CodigoB = $CamposFile[7]['Valor'];
                            if (is_numeric(substr($CamposFile[8]['Valor'], 0, 2))) {
                                $NoAnio = 0;
                                $NoMeses = intval(substr($CamposFile[8]['Valor'], 0, 2));
                                $Mes = MesesLetras($NoMeses);
                                $NombreCliente = substr($CamposFile[8]['Valor'], 2, strlen($CamposFile[8]['Valor']));
                            } else {
                                $NoMeses = intval($CamposFile[14]['Valor']);
                                $Mes = MesesLetras($NoMeses);
                                $NombreCliente = substr($CamposFile[8]['Valor'], strlen($Mes) - 1, strlen($CamposFile[8]['Valor']) - 1);
                            }

                            $Producto = "PENSION DE: " . $Mes;
                            $FechaTexto = $CamposFile[12]['Valor'];
                            $SubTotal = $CamposFile[9]['Valor'] / 100;
                            $Total = $CamposFile[9]['Valor'] / 100;
                            if (strlen($Codigo4) <= 1) {
                                $Codigo4 = G_NINGUNO;
                            }
                            $Cantidad = 1;
                        }
                    }
                    break;
                case "INTERNACIONAL":
                    //TODO: 
                    break;
            }
            $Si_No = True;
            if (strlen($CodigoCli) > 5) {
                while (strlen($CodigoCli) <= 10) {
                    $AdoCliDB = $this->modelo->AdoCliDB($CodigoCli);
                    if (count($AdoCliDB) > 0) {
                        $CodigoCli = $AdoCliDB[0]['Codigo'];
                        $NombreCliente = $AdoCliDB[0]['Cliente'];
                        $Grupo_No = $AdoCliDB[0]['Grupo'];
                        $Si_No = False;
                    } else {
                        $CodigoCli = "0" . $CodigoCli;
                    }
                }
                //Progreso_Esperar
            }

            if (strlen($CodigoCli) > 10) {
                $CodigoCli = G_NINGUNO;
            }

            $Producto = "PENSION DE: ";
            if ($CodigoInv <> G_NINGUNO) {
                $AdoAux = $this->modelo->AdoAuxProducto($CodigoInv);
                if (count($AdoAux) > 0) {
                    $Producto = $AdoAux[0]['Producto'];
                }
            }

            $FechaTope = date("Y-m-t", strtotime(FechaSistema()));

            //Verificamos la primera deuda antgua que tenga el Cliente de ese mes
            if (IsDate($FechaTexto)) {
                $AdoAux = $this->modelo->AdoAuxClientes_Facturacion($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope);
                if (count($AdoAux) > 0 && ($CodigoCli <> G_NINGUNO)) {
                    $Sumatoria = $Total;
                    foreach ($AdoAux as $key => $value) {
                        $NoAnio = $value['Periodo'];
                        $NoMeses = $value['Num_Mes'];
                        $Mes = MesesLetras($NoMeses);
                        $CodigoInv = $value['Codigo_Inv'];
                        $Total = $value['Valor'] - ($value['Descuento'] + $value['Descuento2']);
                        //Progreso_Esperar True
                        if ($Total <= $Sumatoria) {
                            SetAdoAddNew("Asiento_F");
                            SetAdoFields("CODIGO", $CodigoInv);
                            SetAdoFields("CANT", $Cantidad);
                            SetAdoFields("PRODUCTO", $Producto);
                            SetAdoFields("PRECIO", $Total);
                            SetAdoFields("TOTAL", $Total);
                            SetAdoFields("Mes", $Mes);
                            SetAdoFields("A_No", $NoMeses);
                            SetAdoFields("HABIT", $NoAnio);
                            SetAdoFields("FECHA", $FechaTexto);
                            SetAdoFields("RUTA", $NombreCliente);
                            SetAdoFields("Codigo_Cliente", $CodigoCli);
                            SetAdoFields("Cod_Ejec", $CodigoP);
                            SetAdoFields("Cta", $Grupo_No);
                            SetAdoFields("Numero", $parametros['Factura_No']);
                            SetAdoFields("Serie", $parametros['FA']['Serie']);
                            SetAdoFields("Autorizacion", $parametros['FA']['Autorizacion']);
                            SetAdoFields("CODIGO_L", $Codigo3);
                            SetAdoFields("TICKET", $Codigo4);
                            if ($CostoTarjeta > 0) {
                                SetAdoFields("COSTO", $CostoTarjeta);
                            }
                            SetAdoUpdate();
                            $parametros['Factura_No'] = $parametros['Factura_No'] + 1;
                            $Sumatoria = $Sumatoria - $Total;
                            $this->modelo->AdoAuxClientes_FacturacionUpdate($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope);
                        }
                    }
                } else {
                    if ($Total_Alumnos <> 0) {
                        $Total_Letras = number_format($Total, 2, '.', ',');
                        $Total_Letras = str_pad($Total_Letras, 12, " ", STR_PAD_LEFT);
                        $Cadena = "Codigo: " . $CodigoCli . " \t" . "Cedula: " . $CodigoP . " \t" . $FechaTexto .
                            " \t" . $Mes . "/" . $NoAnio . ", USD " . $Total_Letras . " \t" . $NombreCliente;
                        if (strlen($NumeroTarjeta) > 1) {
                            $Cadena .= " \t" . $NumeroTarjeta;
                        }
                        //TODO: Insertar_Texto_Temporal_SP Cadena
                        $TextoImprimio .= $Cadena . "\n";
                    }
                }
                $Total_Alumnos++;
            }
        }
        fclose($file);

        $Numero = 0;
        $AdoFactura = $this->modelo->AdoFactura();
        if (count($AdoFactura) > 0) {
            foreach ($AdoFactura as $key => $value) {
                //Progreso ESPERAR
                $AdoAux = $this->modelo->AdoAuxClientesFacturacion2(
                    $value['Codigo_Cliente'],
                    $value['CODIGO'],
                    $value['A_No'],
                    $value['HABIT']
                );
                if (count($AdoAux) <= 0) {
                    $this->modelo->AdoFacturaUpdate();
                }
            }
        }

        $this->modelo->DeleteAsientoF();

        $AdoFactura = $this->modelo->AdoFactura2();
        if (count($AdoFactura) > 0) {
            $Mifecha = $AdoFactura[0]['FECHA'];
            $Numero = $parametros['Factura_No'];
            $CodigoC = $AdoFactura[0]['Codigo_Cliente'];
            foreach ($AdoFactura as $key => $value) {
                if ($CodigoC <> $value['Codigo_Cliente'] || strtotime($Mifecha) <> strtotime($value['FECHA'])) {
                    $CodigoC = $value['Codigo_Cliente'];
                    $Mifecha = $value['FECHA'];
                    $parametros['Factura_No'] = $parametros['Factura_No'] + 1;
                }
                $this->modelo->AdoFacturaUpdate2($parametros['Factura_No']);
            }
        }

        return $this->PreFacturacionDelDia($TxtFile);

    }

    public function PreFacturacionDelDia($TxtFile)
    {
        $DGFactura = $this->modelo->DGFactura();
        $TotalIngreso = 0;
        if (count($DGFactura['AdoAsientoF']) > 0) {
            foreach ($DGFactura['AdoAsientoF'] as $key => $value) {
                $TotalIngreso += $value['TOTAL'];
            }
        }
        return array('tbl' => $DGFactura['datos'], 'TotalIngreso' => $TotalIngreso, 'TxtFile' => $TxtFile);
    }

    public function DatosBanco()
    {
        $Tipo_Carga = Leer_Campo_Empresa("Tipo_Carga_Banco");
        $Costo_Banco = Leer_Campo_Empresa("Costo_Bancario");
        $Cta_Bancaria = Leer_Campo_Empresa("Cta_Banco");
        $Cta_Gasto_Banco = Leer_Seteos_Ctas("Cta_Gasto_Bancario");
        return array(
            'Tipo_Carga' => $Tipo_Carga,
            'Costo_Banco' => $Costo_Banco,
            'Cta_Bancaria' => $Cta_Bancaria,
            'Cta_Gasto_Banco' => $Cta_Gasto_Banco
        );
    }

    public function Command4_Click($parametros)
    {   
        Actualizar_Datos_Representantes_SP();
        $Cta_Banco = trim(strtoupper(SinEspaciosDer($parametros['DCBanco'])));
        if (strlen($Cta_Banco) <= 1) {
            $Cta_Banco = "0000000000";
        }
        $TipoDoc = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $FechaFinal = $parametros['MBFechaF'];
        $FechaTexto = FechaSistema();
        $FechaTexto1 = date("m/d/y", strtotime($parametros['MBFechaI']));
        $MiFecha = BuscarFecha($parametros['MBFechaI']);
        $MiMes = date("m", strtotime($parametros['MBFechaI']));
        $Contador = 0;
        //Consultamos la deuda Pendiente
        $this->modelo->UpdateClientesFacturacion();
        $this->modelo->UpdateClientesFacturacion2($parametros['MBFechaI']);
        $this->modelo->UpdateClientesFacturacion3();

        $AdoFactura = $this->modelo->Tipo_Carga($parametros);

        $RutaDestino = "../../../TEMP/FRecaudacionBancosPreFa/DEUDAESTUDIANTE.csv";
        $Codigo = strtoupper(MesesLetras(date("m", strtotime($parametros['MBFechaI']))) . "-" . date("d", strtotime($parametros['MBFechaI'])) . "-" . date("Y", strtotime($parametros['MBFechaI'])));
        $RutaDestino = "../../../TEMP/FRecaudacionBancosPreFa/" . $Codigo . ".txt";
        $NombreArchivoZip = $RutaDestino;
        $FechaFin = BuscarFecha($parametros['MBFechaF']);
        $TextoImprimio = "";
        $FechaFin = BuscarFecha(date("y-m-t", strtotime($parametros['MBFechaF'])));
        switch ($parametros['TextoBanco']) {
            case "PACIFICO":
                //TODO: Generar Pacifico
                break;
            case "PICHINCHA":
                //TODO: Genera Pichincha
                return $this->Generar_Pichincha($parametros, $AdoFactura);
            case "BOLIVARIANO":
                //TODO: Genera Bolivariano
                break;
            case "GUAYAQUIL":
                //TODO: Genera Guayaquil
                break;
            case "PRODUBANCO":
                //TODO: Genera Produbanco
                break;
            case "TARJETAS":
                //TODO: Genera Tarjetas
                break;
            case "INTERNACIONAL":
                //TODO Genera Internacional
                break;
            default:
                break;
        }
    }

    public function Generar_Pichincha($parametros, $AdoFactura)
    {
        $Total_Banco = 0;
        $Mifecha = BuscarFecha($parametros['MBFechaI']);
        $MiMes = date("m", strtotime($parametros['MBFechaI']));
        $FechaFin = BuscarFecha(date('Y-m-t', strtotime($parametros['MBFechaI'])));
        $TextoImprimio = "";
        $AuxNumEmp = $_SESSION['INGRESO']['item'];
        $Cta_Cobrar = G_NINGUNO;
        $FechaTexto = FechaSistema();
        //Comenzamos a generar el archivo: SCRECXX.TXT
        $EsComa = True;
        $Estab = False;
        $Contador = 0;
        $Factura_No = 0;
        $Fecha_Meses = $parametros['MBFechaI'] . " al " . $parametros['MBFechaF'];
        $Fecha_Meses = str_replace("/", "-", $Fecha_Meses);

        $Mes = date("m", strtotime($parametros['MBFechaI']));
        $Anio = intval(substr(date("Y", strtotime($parametros['MBFechaI'])), 1, 3));
        $Dia = "15";

        $RutaGeneraFile = strtoupper(dirname(__DIR__, 3) . "/TEMP/FRecaudacionBancosPreFa/SCREC " . $Fecha_Meses . ".TXT");
        $NombreFile = "SCREC " . $Fecha_Meses . ".TXT";
        $NumFileFacturas1 = fopen($RutaGeneraFile, "w"); //Abre el archivo

        $RutaGeneraFile2 = strtoupper(dirname(__DIR__, 3) . "/TEMP/FRecaudacionBancosPreFa/SCCOB " . $Fecha_Meses . ".TXT");
        $NombreFile2 = "SCCOB " . $Fecha_Meses . ".TXT";
        $NumFileFacturas2 = fopen($RutaGeneraFile2, "w"); //Abre el archivo

        if (count($AdoFactura)) {
            foreach ($AdoFactura as $key => $value) {
                $Contador++;
                $CodigoCli = $value['Codigo'];
                $NombreCliente = $this->Sin_Signos_Especiales($value['Cliente']);
                $Factura_No = $Factura_No + 1;
                $Total = $value['Valor_Cobro'];
                $Saldo = $value['Valor_Cobro'] * 100;
                $CodigoP = $value['CI_RUC'];
                $CodigoC = intval($value['CI_RUC']);
                $CodigoC = $CodigoC . str_repeat(" ", max(0, 4 - strlen($CodigoC)));
                $DireccionCli = $this->Sin_Signos_Especiales($value['Direccion']);
                $Codigo3 = trim($this->SinEspaciosDer2($DireccionCli));
                $DireccionCli = trim(substr($DireccionCli, 0, strlen($DireccionCli) - strlen($Codigo3)));
                $Codigo3 = trim($this->SinEspaciosDer2($DireccionCli));
                //$Codigo1 = 
                $Codigo4 = substr($value['Codigo_Inv'] . " " . $value['Producto'], 0, 33);
                $Codigo4 = $Codigo4 . str_repeat(" ", max(0, 33 - strlen($Codigo4)))
                    . sprintf("%02d", $value["Num_Mes"]) . " "
                    . $value["Periodo"];

                $Tabulador = "\t";

                if (strlen($value['Actividad']) >= 3) {
                    fwrite($NumFileFacturas2, "CO" . $Tabulador);
                    fwrite($NumFileFacturas2, $parametros['Cta_Bancaria'] . $Tabulador);
                    fwrite($NumFileFacturas2, $Contador . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%013d", $Factura_No) . $Tabulador);
                    fwrite($NumFileFacturas2, $CodigoP . $Tabulador);
                    fwrite($NumFileFacturas2, "USD" . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%013d", $Saldo) . $Tabulador);
                    fwrite($NumFileFacturas2, "CTA" . $Tabulador);
                    fwrite($NumFileFacturas2, "10" . $Tabulador);
                    $NumStrg = SinEspaciosIzq($value['Actividad']);
                    if (strlen($NumStrg) === 3) {
                        fwrite($NumFileFacturas2, SinEspaciosIzq($value['Actividad']) . $Tabulador);
                        fwrite($NumFileFacturas2, $this->SinEspaciosDer2($value['Actividad']) . $Tabulador);
                    }else{
                        fwrite($NumFileFacturas2, $Tabulador);
                        fwrite($NumFileFacturas2, $Tabulador);
                    }
                    fwrite($NumFileFacturas2, "R" . $Tabulador);
                    fwrite($NumFileFacturas2, $_SESSION['INGRESO']['RUC'] . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%02d", $value['Num_Mes'] . " " . substr($NombreCliente, 0, 37)) . $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, $Tabulador);
                    fwrite($NumFileFacturas2, date("m", strtotime($value['Fecha'])) . $Tabulador);
                    fwrite($NumFileFacturas2, strtoupper($Codigo4) . $Tabulador);
                    fwrite($NumFileFacturas2, sprintf("%013d", $Saldo) . $Tabulador);
                    fwrite($NumFileFacturas2, "\n");
                }else{
                    if($parametros['TipoCarga'] >= 1){
                        //Tipo Gualaceo
                        fwrite($NumFileFacturas1, "CO" . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%013d", $CodigoC) . $Tabulador);
                        fwrite($NumFileFacturas1, "USD". $Tabulador);
                        fwrite($NumFileFacturas1, $Saldo . $Tabulador);
                        fwrite($NumFileFacturas1, "REC" . $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, strtoupper($Codigo4) . $Tabulador);
                        fwrite($NumFileFacturas1, "N" . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%013d", intval($CodigoC)) . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%02d", $value['Num_Mes']) . " " . substr($NombreCliente, 0, 37) . $Tabulador);
                        fwrite($NumFileFacturas1, "\n");
                    }else{
                        //Tipo General
                        fwrite($NumFileFacturas1, "CO" . $Tabulador);
                        fwrite($NumFileFacturas1, $parametros['Cta_Bancaria'] . $Tabulador);
                        fwrite($NumFileFacturas1, $Contador . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%013d", $Factura_No) . $Tabulador);
                        fwrite($NumFileFacturas1, $CodigoP . $Tabulador);
                        fwrite($NumFileFacturas1, "USD" . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%013d", $Saldo) . $Tabulador);
                        fwrite($NumFileFacturas1, "REC" . $Tabulador);
                        fwrite($NumFileFacturas1, "10" . $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, "0" . $Tabulador);
                        fwrite($NumFileFacturas1, "R" . $Tabulador);
                        fwrite($NumFileFacturas1, $_SESSION['INGRESO']['RUC'] . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%02d", $value['Num_Mes']) . " " . substr($NombreCliente, 0, 37) . $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, $Tabulador);
                        fwrite($NumFileFacturas1, date("m", strtotime($value['Fecha'])) . $Tabulador);
                        fwrite($NumFileFacturas1, strtoupper($Codigo4) . $Tabulador);
                        fwrite($NumFileFacturas1, sprintf("%013d", $Saldo) . $Tabulador);
                        fwrite($NumFileFacturas1, "\n");
                    }
                }

            }
        }
        fclose($NumFileFacturas1);
        fclose($NumFileFacturas2);
        return array('LabelAbonos' => sprintf("%02d", $Total_Banco),
                     'Mensaje' => "SE GENERARON LOS SIGUIENTES ARCHIVOS: \n" . $NombreFile . "\n" . $NombreFile2,
                    'Archivo1' => $RutaGeneraFile,
                    'Archivo2' => $RutaGeneraFile2,
                    'Nombre1' => $NombreFile,
                    'Nombre2' => $NombreFile2);

    }

    //TODO: CAMBIAR ESTOS METODOS LUEGO POR LOS QUE ESTAN EN FUNCIONES
    function SinEspaciosDer2($texto = "")
    {
        $resultado = explode(" ", $texto, 2); // El tercer parámetro limita el número de elementos en el array
        return isset($resultado[1]) ? $resultado[1] : $resultado[0];
    }

    function Sin_Signos_Especiales($cad)
    {
        //$cad = trim($cadena);
        $cad = str_replace(array("á", "é", "í", "ó", "ú"), array("a", "e", "i", "o", "u"), $cad);
        // $cad = str_replace(array("Á", "É", "Í", "Ó", "Ú"), array("A", "E", "I", "O", "U"), $cad);
        $cad = str_replace(array("à", "è", "ì", "ò", "ù"), array("a", "e", "i", "o", "u"), $cad);
        $cad = str_replace(array("À", "È", "Ì", "Ò", "Ù"), array("A", "E", "I", "O", "U"), $cad);
        $cad = str_replace(array("ñ", "Ñ"), array("n", "N"), $cad);
        $cad = str_replace("ü", "u", $cad);
        $cad = str_replace("Ü", "U", $cad);
        $cad = str_replace("&", "Y", $cad);
        $cad = str_replace(array("\r", "\n"), "|", $cad);
        $cad = str_replace("Nº", "No.", $cad);
        // $cad = str_replace("#", "No.", $cad);
        $cad = str_replace("ª", "a. ", $cad);
        $cad = str_replace("°", "o. ", $cad);
        $cad = str_replace("½", "1/2", $cad);
        $cad = str_replace("¼", "1/4", $cad);
        $cad = str_replace(chr(255), " ", $cad);
        $cad = str_replace(chr(254), " ", $cad);
        $cad = str_replace("^", "", $cad);
        // $cad = str_replace(":", " ", $cad);
        // $cad = str_replace("\"", " ", $cad);
        $cad = str_replace("´", " ", $cad);

        return $cad;
    }
}
