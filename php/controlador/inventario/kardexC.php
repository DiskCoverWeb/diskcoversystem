<?php 
include(dirname(__DIR__,2).'/modelo/inventario/kardexM.php');
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new kardexC();


if(isset($_GET['cambiarProducto']))
{
	$codigoProducto = $_POST['codigoProducto'];
  	$datos = $controlador->ListarProductos('P',$codigoProducto);
  	echo json_encode($datos);
}

if(isset($_GET['Consultar_Tipo_Kardex']))
{
  	echo json_encode($controlador->Consultar_Tipo_De_Kardex($_GET['EsKardexIndividual'],$_POST));
}

if(isset($_GET['consulta_kardex']))
{
  	echo json_encode($controlador->Consultar_Kardex($_POST));
}

if(isset($_GET['kardex_total']))
{
  	echo json_encode($controlador->kardex_total());
}

if(isset($_GET['generarPDF']))
{
  $controlador->generarPDF();
}

if(isset($_GET['generarExcel']))
{
  $controlador->generarExcel();
}

if(isset($_GET['funcion']))
{
  $controlador->funcionInicio();
}
if(isset($_GET['ActualizarSerie']))
{
  echo json_encode($controlador->ActualizarSerie($_POST));
}

class kardexC
{
	private $modelo;
	private $pdf;
  public $NumEmpresa;
  public $Periodo_Contable;
	function __construct()
	{
		$this->modelo = new kardexM();
		$this->pdf = new cabecera_pdf();
    $this->NumEmpresa = $_SESSION['INGRESO']['item'];
    $this->Periodo_Contable = $_SESSION['INGRESO']['periodo'];
	}

	public function ListarProductos($tipo,$codigoProducto){
		$datos = $this->modelo->ListarProductos($tipo,$codigoProducto);
    $productos = [];
		foreach ($datos as $key => $value) {
			$productos[] = array('LabelCodigo'=>$value['Codigo_Inv']."/".$value['Minimo']."/".$value['Maximo']."/".$value['Unidad'],'nombre'=>utf8_encode($value['Codigo_Inv'])." ".utf8_encode($value['NomProd']));
		}
		if(count($productos)<=0){
			$productos[0] = array('LabelCodigo'=>'','nombre'=>'No existen datos.');
		}
		return $productos;
	}

	public function bodegas(){
		$datos = $this->modelo->bodegas();
		$bodegas = [];
		foreach ($datos as $key => $value) {
			$bodegas[] = array('LabelCodigo'=>$value['CodBod'],'nombre'=>utf8_encode($value['CodBod'])." - ".utf8_encode($value['Bodega']));
		}
		if(count($bodegas)<=0){
			$bodegas[0] = array('LabelCodigo'=>'','nombre'=>'No existen datos.');
		}
		return $bodegas;
	}

	public function Consultar_Tipo_De_Kardex($EsKardexIndividual,$parametros){
		extract($parametros);
		$error = false;
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

		$GrupoInv = "";
    $Debe = 0;
    $Haber = 0;
    $GrupoInv = trim($DCTInv);
    $Codigo = $LabelCodigo;
    $Codigo1 = trim($DCBodega);
    if ($Codigo == "") {
        $Codigo = ".";
    }
    if ($GrupoInv == "") {
        $GrupoInv = "*";
    }
  
    $sSQL = "SELECT TK.Codigo_Inv, CP.Producto, CP.Unidad, TK.CodBodega AS Bodega, TK.Fecha, TK.TP, TK.Numero, TK.Entrada, TK.Salida, TK.Existencia AS Stock, TK.Costo, " .
            "TK.Total AS Saldo, TK.Valor_Unitario, TK.Valor_Total, TK.TC, TK.Serie, TK.Factura, TK.Cta_Inv, TK.Contra_Cta, TK.Serie_No, TK.Codigo_Barra, TK.Lote_No, " .
            "TK.Codigo_Tra AS CI_RUC_CC, CM.Marca AS 'Marca_Tipo_Proceso', TK.Detalle, TK.Centro_Costo AS Beneficiario_Centro_Costo, TK.Orden_No, TK.ID " .
            "FROM Trans_Kardex AS TK, Catalogo_Productos AS CP, Catalogo_Marcas AS CM " .
            "WHERE TK.Item = '" . $this->NumEmpresa . "' " .
            "AND TK.Periodo = '" . $this->Periodo_Contable . "' " .
            "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND TK.T = '" . G_NORMAL . "' ";
    if ($EsKardexIndividual) {
        $sSQL = $sSQL . "AND TK.Codigo_Inv = '" . $Codigo . "' ";
    } else {
        if ($GrupoInv != "*") {
            $sSQL = $sSQL . "AND TK.Codigo_Inv LIKE '" . $GrupoInv . "%' ";
        }
    }
    if (isset($CheqBod) && $CheqBod == 1) {
        $sSQL = $sSQL . "AND TK.CodBodega = '" . $Codigo1 . "' ";
    }
    $sSQL = $sSQL .
            "AND TK.Item = CP.Item " .
            "AND TK.Item = CM.Item " .
            "AND TK.Periodo = CP.Periodo " .
            "AND TK.Periodo = CM.Periodo " .
            "AND TK.Codigo_Inv = CP.Codigo_Inv " .
            "AND TK.CodMarca = CM.CodMar " .
            "ORDER BY TK.Codigo_Inv, TK.Fecha,TK.Entrada DESC,TK.Salida,TK.TP,TK.Numero,TK.ID ";
    //$SQLDec = "TK.Costo " . strval($Dec_Costo) . "| TK.Valor_Unitario " . strval($Dec_Costo) . "|,TK.Valor_Total 2|.";
    $AdoKardex = $this->modelo->SelectDB($sSQL);
    
    if ($EsKardexIndividual) {
      if (count($AdoKardex) > 0) {
        foreach ($AdoKardex as $key => $Fields) {
            $Debe = $Debe + $Fields["Entrada"];
            $Haber = $Haber + $Fields["Salida"];
        }
      }
    } 

    $LabelExitencia = number_format($Debe - $Haber, 2);

    // $botones[0] = array('boton'=>'Imprime Codigos de Barra', 'icono'=>'<i class="fa fa-file-pdf-o"></i>', 'tipo'=>'danger mr-1', 'id'=>'ImprimeCodigosBarra');
    // $botones[1] = array('boton'=>'Cambiar Articulo', 'icono'=>'<i class="fa fa-refresh"></i>', 'tipo'=>'warning mr-1', 'id'=>'TP' );
    // $botones[2] = array('boton'=>'Cambia Codigo de Barra', 'icono'=>'<i class="fa fa-barcode"></i>', 'tipo'=>'info mr-1', 'id'=>'TP' );
    $botones[3] = array('boton'=>'Cambia la Serie', 'icono'=>'<i class="fa fa-retweet"></i>', 'tipo'=>'success', 'id'=>'Producto,ID,TC,Serie,Factura,Codigo_Inv' );
    $med_b = "110";
    $DGKardex = grilla_generica_new($sSQL,'Trans_Kardex','myTable','',$botones,false,false,1,1,1,100, med_b:$med_b);
		return compact('error','DGKardex','LabelExitencia');;
	}

	public function Consultar_Kardex($parametros){
		extract($parametros);
        $error = false;
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
        $Codigo = $LabelCodigo;
        $Codigo1 = trim($DCBodega);
        $Debe = 0;
        $Haber = 0;
        if ($Codigo == "") {
            $Codigo = ".";
        }

		$sSQL  =  "SELECT K.Codigo_Inv, K.Codigo_Barra, SUM(Entrada) As Entradas, SUM(Salida) As Salidas, SUM(Entrada-Salida) As Stock_Kardex 
            FROM Trans_Kardex As K, Comprobantes As C 
            WHERE K.Fecha BETWEEN '".$FechaIni."' AND '".$FechaFin."' 
            AND K.Codigo_Inv = '".$Codigo."'
            AND K.T = '".G_NORMAL."' 
            AND K.Item = '".$_SESSION['INGRESO']['item']."' 
            AND K.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        if (isset($CheqBod) && $CheqBod=='1') {
          $sSQL  .= "AND K.CodBodega = '".$Codigo1."' ";
        }
        $sSQL  .= "AND K.TP = C.TP 
                AND K.Fecha = C.Fecha 
                AND K.Numero = C.Numero 
                AND K.Item = C.Item 
                AND K.Periodo = C.Periodo 
                GROUP BY K.Codigo_Inv, K.Codigo_Barra
                HAVING SUM(Entrada-Salida) >=1 
                ORDER BY K.Codigo_Inv, K.Codigo_Barra ";
        $DGKardex = grilla_generica_new($sSQL ,'Trans_Kardex As K, Comprobantes As C','myTable','',false,false,false,1,1,1,100);

        $AdoKardex = $this->modelo->SelectDB($sSQL);
        if (count($AdoKardex) > 0) {
            foreach ($AdoKardex as $key => $Fields) {
              $Debe += $Fields["Stock_Kardex"];
            }
        }
        $LabelExitencia = number_format($Debe - $Haber, 2);
		return compact('error','DGKardex','LabelExitencia');
	}

	public function funcionInicio(){
		$this->modelo->funcionInicio();
	}

    public function ActualizarSerie($parametros){
        extract($parametros);
        if (strlen($CodigoP) > 1) {
          $sSQL = "UPDATE Trans_Kardex " .
            "SET Serie_No = '" . $CodigoP . "' " .
            "WHERE ID = " . $ID_Reg . " " .
            "AND Item = '" . $this->NumEmpresa . "' " .
            "AND Periodo = '" . $this->Periodo_Contable . "'";
            $rps=$this->modelo->ExecuteDB($sSQL);

          if (strlen($TC) == 2 && strlen($Serie) == 6 && $Factura > 0) {
            $sSQL = "UPDATE Detalle_Factura " .
              "SET Serie_No = '" . $CodigoP . "' " .
              "WHERE Item = '" . $this->NumEmpresa . "' " .
              "AND Periodo = '" . $this->Periodo_Contable . "' " .
              "AND TC = '" . $TC . "' " .
              "AND Serie = '" . $Serie . "' " .
              "AND Factura = " . $Factura . " " .
              "AND Codigo = '" . $CodigoInv . "'";
            $rps=$this->modelo->ExecuteDB($sSQL);
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar las facturas.'];
            }
          }
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar el kardex.'];
            }else{
                return ['rps'=>true, 'mensaje'=>'Proceso Terminado, vuelva a listar el documento'];
            }
        }
    }

	public function generarPDF(){
	    $MBoxFechaI = $_GET['MBoxFechaI'];
		$MBoxFechaF = $_GET['MBoxFechaF'];
		$LabelCodigo = $_GET['LabelCodigo'];
	    $datos = $this->modelo->consulta_kardex_producto($MBoxFechaI,$MBoxFechaF,$LabelCodigo);
	    $titulo = 'Control de existencias';
	    $parametros['MBoxFechaI'] = false;
	    $parametros['MBoxFechaF'] = false;
	    $sizetable = 8;
	    $mostrar = true;
	    $tablaHTML = array();


	    $tablaHTML[0]['medidas'] = array(15,18,10,16,80,20,20,20,20,20,20,20);
	    $tablaHTML[0]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L','L');
	    $tablaHTML[0]['datos'] = array('Bodega','Fecha','TP','Numero','Detalle','Entrada','Salida','Valor_Unit','Valor_Total','Stock Act.','Costo_Prom','Saldo Total');
	    $tablaHTML[0]['borde'] = 1;
	    $tablaHTML[0]['estilo'] = 'B';

	    $count = 1;
	    foreach ($datos as $key => $value) {
	      	$tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
	      	$tablaHTML[$count]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L','L');
	      	$tablaHTML[$count]['datos'] = array($value['Bodega'],$value['Fecha']->format('Y-m-d'),$value['TP'],$value['Comp_No'],$value['Detalle'], $value['Entrada'],$value['Salida'],$value['Valor_Unitario'],$value['Valor_Total'],$value['Stock'],$value['Costo'],$value['Saldo']);
	      	$tablaHTML[$count]['borde'] = $tablaHTML[0]['borde'];
	      	$count += 1;
	    }
	    $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['MBoxFechaI'],$parametros['MBoxFechaF'],$sizetable,$mostrar,25,$orientacion='L',true);
	}

	public function generarExcel($download = true){
	    $titulos = $_GET['array_titulo'];
	    $datos = explode(",", $_GET['array_datos']);
	    kardexExcel($titulos,$datos,$ti='ControlExistencias',$camne=null,$b=null,$base=null,$download);
	 }
}

?>