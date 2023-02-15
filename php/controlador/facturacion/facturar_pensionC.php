<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturar_pensionM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/catalogo_productosM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
date_default_timezone_set('America/Guayaquil'); 
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new facturar_pensionC();
if(isset($_GET['cliente']))
{
	$query = '';
	if(isset($_GET['q']) && $_GET['q'] != ''  )
	{
		$query = $_GET['q']; 
	}
  if (isset($_GET['total'])) {
    $datos = $controlador->totalClientes();
  }else{
    $datos = $controlador->getClientes($query);
  }
  echo json_encode($controlador->getClientes($query));
}

if(isset($_GET['numFactura']))
{
  $query = '';
  $DCLinea = $_POST['DCLinea'];
  $fact = SinEspaciosIzq($DCLinea);
  $serie = SinEspaciosDer($DCLinea);
  $DCLinea = explode(" ", $DCLinea);
  $autorizacion = $DCLinea[2];
  $codigo = ReadSetDataNum($fact."_SERIE_".$serie, True, False);
  echo json_encode(array('codigo' =>generaCeros($codigo,8),'serie' => $serie,'autorizacion' => $autorizacion));
  exit();
}

if(isset($_GET['catalogo']))
{
	$datos = $controlador->getCatalogoLineas();
  echo json_encode($datos);
}

if(isset($_GET['catalogoProducto']))
{
	$datos = $controlador->getCatalogoProductos();
  echo json_encode($datos);
}

if(isset($_GET['historiaCliente']))
{
  $controlador->historiaCliente();
}

if(isset($_GET['historiaClienteExcel']))
{
  $controlador->historiaClienteExcel($_REQUEST['codigoCliente']);
}

if(isset($_GET['historiaClientePDF']))
{
  $controlador->historiaClientePDF($_REQUEST['codigoCliente']);
}

if(isset($_GET['DeudaPensionPDF']))
{
  $parametros = $_GET['lineas'];
  // print_r($parametros);die();
  $controlador->DeudaPensionPDF($_GET['codigoCliente'],$parametros);
}

if(isset($_GET['enviarCorreo']))
{
  $controlador->enviarCorreo();
}

if(isset($_GET['saldoFavor']))
{
	$controlador->getSaldoFavor();
}

if(isset($_GET['saldoPendiente']))
{
	$controlador->getSaldoPendiente();
}

if(isset($_GET['guardarPension']))
{
	$controlador->guardarFacturaPension();
}

if(isset($_GET['guardarLineas']))
{
  $controlador->guardarLineas();
}

if(isset($_GET['CatalogoProductosByPeriodo']))
{
    //se definen los parametros que deseamos obtener
    $columnas = [
      'Codigo_Inv as id',
      'Producto as text'
    ];
  $controlador->CatalogoProductosByPeriodo($columnas);

}
else if(isset($_GET['GuardarInsPreFacturas']))
{
  //Eliminamos pensiones si los tuviera
  $controlador->deleteClientesFacturacionProductoClienteAnioMes($_POST);
  //Procedemos a insertar las pensiones
  $controlador->insertClientesFacturacionProductoClienteAnioMes($_POST);
  exit();
}
else if(isset($_GET['EliminarInsPreFacturas']))
{
  $controlador->deleteClientesFacturacionProductoClienteAnioMes($_POST, true);
  exit();
}

if(isset($_GET['clienteMatricula']))
{
  echo json_encode($controlador->getClientesMatriculas(@$_GET['codigoCliente']));
}
if(isset($_GET['cargarBancos']))
{
  $columnas = [
      'Codigo as id',
      'Descripcion as text'
    ];

  $filtro = '';
  if(isset($_GET['q']) && $_GET['q'] != ''  )
  {
    $filtro = $_GET['q']; 
  }
  echo json_encode($controlador->getcargarBancos($columnas, @$_GET['id'],$filtro, @$_GET['limit']));
}
if(isset($_GET['DCGrupo_No']))
{
  $query = '';
  if(isset($_GET['q']))
  {
    $query = $_GET['q'];
  }
  echo json_encode($controlador->getDCGrupo($query));
}

if(isset($_GET['DireccionByGrupo']))
{
  echo json_encode($controlador->getCargarDireccionByGrupo(@$_GET['grupo']));
}

class facturar_pensionC
{
  private $facturacion;
	private $catalogoProductosModel;
  private $pdf;


	public function __construct(){
        $this->facturacion = new facturar_pensionM();
        $this->catalogoProductosModel = new catalogo_productosM();
        $this->autorizar_sri = new autorizacion_sri();
        $this->pdf = new cabecera_pdf();
        $this->email = new enviar_emails(); 
        //$this->modelo = new MesaModel();
    }

	public function getClientes($query){
    // Leer_Datos_Cliente_SP($codigo)
		$datos = $this->facturacion->getClientes($query);
		$clientes = [];
		foreach ($datos as $value) {
			$clientes[] = array('id'=>$value['Cliente'],'text'=>$value['Cliente'],'data'=>array('email'=> $value['Email'],'direccion' => $value['Direccion'],'direccion1'=>$value['DireccionT'], 'telefono' =>$value['Telefono'], 'ci_ruc' => $value['CI_RUC'], 'codigo' => $value['Codigo'], 'cliente' => $value['Cliente'], 'grupo' => $value['Grupo'], 'tdCliente' => $value['TD'], 'Archivo_Foto'=> $value['Archivo_Foto'])); //,'dataMatricula'=>$matricula);
		}
    return $clientes;
	}

  public function totalClientes(){
    $datos = $this->facturacion->getClientes('total');
    $total = count($datos);
    echo json_encode(array('registros'=>$total));
    exit();
  }

	public function getCatalogoLineas(){
		$emision = $_POST['fechaEmision'];
		$vencimiento = $_POST['fechaVencimiento'];

    //busco serie_FA en accesos SQLSERVER
    $usuario = $this->facturacion->getSerieUsuario($_SESSION['INGRESO']['CodigoU']);
    // print_r($usuario);die();
    $serie = '.';
    $datos = array();
    if(count($usuario)>0)
      { 
        if(isset($usuario[0]['Serie_FA']))
          {
            $serie = $usuario[0]['Serie_FA'];
          }
      }
    //buscar serie de usuario
   
    if($serie=='.'){ 

      if($_SESSION['INGRESO']['Serie_FA']!='.')
        { 
          $serie = $_SESSION['INGRESO']['Serie_FA'];
        }

    }
    if($serie!='.'){
      // si hay serie busco en catalogo lineas
      $datos = $this->facturacion->getCatalogoLineas($emision,$vencimiento,$serie);
      if(count($datos)==0)
      {
        return array();
      }  
    }else{
      $datos = $this->facturacion->getCatalogoLineas13($emision,$vencimiento);
      if(count($datos)==0)
      {
        return array();
      } 
    }

    $catalogo = [];
    foreach ($datos as $value) {
      $catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC'] ,'text'=>utf8_encode($value['Concepto']));
    }    
    return $catalogo;
	}

	public function getCatalogoProductos(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getCatalogoProductos($codigoCliente);
		$catalogo = [];
		foreach ($datos as $value) {
			$catalogo[] = array('mes'=> utf8_encode($value['Mes']),'codigo'=> utf8_encode($value['Codigo_Inv']),'periodo'=> utf8_encode($value['Periodos']),'producto'=>$value['Producto'],'valor'=> utf8_encode($value['Valor']), 'descuento'=> utf8_encode($value['Descuento']),'descuento2'=> utf8_encode($value['Descuento2']),'iva'=> utf8_encode($value['IVA']),'CodigoL'=> utf8_encode($value['Codigo']),'CodigoL'=> utf8_encode($value['Codigo']));
		}
    return $catalogo;
	}

  public function historiaCliente(){
    $codigoCliente = $_POST['codigoCliente'];
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->facturacion->historiaCliente($codigoCliente);
    $historia = [];
    foreach ($datos as $value) {
      $historia[] = array('TD'=> utf8_encode($value['TD']),'Fecha'=> utf8_encode($value['Fecha']->format('Y-m-d')),'Serie'=> utf8_encode($value['Serie']),'Factura'=> utf8_encode($value['Factura']),'Detalle'=> $value['Detalle'], 'Anio'=> utf8_encode($value['Anio']),'Mes'=> utf8_encode($value['Mes']),'Total'=> utf8_encode($value['Total']),'Abonos'=> utf8_encode($value['Abonos']),'Mes_No'=> utf8_encode($value['Mes_No']),'No'=> utf8_encode($value['No']) );
    }
    echo json_encode($historia);
    exit();
  }

  public function historiaClienteExcel($codigo,$download = true){
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->facturacion->historiaCliente($codigoCliente);

    $tablaHTML =array();
       $tablaHTML[0]['medidas']=array(9,20,9,9,50,9,9,9,9,9,9);
         $tablaHTML[0]['datos']=array('TD','Fecha','Serie','Factura','Detalle','Año','Mes','Total','Abonos','Mes No','No');
         $tablaHTML[0]['tipo'] ='C';
         $pos = 1;
    foreach ($datos as $key => $value) {
       $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
           $tablaHTML[$pos]['datos']=array($value['TD'],$value['Fecha'],$value['Serie'],$value['Factura'],$value['Detalle'],$value['Anio'],$value['Mes'],number_format($value['Total'],2),number_format($value['Abonos'],2),$value['Mes_No'],$value['No']);
           $tablaHTML[$pos]['tipo'] ='N';
           $pos+=1;
    }
    excel_generico($titulo='Historia del cliente',$tablaHTML);

    // historiaClienteExcel($datos,$ti='HistoriaCliente',$camne=null,$b=null,$base=null,$download);
  }

  public function historiaClientePDf($codigo,$download = true){
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->facturacion->historiaCliente($codigoCliente);
    $cli = $this->facturacion->getClientes(false,$codigoCliente);
    // print_r($cli);die();

    $titulo = 'RESUMEN DE CARTERA POR CLIENTES';
    $parametros['desde'] = false;
    $parametros['hasta'] = false;
    $sizetable = 6;
    $mostrar = true;
    $tablaHTML = array();

    $contenido[0]['tipo'] = 'texto';
    $contenido[0]['posicion'] = 'top-tabla';
    $contenido[0]['valor'] = 'Cliente: '.$cli[0]['Cliente'];
    $contenido[0]['estilo'] = 'I';
    $contenido[1]['tipo'] = 'texto';
    $contenido[1]['posicion'] = 'top-tabla';
    $contenido[1]['valor'] = 'Direccion: '.$cli[0]['Direccion'];
    $contenido[1]['estilo'] = 'I';



    $tablaHTML[0]['medidas'] = array(8,18,10,15,60,10,20,15,15,10,10);
    $tablaHTML[0]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L');
    $tablaHTML[0]['datos'] = array('TD','Fecha','Serie','Factura','Detalle','Año','Mes','Total','Abonos','Mes No','No');
    $tablaHTML[0]['borde'] = 1;
    $tablaHTML[0]['estilo'] = 'B';

    $count = 1;
    foreach ($datos as $value) {
      $tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
      $tablaHTML[$count]['alineado'] = array('L','L','L','L','L','L','R','R','R','R','R');
      $tablaHTML[$count]['datos'] = array($value['TD'],$value['Fecha']->format('Y-m-d'),$value['Serie'],$value['Factura'],$value['Detalle'], $value['Anio'],$value['Mes'],number_format($value['Total'],2),number_format($value['Abonos'],2),$value['Mes_No'],$value['No']);
      $tablaHTML[$count]['borde'] = $tablaHTML[0]['borde'];
      $count+=1;
    }
    $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,25,$orientacion='P',$download);
  }

  public function DeudaPensionPDF($codigo,$lineas,$download = true){

    $lin = json_decode($lineas, true);
    // print_r($lin);die();
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    // $datos = $this->facturacion->historiaCliente($codigoCliente);

    $titulo = 'HistoriaCliente';
    $fechaini = false;
    $fechafin = false;
    $sizetable = 8;
    $mostrar = true;
    $tablaHTML = array();


    $tablaHTML[0]['medidas'] = array(20,18,15,40,25,25,25,25);
    $tablaHTML[0]['alineado'] = array('L','L','L','L','R','R','R','R');
    $tablaHTML[0]['datos'] = array('MES','CODIGO','AÑO','PRODUCTO','VALOR','DESCUENTO','DESC. P.P.','TOTAL');
    $tablaHTML[0]['borde'] = 'B';

    $count = 1;
    $total = 0;
    foreach ($lin as $key => $value) {
      $tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
      $tablaHTML[$count]['alineado'] = $tablaHTML[0]['alineado'];
      $tablaHTML[$count]['datos'] = array($value['mes'],$value['cod'],$value['ani'],$value['pro'],number_format($value['val'],2),number_format($value['des'],2),number_format($value['p.p'],2),number_format($value['tot'],2));
      $tablaHTML[$count]['borde'] = 'B';
      $count=$count+1;
      $total+=$value['tot'];
    }
    $count=$count+1;   
    $tablaHTML[$count]['medidas'] = array(128,40,25);
    $tablaHTML[$count]['alineado'] = array('L','R','R');
    $tablaHTML[$count]['datos'] = array('CORTE AL '.date('r'),'TOTAL A PAGAR USD ',number_format($total,2));

    $tablaHTML[$count+1]['medidas'] = array(190);
    $tablaHTML[$count+1]['alineado'] = array('L');
    $tablaHTML[$count+1]['datos'] = array('Los datos presentados en este reporte, reflejan los valores pendientes de pago, por concepto de Pensiones Educativas.');

    $this->pdf->DeudapendientePensionesPDF($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar,$sal_hea_body=30,$orientacion='P',$download=true);                                         
  }



  public function enviarCorreo(){
    //Eliminar archivos temporales
    if (file_exists(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.xlsx')) {
      unlink(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.xlsx');
    }
    if (file_exists(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.pdf')) {
      unlink(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.pdf');
    }
    $this->historiaClientePDf($_REQUEST['codigoCliente'],false);
    $this->historiaClienteExcel($_REQUEST['codigoCliente'],false);
    $archivos[0] = dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.xlsx';
    $archivos[1] = dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.pdf';
    $to_correo = $_REQUEST['email'];
    $titulo_correo = 'Historial de cliente';
    $nombre = 'DiskCover System';
    $cuerpo_correo = 'Estimado (a) ha recibido su historial en formato PDF y EXCEL';
    $cuerpo_correo .= '<br>'.utf8_decode('
    <pre>
      -----------------------------------
      SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO.


      Este correo electrónico fue generado automáticamente del Sistema Financiero Contable DiskCover System a usted porque figura como correo electrónico alternativo de Oblatas de San Francisco de Sales.
      Nosotros respetamos su privacidad y solamente se utiliza este correo electrónico para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su información personal fuera de nuestra empresa. Para obtener más información, comunicate a nuestro Centro de Atención al Cliente Teléfono: 052310304. Este mensaje fue recibido por: DiskCover Sytem.

      Por la atención que se de al presente quedo de usted.


      Esta dirección de correo electrónico no admite respuestas. En caso de requerir atención personalizada por parte de un asesor de servicio al cliente de DiskCover System, Usted podrá solicitar ayuda mediante los canales de atención al cliente oficiales que detallamos a continuación: Telefonos: (+593) 02-321-0051/098-652-4396/099-965-4196/098-910-5300.
      Emails: prisma_net@hotmail.es/diskcover@msn.com.

      www.diskcoversystem.com
      QUITO - ECUADOR</pre>');
    $this->email->enviar_historial($archivos,$to_correo,$cuerpo_correo,$titulo_correo,$nombre);
    exit();
    
  }

	public function getCatalogoCuentas(){
		$datos = $this->facturacion->getCatalogoCuentas();
		$cuentas = [];
    $cuentas[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
			$cuentas[$i] = array('codigo'=>$value['Codigo']."/".$value['TC'],'nombre'=>$value['Codigo']." - ".$value['NomCuenta']);
      $i++;
		}
		return $cuentas;
	}

	public function getNotasCredito(){
		$datos = $this->facturacion->getNotasCredito();
		$cuentas = [];
    $cuentas[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
		foreach ($datos as $value) {
			$cuentas[$i] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Codigo']." - ".$value['NomCuenta']);
      $i++;
		}
		return $cuentas;
	}

  public function getAnticipos(){
    $codigo = Leer_Seteos_Ctas('Cta_Anticipos_Clientes');
    $datos = $this->facturacion->getAnticipos($codigo);
    $cuentas = [];
    $cuentas[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
      $cuentas[$i] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Codigo']." - ".$value['NomCuenta']);
      $i++;
    }
    return $cuentas;
  }

	public function getSaldoFavor(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getSaldoFavor($codigoCliente);
    // print_r($datos);
		// $catalogo = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
		echo json_encode($datos);
		exit();
	}

	public function getSaldoPendiente(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getSaldoPendiente($codigoCliente);
		// $catalogo = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
		echo json_encode($datos);
		exit();
	}

	public function guardarFacturaPension(){

    // print_r($_POST);die();
		$TextRepresentante = $_POST['TextRepresentante'];
		$TxtDireccion = $_POST['TxtDireccion'];
		$TxtTelefono = $_POST['TxtTelefono'];
		$TextFacturaNo = $_POST['TextFacturaNo'];
		$TxtGrupo = $_POST['TxtGrupo'];
  	$TextCI = $_POST['TextCI'];
  	$TD_Rep = $_POST['TD_Rep'];
  	$TxtEmail = $_POST['TxtEmail'];
  	$TxtDirS = $_POST['TxtDirS'];
		$codigoCliente = $_POST['codigoCliente'];
		$update = $_POST['update'];
		$CtaPagoMax = "";
		$ValPagoMax = "";
  	TextoValido($TextRepresentante,"" , true);
  	TextoValido($TxtDireccion, "" , True);
  	TextoValido($TxtTelefono, "" , True);
  	TextoValido($TxtEmail);
  	//cuentas
  	$TextCheque = $_POST['TextCheque'];
  	$DCBanco = $_POST['DCBanco'];
  	$TxtEfectivo = $_POST['TxtEfectivo'];
  	$TxtNC = $_POST['TxtNC'];
  	$DCNC = $_POST['DCNC'];

    $DCDebito = $_POST['DCDebito'];
    $CTipoCta = $_POST['CTipoCta'];
    $TxtCtaNo = $_POST['TxtCtaNo'];
    $MBFecha = $_POST['MBFecha'];
    $CheqPorDeposito = @($_POST['CheqPorDeposito']=='on' || $_POST['CheqPorDeposito'] == '1')?true:false;
  	$Cta_CajaG = 1;
    $Titulo = "Formulario de Grabacion";
    $Mensajes = "Esta Seguro que desea grabar: La Factura No. ".$TextFacturaNo;
    $ValPagoMax = 0;
    $CtaPagoMax = "1";
    if ($ValPagoMax <= intval($TextCheque)) {
     	$ValPagoMax = intval($TextCheque);
      $CtaPagoMax = SinEspaciosIzq($DCBanco);
    }
    if ($ValPagoMax <= intval($TxtEfectivo)) {
	    $ValPagoMax = intval($TxtEfectivo);
	    $CtaPagoMax = $Cta_CajaG;
    }
    if ($ValPagoMax <= intval($TxtNC)) {
     	$ValPagoMax = intval($TxtNC);
      $CtaPagoMax = SinEspaciosIzq($DCNC);
    }
    $Cta_Aux = Leer_Cta_Catalogo($CtaPagoMax);
    if ($Cta_Aux) {
     	$Tipo_Pago = $Cta_Aux['TipoPago'];
    }

   	if ($update) {
   		$updateCliF = $this->facturacion->updateClientesFacturacion($TxtGrupo,$codigoCliente);
   		$updateCli = $this->facturacion->updateClientes($TxtTelefono,$TxtDirS,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente);

      $dataClienteMatricula = $this->facturacion->getClientesMatriculas($codigoCliente);
      if(count($dataClienteMatricula)>0){
        $updateCliM = $this->facturacion->updateClientesMatriculas($TextRepresentante,$TextCI,$TD_Rep,$TxtTelefono,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente,$CTipoCta,$TxtCtaNo,$CheqPorDeposito,UltimoDiaMes2("01/".$MBFecha, 'm/d/Y'),$DCDebito);
      }else{
        $updateCliM = $this->facturacion->insertClientesMatriculas($TextRepresentante,$TextCI,$TD_Rep,$TxtTelefono,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente,$CTipoCta,$TxtCtaNo,$CheqPorDeposito,UltimoDiaMes2("01/".$MBFecha, 'm/d/Y'),$DCDebito);
      }
   	}
    $TC = SinEspaciosIzq($_POST['DCLinea']);
    $serie = SinEspaciosDer($_POST['DCLinea']);
    //traer secuencial de catalogo lineas
   	$TextFacturaNo = ReadSetDataNum($TC."_SERIE_".$serie, True, False);
   	$this->Grabar_FA_Pensiones($_POST,$TextFacturaNo);
	}


	public function Grabar_FA_Pensiones($FA,$TextFacturaNo){
    $codigoCliente = $FA['codigoCliente'];
		//Seteamos los encabezados para las facturas
		$Estudiante['cedula'] = $FA['TextCI'];
		$Estudiante['fonopaga'] = $FA['TxtTelefono'];
  	$Estudiante['pagador'] = $FA['TextRepresentante'];
		$Estudiante['direcpaga'] = $FA['TxtDireccion'];
    $resultado = explode(" ", $FA['DCLinea']);
    $FA['Autorizacion'] = $resultado[2];
    $FA['Cta_CxP'] = $resultado[3];
		//Procedemos a grabar la factura
  	$datos = $this->facturacion->getAsiento();
    $Total_Abonos = $FA['TxtEfectivo']+$FA['TextCheque']+$FA['TxtNCVal']+$FA['saldoFavor']; 
    foreach ($datos as $key => $value) {
       
       $Valor = $value["TOTAL"];
       $Total_Desc = $value["Total_Desc"]+$value["Total_Desc2"];
       $ValorDH = $Valor - $Total_Desc;
       $Codigo = $value["Codigo_Cliente"];
       $Codigo1 = $value["CODIGO"];
       $Codigo2 = $value["Mes"];
       $Codigo3 = $value["HABIT"];
       $Anio1 = $value["TICKET"];
       $ID_Reg = $value["A_No"];
       $Total_Abonos = $Total_Abonos - $ValorDH;
          if($Total_Abonos >= 0){
            $this->facturacion->actualizar_Clientes_Facturacion($Valor,$Anio1,$Codigo,$Codigo1,$Codigo2,$Codigo3);
          }else{
            $Valor = $Valor + $Total_Abonos;
            if($Valor > 0){
              $this->facturacion->actualizar_Clientes_Facturacion2($Total_Abonos,$Total_Desc,$Anio1,$Codigo,$Codigo1,$Codigo2,$Codigo3);
               $Total_Abonos = $Total_Abonos + $Total_Desc;
               $Valor = $Valor - $Total_Desc;
              $this->facturacion->actualizar_asiento_F($Valor,$ID_Reg);
            }else{
              $this->facturacion->deleteAsientoEd($ID_Reg);              
            }
          }
    }


    // print_r($FA);
    // print_r($TotalAbonos);
    // print_r($datos);die();

    foreach ($datos as $key => $value) {
      // print_r($key);
		  $TFA = Calculos_Totales_Factura($codigoCliente);
      $FA['CodigoC'] = $codigoCliente;
      $FA['Tipo_PRN'] = "FM";
      $FA['FacturaNo'] = $TextFacturaNo;
      $FA['Nuevo_Doc'] = true;
      $FA['Factura'] = intval($TextFacturaNo);
      $FA['TC'] = SinEspaciosIzq($FA['DCLinea']);
      $FA['Serie'] = SinEspaciosDer($FA['DCLinea']);
      if (Existe_Factura($FA)) {
        
      }
      $SaldoPendiente = 0;
      $DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
      if ($FA['Nuevo_Doc']) {
        $FA['Factura'] = ReadSetDataNum($FA['TC']."_SERIE_".$FA['Serie'], True, True);
      }
      $SubTotal_NC = $FA['DCNC'];
      $Total_Bancos = $FA['TextCheque'];
      $TotalCajaMN = $FA['Total'] - $Total_Bancos - $SubTotal_NC;
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN + $Total_Bancos + $SubTotal_NC;
      $FA['Total_Abonos'] = $Total_Abonos;
      $FA['T'] = G_PENDIENTE;
      $FA['Saldo_MN'] = $FA['Total'] - $Total_Abonos;
      $FA['Porc_IVA'] = $value['Total_IVA'];
      $FA['Cliente'] = $FA['TextRepresentante'];
      $FA['me'] = $value['HABIT'];
      $TA['me'] = $value['HABIT'];
      $TA['Recibi_de'] = $FA['Cliente'];
      $Cta = SinEspaciosIzq($FA['DCBanco']);
      $Cta1 = SinEspaciosIzq($FA['DCNC']);
      $Valor = $value["TOTAL"];
      $Codigo = $value["Codigo_Cliente"];
      $Codigo1 = $value["CODIGO"];
      $Codigo2 = $value["Mes"];
      $Codigo3 = ".";
      $Anio1 = $value["TICKET"];
      // $this->facturacion->updateClientesFacturacion1($Valor,$Anio1,$Codigo1,$Codigo,$Codigo3,$Codigo2);
      //Grabamos el numero de factura
      Grabar_Factura1($FA);

      //Seteos de Abonos Generales para todos los tipos de abonos
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente']; //codigo cliente
      $TA['Factura'] = $FA['Factura'];
      $TA['Fecha'] = $FA['Fecha'];
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['email'] = $FA['TxtEmail'];
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
     
      //Abono de Factura Banco o Tarjetas
      $TA['Cta'] = $Cta;
      $TA['Banco'] = strtoupper($FA['TxtGrupo'])." - ".$FA['DCBanco'];
      $TA['Cheque'] = $FA['chequeNo'];
      $TA['Abono'] = $Total_Bancos;
      Grabar_Abonos($TA);

      $Cta_CajaG = 1;
      //Abono de Factura
      $TA['Cta'] = $Cta_CajaG;
      $TA['Banco'] = "EFECTIVO MN";
      $TA['Cheque'] = strtoupper($FA['TextCheque']);
      $TA['Abono'] = $TotalCajaMN;
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
      Grabar_Abonos($TA);

      //Forma del Abono SubTotal NC
      if ($SubTotal_NC > 0) {
        $SubTotal_NC = $SubTotal_NC - $TFA['Total_IVA'];
        $TA['Cta'] = $Cta1;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "VENTAS";
        $TA['Abono'] = $SubTotal_NC;
        Grabar_Abonos($TA);
      }
     
      //Forma del Abono IVA NC
      if ($TFA['Total_IVA'] > 0) {
        $TA['Cta'] = $Cta_IVA;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "I.V.A.";
        $TA['Abono'] = $TFA['Total_IVA'];
        Grabar_Abonos($TA);
      }
     
      $TextInteres = 0;
      //Abono de Factura
      $TA['T'] = G_NORMAL;
      $TA['TP'] = "TJ";
      $TACta = $Cta;
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['Banco'] = "INTERES POR TARJETA";
      $TA['Cheque'] =  $FA['TextCheque'];
      $TA['Abono'] = intval($TextInteres);
      $TA['Recibi_de'] = $FA['Cliente'];
      Grabar_Abonos($TA);
       
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente'];

      $TxtEfectivo = "0.00";
      if (strlen($FA['Autorizacion']) >= 13) {
        /*if (!$No_Autorizar) {
        }*/
        //print_r("expression");
        //generar_xml();
        $FA['Desde'] = $FA['Factura'];
        $FA['Hasta'] = $FA['Factura'];
        //Imprimir_Facturas_CxC(FacturasPension, FA, True, False, True, True);
        //SRI_Generar_PDF_FA(FA, True);
      }
      $FA['serie'] = $FA['Serie'];
      $FA['num_fac'] = $FA['Factura'];
      $FA['tc'] = $FA['TC'];
      $FA['cod_doc'] = '01';
      if (strlen($FA['Autorizacion']) == 13) {
        $rep = $resultado = $this->autorizar_sri->Autorizar_factura_o_liquidacion($FA);
        if($rep==1)
           {
              $resultado = array('respuesta'=>$rep);
           }else{ $resultado = array('respuesta'=>-1,'text'=>$rep);}

      }else{
        $resultado = array('respuesta'=>5);
      }
      echo json_encode($resultado);
      exit();
    }
	}

  public function guardarLineas(){
    $this->facturacion->deleteAsiento($_POST['codigoCliente']);
    $datos = array();
    $Contador = 0;
    foreach ($_POST['datos'] as $key => $producto) {
      $dato[0]['campo']='CODIGO';
      $dato[0]['dato']= $producto['Codigo'];
      $dato[1]['campo']='CODIGO_L';
      $dato[1]['dato']= $producto['CodigoL'];
      $dato[2]['campo']='PRODUCTO';
      $dato[2]['dato']= $producto['Producto'] ;
      $dato[3]['campo']='CANT';
      $dato[3]['dato']= 1;
      $dato[4]['campo']='PRECIO';
      $dato[4]['dato']= $producto['Precio'] ;
      $dato[5]['campo']='Total_Desc';
      $dato[5]['dato']= $producto['Total_Desc'] ;
      $dato[6]['campo']='Total_Desc2';
      $dato[6]['dato']= $producto['Total_Desc2'] ;
      $dato[7]['campo']='TOTAL';
      $dato[7]['dato']= $producto['Total'];
      $dato[8]['campo']='Total_IVA';
      $dato[8]['dato']= $producto['Total'] * ($producto['Iva'] / 100);
      $dato[9]['campo']='Cta';
      $dato[9]['dato']= 'Cuenta' ;
      $dato[10]['campo']='Item';
      $dato[10]['dato']= $_SESSION['INGRESO']['item'];
      $dato[11]['campo']='Codigo_Cliente';
      $dato[11]['dato']= $_POST['codigoCliente'];
      $dato[12]['campo']='HABIT';
      $dato[12]['dato']= G_NINGUNO; // hay que revisar aqui que valor va por el moento que da con punto
      $dato[13]['campo']='Mes';
      $dato[13]['dato']= $producto['MiMes'] ;
      $dato[14]['campo']='TICKET';
      $dato[14]['dato']= $producto['Periodo'] ;
      $dato[15]['campo']='CodigoU';
      $dato[15]['dato']= $_SESSION['INGRESO']['CodigoU'];
      $dato[16]['campo']='A_No';
      $dato[16]['dato']= $Contador;
      $Contador++;
      insert_generico("Asiento_F",$dato);
    }
  }
  //El parametro columnas es un array que definen los parametros que deseamos obtener de la consulta sql
  public function CatalogoProductosByPeriodo(array $columnas){
    echo json_encode($this->catalogoProductosModel->getCatalogoProductosByPeriodo($columnas));
    exit();
  }

  public function deleteClientesFacturacionProductoClienteAnioMes($POST, $responder = false)
  {
    $CheqProducto   = @$POST['PFcheckProducto'];
    $DCProducto     = @$POST['PFselectProducto']; 
    $MBFechaP       = @$POST['PFfechaInicial']; 
    $TxtCantidad    = @$POST['PFcantidad']; 
    $TxtValor       = @$POST['PFvalor']; 
    $TxtDescuento   = @$POST['PFdescuento']; 
    $TxtDescuento2  = @$POST['PFdescuento2']; 
    $codigoCliente  = @$POST['PFcodigoCliente']; 

    if($codigoCliente!=""){
      $respuestaDB = $peticionesDB = 0;
      foreach ($CheqProducto as $item => $check) {
        if($check=='on' || $check == '1'){
          $Cantidad = $TxtCantidad[$item];
          $Valor = $TxtValor[$item];
          if ($Cantidad > 0){
            $Mifecha = PrimerDiaMes($MBFechaP[$item]);
            $CodigoInv = $DCProducto[$item];
            $CodigoInv = ($CodigoInv!="")?$CodigoInv:G_NINGUNO;
            for ($i=0; $i < $Cantidad; $i++) { 
              $NoMes = ObtenerMesFecha($Mifecha);
              $Anio = ObtenerAnioFecha($Mifecha);
              $peticionesDB++;
              $respuestaDB += $this->facturacion->deleteClientes_FacturacionProductoClienteAnioMes($codigoCliente, $CodigoInv, $Anio, $NoMes);
              $Mifecha = PrimerDiaSeguienteMes($Mifecha);
            }
          }
        }
      }
      if($responder){
        echo json_encode(array("rps" => 1 , "mensaje" => "Proceso finalizado correctamente" ));
      }
    }else{
      if($responder){
        echo json_encode(array("rps" => false , "mensaje" => "Debe seleccionar un cliente." ));
      }
    }
  }

  public function insertClientesFacturacionProductoClienteAnioMes($POST)
  {
    $CheqProducto   = @$POST['PFcheckProducto'];
    $DCProducto     = @$POST['PFselectProducto']; 
    $MBFechaP       = @$POST['PFfechaInicial']; 
    $TxtCantidad    = @$POST['PFcantidad']; 
    $TxtValor       = @$POST['PFvalor']; 
    $TxtDescuento   = @$POST['PFdescuento']; 
    $TxtDescuento2  = @$POST['PFdescuento2']; 
    $codigoCliente  = @$POST['PFcodigoCliente']; 
    $GrupoNo        = @$POST['PFGrupoNo']; 

    if($codigoCliente!=""){
      $respuestaDB = $peticionesDB = 0;
      foreach ($CheqProducto as $item => $check) {
        if($check=='on' || $check == '1'){
          $Cantidad = $TxtCantidad[$item];
          $Valor = $TxtValor[$item];
          $Total_Desc = (($TxtDescuento[$item]=="")?0:$TxtDescuento[$item]);
          $Total_Desc2 = (($TxtDescuento2[$item]=="")?0:$TxtDescuento2[$item]);
          if ($Cantidad > 0 && $Valor > 0){
            $Mifecha = PrimerDiaMes($MBFechaP[$item]);
            $CodigoInv = $DCProducto[$item];
            $CodigoInv = ($CodigoInv!="")?$CodigoInv:G_NINGUNO;
            for ($i=0; $i < $Cantidad; $i++) { 
              $NoMes = ObtenerMesFecha($Mifecha);
              $Anio = ObtenerAnioFecha($Mifecha);
              $peticionesDB++;
              $respuestaDB += $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $CodigoInv, $Valor, $GrupoNo, $NoMes, $Anio, $Mifecha, $Total_Desc, $Total_Desc2);
              $Mifecha = PrimerDiaSeguienteMes($Mifecha);     
            }
          }
        }
      }
      echo json_encode(array("rps" => 1 , "mensaje" => "PROCESO EXITOSO.", "mensaje_extra" => "Vuelva a listar el Cliente y verifique los datos procesados" ));
    }else{
      echo json_encode(array("rps" => false , "mensaje" => "Debe seleccionar un cliente." ));
    }
  }

  public function getClientesMatriculas($codigoCliente)
  {
    return $this->facturacion->getClientesMatriculas($codigoCliente);
  }

  public function getcargarBancos($columnas, $id, $filtro, $limit)
  {
    return $this->facturacion->getBancos($columnas,$id, $filtro, $limit);
  }

  public function getCargarDireccionByGrupo($grupo)
  {
    return $this->facturacion->getDireccionByGrupo($grupo);
  }   

  public function getDCGrupo($query)
  {
    $datos = $this->facturacion->getDCGrupo($query);
    $lis = array();
    foreach ($datos as $key => $value) {
      $lis[] =array('id'=>$value['Grupo'],'text'=>$value['Grupo']);
    }
    return $lis;
  }

}
?>