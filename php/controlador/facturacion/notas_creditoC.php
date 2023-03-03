<?php 
require(dirname(__DIR__,2).'/modelo/facturacion/notas_creditoM.php');
require(dirname(__DIR__,2).'/comprobantes/SRI/autorizar_sri.php');
require_once(dirname(__DIR__,3)."/lib/fpdf/cabecera_pdf.php");

$controlador = new notas_creditoC();
if(isset($_GET['delete_sientos_nc']))
{
	echo json_encode($controlador->delete_sientos_nc());
}

if(isset($_GET['DCBodega']))
{
	echo json_encode($controlador->DCBodega());
}

if(isset($_GET['DCMarca']))
{
	echo json_encode($controlador->DCMarca());
}

if(isset($_GET['generar_pdf']))
{
	echo json_encode($controlador->generar_pdf());
}
if(isset($_GET['DCContraCta']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->DCContraCta($q));
}

if(isset($_GET['DCArticulo']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->DCArticulo($q));
}

if(isset($_GET['tabla']))
{
	$parametros = array();
	echo json_encode($controlador->cargar_tabla($parametros));
}

if(isset($_GET['DCLineas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCLineas($parametros));
}
if(isset($_GET['numero_autorizacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->numero_autorizacion($parametros));
}

if(isset($_GET['DCTC']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCTC($parametros));
}

if(isset($_GET['DCSerie']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCSerie($parametros));
}

if(isset($_GET['Detalle_Factura']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($controlador->Detalle_Factura($parametros));
}

if(isset($_GET['Lineas_Factura']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($controlador->Lineas_Factura($parametros));
}

if(isset($_GET['DCFactura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCFactura($parametros));
}

if(isset($_GET['cliente']))
{
	$q = '';
	if(isset($_GET['q'])){ $q = $_GET['q'];}
	echo json_encode($controlador->Listar_Facturas_Pendientes_NC($q));
}

if(isset($_GET['guardar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar($parametros));
}

if(isset($_GET['eliminar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea($parametros));
}

if(isset($_GET['generar_nota_credito']))
{
	$parametros = $_POST;
	echo json_encode($controlador->generar_nota_credito($parametros));
}

/**
 * 
 */
class notas_creditoC
{
	private $modelo;	
	private $sri;
	function __construct()
	{

		$this->modelo = new notas_creditoM(); 
		$this->sri = new autorizacion_sri(); 
		// code...
	}

	function DCBodega()
	{
		$datos =  $this->modelo->catalogo_bodega();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['CodBod'],'nombre'=>$value['Bodega']);
		}
		return $list;
	}

	function DCMarca()
	{
		$datos =  $this->modelo->catalogo_marca();
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['CodMar'],'nombre'=>$value['Marca']);
		}
		return $list;
	}

	function DCContraCta($query)
	{
		$datos =  $this->modelo->Catalogo_Cuentas($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id'=>$value['Codigo'],'text'=>$value['NomCuenta'],'data'=>$value);
		}
		return $list;
	}

	function DCArticulo($query)
	{
		$datos =  $this->modelo->Catalogo_Productos($query);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto'],'data'=>$value);
		}
		return $list;
	}

	function cargar_tabla($parametro)
	{
		$IVA_NC = 0;
		$Total_Con_IVA = 0;
		$Total_Desc2  = 0;
		$Total_Sin_IVA  = 0;
		$Total_Desc = 0;
		$SubTotal_NC = 0;

		 $table = $this->modelo->cargar_tabla($parametro,$tabla=1);
		 $totales = $this->modelo->cargar_tabla($parametro);

		 foreach ($totales as $key => $value) {
		 		  if($value["TOTAL_IVA"] > 0 ){
               $IVA_NC = $IVA_NC + $value["TOTAL_IVA"];
               $Total_Con_IVA = $Total_Con_IVA + $value["SUBTOTAL"];
               $Total_Desc2 = $Total_Desc2 + $value["DESCUENTO"];
           }else{
               $Total_Sin_IVA = $Total_Sin_IVA + $value["SUBTOTAL"];
               $Total_Desc = $Total_Desc + $value["DESCUENTO"];
           }
           $SubTotal_NC = $SubTotal_NC + $value["SUBTOTAL"];
		 }

		return  array('tabla'=>$table,'TxtIVA'=>$IVA_NC,'TxtConIVA'=>$Total_Con_IVA,'TxtDescuento'=>$Total_Desc2+$Total_Desc,'TxtSinIVA'=>$Total_Sin_IVA,'TxtSaldo'=>$SubTotal_NC,'LblTotalDC'=>$SubTotal_NC+$IVA_NC - ($Total_Desc + $Total_Desc2) );
	}

	function Listar_Facturas_Pendientes_NC($q)
	{
		$datos = $this->modelo->Listar_Facturas_Pendientes_NC($q);
		$cli = array();	
		foreach ($datos as $key => $value) {
			$cli[] = array('id'=>$value['Codigo'],'text'=>$value['Cliente'],'data'=>$value);
		}
		return $cli;
	}

	function DClineas($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->DClineas($parametro['fecha'],$parametro['cta_cxp']);
		$list = array();		
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Serie'],'nombre'=>$value['Concepto'],'Autorizacion'=>$value['Autorizacion']); 
		}
		if(count($list)==0)
		{
			$list[] = array('codigo'=>'','nombre'=>'No existen datos');	
		}
		return $list;
	}


	function numero_autorizacion($parametro)
	{
		 $numero  = ReadSetDataNum("NC_SERIE_".$parametro['serie'], True, False);
		 return $numero;

	}

	function DCTC($parametro)
	{
		// print_r($parametro);die();
		$datos = $this->modelo->DCTC($parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['TC'],'nombre'=>$value['TC']); 
		}
		return $list;
	}

	function DCSerie($parametro)
	{
		$datos = $this->modelo->DCSerie($parametro['TC'],$parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Serie'],'nombre'=>$value['Serie']); 
		}
		return $list;
	}

	function DCFactura($parametro)
	{
		$datos = $this->modelo->DCFactura($parametro['Serie'],$parametro['TC'],$parametro['CodigoC']);
		$list = array();
		foreach ($datos as $key => $value) {
			$list[] = array('codigo'=>$value['Factura'],'nombre'=>$value['Factura']); 
		}
		return $list;
	}

	function Detalle_Factura($parametro)
	{
		return $this->modelo->Factura_detalle($parametro['Factura'],$parametro['Serie'],$parametro['TC'],$parametro['CodigoC']);
	}


	function delete_sientos_nc()
	{
		return $this->modelo->delete_asiento_nc();
	}

	function Lineas_Factura($parametros)
	{
		// print_r($parametro);die();
     $DocConInv = false;
     $Ln_No = 0;
     $this->modelo->delete_asiento_nc();
    
     //LblSaldo.Caption = Format(FA.Saldo_MN, "#,##0.00")
     //LblTotal.Caption = Format(FA.Total_MN, "#,##0.00")
     $datos = $this->modelo->lineas_factura($parametros['Factura'],$parametros['Serie'],$parametros['TC'],$parametros['Autorizacion']);
     // print_r($datos);die();     
      if(count($datos)  > 0)
      {
          // $FA.Cod_Ejec = .fields("Cod_Ejec")
          // $FA.Porc_C = .fields("Porc_C")
          $NoMes = $datos[0]["Mes_No"];
          $MiMes = $datos[0]["Mes"];
          $Cod_Bodega = $datos[0]["CodBodega"];
          foreach ($datos as $key => $value) 
          {
             $Ok_Inv = Leer_Codigo_Inv($value["Codigo"], $parametros['Fecha']);
              // print_r($Ok_Inv);
              // print_r($datos);die();     
             $datosNC[0]['campo'] =  "CODIGO"; 
             $datosNC[0]['dato'] = $value[ "Codigo"];
             $datosNC[1]['campo'] =  "CANT"; 
             $datosNC[1]['dato'] = $value[ "Cantidad"];
             $datosNC[2]['campo'] =  "PRODUCTO"; 
             $datosNC[2]['dato'] = $value[ "Producto"];
             $datosNC[3]['campo'] =  "SUBTOTAL"; 
             $datosNC[3]['dato'] = $value[ "Total"];
             $datosNC[4]['campo'] =  "DESCUENTO"; 
             $datosNC[4]['dato'] = $value[ "Total_Desc"] + $value["Total_Desc2"];
             $datosNC[5]['campo'] =  "TOTAL_IVA"; 
             $datosNC[5]['dato'] = $value[ "Total_IVA"];
             $datosNC[6]['campo'] =  "CodBod"; 
             $datosNC[6]['dato'] = $value[ "CodBodega"];
             $datosNC[7]['campo'] =  "CodMar"; 
             $datosNC[7]['dato'] = $value[ "CodMarca"];
             $datosNC[8]['campo'] =  "Codigo_C"; 
             $datosNC[8]['dato'] =  $parametros['CodigoC'];
             $datosNC[9]['campo'] =  "Item"; 
             $datosNC[9]['dato'] =  $_SESSION['INGRESO']['item']; // NumEmpresa
             $datosNC[10]['campo'] =  "CodigoU"; 
             $datosNC[10]['dato'] =  $_SESSION['INGRESO']['CodigoU']; // CodigoUsuario
             $datosNC[11]['campo'] =  "PVP"; 
             $datosNC[11]['dato'] = $value[ "Precio"];
             $datosNC[12]['campo'] =  "COSTO";
             $datosNC[12]['dato'] =  $Ok_Inv['datos']['Costo'];
             $datosNC[13]['campo'] =  "Cod_Ejec"; 
             $datosNC[13]['dato'] = $value[ "Cod_Ejec"];
             $datosNC[14]['campo'] =  "Porc_C"; 
             $datosNC[14]['dato'] = $value[ "Porc_C"];
             $datosNC[15]['campo'] =  "Porc_IVA"; 
             $datosNC[15]['dato'] = $value[ "Porc_IVA"];
             $datosNC[16]['campo'] =  "Mes_No"; 
             $datosNC[16]['dato'] = $value[ "Mes_No"];
             $datosNC[17]['campo'] =  "Mes"; 
             $datosNC[17]['dato'] = $value[ "Mes"];
             $datosNC[18]['campo'] =  "Anio"; 
             $datosNC[18]['dato'] = $value[ "Ticket"];
             $datosNC[19]['campo'] =  "A_No";
             $datosNC[19]['dato'] = $Ln_No;           
             if($Ok_Inv['datos']['Con_Kardex'])
             {
               $datosNC[20]['campo'] =  "Ok";
               $datosNC[20]['dato'] = $Ok_Inv['datos']['Con_Kardex'];
               $datosNC[21]['campo'] =  "Cta_Inventario"; 
               $datosNC[21]['dato']  = $Ok_tInv['Cta_Inventario'];
               $datosNC[22]['campo'] =  "Cta_Costo"; 
               $datosNC[22]['dato']  = $Ok_tInv['Cta_Costo_Venta'];
             }
             insert_generico('Asiento_NC',$datosNC);
             $Ln_No = $Ln_No + 1;
             // DocConInv = DatInv.Con_Kardex
          }
      }

     // Listar_Articulos_Malla
     // If DocConInv Then DCBodega.SetFocus Else DGAsiento_NC.SetFocus
	}

	function guardar($parametros)
	{
		$SubTotalDesc = 0;
    $SubTotalIVA = 0;
		$SubTotal_NC = $parametros['Saldo'];
		$IVA_NC = $parametros['IVA'];
		$Total_Desc = $parametros['Descuento'];

		$lista = $this->modelo->lineas_factura($parametros['Factura'],$parametros['Serie'],$parametros['TC'],$parametros['Autorizacion']);
		foreach ($lista as $key => $value) {
			if($value['Codigo']==$parametros['productos'])
			{
				 return -3; // ya esta reguistrado alerta
			}
		}
		$Ln_No  = count($lista)+1;

		if($parametros['TextCant'] > 0 &&  $parametros['TextVUnit'] > 0 ){
       $SubTotalDesc = $parametros['TextDesc'];
       $SubTotal = number_format($parametros['TextCant'] * $parametros['TextVUnit'],2,'.','');
       $product = Leer_Codigo_Inv($parametros['productos'],$parametros['MBoxFecha']);
       $BanIVA = $product['datos']['IVA'];
       if($BanIVA==1 && $parametros['TC'] <> "NV"){ $SubTotalIVA = number_format(($SubTotal-$SubTotalDesc)*$_SESSION['INGRESO']['porc'], 4,'.','');}
       $Total = $SubTotal_NC + $SubTotal + $IVA_NC + $SubTotalIVA - $SubTotalDesc - $Total_Desc;
       // SetAdoAddNew "Asiento_NC"
       $datosNC[0]['campo']= "CODIGO";
       $datosNC[0]['dato'] =  $parametros['productos'];
       $datosNC[1]['campo']= "CANT";
       $datosNC[1]['dato'] =  $parametros['TextCant'];
       $datosNC[2]['campo']= "PRODUCTO";
       $datosNC[2]['dato'] =  $product['datos']['Producto'];
       $datosNC[3]['campo']= "SUBTOTAL";
       $datosNC[3]['dato'] =  $SubTotal;
       $datosNC[4]['campo']= "DESCUENTO";
       $datosNC[4]['dato'] =  $SubTotalDesc;
       $datosNC[5]['campo']= "TOTAL_IVA";
       $datosNC[5]['dato'] =  $SubTotalIVA;
       $datosNC[6]['campo']= "CodBod";
       $datosNC[6]['dato'] =  $parametros['Cod_Bodega'];
       $datosNC[7]['campo']= "CodMar";
       $datosNC[7]['dato'] =  $parametros['Cod_Marca'];
       $datosNC[8]['campo']= "Codigo_C";
       $datosNC[8]['dato'] =  $parametros['CodigoC'];
       $datosNC[9]['campo']= "Item";
       $datosNC[9]['dato'] =  $_SESSION['INGRESO']['item']; 
       $datosNC[10]['campo']= "CodigoU";
       $datosNC[10]['dato'] =  $_SESSION['INGRESO']['CodigoU'];
       $datosNC[11]['campo']= "PVP";
       $datosNC[11]['dato'] =  number_format($parametros['TextVUnit'],$_SESSION['INGRESO']['Dec_PVP'],'.','');
       $datosNC[12]['campo']= "COSTO";
       $datosNC[12]['dato'] =  $product['datos']['Costo'];
       $datosNC[13]['campo']= "Mes_No";
       $datosNC[13]['dato'] =  date('m',strtotime($parametros['MBoxFecha']));
       $datosNC[14]['campo']= "Mes";
       $datosNC[14]['dato'] =  MesesLetras(date('m',strtotime($parametros['MBoxFecha'])));
       $datosNC[15]['campo']= "Anio";
       $datosNC[15]['dato'] =  date('Y',strtotime($parametros['MBoxFecha']));
       $datosNC[16]['campo']= "Porc_IVA";
       $datosNC[16]['dato'] =  $_SESSION['INGRESO']['porc'];
       $datosNC[17]['campo']= "A_No";
       $datosNC[17]['dato'] = $Ln_No;
       if($product['datos']['Con_Kardex']){
         $datosNC[18]['campo']= "Ok";
         $datosNC[18]['dato'] = $product['datos']['Con_Kardex'];
         $datosNC[19]['campo']= "Cta_Inventario";
         $datosNC[19]['dato'] = $product['datos']['Cta_Inventario'];
         $datosNC[20]['campo']= "Cta_Costo";
         $datosNC[20]['dato'] = $product['datos']['Cta_Costo_Venta'];
       }
       
       if(insert_generico('Asiento_NC',$datosNC)==null)
       {
       	return 1;
       }
		}else
		{
			 return -1;
		}
		 // print_r($parametros);die();
	}

	function eliminar_linea($parametros)
	{
		// print_r($parametros);die();
		return  $this->modelo->delete_asientonNC($parametros['codigo'],$parametros['a_no']);
	}

	function generar_nota_credito($parametros)
	{

		// print_r($parametros);die();
		$FA = array();
		$SubTotalCosto = 0;
		$Grupo = '';
		$FA['Serie_NC'] = $parametros['TextCheqNo'];
		$FA['Serie'] = $parametros['DCSerie'];
		$FA['TC'] = $parametros['DCTC'];
		$FA['Factura'] = $parametros['DCFactura'];	    
		$FA['Nota_Credito'] = $parametros['TextCompRet'];
		$FA['Autorizacion_NC'] = $parametros['TextBanco'];
		$FA['Autorizacion'] = $parametros['TxtAutorizacion'];
    $FA['CodigoC'] = $parametros['DCClientes'];
    $FA['Cliente'] = $parametros['Cliente'];
    $cliente_cta =  $this->modelo->Listar_Facturas_Pendientes_NC($parametros['Cliente']);
    $FA['Cta_CxP'] = $cliente_cta[0]['Cta_CxP'];
    $FA['Nota'] = $parametros['TxtConcepto'];


		$FAC = $this->modelo->Factura_detalle($parametros['DCFactura'],$parametros['DCSerie'],$parametros['DCTC']);
	  $FA['T'] = $FAC[0]["T"];
    $FA['Fecha'] = $FAC[0]["Fecha"];
    $FA['Cta_CxP'] = $FAC[0]["Cta_CxP"];
    $FA['Cod_CxC'] = $FAC[0]["Cod_CxC"];
    $FA['Porc_IVA'] = $FAC[0]["Porc_IVA"];
    $FA['Total_MN'] = $FAC[0]["Total_MN"];
    $FA['Saldo_MN'] = $FAC[0]["Saldo_MN"];
    $FA['Autorizacion'] = $FAC[0]["Autorizacion"];
    $FA['Descuento'] = $FAC[0]["Descuento"];
    $FA['IVA'] = $FAC[0]["IVA"];
    if($FAC[0]["IVA"] > 0){ $FA['Porc_NC'] = $FAC[0]["Porc_IVA"];}

		$MBoxFecha = $parametros['MBoxFecha'];

		$IVA_NC = 0;
		$Total_Con_IVA = 0;
		$Total_Desc2  = 0;
		$Total_Sin_IVA  = 0;
		$Total_Desc = 0;
		$SubTotal_NC = 0;

		 $totales = $this->modelo->cargar_tabla($parametros);
		 foreach ($totales as $key => $value) {
		 		  if($value["TOTAL_IVA"] > 0 ){
               $IVA_NC = $IVA_NC + $value["TOTAL_IVA"];
               $Total_Con_IVA = $Total_Con_IVA + $value["SUBTOTAL"];
               $Total_Desc2 = $Total_Desc2 + $value["DESCUENTO"];
           }else{
               $Total_Sin_IVA = $Total_Sin_IVA + $value["SUBTOTAL"];
               $Total_Desc = $Total_Desc + $value["DESCUENTO"];
           }
           $SubTotal_NC = $SubTotal_NC + $value["SUBTOTAL"];
		 }


		    	// print_r($parametros);die();
		    if( floatval($parametros['LblTotalDC']) <= floatval($parametros['LblSaldo']))
		    {
		       if($parametros['ReIngNC']==0){ $FA['Nota_Credito'] = ReadSetDataNum("NC_SERIE_".$FA['Serie_NC'],True,True); }
		        $FA['Fecha_NC'] = $MBoxFecha;
		        $Contra_Cta = $parametros['DCContraCta'];
		        if(strlen($Contra_Cta) <= 1 ){ $Contra_Cta = ReadAdoCta("Cta_Devolucion_Ventas"); }
		        $Listar_Articulos_Malla = $this->modelo->cargar_tabla($parametros,$tabla = false);		        
		        Actualiza_Procesado_Kardex_Factura($FA);
		        
		        //$resp = $this->modelo->delete_Detalle_Nota_Credito($FA['Serie_NC'],$FA['Nota_Credito']);
		        
		        $FA['ClaveAcceso_NC'] = G_NINGUNO;
		        $FA['SubTotal_NC'] = 0;
		        $FA['Total_IVA_NC'] = 0;
		        $FA['Descuento_NC'] = 0;
		        $Cantidad = 0;
		        if(strlen($FA['Autorizacion_NC']) >= 13 ){ $TMail['TipoDeEnvio'] = "CE"; }



		        foreach ($Listar_Articulos_Malla  as $key => $value) 
		        {		        	
		                $FA['SubTotal_NC'] = $FA['SubTotal_NC']+ $value["SUBTOTAL"];
		                $FA['Total_IVA_NC'] = $FA['Total_IVA_NC']+ $value["TOTAL_IVA"];
		                $FA['Descuento_NC'] = $FA['Descuento_NC']+ $value["DESCUENTO"];
		                $SubTotalCosto = number_format(($value["SUBTOTAL"] / $value["CANT"]), 6,'.','');
		               // 'SubTotal = Redondear(.Fields("CANT") * SubTotalCosto, 2)
		                $SubTotal = number_format($value["CANT"] * $value["COSTO"], 2,'.','');
		                
		               // 'Grabamos el detalle de la NC
		               // 'Cta_Devolucion, , Porc_IVA,
		                // SetAdoAddNew "Detalle_Nota_Credito"
		                $datosDNC[0]['campo'] = "T"; 
		                $datosDNC[0]['dato']  = G_NORMAL;
		                $datosDNC[1]['campo'] = "CodigoC"; 
		                $datosDNC[1]['dato']  = $value['Codigo_C'];
		                $datosDNC[2]['campo'] = "Cta_Devolucion"; 
		                $datosDNC[2]['dato']  = $Contra_Cta;
		                $datosDNC[3]['campo'] = "Fecha"; 
		                $datosDNC[3]['dato']  = $FA['Fecha_NC'];
		                $datosDNC[4]['campo'] = "Serie"; 
		                $datosDNC[4]['dato']  = $FA['Serie_NC'];
		                $datosDNC[5]['campo'] = "Secuencial"; 
		                $datosDNC[5]['dato']  = $FA['Nota_Credito'];
		                $datosDNC[6]['campo'] = "Autorizacion"; 
		                $datosDNC[6]['dato']  = $FA['Autorizacion_NC'];
		                $datosDNC[7]['campo'] = "Codigo_Inv"; 
		                $datosDNC[7]['dato']  = $value["CODIGO"];
		                $datosDNC[8]['campo'] = "Cantidad"; 
		                $datosDNC[8]['dato']  = $value["CANT"];
		                $datosDNC[9]['campo'] = "Producto"; 
		                $datosDNC[9]['dato']  = $value["PRODUCTO"];
		                $datosDNC[10]['campo'] = "CodBodega"; 
		                $datosDNC[10]['dato']  = $value["CodBod"];
		                $datosDNC[11]['campo'] = "Total_IVA"; 
		                $datosDNC[11]['dato']  = $value["TOTAL_IVA"];
		                $datosDNC[12]['campo'] = "Precio"; 
		                $datosDNC[12]['dato']  = $value["PVP"];
		                $datosDNC[13]['campo'] = "Total"; 
		                $datosDNC[13]['dato']  = $value["SUBTOTAL"];
		                $datosDNC[14]['campo'] = "CodMar"; 
		                $datosDNC[14]['dato']  = $value["CodMar"];
		                $datosDNC[15]['campo'] = "Cod_Ejec"; 
		                $datosDNC[15]['dato']  = $value["Cod_Ejec"];
		                $datosDNC[16]['campo'] = "Porc_C"; 
		                $datosDNC[16]['dato']  = $value["Porc_C"];
		                $datosDNC[17]['campo'] = "Porc_IVA"; 
		                $datosDNC[17]['dato']  = $value["Porc_IVA"];
		                $datosDNC[18]['campo'] = "Mes_No"; 
		                $datosDNC[18]['dato']  = $value["Mes_No"];
		                $datosDNC[19]['campo'] = "Mes"; 
		                $datosDNC[19]['dato']  = $value["Mes"];
		                $datosDNC[20]['campo'] = "Anio"; 
		                $datosDNC[20]['dato']  = $value["Anio"];
		                $datosDNC[21]['campo'] = "TC"; 
		                $datosDNC[21]['dato']  = $FA['TC'];
		                $datosDNC[22]['campo'] = "Serie_FA"; 
		                $datosDNC[22]['dato']  = $FA['Serie'];
		                $datosDNC[23]['campo'] = "Factura"; 
		                $datosDNC[23]['dato']  = $FA['Factura'];
		                $datosDNC[24]['campo'] =  "Item"; 
		                $datosDNC[24]['dato'] =  $_SESSION['INGRESO']['item'];
		                $datosDNC[25]['campo'] =  "Periodo"; 
		                $datosDNC[25]['dato'] =  $_SESSION['INGRESO']['periodo'];
		                $datosDNC[26]['campo'] =  "CodigoU"; 
		                $datosDNC[26]['dato'] =  $_SESSION['INGRESO']['CodigoU'];		                    
		                $datosDNC[27]['campo'] = "A_No"; 
		                $datosDNC[27]['dato'] = $key+1;


		                insert_generico('Detalle_Nota_Credito',$datosDNC);
		                
		               // 'Grabamos en el Kardex la factura
		                if($value["Ok"])
		                {
		                    // SetAdoAddNew "Trans_Kardex"
		                    $datosTK[0]['campo'] =  "T"; 
		                    $datosTK[0]['dato']  =  G_NORMAL;
		                    $datosTK[1]['campo'] =  "TP"; 
		                    $datosTK[1]['dato']  =  G_NINGUNO;
		                    $datosTK[2]['campo'] =  "Numero"; 
		                    $datosTK[2]['dato']  = '0';
		                    $datosTK[3]['campo'] =  "TC"; 
		                    $datosTK[3]['dato']  = $FA['TC'];
		                    $datosTK[4]['campo'] = "Serie"; 
		                    $datosTK[4]['dato']  = $FA['Serie'];
		                    $datosTK[5]['campo'] = "Fecha"; 
		                    $datosTK[5]['dato']  = $FA['Fecha_NC'];
		                    $datosTK[6]['campo'] = "Factura"; 
		                    $datosTK[6]['dato']  = $FA['Factura'];
		                    $datosTK[7]['campo'] = "Codigo_P"; 
		                    $datosTK[7]['dato']  = $FA['CodigoC'];
		                    $datosTK[8]['campo'] =  "CodigoL"; 
		                    $datosTK[8]['dato'] =  $FA['Cod_CxC'];
		                    $datosTK[9]['campo'] =  "Codigo_Inv"; 
		                    $datosTK[9]['dato'] = $value["CODIGO"];
		                    $datosTK[10]['campo'] =  "Total_IVA";
		                    $datosTK[10]['dato'] = $value["TOTAL_IVA"];
		                    $datosTK[11]['campo'] =  "Entrada"; 
		                    $datosTK[11]['dato'] = $value["CANT"];
		                    $datosTK[12]['campo'] =  "PVP"; 
		                    $datosTK[12]['dato'] = $value["PVP"]; //'SubTotalCosto
		                    $datosTK[13]['campo'] =  "Valor_Unitario"; 
		                    $datosTK[13]['dato'] = $value["COSTO"]; //'SubTotalCosto
		                    $datosTK[14]['campo'] =  "Costo"; 
		                    $datosTK[14]['dato'] = $value["COSTO"];
		                    $datosTK[15]['campo'] =  "Valor_Total"; 
		                    $datosTK[15]['dato'] = number_format($value["CANT"]*$value["COSTO"], 2,'.','');
		                    $datosTK[16]['campo'] =  "Total"; 
		                    $datosTK[16]['dato'] = number_format($value["CANT"]*$value["COSTO"], 2,'.','');
		                    $datosTK[17]['campo'] =  "Descuento"; 
		                    $datosTK[17]['dato'] = $value["DESCUENTO"];
		                    $datosTK[18]['campo'] =  "Detalle"; 
		                    $datosTK[18]['dato'] = "NC:".$FA['Serie_NC']."-".generaCeros($FA['Nota_Credito'],9)."-".$FA['Cliente'];
		                    $datosTK[19]['campo'] =  "Cta_Inv"; 
		                    $datosTK[19]['dato'] = $value["Cta_Inventario"];
		                    $datosTK[20]['campo'] =  "Contra_Cta"; 
		                    $datosTK[20]['dato'] = $value["Cta_Costo"];
		                    $datosTK[21]['campo'] =  "CodBodega"; 
		                    $datosTK[21]['dato'] = $value["CodBod"];
		                    $datosTK[22]['campo'] =  "CodMarca"; 
		                    $datosTK[22]['dato'] = $value["CodMar"];
		                    $datosTK[23]['campo'] =  "Item"; 
		                    $datosTK[23]['dato'] =  $_SESSION['INGRESO']['item'];
		                    $datosTK[24]['campo'] =  "Periodo"; 
		                    $datosTK[24]['dato'] =  $_SESSION['INGRESO']['periodo'];
		                    $datosTK[25]['campo'] =  "CodigoU"; 
		                    $datosTK[25]['dato'] =  $_SESSION['INGRESO']['CodigoU'];
		                    insert_generico('Trans_Kardex',$datosDNC);
		                    // 'MsgBox "Grabado"
		                }
		        }

		        $TA['T'] = G_NORMAL;
		        $TA['TP'] = $FA['TC'];
		        $TA['Serie'] = $FA['Serie'];
		        $TA['Factura'] = $FA['Factura'];
		        $TA['Autorizacion'] = $FA['Autorizacion'];
		        $TA['Fecha'] = $MBoxFecha;
		        $TA['CodigoC'] = $FA['CodigoC'];
		        $TA['Cta_CxP'] = $FA['Cta_CxP'];
		        $TA['Cta'] = $Contra_Cta;
		        
		        $TA['Serie_NC'] = $FA['Serie_NC'];
		        $TA['Autorizacion_NC'] = $FA['Autorizacion_NC'];
		        $TA['Nota_Credito'] = $FA['Nota_Credito'];
		        
		        $TA['Banco'] = "NOTA DE CREDITO";
		        $TA['Cheque'] = "VENTAS SIN IVA";
		        $TA['Abono'] = $Total_Sin_IVA - $Total_Desc;
		        Grabar_Abonos($TA);

		        
		        $TA['Banco'] = "NOTA DE CREDITO";
		        $TA['Cheque'] = "VENTAS CON IVA";
		        $TA['Abono'] = $Total_Con_IVA - $Total_Desc2;
		        Grabar_Abonos($TA);

		        $Cta_IVA = "";		        
		        $TA['Cta'] = $Cta_IVA;
		        $TA['Banco'] = "NOTA DE CREDITO";
		        $TA['Cheque'] = "I.V.A.";
		        $TA['Abono'] = $FA['Total_IVA_NC'];
		        Grabar_Abonos($TA);


		        if( $parametros['TxtConcepto'] == ""){ $parametros['TxtConcepto'] = G_NINGUNO;}
		        
		        $resp = $this->modelo->Actualizar_facturas_trans_abonos($parametros['TxtConcepto'] ,$FA);


		        if(($FA['SubTotal_NC'] + $FA['Total_IVA_NC']) > 0 && strLen($FA['Autorizacion_NC']) >= 13)
		        { 

		        	  $resp = $this->sri->SRI_Crear_Clave_Acceso_Nota_Credito($FA); 

		        	  // print_r($FA);die();

		        	  // crea pdf
		        	  $this->modelo->pdf_nota_credito($FA);
		        	  $clave = $this->sri->Clave_acceso($FA['Fecha_NC'],'04',$FA['Serie_NC'],$FA['Nota_Credito']);		        	 
		        	 return array('respuesta'=>$resp,'pdf'=>$FA['Serie_NC'].'-'.generaCeros($FA['Nota_Credito'],7),'clave'=>$clave);
		        	  // return $resp;
		        	//genera aqui el xml
		        
		  			}

			        $Ln_No = 0;
			        $this->modelo->delete_asientonNC();		

			        // hay que generar esta funcion o proceso almacenado        
			        // Actualizar_Saldos_Facturas_SP($FA['TC'],$FA['Serie'],$FA['Factura']);

			        return array('respuesta'=>1,'pdf'=>$FA['Serie_NC'].'-'.generaCeros($FA['Nota_Credito'],7),'clave'=>'');

			        // esto pasasr avista
			        
			        // Listar_Facturas_Pendientes_NC();
			        // Listar_Articulos_Malla
			        // RatonNormal
			        // MsgBox "Proceso Terminado con éxito"
			        // MBoxFecha.SetFocus
				    
			}else
			{
				return array('respuesta'=>5);
			}
	}

	function generar_pdf()
	{
	/*	$TFA['TC'] = 'NC';
		$TFA['Serie'] = '001003';
		$TFA['Autorizacion'] = '0604202201070216417900110010030000006691234567814';
		$TFA['Factura'] = '669';
		$TFA['Serie_NC'] = '001003';
		$TFA['Nota_Credito'] = '71';
		$TFA['CodigoC'] = '1792558662';

		$TFA['Fecha_NC'] = '2012-12-03';

		$TFA['Fecha'] = '2012-01-03';
		$TFA['Autorizacion_NC'] = '0902202304070216417900110010030000000711234567818';
		$TFA['ClaveAcceso_NC']  = '0902202304070216417900110010030000000711234567818';
		$TFA['Porc_IVA'] = '12';
		$TFA['Descuento']=0;
		$TFA['Descuento2'] = 0;
		$TFA['IVA'] = '0';
		$TFA['Total_MN'] = 0;
		$TFA['Nota'] = '- Nota de Crédito de: VACA PRIETO WALTER JALIL';

*/
		//$FA['Autorizacion_NC'] = $parametros['TextBanco'];
		//

		

		 $this->modelo->pdf_nota_credito($TFA);
	}
		        	 
}
?>