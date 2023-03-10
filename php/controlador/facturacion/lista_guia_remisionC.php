<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/lista_guia_remisionM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/punto_ventaM.php");
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
if(!class_exists('enviar_emails'))
{
	require(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
}
require(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

$controlador = new lista_guia_remisionC();

if(isset($_GET['tabla']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}

if(isset($_GET['autorizar_nota']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->autorizar_sri($parametros));
}

if(isset($_GET['Ver_guia_remision']))
{
  $controlador->ver_guia_remision_pdf($_GET['tc'],$_GET['serie'],$_GET['factura'],$_GET['Auto'],$_GET['AutoGR']);
}

if(isset($_GET['enviar_email_detalle']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->enviar_email_detalle($parametros));
}

if(isset($_GET['descargar_guia']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_guia($parametros));
}

if(isset($_GET['descargar_xml']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->descargar_xml($parametros));
}
if(isset($_GET['guardarLineas']))
{
	$parametros = $_GET;
	$linea = $_POST['lineas'];
    echo json_encode($controlador->guardarLineas($parametros,$linea));
}
if(isset($_GET['cargarLineas']))
{
  // print_r('e');die();
  $datos = $controlador->cargaLineas();
  echo json_encode($datos);
}
if(isset($_GET['Eliminar']))
{
  // print_r('e');die();
  $datos = $controlador->Eliminar($_POST['cod']);
  echo json_encode($datos);
}
if(isset($_GET['guardarFactura']))
{
	$parametros = $_POST;
  echo json_encode($controlador->guardarFactura($parametros));
}
if(isset($_GET['limpiar_grid']))
{
  // print_r('e');die();
  $datos = $controlador->limpiar_grid();
  echo json_encode($datos);
}



/**
 * 
 */
class lista_guia_remisionC
{	
	private $modelo;
    private $email;
    public $pdf;
    private $punto_venta;
    
	public function __construct(){
    	$this->modelo = new lista_guia_remisionM();
		$this->pdf = new cabecera_pdf();
		$this->email = new enviar_emails();
		$this->empresaGeneral = Empresa_data();
		$this->sri = new autorizacion_sri();
		$this->punto_venta = new punto_ventaM();
    }

   function tabla_facturas($parametros)
    {

    	// print_r($parametros);die();
    	$codigo = $parametros['ci'];
    	$tbl = $this->modelo->guia_remision_emitidas_tabla($codigo,$parametros['desde'],$parametros['hasta'],$parametros['serie']);
    	$tr='';
    	foreach ($tbl as $key => $value) {
    		 $exis = $this->sri->catalogo_lineas('GR',$value['Serie_GR']);
    		 $autorizar = '';$anular = '';
    		 $cli_data = Cliente($value['CodigoC']);
    		 $email = '';
    		 if(count($cli_data)>0)
    		 {
    		 	 if($cli_data[0]['Email']!='.' && $cli_data[0]['Email']!='')
    		 	 {
    		 	 	 $email.=$cli_data[0]['Email'].',';
    		 	 }
    		 	 if($cli_data[0]['EmailR']!='.' && $cli_data[0]['EmailR']!='')
    		 	 {
    		 	 	 $email.=$cli_data[0]['EmailR'].',';
    		 	 }
    		 	 if($cli_data[0]['Email2']!='.' && $cli_data[0]['Email2']!='')
    		 	 {
    		 	 	 $email.=$cli_data[0]['Email2'].',';
    		 	 }
    		 }
    		 // print_r($exis);die();$retencion,$numero,$serie_r
    		$tr.='<tr>
            <td>
            <div class="input-group-btn">
					<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones
					<span class="fa fa-caret-down"></span></button>
					<ul class="dropdown-menu">
					<li><a href="#" onclick="Ver_guia_remision(\''.$value['TC'].'\',\''.$value['Serie'].'\',\''.$value['Factura'].'\',\''.$value['Autorizacion'].'\',\''.$value['Autorizacion_GR'].'\')"><i class="fa fa-eye"></i> Ver  Guia de Remision</a></li>';
					if(count($exis)>0 && strlen($value['Autorizacion_GR'])==13)
					{
						$tr.='<li><a href="#" onclick="autorizar(\''.$value['Remision'].'\',\''.$value['Serie_GR'].'\',\''.$value['FechaGRE']->format('Y-m-d').'\')" ><i class="fa fa-paper-plane"></i>Autorizar</a></li>';
					}else if(count($exis)==0 && strlen($value['Autorizacion_GR'])==13)
					{
						$tr.='<li><a class="btn-danger"><i class="fa fa-info"></i>Para autorizar Asigne en catalo de lineas la serie:'.$value['Serie_GR'].'</a></li>';
					}
				/*	if($value['T']!='A')
					{
						$tr.='<li><a href="#" onclick="anular_factura(\''.$value['Remision'].'\',\''.$value['Serie_GR'].'\',\''.$value['CodigoC'].'\')"><i class="fa fa-times-circle"></i>Anular Nota de credito</a></li>';
					}*/
					$tr.='<li><a href="#" onclick=" modal_email_guia(\''.$value['Remision'].'\',\''.$value['Serie_GR'].'\',\''.$value['Factura'].'\',\''.$value['Serie'].'\',\''.$value['Autorizacion_GR'].'\',\''.$value['Autorizacion'].'\',\''.$email.'\')"><i class="fa fa-envelope"></i> Enviar  Guia de Remision por email</a></li>
					<li><a href="#" onclick="descargar_guia(\''.$value['Factura'].'\',\''.$value['Serie'].'\',\''.$value['Autorizacion'].'\',\''.$value['Autorizacion_GR'].'\',\''.$value['Remision'].'\',\''.$value['Serie_GR'].'\')"><i class="fa fa-download"></i> Descargar Guia de Remision</a></li>';
					if(strlen($value['Autorizacion_GR'])>13)
					{
					 $tr.='<li><a href="#" onclick="descargar_xml(\''.$value['Autorizacion_GR'].'\')"><i class="fa fa-download"></i> Descargar XML</a></li>';
					}
					 $tr.='
					</ul>
			</div>


            </td>
            <td>'.$cli_data[0]['Cliente'].'</td>
            <td>'.$value['TC'].'</td>
            <td>'.$value['Serie_GR'].'</td>
            <td>'.$value['Autorizacion_GR'].'</td>
            <td>'.$value['Remision'].'</td>
            <td>'.$value['FechaGRE']->format('Y-m-d').'</td>
            <td class="text-right">'.$value['Factura'].'</td>
            <td class="text-right">'.$value['Serie'].'</td>
            <td class="text-right">'.$value['Autorizacion'].'</td>
            <td class="text-right">'.$value['CiudadGRI'].'</td>
            <td class="text-right">'.$value['CiudadGRF'].'</td>
            <td class="text-right">'.$value['Placa_Vehiculo'].'</td>
            <td>'.$cli_data[0]['CI_RUC'].'</td>
          </tr>';
    	}

    	// print_r($tr);die();

    	return $tr;
    }



    function autorizar_sri($parametros)
    {
    	// print_r($parametros);die();
    	$datos = $this->modelo->guia_remision_emitidas_tabla($codigo=false,$desde=false,$hasta=false,$parametros['serie'],$parametros['nota']);

    	$TFA['Serie_NC'] = $parametros['serie'];
		$TFA['Nota_Credito'] = $parametros['nota'];
		$TFA['Serie'] = $datos[0]['Serie'];
		$TFA['TC'] = $datos[0]['Serie'];
		$TFA['Factura'] = $datos[0]['Factura'];
		$TFA['Porc_NC'] = $datos[0]['Porc_IVA'];
		$TFA['Autorizacion'] = $datos[0]['Autorizacion'];	
		$TFA['Fecha'] = $datos[0]['FechaF'];		
		$TFA['Fecha_NC'] = $datos[0]['Fecha']->format('Y-m-d');

		$TFA['Cod_Ejec'] = $datos[0]['Cod_Ejec'];
		$TFA['CodigoU'] = $datos[0]['CodigoU'];
		$TFA['Tipo_Pago'] = $datos[0]['Tipo_Pago'];
		$TFA['Cod_CxC'] = $datos[0]['Cod_CxC']	;
		$TFA['TB'] = $datos[0]['TB'];
		$TFA['RUC_CI'] = $datos[0]['CI_RUC'];
		$TFA['Cliente'] = $datos[0]['Cliente'];
		$TFA['Razon_Social'] = $datos[0]['Cliente'];


        $res = $this->sri->SRI_Crear_Clave_Acceso_Nota_Credito($TFA);
       $clave = $this->sri->Clave_acceso($TFA['Fecha_NC'],'04',$TFA['Serie_NC'],$TFA['Nota_Credito']);		        	 
		return array('respuesta'=>$res,'pdf'=>$TFA['Serie_NC'].'-'.generaCeros($TFA['Nota_Credito'],7),'clave'=>$clave);


        // return $res;

    }

    function ver_guia_remision_pdf($tc,$serie,$factura,$Auto,$AutoGR)
    {
    	$FA = $this->modelo->factura($factura,$serie,$Auto);
    	$TFA['TC'] = $tc;
		$TFA['Serie'] = $serie;
		$TFA['Autorizacion'] = $Auto;
		$TFA['Factura'] = $factura;
		$TFA['Autorizacion_GR'] = $AutoGR;
		$TFA['CodigoC'] = $FA[0]['CodigoC']; 
      	$this->punto_venta->pdf_guia_remision_elec($TFA,$TFA['Autorizacion_GR'],$periodo=false,0,0);
	 	
   
    }

     function descargar_guia($parametros)
    {

    	$FA = $this->modelo->factura($parametros['factura'],$parametros['serie'],$parametros['autorizacion']);
    	$TFA['TC'] = $FA[0]['TC'];
		$TFA['Serie'] = $FA[0]['Serie'];
		$TFA['Autorizacion'] = $FA[0]['Autorizacion'];
		$TFA['Factura'] = $parametros['factura'];
		$TFA['Autorizacion_GR'] = $parametros['autorizacion_gr'];
		$TFA['CodigoC'] = $FA[0]['CodigoC']; 
      	$this->punto_venta->pdf_guia_remision_elec($TFA,$TFA['Autorizacion_GR'],$periodo=false,0,1);
	 	
     	
       return $parametros['serie_gr'].'-'.generaCeros($parametros['guia'],7).'.pdf';
    }

     function descargar_xml($parametros)
    {
    	$rutaA = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$parametros['xml'].'.xml';

    	$rutaB = 'comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$parametros['xml'].'.xml';
    	if(file_exists($rutaA))
    	{
    		return array('ruta'=>$rutaB,'xml'=>$parametros['xml'].'.xml');
    	}else
    	{
    		$this->sri->generar_carpetas(generaCeros($_SESSION['INGRESO']['IDEntidad'],3),generaCeros($_SESSION['INGRESO']['item'],3));

    		$docs = $this->modelo->trans_documentos($parametros['xml']);
    		if(count($docs)>0)
    		{


    			$contenido = $docs[0]['Documento_Autorizado'];
					$archivo = fopen($rutaA,'a');
					fputs($archivo,$contenido);
					fclose($archivo);
    			return array('ruta'=>$rutaB,'xml'=>$parametros['xml'].'.xml');
    		}else
    		{
    			return -1;
    		}
    	}
    }

    function enviar_email_detalle($parametros)
    {
    	$to_correo = substr($parametros['to'],0,-1);
    	$cuerpo_correo = $parametros['cuerpo'];
    	$titulo_correo = $parametros['titulo'];


    	// print_r($parametros);die();

    	$FA = $this->modelo->factura($parametros['factura'],$parametros['serie'],$parametros['autoriza']);
    	$TFA['TC'] = $FA[0]['TC'];
		$TFA['Serie'] = $FA[0]['Serie'];
		$TFA['Autorizacion'] = $FA[0]['Autorizacion'];
		$TFA['Factura'] = $parametros['factura'];
		$TFA['Autorizacion_GR'] = $parametros['autorizagr'];
		$TFA['CodigoC'] = $FA[0]['CodigoC']; 
      	$this->punto_venta->pdf_guia_remision_elec($TFA,$TFA['Autorizacion_GR'],$periodo=false,0,1);
    	$archivos[0] =$parametros['seriegr'].'-'.generaCeros($parametros['remision'],7).'.pdf';

    	$autorizar = $parametros['autorizagr'];
// print_r('expression');die();
    	
    	$rutaA = dirname(__DIR__,2).'/comprobantes/entidades/entidad_'.generaCeros($_SESSION['INGRESO']['IDEntidad'],3).'/CE'.generaCeros($_SESSION['INGRESO']['item'],3).'/Autorizados/'.$autorizar.'.xml';

    		// print_r($rutaA);die();
    	if(file_exists($rutaA))
    	{
    		$archivos[1] = $autorizar.'.xml';
    		// print_r($archivos);die();
    	}else
    	{
    		$this->sri->generar_carpetas(generaCeros($_SESSION['INGRESO']['IDEntidad'],3),generaCeros($_SESSION['INGRESO']['item'],3));

    		$docs = $this->modelo->trans_documentos($autorizar);
    		if(count($docs)>0)
    		{

    			$contenido = $docs[0]['Documento_Autorizado'];
					$archivo = fopen($rutaA,'a');
					fputs($archivo,$contenido);
					fclose($archivo);
    			$archivos[1] = $autorizar.'.xml';
    		}
    	}

    	// print_r($archivo);die();
    	$cuerpo_correo = '
Este correo electronico fue generado automaticamente a usted desde El Sistema Financiero Contable DiskCover System, porque figura como correo electronico alternativo de '.$_SESSION['INGRESO']['Razon_Social'].'. Nosotros respetamos su privacidad y solamente se utiliza este medio para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su informacion personal fuera de nuestra empresa. Este mensaje fue procesado por un funcionario que forma parte de la Institucion.

Por la atencion que se de al presente quedo de usted.

Atentamente,

 '.$_SESSION['INGRESO']['Razon_Social'].'

Esta direccion de correo electronico no admite respuestas. En caso de requerir atencion personalizada por parte de un asesor de Servicio al Cliente de VACA PRIETO WALTER JALIL, podra solicitar ayuda mediante los canales oficiales que detallamos a continuación: Telefonos: 026052430 /  Correo: infosistema@diskcoversystem.com.

www.diskcoversystem.com
QUITO - ECUADOR';

    	return  $this->email->enviar_email($archivos,$to_correo,$cuerpo_correo,$titulo_correo,$HTML=false);
    	
    }


  	public function guardarLineas($parametros,$linea){
	    // $this->modelo->deleteAsiento($_POST['codigoCliente']);
        // print_r($linea);
	    // print_r($parametros);die();
	    $num = count($this->modelo->getAsiento());
	    $datos = array();
	    $precio_nuevo = $linea['Precio'];
	    $totalNuevo = number_format($linea['Total'],2,'.','');
	    $producto = $this->modelo->getProductos_datos($linea['productoCod']);
	    $codig_l = explode('_',$parametros['DCSerieGR']);

	      // print_r($producto);
	      // print_r($parametros);die();

			SetAdoAddNew('Detalle_Factura');
			SetAdoFields('TC','GR');
			SetAdoFields('T',$parametros['T']);
			SetAdoFields('Codigo',$producto[0]['Codigo_Inv']);
			SetAdoFields('CodigoL',$codig_l[0]);
			SetAdoFields('Producto',$producto[0]['Producto'] );
			SetAdoFields('Cantidad',number_format($linea['Cantidad'],2,'.','') );
			SetAdoFields('Precio',$precio_nuevo );
			SetAdoFields('Total_Desc',$linea['Total_Desc'] );
			SetAdoFields('Total_Desc2',$linea['Total_Desc2']);
			SetAdoFields('Total',$totalNuevo );
			SetAdoFields('Total_IVA',number_format($linea['Total'] * ($linea['Iva'] / 100),2,'.','') );
			SetAdoFields('Cta','Cuenta' );
			SetAdoFields('Item',$_SESSION['INGRESO']['item']);
			SetAdoFields('CodigoC',$linea['codigoCliente']);
			SetAdoFields('HABIT', G_PENDIENTE);
			SetAdoFields('Mes',date('mm'));
			SetAdoFields('Ticket',$linea['Periodo'] );
			SetAdoFields('CodigoU',$_SESSION['INGRESO']['CodigoU'] );
			SetAdoFields('A_No',$num+1);
			SetAdoFields('Precio2',$linea['Precio']);
			SetAdoFields('Factura',$parametros['txt_num_fac']);
			SetAdoFields('Autorizacion',$parametros['txt_auto_fac']);			
			SetAdoFields('Serie',$parametros['txt_serie_fac']);
			return SetAdoUpdate();
	      
	      
	     // return insert_generico("Detalle_Factura",$dato);
 	}

 	function cargaLineas()
	{
	    $reg = $this->modelo->cargarLineas();
	    $total = 0;
	    foreach ($reg['datos'] as $key => $value) {
	      $total+=$value['Total'];     
	    }
	    return array('tbl'=>$reg['tbl'],'total'=>$total);
	}

	function Eliminar($codigo)
  	{
    	return $this->modelo->limpiarGrid($codigo);
  	}

  	function guardarFactura($parametros)
  	{
  		
	    // print_r($parametros);die();
	    $TFA = array();
	    $ci_comer = explode('_', $parametros['DCRazonSocial']);
	    $codig_l = explode('_',$parametros['DCSerieGR']);
	    $GR = $this->modelo->guia_remision_existente($codigo=false,$desde=false,$hasta=false,$serie=$codig_l[1],$factura=$parametros['LblGuiaR_']);
	    $cliente = Cliente($parametros['codigoCliente'],$grupo = false,$query=false,$clave=false);

	    // print_r($cliente);die();
// print_r($GR);die();
  		if(count($GR)>0)
  		{
        $lc = ReadSetDataNum("GR_SERIE_".$codig_l[1], True, True);
  			return array('resp'=>5,'clave'=>'');
  		}
	    SetAdoAddNew('Facturas_Auxiliares');
			SetAdoFields('Periodo',$_SESSION['INGRESO']['periodo']);
			SetAdoFields('Item',$_SESSION['INGRESO']['item']);
			SetAdoFields('TC','GR');	
			SetAdoFields('Serie',$parametros['txt_serie_fac']);
			SetAdoFields('Factura',$parametros['txt_num_fac']);
			SetAdoFields('Autorizacion',$parametros['txt_auto_fac']);	
			SetAdoFields('Fecha',$parametros['MBoxFechaGRE']);
			SetAdoFields('CodigoC',$parametros['codigoCliente']);
			SetAdoFields('Remision',$parametros['LblGuiaR_']);
			SetAdoFields('Comercial',$parametros['Comercial'] );
			SetAdoFields('CIRUC_Comercial',$ci_comer[0]);
			SetAdoFields('Entrega',$parametros['Entrega'] );
			SetAdoFields('CIRUC_Entrega',$parametros['DCEmpresaEntrega'] );

			SetAdoFields('CiudadGRI',$parametros['DCCiudadI']);
			SetAdoFields('CiudadGRF',$parametros['DCCiudadF'] );
			SetAdoFields('Placa_Vehiculo',$parametros['TxtPlaca'] );
			SetAdoFields('FechaGRE',$parametros['MBoxFechaGRE'] );
			SetAdoFields('FechaGRI',$parametros['MBoxFechaGRI']);
			SetAdoFields('FechaGRF',$parametros['MBoxFechaGRF']);
			SetAdoFields('Pedido',$parametros['TxtPedido']);
			SetAdoFields('Zona',$parametros['TxtZona_'] );
			SetAdoFields('Serie_GR',$codig_l[1]  );
			SetAdoFields('Autorizacion_GR',$parametros['LblAutGuiaRem_']);
			SetAdoFields('Clave_Acceso_GR',$parametros['LblAutGuiaRem_']);
			SetAdoFields('Lugar_Entrega',$parametros['TxtLugarEntrega']);	
			SetAdoUpdate();


			if(strlen($parametros['LblAutGuiaRem_'])>=13)
			{
				// print_r('prin');die();
				$TFA['CodigoC'] = $parametros['codigoCliente'];
				$TFA['TC'] = 'GR';
				$TFA['Serie'] = $parametros['txt_serie_fac'];
				$TFA['Autorizacion'] = $parametros['txt_auto_fac'];
				$TFA['Factura'] = $parametros['txt_num_fac'];
				$FTA['SinFactura'] = 1;
				$TFA['Porc_IVA'] = $_SESSION['INGRESO']['porc'];
				$TFA['Fecha'] = $parametros['MBoxFechaGRE'];
        $TFA['Vencimiento'] = $parametros['MBoxFechaGRE'];

				$TFA['Comercial'] = $parametros['Comercial'];
				$TFA['CIRUCComercial'] =$ci_comer[0];
				$TFA['FechaGRI'] = $parametros['MBoxFechaGRI'];
				$TFA['FechaGRF'] = $parametros['MBoxFechaGRF'];
				$TFA['Placa_Vehiculo'] = $parametros['TxtPlaca'];
				$TFA['CIRUCEntrega'] = $parametros['DCEmpresaEntrega'];
				$TFA['Entrega'] = $parametros['Entrega'];
				$TFA['CiudadGRI'] = $parametros['DCCiudadI'];
				$TFA['CiudadGRF'] = $parametros['DCCiudadF'];
				$TFA['Serie_GR'] = $codig_l[1];
				$TFA['Remision'] = $parametros['LblGuiaR_'];



				$datos = $this->modelo->lineas_guia_remision($codig_l[1],$parametros['LblGuiaR_']);
				$descuento = 0;
				$descuento2 = 0;
				$subtotal = 0;
				$Total = 0;
				$total_iva = 0;
				$con_iva = 0;$sin_iva=0;
				foreach ($datos as $key => $value) {
					$descuento+=$value['Total_Desc'];
					$descuento2+=$value['Total_Desc2'];
					$subtotal+=$value['Total'];
					$Total+=$value['Total'];
					$total_iva+=$value['Total_IVA'];
					if($value['Total_IVA']>0)
					{
						$con_iva+=number_format($value['Cantidad']*$value['Precio'],2,'.','');
					}else
					{
						$sin_iva+=number_format($value['Cantidad']*$value['Precio'],2,'.','');
					}

				}

         $TFA['Imp_Mes'] = '.';
         $TFA['SubTotal'] = $subtotal;
         $TFA['Sin_IVA'] = $sin_iva;
         $TFA['Con_IVA'] = $con_iva;
         $TFA['Descuento'] = $descuento;
         $TFA['Descuento2'] = $descuento2;
         $TFA['Total_IVA'] = $total_iva;
         $TFA['Total_MN'] = $Total;
        $lc = ReadSetDataNum($TFA['TC']."_SERIE_".$TFA['Serie_GR'], True, True);
        $ClaveAcceso_GR = $this->sri->Clave_acceso($TFA['Fecha'],'06',$TFA['Serie_GR'],$TFA['Remision']);
        $TFA['Autorizacion_GR'] = $ClaveAcceso_GR;
 				$respuesta = $this->sri->SRI_Crear_Clave_Acceso_Guia_Remision_sin_factura($TFA);
 				 $TFA['Razon_Social'] = $cliente[0]['Cliente'];
         $TFA['RUC_CI'] = $cliente[0]['CI_RUC'];
         $TFA['Direccion_RS'] = $cliente[0]['Direccion'];

 				$this->punto_venta->pdf_guia_remision_elec_sin_fac($TFA,$TFA['Autorizacion_GR'],$periodo=false,0,1);

 				return array('resp'=>$respuesta,'clave'=>$ClaveAcceso_GR,'pdf'=>$TFA['Serie_GR'].'-'.generaCeros($TFA['Remision'],7));
    	}
  }

    function limpiar_grid()
 	{
    	return $this->modelo->limpiarGrid();
  	}
}


?>