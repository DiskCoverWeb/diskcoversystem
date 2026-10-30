<?php
require_once dirname(__DIR__, 2) . '/db/session_guard.php';
include(dirname(__DIR__,2).'/modelo/inventario/kardexM.php');
    require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}
require_once(dirname(__DIR__,3).'/lib/fpdf/generar_codigo_barras.php');

$controlador = new kardexC();
if(isset($_GET["generarExcelKardex"])){
    // $controlador->CargaInicial($_GET);
    echo json_decode($controlador->generarExcelKardex($_GET));
    exit();
}else

if(isset($_GET['generarPDF']))
{
    $controlador->CargaInicial($_GET);
    $controlador->generarPDF($_GET);
    exit();
}
elseif(isset($_GET['cambiarProducto']))
{
	$codigoProducto = $_GET['codigoProducto'];
	$tipo = $_GET['tipo'];
  $query = '';
  if (isset($_GET['q'])) {
    $query = $_GET['q'];
  }
  echo json_encode($controlador->ListarProductos($tipo,$codigoProducto,$query));
}

if(isset($_GET['bodegas']))
{
  $query = '';
  if (isset($_GET['q'])) {
    $query = $_GET['q'];
  }
  $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'BODEGA';
  echo json_encode($controlador->bodegas($query, $tipo));
}

if(isset($_GET['Consultar_Tipo_Kardex']))
{
    $controlador->CargaInicial($_POST);
  	echo json_encode($controlador->Consultar_Tipo_De_Kardex($_GET['EsKardexIndividual'],$_POST));
}

if(isset($_GET['Kardex_Total_QR']))
{
    $controlador->CargaInicial($_POST);
    echo json_encode($controlador->Kardex_Total_QR($_POST));
}

if(isset($_GET['Eliminar_Kardex_Cero']))
{
    echo json_encode($controlador->Eliminar_Kardex_Cero($_POST));
}

if(isset($_GET['imprimirCodigoBarras']))
{
    $controlador->imprimirCodigoBarras($_GET);
    exit();
}

if(isset($_GET['consulta_kardex']))
{
    $controlador->CargaInicial($_POST);
  	echo json_encode($controlador->Consultar_Kardex($_POST));
}

if(isset($_GET['kardex_total']))
{
  	echo json_encode($controlador->kardex_total());
}

if(isset($_GET['funcion']))
{
  $controlador->funcionInicio();
}
if(isset($_GET['ActualizarSerie']))
{
  echo json_encode($controlador->ActualizarSerie($_POST));
}
if(isset($_GET['CambiaCodigodeBarra']))
{
  echo json_encode($controlador->CambiaCodigodeBarra($_POST));
}
if(isset($_GET['ListarArticulos']))
{
  $query = '';
  if (isset($_GET['q'])) {
    $query = $_GET['q'];
  }
  echo json_encode($controlador->ListarArticulos(true, $query));
}
if(isset($_GET['ConfirmarCambiar_Articulo']))
{
  echo json_encode($controlador->ConfirmarCambiar_Articulo($_POST));
}

class kardexC
{
	private $modelo;
	private $pdf;
  private $barras;
	function __construct()
	{
		$this->modelo = new kardexM();
		$this->pdf = new cabecera_pdf();
    $this->barras = new generar_codigo_barras();
	}

	function ListarProductos($tipo,$codigoProducto,$query=false){
		$datos = $this->modelo->ListarProductos($tipo,$codigoProducto,$query);

    if($tipo == 'I'){
      $lis = array();
      foreach ($datos as $key => $value) {
        $sep = explode(' ', $value['NomProd']);
        $lis[] = array('id' => $sep[0], 'text' => $value['NomProd'], 'datos' => $value);
      }
      // print_r($lis);die();
      return $lis;
    }else if($tipo == 'P'){
      $productos = [];
		  foreach ($datos as $key => $value) {
		  	$productos[] = array('codigo'=>$value['Codigo_Inv']."/".$value['Minimo']."/".$value['Maximo']."/".$value['Unidad']."/".$value['Producto'],'nombre'=>mb_convert_encoding($value['Producto'], 'UTF-8'));
		  }
		  if(count($productos)<=0){
		  	$productos[0] = array('codigo'=>'','nombre'=>'No existen datos.');
		  }
      return $productos;
    }
	}

	function bodegas($query=false, $tipo='BODEGA'){
		$datos = $this->modelo->bodegas($query, $tipo);
		
    $lis = array();
    foreach ($datos as $key => $value) {
      $codigo = $value['Codigo'];
      $nombre = $value['Nombre'];
      $lis[] = array('id' => $codigo, 'text' => mb_convert_encoding($codigo, 'UTF-8')." - ".mb_convert_encoding($nombre, 'UTF-8'), 'datos' => $value);
    }
    return $lis;
	}

	function Consultar_Tipo_De_Kardex($EsKardexIndividual,$parametros){
		extract($parametros);
    $EsKardexIndividual = in_array($EsKardexIndividual, [true, 'true', 1, '1'], true);
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
    if(@$DCTInv!=""){
        $DCTInv = explode("/",$DCTInv);
        $DCTInv = $DCTInv[0];
    }
    $GrupoInv = trim($DCTInv);
    $Codigo = $LabelCodigo;
    $Codigo1 = isset($DCBodega) ? trim($DCBodega) : '';
    $ModoConsulta = isset($ModoConsulta) ? $ModoConsulta : 'TODOS';
    if ($Codigo == "") {
        $Codigo = ".";
    }
    if ($GrupoInv == "") {
        $GrupoInv = "*";
    }

    // print_r('expression');die();  
    
    $datos = $this->modelo->Consultar_Tipo_De_Kardex($Codigo, $Codigo1, $FechaIni, $FechaFin, $EsKardexIndividual, $GrupoInv, $ModoConsulta);
    $AdoKardex = $datos; 
      // $datos['data'];

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
    $botones[1] = array('boton'=>'Cambiar Articulo', 'icono'=>'<i class="fa fa-refresh"></i>', 'tipo'=>'warning mr-1', 'id'=>'Producto,ID,TC,Serie,Factura,Codigo_Inv' );
    $botones[2] = array('boton'=>'Cambia Codigo de Barra', 'icono'=>'<i class="fa fa-barcode"></i>', 'tipo'=>'info mr-1', 'id'=>'Producto,ID,TC,Serie,Factura,Codigo_Inv' );
    $botones[3] = array('boton'=>'Cambia la Serie', 'icono'=>'<i class="fa fa-retweet"></i>', 'tipo'=>'success', 'id'=>'Producto,ID,TC,Serie,Factura,Codigo_Inv' );
    $med_b = "110";

    if($heightDisponible>135){
        $heightDisponible-=35;
    }
    //print_r($sSQL);die();
    $DGKardex = $datos;
		return array('data' => $DGKardex, 'LabelExistencia'=> $LabelExitencia);
	}

	function Consultar_Kardex($parametros){
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
        $Codigo1 = isset($DCBodega) ? trim($DCBodega) : '';
        $ModoConsulta = isset($ModoConsulta) ? $ModoConsulta : 'TODOS';
        $Debe = 0;
        $Haber = 0;
        if ($Codigo == "") {
            $Codigo = ".";
        }

        $DGKardex = $this->modelo->Consultar_Kardex($Codigo, $Codigo1, $FechaIni, $FechaFin, $ModoConsulta);
        
		
        //$_SESSION['DGKardex']['sSQL'] = $sSQL ;
        if($heightDisponible>135){
            $heightDisponible-=35;
        }
        //$DGKardex = grilla_generica_new($sSQL);

        $AdoKardex = $DGKardex['data'];
        if (count($AdoKardex) > 0) {
            foreach ($AdoKardex as $key => $Fields) {
              $Debe += $Fields["Stock_Kardex"];
            }
        }
        $LabelExitencia = number_format($Debe - $Haber, 2);

        return array('data' => $DGKardex['data'], 'LabelExistencia'=> $LabelExitencia);
		//return compact('error','DGKardex','LabelExitencia');
	}

  function Kardex_Total_QR($parametros){
    $FechaValida = FechaValida($parametros['MBoxFechaI']);
    if($FechaValida["ErrorFecha"]){
      return ['error' => true, 'mensaje' => $FechaValida["MsgBox"]];
    }
    $FechaValida = FechaValida($parametros['MBoxFechaF']);
    if($FechaValida["ErrorFecha"]){
      return ['error' => true, 'mensaje' => $FechaValida["MsgBox"]];
    }

    $modoConsulta = isset($parametros['ModoConsulta']) ? $parametros['ModoConsulta'] : 'TODOS';
    $codigo = isset($parametros['DCBodega']) ? trim($parametros['DCBodega']) : '';
    $datos = $this->modelo->Kardex_Total_QR(
      BuscarFecha($parametros['MBoxFechaI']),
      BuscarFecha($parametros['MBoxFechaF']),
      $codigo,
      $modoConsulta
    );

    $existencia = 0;
    foreach ($datos['data'] as $fila) {
      $existencia += $fila['Entrada'] - $fila['Salida'];
    }

    return ['data' => $datos['data'], 'LabelExistencia' => number_format($existencia, 2)];
  }

  function Eliminar_Kardex_Cero($parametros){
    $FechaValida = FechaValida($parametros['MBoxFechaI']);
    if($FechaValida["ErrorFecha"]){
      return ['rps' => false, 'mensaje' => $FechaValida["MsgBox"]];
    }
    $FechaValida = FechaValida($parametros['MBoxFechaF']);
    if($FechaValida["ErrorFecha"]){
      return ['rps' => false, 'mensaje' => $FechaValida["MsgBox"]];
    }

    $resultado = $this->modelo->Eliminar_Kardex_Cero(
      BuscarFecha($parametros['MBoxFechaI']),
      BuscarFecha($parametros['MBoxFechaF'])
    );
    if($resultado !== 1){
      return ['rps' => false, 'mensaje' => 'No fue posible eliminar los registros.'];
    }
    return ['rps' => true, 'mensaje' => 'Proceso terminado, vuelva a listar el Kardex.'];
  }

  function imprimirCodigoBarras($parametros){
    $idReg = isset($parametros['ID_Reg']) ? intval($parametros['ID_Reg']) : 0;
    $cantidad = isset($parametros['Cantidad']) ? intval($parametros['Cantidad']) : 0;
    if($idReg <= 0 || $cantidad <= 0 || $cantidad > 1000){
      http_response_code(400);
      exit('Seleccione un registro válido y una cantidad entre 1 y 1000.');
    }

    $datos = $this->modelo->DatosEtiquetaKardex($idReg);
    if(count($datos) === 0){
      http_response_code(404);
      exit('No se encontró el producto para imprimir.');
    }
    $this->barras->generar_barras($cantidad, $datos[0]);
  }

	function funcionInicio(){
		$this->modelo->funcionInicio();
	}

    function ActualizarSerie($parametros){
        extract($parametros);
        if (strlen($CodigoP) > 1) {
          $rps = $this->modelo->ActualizarSerieTK($CodigoP, $ID_Reg);

          if (strlen($TC) == 2 && strlen($Serie) == 6 && $Factura > 0) {
            $rps = $this->modelo->ActualizarSerieDF($CodigoP, $TC, $Serie, $Factura, $CodigoInv);
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
    function CambiaCodigodeBarra($parametros)
    {
        extract($parametros);
        if (strlen($CodigoB) > 1) {
            $rps = $this->modelo->CambiaCodigodeBarraTK($CodigoB, $ID_Reg);

          if (strlen($TC) == 2 && strlen($Serie) == 6 && $Factura > 0) {
            
            $rps = $this->modelo->CambiaCodigodeBarraDF($CodigoP, $TC, $Serie, $Factura, $CodigoInv);
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

    function ListarArticulos($SoActivos, $query=false)
    {
        $datos = $this->modelo->Listar_Articulos($SoActivos, true, $query);
        $rps = true;

        $lis = array();
        foreach ($datos as $key => $value) {
          //$sep = explode(' ', $value['NomProd']);
          $lis[] = array('id' => $value['codigo'], 'text' => $value['nombre'], 'datos' => $value);
        }
        // print_r($lis);die();
        return $lis;
        //return compact('rps','DCArt');
    }

    function ConfirmarCambiar_Articulo($parametros)
    {
        extract($parametros);
        if (strlen($DCArt) > 1) {
          
            $rps = $this->modelo->ConfirmarCambiar_ArticuloTK($DCArt, $ID_Reg);

          if (strlen($TC) == 2 && strlen($Serie) == 6 && $Factura > 0) {
            
            $rps=$this->modelo->ConfirmarCambiar_ArticuloDF($DCArt, $TC, $Serie, $Factura, $CodigoInv);
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar las facturas.'];
            }
          }
            if($rps!=1){
                return ['rps'=>false, 'mensaje'=>'No fue posible actualizar el kardex.'];
            }else{
                return ['rps'=>true, 'mensaje'=>'Proceso Terminado, vuelva a listar el Kardex'];
            }
        }
    }
    function generarExcelKardex($parametros){
        if(isset($_SESSION['DGKardex']['sSQL'])){
            extract($parametros);
            $titulo = 'Kardex del '.BuscarFecha($MBoxFechaI).' al '. BuscarFecha($MBoxFechaF);
            $sSQL   = $_SESSION['DGKardex']['sSQL'];
            $result = $this->modelo->SelectDB($sSQL);
            //print_r($replacedQuery);die();
            if(count($result) <= 0){
              die("Regrese a la pantalla anterior y cargue la informacion antes de imprimir el pdf");
            }
            $medidas = array(12,30,15,12,18,9,12,12,12,12,12,15,15,15,10,20,20,15,15,20,25,15,15,20,25,25,15,15,9);
            return exportar_excel_generico_SQl($titulo,$sSQL, $medidas, fecha_sin_hora:true);
        }else{
            die("Primero debe cargar la informacion");
        }
    }

	function generarPDF($parametros){
        if(!isset($_SESSION['DGKardex']['sSQL'])){
            die("Primero debe cargar la informacion");
        }

        extract($parametros);
        $query = $_SESSION['DGKardex']['sSQL'];
        if (strpos($query, 'FROM Trans_Kardex As K') !== false) {
            $result = $this->modelo->SelectDB($query);
            if(count($result) <= 0){
              die("Regrese a la pantalla anterior y cargue la informacion antes de imprimir el pdf");
            }

            $columnas = array('Codigo_Inv', 'Codigo_Barra', 'Entradas', 'Salidas', 'Stock_Kardex');
            $tablaHTML = array();
            $tablaHTML[0] = array(
              'medidas' => array(30, 70, 30, 30, 35),
              'alineado' => array('L', 'L', 'R', 'R', 'R'),
              'datos' => $columnas,
              'estilo' => 'B',
              'borde' => 'B'
            );
            foreach ($result as $key => $row) {
              $tablaHTML[$key + 1] = array(
                'medidas' => $tablaHTML[0]['medidas'],
                'alineado' => $tablaHTML[0]['alineado'],
                'datos' => array(
                  $row['Codigo_Inv'],
                  $row['Codigo_Barra'],
                  number_format((float)$row['Entradas'], 2, '.', ''),
                  number_format((float)$row['Salidas'], 2, '.', ''),
                  number_format((float)$row['Stock_Kardex'], 2, '.', '')
                ),
                'estilo' => 'I',
                'borde' => $key === count($result) - 1 ? 'LRB' : 'LR'
              );
            }

            $pdf = new cabecera_pdf();
            $pdf->cabecera_reporte_MC(
              'RESUMEN DE CODIGOS DE BARRA',
              $tablaHTML,
              $contenido=false,
              $image=false,
              $MBoxFechaI,
              $MBoxFechaF,
              8,
              true,
              orientacion: 'L',
              mostrar_cero: true
            );
            return;
        }
        // Extraer la sección entre "SELECT" y "WHERE" usando una expresión regular
        $pattern = "/SELECT(.*?)FROM/s";
        $matches = [];
        preg_match($pattern, $query, $matches);
        $sectionToReplace = $matches[1];

        // Reemplazar la sección extraída con el nuevo contenido deseado
        $newSection = " TK.CodBodega AS Bod, TK.Fecha, TK.TP, TK.Numero, TK.Detalle, TK.Entrada, TK.Salida, TK.Valor_Unitario, TK.Valor_Total, TK.Existencia AS Cantidad, TK.Costo as Costo_Prom, TK.Total AS Saldo_Total ";
        $replacedQuery = str_replace($sectionToReplace, $newSection, $query);
        $result = $this->modelo->SelectDB($replacedQuery);
        //print_r($replacedQuery);die();
        if(count($result) <= 0){
          die("Regrese a la pantalla anterior y cargue la informacion antes de imprimir el pdf");
        }
        $campos = array();
        foreach ($result[0] as $key => $value) {
            array_push($campos,$key);
        }
        $medi =array(8,17,9,28,90,13,12,23,18,15,19,18);
        $medip =array(8,17,9,28,90,13,12,23,18,15,19,18);

        $pdf = new cabecera_pdf();  
        $titulo = "CONTROL DE EXISTENCIAS";
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

    function CargaInicial($parametros)
    {
      // print_r($parametros);die();
        extract($parametros);
        $FechaIni = BuscarFecha($MBoxFechaI);
        $FechaFin = BuscarFecha($MBoxFechaF);
        $Codigo1 = "";
        if(isset($DCBodega)){
            $Codigo1 = trim($DCBodega);
        }
        if ($Codigo1 == "") {
            $Codigo1 = ".";
        }

        // $this->modelo->CargaInicial($FechaIni, $FechaFin);
    }

}

?>