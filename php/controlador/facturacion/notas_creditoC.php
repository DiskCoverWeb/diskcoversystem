<?php 
require(dirname(__DIR__,2).'/modelo/facturacion/notas_creditoM.php');

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
	function __construct()
	{
		$this->modelo = new notas_creditoM(); 
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

	function Listar_Facturas_Pendientes_NC()
	{
		$datos = $this->modelo->Listar_Facturas_Pendientes_NC();
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
			$list[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Concepto']); 
		}
		if(count($list)==0)
		{
			$list[] = array('codigo'=>'','nombre'=>'No exsiten datos');	
		}
		return $list;
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

	function generar_nota_credito($parametros)
	{

		print_r($parametros);die();

		/*
		Dim SubTotalCosto As Currency
		Dim Grupo As String
		    FechaValida MBoxFecha
		   // 'MsgBox CCur(LblTotalDC.Caption) & vbCrLf & CCur(LblSaldo.Caption)
		    If CCur(LblTotalDC.Caption) <= CCur(LblSaldo.Caption) Then
		       If Not ReIngNC Then FA.Nota_Credito = ReadSetDataNum("NC_SERIE_" & FA.Serie_NC, True, True)
		        FA.Fecha_NC = MBoxFecha
		        Contra_Cta = SinEspaciosIzq(DCContraCta)
		        If Len(Contra_Cta) <= 1 Then Contra_Cta = ReadAdoCta("Cta_Devolucion_Ventas")
		        Listar_Articulos_Malla
		        
		        Actualiza_Procesado_Kardex_Factura FA
		        
		        sSQL = "DELETE * " _
		             & "FROM Detalle_Nota_Credito " _
		             & "WHERE Item = '" & NumEmpresa & "' " _
		             & "AND Periodo = '" & Periodo_Contable & "' " _
		             & "AND Serie = '" & FA.Serie_NC & "' " _
		             & "AND Secuencial = " & FA.Nota_Credito & " "
		        Ejecutar_SQL_SP sSQL
		        
		        FA.ClaveAcceso_NC = Ninguno
		        FA.SubTotal_NC = 0
		        FA.Total_IVA_NC = 0
		        FA.Descuento_NC = 0
		        Cantidad = 0
		        If Len(FA.Autorizacion_NC) >= 13 Then TMail.TipoDeEnvio = "CE"
		        With AdoAsiento_NC.Recordset
		         If .RecordCount > 0 Then
		            .MoveFirst
		             Do While Not .EOF
		                FA.SubTotal_NC = FA.SubTotal_NC + .fields("SUBTOTAL")
		                FA.Total_IVA_NC = FA.Total_IVA_NC + .fields("TOTAL_IVA")
		                FA.Descuento_NC = FA.Descuento_NC + .fields("DESCUENTO")
		                SubTotalCosto = Redondear(.fields("SUBTOTAL") / .fields("CANT"), 6)
		               // 'SubTotal = Redondear(.Fields("CANT") * SubTotalCosto, 2)
		                SubTotal = Redondear(.fields("CANT") * .fields("COSTO"), 2)
		                
		               // 'Grabamos el detalle de la NC
		               // 'Cta_Devolucion, , Porc_IVA,
		                SetAdoAddNew "Detalle_Nota_Credito"
		                SetAdoFields "T", Normal
		                SetAdoFields "CodigoC", .fields("Codigo_C")
		                SetAdoFields "Cta_Devolucion", Contra_Cta
		                SetAdoFields "Fecha", FA.Fecha_NC
		                SetAdoFields "Serie", FA.Serie_NC
		                SetAdoFields "Secuencial", FA.Nota_Credito
		                SetAdoFields "Autorizacion", FA.Autorizacion_NC
		                SetAdoFields "Codigo_Inv", .fields("CODIGO")
		                SetAdoFields "Cantidad", .fields("CANT")
		                SetAdoFields "Producto", .fields("PRODUCTO")
		                SetAdoFields "CodBodega", .fields("CodBod")
		                SetAdoFields "Total_IVA", .fields("TOTAL_IVA")
		                SetAdoFields "Precio", .fields("PVP")
		                SetAdoFields "Total", .fields("SUBTOTAL")
		                SetAdoFields "CodMar", .fields("CodMar")
		                SetAdoFields "Cod_Ejec", .fields("Cod_Ejec")
		                SetAdoFields "Porc_C", .fields("Porc_C")
		                SetAdoFields "Porc_IVA", .fields("Porc_IVA")
		                SetAdoFields "Mes_No", .fields("Mes_No")
		                SetAdoFields "Mes", .fields("Mes")
		                SetAdoFields "Anio", .fields("Anio")
		                SetAdoFields "TC", FA.TC
		                SetAdoFields "Serie_FA", FA.Serie
		                SetAdoFields "Factura", FA.Factura
		                SetAdoFields "A_No", CByte(Ln_No)
		                SetAdoUpdate
		                
		               // 'Grabamos en el Kardex la factura
		                If .fields("Ok") Then
		                    SetAdoAddNew "Trans_Kardex"
		                    SetAdoFields "T", Normal
		                    SetAdoFields "TP", Ninguno
		                    SetAdoFields "Numero", 0
		                    SetAdoFields "TC", FA.TC
		                    SetAdoFields "Serie", FA.Serie
		                    SetAdoFields "Fecha", FA.Fecha_NC
		                    SetAdoFields "Factura", FA.Factura
		                    SetAdoFields "Codigo_P", FA.CodigoC
		                    SetAdoFields "CodigoL", FA.Cod_CxC
		                    SetAdoFields "Codigo_Inv", .fields("CODIGO")
		                    SetAdoFields "Total_IVA", .fields("Total_IVA")
		                    SetAdoFields "Entrada", .fields("CANT")
		                    SetAdoFields "PVP", .fields("PVP") 'SubTotalCosto
		                    SetAdoFields "Valor_Unitario", .fields("COSTO") 'SubTotalCosto
		                    SetAdoFields "Costo", .fields("COSTO")
		                    SetAdoFields "Valor_Total", Redondear(.fields("CANT") * .fields("COSTO"), 2)
		                    SetAdoFields "Total", Redondear(.fields("CANT") * .fields("COSTO"), 2)
		                    SetAdoFields "Descuento", .fields("DESCUENTO")
		                    SetAdoFields "Detalle", "NC: " + FA.Serie_NC + "-" + Format(FA.Nota_Credito, "000000000") + " -" + MidStrg(FA.Cliente, 1, 79)
		                    SetAdoFields "Cta_Inv", .fields("Cta_Inventario")
		                    SetAdoFields "Contra_Cta", .fields("Cta_Costo")
		                    SetAdoFields "CodBodega", .fields("CodBod")
		                    SetAdoFields "CodMarca", .fields("CodMar")
		                    SetAdoFields "Item", NumEmpresa
		                    SetAdoFields "Periodo", Periodo_Contable
		                    SetAdoFields "CodigoU", CodigoUsuario
		                    SetAdoUpdate
		                   // 'MsgBox "Grabado"
		                End If
		               .MoveNext
		             Loop
		         End If
		        End With
		        
		        TA.T = Normal
		        TA.TP = FA.TC
		        TA.Serie = FA.Serie
		        TA.Factura = FA.Factura
		        TA.Autorizacion = FA.Autorizacion
		        TA.Fecha = MBoxFecha
		        TA.CodigoC = FA.CodigoC
		        TA.Cta_CxP = FA.Cta_CxP
		        TA.Cta = Contra_Cta
		        
		        TA.Serie_NC = FA.Serie_NC
		        TA.Autorizacion_NC = FA.Autorizacion_NC
		        TA.Nota_Credito = FA.Nota_Credito
		        
		        TA.Banco = "NOTA DE CREDITO"
		        TA.Cheque = "VENTAS SIN IVA"
		        TA.Abono = Total_Sin_IVA - Total_Desc
		        Grabar_Abonos TA
		        
		        TA.Banco = "NOTA DE CREDITO"
		        TA.Cheque = "VENTAS CON IVA"
		        TA.Abono = Total_Con_IVA - Total_Desc2
		        Grabar_Abonos TA
		        
		        TA.Cta = Cta_IVA
		        TA.Banco = "NOTA DE CREDITO"
		        TA.Cheque = "I.V.A."
		        TA.Abono = FA.Total_IVA_NC
		        Grabar_Abonos TA
		        If TxtConcepto = "" Then TxtConcepto = Ninguno
		        
		        sSQL = "UPDATE Facturas " _
		             & "SET Nota = '" & TxtConcepto & "' " _
		             & "WHERE Item = '" & NumEmpresa & "' " _
		             & "AND Periodo = '" & Periodo_Contable & "' " _
		             & "AND Factura = " & FA.Factura & " " _
		             & "AND TC = '" & FA.TC & "' " _
		             & "AND Serie = '" & FA.Serie & "' " _
		             & "AND Autorizacion = '" & FA.Autorizacion & "' "
		        Ejecutar_SQL_SP sSQL
		            
		        sSQL = "UPDATE Trans_Abonos " _
		             & "SET Serie_NC = '" & FA.Serie_NC & "', " _
		             & "Autorizacion_NC = '" & FA.Autorizacion_NC & "', " _
		             & "Secuencial_NC = '" & FA.Nota_Credito & "', " _
		             & "Clave_Acceso_NC = '" & Ninguno & "', " _
		             & "Estado_SRI_NC = 'CG' " _
		             & "WHERE Item = '" & NumEmpresa & "' " _
		             & "AND Periodo = '" & Periodo_Contable & "' " _
		             & "AND Factura = " & FA.Factura & " " _
		             & "AND TP = '" & FA.TC & "' " _
		             & "AND Serie = '" & FA.Serie & "' " _
		             & "AND Autorizacion = '" & FA.Autorizacion & "' "
		        Ejecutar_SQL_SP sSQL
		        If ((FA.SubTotal_NC + FA.Total_IVA_NC) > 0) And Len(FA.Autorizacion_NC) >= 13 Then SRI_Crear_Clave_Acceso_Nota_Credito FA, True
		        
		    // ''''  If SaldoPendiente + SubTotal_IVA > 0 Then
		    // ''''     Mensajes = "Esta seguro que desea proceder," & vbCrLf _
		    // ''''              & "con la Nota de Credito"
		    // ''''     Titulo = "FORMULARIO DE NC"
		    // ''''     If BoxMensaje = vbYes Then
		    // ''''        RatonReloj
		    // ''''        sSQL = "SELECT * " _
		    // ''''             & "FROM Catalogo_CxCxP " _
		    // ''''             & "WHERE Item = '" & NumEmpresa & "' " _
		    // ''''             & "AND Periodo = '" & Periodo_Contable & "' " _
		    // ''''             & "AND Codigo = '" & CodigoCliente & "' " _
		    // ''''             & "AND Cta = '" & TA.Cta_CxP & "' " _
		    // ''''             & "AND TC = 'P' "
		    // ''''        Select_Adodc AdoComision, sSQL
		    // ''''        With AdoComision.Recordset
		    // ''''         If .RecordCount <= 0 Then
		    // ''''             SetAddNew AdoComision
		    // ''''             SetFields AdoComision, "Item", NumEmpresa
		    // ''''             SetFields AdoComision, "Periodo", Periodo_Contable
		    // ''''             SetFields AdoComision, "Codigo", CodigoCliente
		    // ''''             SetFields AdoComision, "Cta", TA.Cta_CxP
		    // ''''             SetFields AdoComision, "TC", "P"
		    // ''''             SetUpdate AdoComision
		    // ''''         End If
		    // ''''        End With
		        Ln_No = 0
		        sSQL = "DELETE * " _
		             & "FROM Asiento_NC " _
		             & "WHERE Item = '" & NumEmpresa & "' " _
		             & "AND CodigoU = '" & CodigoUsuario & "' "
		        Ejecutar_SQL_SP sSQL
		        
		        Actualizar_Saldos_Facturas_SP FA.TC, FA.Serie, FA.Factura
		        
		        Listar_Facturas_Pendientes_NC

		        Listar_Articulos_Malla
		        RatonNormal
		        MsgBox "Proceso Terminado con éxito"
		        MBoxFecha.SetFocus
		    Else
		        RatonNormal
		        MsgBox "No se puede proceder, El Saldo Pendiente es menor que el total de la Nota de Credito"
		        TxtAutorizacion.SetFocus
		    End If*/
	}
}
?>