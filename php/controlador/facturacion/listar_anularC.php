<?php 
require_once(dirname(__DIR__,2)."/modelo/facturacion/listar_anularM.php");

/**
 * 
 */
$controlador =  new listar_anularC();
if(isset($_GET['DCTipo']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->DCTipo());
}
if(isset($_GET['DCSerie']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCSerie($parametros));
}
if(isset($_GET['DCFact']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCFact($parametros));
}
if(isset($_GET['detalle_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->detalle_factura($parametros));
}
if(isset($_GET['abonos_fac']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->abonos_factura($parametros));
}
if(isset($_GET['guias']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guias($parametros));
}
if(isset($_GET['contabilizacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->contabilizacion($parametros));
}
if(isset($_GET['resultado_sri']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->resultado_sri($parametros));
}
if(isset($_GET['anular_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->anular_factura($parametros));
}

class listar_anularC
{
	private $modelo;
	
	function __construct()
	{
		$this->modelo = new listar_anularM();
	}

	function DCTipo()
	{
		// print_r($parametro);die();
		$datos = $this->modelo->DCTipo();
		$tc = array();
		foreach ($datos as $key => $value) {
			$tc[] = array('codigo'=>$value['TC'],'nombre'=>$value['TC']);
		}
		return $tc; 
	}

	function DCSerie($parametros)
	{
		$datos = $this->modelo->DCSerie($parametros['tc']);
		$serie = array();
		foreach ($datos as $key => $value) {
			$serie[] = array('codigo'=>$value['Serie'],'nombre'=>$value['Serie']);
		}
		return $serie; 
	}
	function DCFact($parametros)
	{
		$datos = $this->modelo->Listar_Factura_NotaVentas($parametros['tc'],$parametros['serie']);
		$DCFact = array();
		$DCFact[] = array('codigo'=>'','nombre'=>'Seleccione');
		foreach ($datos as $key => $value) {
			$DCFact[] = array('codigo'=>$value['Autorizacion'],'nombre'=>$value['Factura']);
		}
		return $DCFact; 
	}

	function  detalle_factura($parametros)
	{

		// print_r($parametros);die();
		sp_Actualizar_Saldos_Facturas($parametros['tc'],$parametros['serie'],$parametros['factura']);
		$TxtXML = "";
		// DGDetalle.Visible = False
		// DGDetalle.BackColor = &H80000005
		// 'Volvemos a recalcular los totales de la factura


		$TFA = $this->modelo->Listar_Factura_NotaVentas_all($parametros['tc'],$parametros['serie'],$parametros['factura'],$parametros['Autorizacion']);
		$FA = $TFA[0];
		// print_r($TFA);die();
	 	$FA = Leer_Datos_FA_NV($FA);
	 	// print_r($FA);die();
 		// 'Procesamos Factura
		  if($FA['Si_Existe_Doc']){
		    // 'Consultamos el detalle de la factura
		     $lineas = $this->modelo->detalle_factura($FA,1);
		     return array('FA'=>$FA,'detalle'=>$lineas);

		     // SQLDec = "Precio " & CStr(Dec_PVP) & "|Total 2|Total_IVA 4|."
		     // Select_Adodc_Grid DGDetalle, AdoDetalle, SQL2, SQLDec
		     // DGDetalle.Visible = True
		    // 'Recolectamos los item de la factura a buscar
		    /* 
		     FechaComp = FA.Fecha
		    
		    // 'LabelFormaPa.Caption = .Fields("Forma_Pago")
		     LabelServicio.Caption = Format$(FA.Servicio, "#,##0.00")
		     LabelConIVA.Caption = Format$(FA.Con_IVA, "#,##0.00")
		     LabelSubTotal.Caption = Format$(FA.Sin_IVA, "#,##0.00")
		     LabelDesc.Caption = Format$(FA.Descuento + FA.Descuento2, "#,##0.00")
		     
		     LabelIVA.Caption = Format$(FA.Total_IVA, "#,##0.00")
		     LabelServicio.Caption = Format$(FA.Servicio, "#,##0.00")
		     LabelTotal.Caption = Format$(FA.Total_MN, "#,##0.00")
		     LabelSaldoAct.Caption = Format$(FA.Saldo_MN, "#,##0.00")
		    */
		    // 'Consultamos los pagos Interes de Tarjetas y Abonos de Bancos con efectivo
		    // 'Procesamos el Saldo de la Factura
		  }else{
		  	/*
		     DGDetalle.Visible = True
		     RatonNormal
		     MsgBox "Esta Factura no existe."
		     DCTipo.SetFocus
		     */
		  }
  // SSTabDetalle.Tab = 0
	}

	function abonos_factura($parametros)
	{
		// print_r($parametros);die();
		return $this->modelo->abonos_factura($parametros,1);
	}
	function guias($parametros)
	{
		return $this->modelo-> guias($parametros,1);
	}
	function contabilizacion($parametros)
	{
		// print_r($parametros);die();
		 
		$this->modelo->eliminar_asiento();
        $Trans_No = 253;
        Insertar_Ctas_Cierre_SP("CXC", 1,$Trans_No);
        $FA = Leer_Datos_FA_NV($parametros);    
        $datos = $this->modelo->contabilizacion($parametros);
        // print_r($datos);die();
        if(count($datos)>0)
        {
        	foreach ($datos as $key => $value) {
        		// print_r($value);die();
        	   $Valor = $value["TTotal"]-$value["TTotal_Desc"]- $value["TTotal_Desc2"] +$value["TTotal_IVA"];
               Insertar_Ctas_Cierre_SP($FA['Cta_CxP'], $Valor,$Trans_No);
               Insertar_Ctas_Cierre_SP($value["Cta_Venta"], -1*$value["TTotal"],$Trans_No);
               Insertar_Ctas_Cierre_SP($_SESSION['SETEOS']['Cta_Desc'], $value["TTotal_Desc"],$Trans_No);
               Insertar_Ctas_Cierre_SP($_SESSION['SETEOS']['Cta_Desc2'],$value["TTotal_Desc2"],$Trans_No);
               Insertar_Ctas_Cierre_SP($_SESSION['SETEOS']['Cta_IVA'], -1*$value["TTotal_IVA"],$Trans_No);
        	}
        }         
           
        $Trans_No = 254;
        Insertar_Ctas_Cierre_SP("ABONO",1,$Trans_No);
        $AdoAuxDB = $this->modelo->AdoAuxDB($FA);
        if(count($AdoAuxDB)>0)
        {
        	foreach ($AdoAuxDB as $key => $value) {
        		Insertar_Ctas_Cierre_SP($value["Cta"], $value["TAbono"],$Trans_No);
                Insertar_Ctas_Cierre_SP($value["Cta_CxP"], -1*$value["TAbono"],$Trans_No);
        	}                         
        }
         $Debe = 0;
         $Haber = 0;
         $DGDetalle = $this->modelo->DGDetalle();
         if(count($DGDetalle)>0)
         {
         	foreach ($DGDetalle as $key => $value) {
         		// print_r($value);die();
         		$Debe = $Debe + number_format($value["DEBE"],2,'.','');
                $Haber = $Haber + number_format($value["HABER"],2,'.','');
         	}
         }
         $tbl= $this->modelo->DGDetalle($tabla=1);

         return array('LabelDebe'=>$Debe,'LabelHaber'=>$Haber,'LblDiferencia'=>$Debe-$Haber,'tbl'=>$tbl);     


	}
	function resultado_sri($parametros)
	{
		 // 'Listamos el error en la autorizacion del documento si tuvier error
		$TxtXML = '';
		$FA = Leer_Datos_FA_NV($parametros);    		
       if(strlen($FA['Autorizacion']) >= 13)
       {
         // CheqClaveAcceso.Caption = "Clave de Accceso: " & FA.TC & " "
          $Cadena = SRI_Mensaje_Error($FA['ClaveAcceso']);
          if(strlen($Cadena) > 1){
             $TxtXML = "Clave de Accceso: ".$FA["ClaveAcceso"]." <br> ".
                    "--------------------------------------------------- <br>".
                    $Cadena."<br> ".
                    "---------------------------------------------------";
          }else{
             $TxtXML = "Clave de Accceso: ".$FA["ClaveAcceso"]." <br>".
             	"--------------------------------------------------------------- <br> ".
               "OK: No existe ningun error en su aprobacion  <br> ".
               "----------------------------------------------------------------";
          }
       }
       return $TxtXML;
	}

	function anular_factura($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->anular_factura($parametros);
		if(count($datos)>0)
		{
			if($datos[0]['T']=='A')
			{
				return -1;
			}else
			{
				return 1;				
			}
		}
		print_r($datos);die();
		/*
		 
        With AdoFactura.Recordset
         If .RecordCount > 0 Then
             If .fields("T") = "A" Then
                 MsgBox "Esta Factura ya esta anulada"
             ElseIf ClaveAuxiliar Then
                 Si_No = False
                 FAnulacion.Show
             End If
         End If
        End With
		*/
	}
}
?>