<?php 
include(dirname(__DIR__,2).'/modelo/inventario/ResumenKM.php');
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
if(!class_exists('cabecera_pdf'))
{
    require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}


$_SESSION['ResumenKC']['QTipoInv'] = false;
$_SESSION['ResumenKC']['CNivel_1'] = false;
$_SESSION['ResumenKC']['GrupoInv'] = "";
$_SESSION['ResumenKC']['AdoDetKardex'] = "";
$_SESSION['ResumenKC']['Opcion'] = 2;

$controlador = new ResumenKC();
if(isset($_GET["generarExcelKardex"])){
    echo json_decode($controlador->generarExcelKardex($_GET));
    exit();
}else

if(isset($_GET['Imprimir_ResumenK']))
{
    $controlador->ImprimirPdf($_GET);
    exit();
}
elseif(isset($_GET['Form_Activate']))
{
    echo json_encode($controlador->Form_Activate($_POST));
}
elseif(isset($_GET['ConsultarStock']))
{
    echo json_encode($controlador->Stock( $_POST,$_GET['StockSuperior']));
}
elseif(isset($_GET['Listar_Por_Producto']))
{ 
    extract($_POST);
    $OpcMarca = (isset($OpcMarca) && $OpcMarca);
    $OpcBarra = (isset($OpcBarra) && $OpcBarra);
    $OpcLote = (isset($OpcLote) && $OpcLote);
    $DCTInv = (isset($DCTInv) && $DCTInv!="")?$DCTInv:G_NINGUNO;
    echo json_encode(array("DCTipoBusqueda"=>$controlador->Listar_Por_Producto($OpcMarca, $OpcBarra, $OpcLote, $DCTInv)));
}
elseif(isset($_GET['Listar_Por_Tipo_SubModulo']))
{ 
    extract($_POST);
    $OpcGasto = (isset($OpcGasto) && $OpcGasto);
    echo json_encode(array("DCSubModulo"=>$controlador->Listar_Por_Tipo_SubModulo($OpcGasto)));
}
elseif(isset($_GET['Listar_Por_Tipo_Cta']))
{ 
    extract($_POST);
    $OpcInv = (isset($OpcInv) && $OpcInv);
    echo json_encode(array("DCCtaInv"=>$controlador->Listar_Por_Tipo_Cta($OpcInv)));
}

class ResumenKC
{
    private $modelo;
    private $pdf;
    public $NumEmpresa;
    public $Periodo_Contable;
    public $CodigoUsuario;
    function __construct()
    {
        $this->modelo = new ResumenKM();
        $this->pdf = new cabecera_pdf();
        $this->NumEmpresa = $_SESSION['INGRESO']['item'];
        $this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];
        $this->CodigoUsuario = $_SESSION['INGRESO']['CodigoU'];
    }

    public function generarExcelKardex($parametros){//pendiente
        if(isset($_SESSION['DGKardex']['sSQL'])){
            extract($parametros);
            $titulo = 'Kardex del '.BuscarFecha($MBoxFechaI).' al '. BuscarFecha($MBoxFechaF);
            $sSQL   = $_SESSION['DGKardex']['sSQL'];
            $medidas = array(12,30,15,12,18,9,12,12,12,12,12,15,15,15,10,20,20,15,15,20,25,15,15,20,25,25,15,15,9);
            return exportar_excel_generico_SQl($titulo,$sSQL, $medidas, fecha_sin_hora:true);
        }else{
            die("Primero debe cargar la informacion");
        }
    }

    public function ImprimirPdf($parametros){//pédiente
        if(!isset($_SESSION['ResumenKC']['AdoDetKardex']) || $_SESSION['ResumenKC']['AdoDetKardex']==""){
            die("Primero debe consultar la informacion");
        }
        extract($parametros);

        $SQLMsg1 = " ";
        if (isset($CheqBod) && $CheqBod == 1) {
            $SQLMsg1 = "POR BODEGA " . strtoupper($DCBodega);
        }
        if (isset($CheqSubMod) && $CheqSubMod == 1) {
            if ($SQLMsg1 != "") {
                $SQLMsg1 = $SQLMsg1 . " Y DE " . strtoupper($DCSubModulo);
            } else {
                $SQLMsg1 = " DE " . strtoupper($DCSubModulo);
            }
        }

        $titulo = "R E S U M E N    D E    E X I S T E N C I A S ".$SQLMsg1 ;
        if($_SESSION['ResumenKC']['Opcion']==1){

        }

        $query = $_SESSION['DGKardex']['sSQL'];
        $pattern = "/SELECT(.*?)FROM/s";
        $matches = [];
        preg_match($pattern, $query, $matches);
        $sectionToReplace = $matches[1];

        $newSection = " TK.CodBodega AS Bod, TK.Fecha, TK.TP, TK.Numero, TK.Detalle, TK.Entrada, TK.Salida, TK.Valor_Unitario, TK.Valor_Total, TK.Existencia AS Cantidad, TK.Costo as Costo_Prom, TK.Total AS Saldo_Total ";
        $replacedQuery = str_replace($sectionToReplace, $newSection, $query);
        $result = $this->modelo->SelectDB($replacedQuery);
        $campos = array();
        foreach ($result[0] as $key => $value) {
            array_push($campos,$key);
        }
        $medi =array(8,17,9,28,90,13,12,23,18,15,19,18);
        $medip =array(8,17,9,28,90,13,12,23,18,15,19,18);

        $pdf = new cabecera_pdf();  
        $mostrar = true;
        $sizetable =8;
        $tablaHTML = array();

        $tablaHTML[0]['medidas']=array(30, 30);
        $tablaHTML[0]['alineado']=array('L', 'L');
        $tablaHTML[0]['datos']=array($LabelCodigo,$LabelUnidad);
        $tablaHTML[0]['estilo']='I';
        $tablaHTML[0]['borde'] = '0';

        $tablaHTML[1]['medidas']=array(150);
        $tablaHTML[1]['alineado']=array('L');
        $tablaHTML[1]['datos']=array($NombreProducto);
        $tablaHTML[1]['estilo']='I';
        $tablaHTML[1]['borde'] = '0';

        $tablaHTML[2]['medidas']=array(100);
        $tablaHTML[2]['alineado']=array('L');
        $tablaHTML[2]['datos']=array("");
        $tablaHTML[2]['estilo']='I';
        $tablaHTML[2]['borde'] = '0';

        $tablaHTML[3]['medidas']=$medi;
        $tablaHTML[3]['alineado']=array('L','C','C','R','L','R','R','R','R','R','R','R');
        $tablaHTML[3]['datos']=$campos;
        $tablaHTML[3]['estilo']='B';
        $tablaHTML[3]['borde'] = 'B';
        $pos = 4;

        $TipoDoc = "";
        $Numero = "";
        $Detalle = "";
        $MiFecha = "";

        foreach ($result as $key => $value) {

            $Entrada = ($value["Entrada"]!=0)? number_format($value["Entrada"], 2, '.', ''):"";
            $Salida = ($value["Salida"]!=0)? number_format($value["Salida"], 2, '.', ''):"";
            $Stock = ($value["Cantidad"]!=0)? number_format($value["Cantidad"], 2, '.', ''):"";
            $Valor_Unitario = ($value["Valor_Unitario"]!=0)? number_format($value["Valor_Unitario"], 2, '.', ''):"";
            $Valor_Total = ($value["Valor_Total"]!=0)? number_format($value["Valor_Total"], 2, '.', ''):"";
            $Costo = ($value["Costo_Prom"]!=0)? number_format($value["Costo_Prom"], 2, '.', ''):"";
            $Saldo = number_format($value["Saldo_Total"], 2, '.', '');

            $data = array($value["Bod"],$value["Fecha"]->format('Y-m-d'),"",$value['Numero'],"",$Entrada, $Salida, $Valor_Unitario, $Valor_Total, $Stock, $Costo, $Saldo);
            if($TipoDoc!=$value['TP'] || $Numero != $value['Numero']){
                $TipoDoc = $value['TP'];
                $Numero = $value['Numero'];
                $data[2] = $TipoDoc;
                $data[4] = $value['Detalle'];
            }

            $tablaHTML[$pos]['medidas']=$tablaHTML[3]['medidas'];
            $tablaHTML[$pos]['alineado']=$tablaHTML[3]['alineado'];
            $tablaHTML[$pos]['datos']=$data;
            $tablaHTML[$pos]['estilo']='I';
            $tablaHTML[$pos]['borde'] = 'LR';
            $pos = $pos+1;
            $Detalle = $value['Detalle'];
            $MiFecha = $value["Fecha"]->format('Y-m-d');
        }
        $tablaHTML[$pos-1]['borde'] = 'LRB';
        $pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$MBoxFechaI,$MBoxFechaF,$sizetable,$mostrar, orientacion: 'L', mostrar_cero:true);
    }

    public function Stock($parametros, $StockSuperior)
    {
        extract($parametros);
        $error = false;
        $OpcMarca = (isset($OpcMarca) && $OpcMarca);
        $OpcBarra = (isset($OpcBarra) && $OpcBarra);
        $OpcLote = (isset($OpcLote) && $OpcLote);
        $OpcProducto = (isset($OpcProducto) && $OpcProducto);
        $CheqProducto = (isset($CheqProducto) && $CheqProducto);
        $CheqBod = (isset($CheqBod) && $CheqBod);
        $CheqMonto = (isset($CheqMonto) && $CheqMonto);
        $CheqExist = (isset($CheqExist) && $CheqExist);
        $DCTInv = (isset($DCTInv) && $DCTInv!="")?$DCTInv:G_NINGUNO;
        $Cod_Bodega = (isset($Cod_Bodega) && $Cod_Bodega!="")?$Cod_Bodega:G_NINGUNO;

        $FechaValida = FechaValida($MBoxFechaI);
        if($FechaValida["ErrorFecha"]){
            return ['error' => true, "mensaje" =>$FechaValida["MsgBox"]];
        }
        $FechaValida = FechaValida($MBoxFechaF);
        if($FechaValida["ErrorFecha"]){
            return ['error' => true, "mensaje" =>$FechaValida["MsgBox"]];
        }
        $FechaIni = BuscarFecha($MBoxFechaI);
        $FechaFin = BuscarFecha($MBoxFechaF);

        $_SESSION['ResumenKC']['QTipoInv'] = false;
        control_procesos("I", "Proceso Stock de Inventario, del $MBoxFechaI al $MBoxFechaF");
        
        if (isset($CheqProducto) && $CheqProducto ==1) {
            $_SESSION['ResumenKC']['Opcion'] = 2;
            $tabla = "Saldo_Diarios";
            $sSQL = "SELECT Recibo As Serie_No, Comprobante As Detalle, " .
                    "Total As Promedio, Saldo_Anterior As Saldo_Ant, Ingresos As Entradas, " .
                    "Egresos As Salidas, Saldo_Actual As Stock_Act " .
                    "FROM Saldo_Diarios " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND CodigoU = '".$this->CodigoUsuario."' " .
                    "AND TP = 'INVE' ";
                    
            if (isset($CheqMonto) && $CheqMonto == 1) {
                $sSQL .= "AND Saldo_Actual = " . $TxtMonto . " ";
            } else {
                $sSQL .= "AND Saldo_Actual <> 0 ";
            }
            $Codigo3 = "Todos";////TODO LS de donde sale sta variale
            if (isset($OpcProducto) && $OpcProducto == 1 && $Codigo3 != "Todos") {
                $sSQL .= "AND Recibo = '$Codigo3' ";
            }
            
            if (isset($CheqExist) && $CheqExist == 1) {
                $sSQL .= "AND Saldo_Actual <> 0 ";
            }
            
            $sSQL .= "ORDER BY Numero ";
        } else {
            $_SESSION['ResumenKC']['Opcion'] = 1;
            Reporte_Resumen_Existencias_SP($MBoxFechaI, $MBoxFechaF, $Cod_Bodega);
            
            //INICIO SQL_Tipo_Busqueda
              $BSQL = " ";
              $CodigoInv = G_NINGUNO;
              if ($OpcProducto) {
                $CodigoInv = $DCTipoBusqueda;
              }
              
              if ($CheqBod) {
                  $BSQL .= "AND TK.CodBodega = '$Cod_Bodega' ";
              }
              
              if ($CheqProducto) {
                  if ($OpcBarra) {
                      $BSQL .= "AND TK.Codigo_Barra = '$CodigoInv' ";
                  } elseif ($OpcLote) {
                      $BSQL .= "AND TK.Lote_No = '$CodigoInv' ";
                  } else {
                      $BSQL .= "AND TK.Codigo_Inv = '$CodigoInv' ";
                  }
              }
              
              if ($CheqMonto) {
                $BSQL .= "AND CP.Stock_Actual = " . $TxtMonto;
              }
              
              if ($CheqExist == 0) {
                $BSQL .= "AND CP.Valor_Total <> 0 ";
              }
            //FIN SQL_Tipo_Busqueda

            $tabla = "Catalogo_Productos";
            $sSQL = "SELECT TC, Codigo_Inv, Producto, Unidad, Stock_Anterior, Entradas, Salidas, Stock_Actual, " .
                    "Promedio As Costo_Unit, Valor_Total As Total, 0 As Diferencias, '' As Bodega, Ubicacion " .
                    "FROM Catalogo_Productos As CP " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' "
                    . $BSQL;
            
            if (isset($CheqGrupo) && $CheqGrupo <> 0) {
                $sSQL .= "AND Codigo_Inv LIKE '".$DCTInv."%' ";
            }
            
            $sSQL .= "ORDER BY Codigo_Inv ";
        }
        
        $AdoDetKardex =  $this->modelo->SelectDB($sSQL);
        
        $Total = 0;
        $Debitos = 0;
        $Creditos = 0;
        
        if(count($AdoDetKardex)>0){
            foreach ($AdoDetKardex as $key => $Fields) {
                if (!isset($OpcProducto) || $OpcProducto != 1) {
                    if ($Fields["TC"] != "I") {
                        $Debitos += number_format($Fields["Entradas"] * $Fields["Costo_Unit"], 2,'.','');
                        $Creditos += number_format($Fields["Salidas"] * $Fields["Costo_Unit"], 2,'.','');
                        $Total += number_format($Fields["Total"], 2,'.','');
                    }
                }
            }
        }
        
        $LabelTot = number_format($Total, 2,'.','');
        $heightDispo = ($heightDisponible>150)?$heightDisponible-45:$heightDisponible;
        $DGQuery = grilla_generica_new($sSQL,$tabla,'myTableDGQuery','',false,false,false,1,1,1,$heightDispo);
        return compact('error','DGQuery','LabelTot');
    }

    public function Listar_Por_Producto($OpcMarca, $OpcBarra, $OpcLote, $DCTInv)
    {
        $sSQL = $this->Listar_Por_ProductoSQL($OpcMarca, $OpcBarra, $OpcLote, $DCTInv);
        return $this->modelo->SelectDB($sSQL);
    }

    function Listar_Por_ProductoSQL($OpcMarca, $OpcBarra, $OpcLote, $DCTInv) {
        if ($OpcMarca) {
            $sSQL = "SELECT CodMar As Codigo, Marca As Producto " .
                    "FROM Catalogo_Marcas " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "AND CodMar <> '.' " .
                    "ORDER BY Marca";
        } elseif ($OpcBarra) {
            $sSQL = "SELECT Codigo_Barra As Codigo, Codigo_Barra As Producto " .
                    "FROM Trans_Kardex " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "GROUP BY Codigo_Barra " .
                    "ORDER BY Codigo_Barra";
        } elseif ($OpcLote) {
            $sSQL = "SELECT Lote_No As Codigo, Lote_No As Producto " .
                    "FROM Trans_Kardex " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "GROUP BY Lote_No " .
                    "ORDER BY Lote_No";
        } else {
            $sSQL = "SELECT Codigo_Inv As Codigo, Producto " .
                    "FROM Catalogo_Productos " .
                    "WHERE Item = '".$this->NumEmpresa."' " .
                    "AND Periodo = '".$this->Periodo_Contable."' " .
                    "AND LEN(Cta_Inventario) > 2 " .
                    "AND Codigo_Inv LIKE '$DCTInv%' " .
                    "AND TC = 'P' " .
                    "ORDER BY Codigo_Inv";
        }
        
        return $sSQL;
    }

    function Listar_Por_Tipo_SubModulo($OpcGasto) {
        if ($OpcGasto) {
            $sSQL = "SELECT TC, Codigo, Detalle AS SubModulo " .
                    "FROM Catalogo_SubCtas " .
                    "WHERE Item = '" . $this->NumEmpresa . "' " .
                    "AND Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND Detalle <> '" . G_NINGUNO . "' " .
                    "ORDER BY TC, Detalle";
        } else {
            $sSQL = "SELECT CP.TC, CP.Codigo, CP.Cta, (C.Cliente + REPLICATE(' ', 60 - LEN(C.Cliente)) + CP.Cta) AS SubModulo " .
                    "FROM Catalogo_CxCxP AS CP, Clientes AS C " .
                    "WHERE CP.Item = '" . $this->NumEmpresa . "' " .
                    "AND CP.Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND C.Cliente <> '" . G_NINGUNO . "' " .
                    "AND CP.TC = 'P' " .
                    "AND CP.Codigo = C.Codigo " .
                    "ORDER BY C.Cliente, CP.Cta";
        }
        return $this->modelo->SelectDB($sSQL);
    }

    function Listar_Por_Tipo_Cta($OpcInv) {
        if ($OpcInv) {
            $sSQL = "SELECT CC.Cuenta, TK.Cta_Inv " .
                    "FROM Catalogo_Cuentas AS CC, Trans_Kardex AS TK " .
                    "WHERE CC.Item = '" . $this->NumEmpresa . "' " .
                    "AND CC.Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND LENGTH(TK.Cta_Inv) > 1 " .
                    "AND CC.Codigo = TK.Cta_Inv " .
                    "AND CC.Item = TK.Item " .
                    "AND CC.Periodo = TK.Periodo " .
                    "GROUP BY CC.Cuenta, TK.Cta_Inv " .
                    "ORDER BY CC.Cuenta, TK.Cta_Inv";
        } else {
            $sSQL = "SELECT CC.Cuenta, TK.Contra_Cta " .
                    "FROM Catalogo_Cuentas AS CC, Trans_Kardex AS TK " .
                    "WHERE CC.Item = '" . $this->NumEmpresa . "' " .
                    "AND CC.Periodo = '" . $this->Periodo_Contable . "' " .
                    "AND LENGTH(TK.Contra_Cta) > 1 " .
                    "AND CC.Codigo = TK.Contra_Cta " .
                    "AND CC.Item = TK.Item " .
                    "AND CC.Periodo = TK.Periodo " .
                    "GROUP BY CC.Cuenta, TK.Contra_Cta " .
                    "ORDER BY CC.Cuenta, TK.Contra_Cta";
        }
        return $this->modelo->SelectDB($sSQL);
    }


    public function ListarProductosResumenK(){
        $sSQL = "SELECT Codigo_Inv, Producto " .
            "FROM Catalogo_Productos " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TC = 'I' " .
            "AND INV <> 0 " .
            "ORDER BY Codigo_Inv";
        return $this->modelo->SelectDB($sSQL);
    }

    public function Form_Activate($parametros)
    {
        extract($parametros);
        mayorizar_inventario_sp(false, modulo_reemplazar:false);

        $sSQL = "SELECT Codigo_Inv, Producto " .
            "FROM Catalogo_Productos " .
            "WHERE Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TC = 'I' " .
            "AND INV <> 0 " .
            "ORDER BY Codigo_Inv";
        return $this->modelo->SelectDB($sSQL);
        $heightDispo = ($heightDisponible>150)?$heightDisponible-45:$heightDisponible;
        $_SESSION['ResumenKC']['AdoDetKardex'] = $sSQL;
        $DGQuery = grilla_generica_new($sSQL,$tabla,'myTableDGQuery','',false,false,false,1,1,1,$heightDispo);
        return compact('DGQuery');
    }
}

?>