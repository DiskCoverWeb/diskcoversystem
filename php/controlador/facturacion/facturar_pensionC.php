<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturarM.php");
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
  echo json_encode($controlador->guardarLineas());
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
  $hayData = false;
  $CheqProducto   = @$_POST['PFcheckProducto'];
  $TxtCantidad    = @$_POST['PFcantidad']; 
  $TxtValor       = @$_POST['PFvalor']; 
  if(is_array($CheqProducto)){
    foreach ($CheqProducto as $item => $check) {
      if($check=='on' || $check == '1'){
        $Cantidad = @(int)$TxtCantidad[$item];
        $Valor = $TxtValor[$item];
        if ($Cantidad > 0 && $Valor > 0){
          $hayData = true;
          break;
        }
      }
    }
  }

  if($hayData){
    //Eliminamos pensiones si los tuviera
    $controlador->deleteClientesFacturacionProductoClienteAnioMes($_POST);
    //Procedemos a insertar las pensiones
    $controlador->insertClientesFacturacionProductoClienteAnioMes($_POST);
  }else{
    echo json_encode(array("rps" => 0 , "mensaje" => "Por favor complete la información."));
  }
  exit();
}
else if(isset($_GET['EliminarInsPreFacturas']))
{
  $hayData = false;
  $CheqProducto   = @$_POST['PFcheckProducto'];
  $TxtCantidad    = @$_POST['PFcantidad']; 
  $TxtValor       = @$_POST['PFvalor']; 
  if(is_array($CheqProducto)){
    foreach ($CheqProducto as $item => $check) {
      if($check=='on' || $check == '1'){
        $Cantidad = @(int)$TxtCantidad[$item];
        if ($Cantidad > 0 ){
          $hayData = true;
          break;
        }
      }
    }
  }

  if($hayData){
    $controlador->deleteClientesFacturacionProductoClienteAnioMes($_POST, true);
  }else{
    echo json_encode(array("rps" => 0 , "mensaje" => "Por favor complete la información."));
  }
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

if(isset($_GET['ActualizaDatosCliente']))
{
  echo json_encode($controlador->ActualizaDatosCliente($_POST));
  exit();
}

if(isset($_GET['getMBHistorico']))
{
  $datos = $controlador->getMBHistorico();
  echo json_encode($datos);
}

if(isset($_GET['BuscarClienteCodigo']))
{
  $data = $controlador->getClientes('',"{$_GET['BuscarClienteCodigo']}");
  if(count($data)>0){
    echo json_encode(['rps'=>true, 'data' => $data[0]]);
  }else{
    echo json_encode(['rps'=>false, 'data' => [], 'mensaje' => 'Usuario no encontrado']);
  }
}

if(isset($_GET['BuscarClienteCodigoMedidor']))
{
  echo json_encode($controlador->getClienteCodigoMedidor("{$_GET['BuscarClienteCodigoMedidor']}"));
  exit();
}

if(isset($_GET['GuardarConsumoAgua']))
{
  echo json_encode($controlador->GuardarConsumoAgua($_POST));
  exit();
}

class facturar_pensionC
{
  private $facturacion;
	private $catalogoProductosModel;
  private $pdf;
  private $facturas;


	public function __construct(){
        $this->facturacion = new facturar_pensionM();
        $this->facturas = new facturarM();
        $this->catalogoProductosModel = new catalogo_productosM();
        $this->autorizar_sri = new autorizacion_sri();
        $this->pdf = new cabecera_pdf();
        $this->email = new enviar_emails(); 
        //$this->modelo = new MesaModel();
    }

	public function getClientes($query, $ruc=false){
    // Leer_Datos_Cliente_SP($codigo)
		$datos = $this->facturacion->getClientes($query,$ruc);
		$clientes = [];
		foreach ($datos as $value) {
			$clientes[] = array('id'=>$value['Cliente'],'text'=>$value['Cliente'],'data'=>array('email'=> $value['Email'],'direccion' => $value['Direccion'],'direccion1'=>$value['DireccionT'], 'telefono' =>$value['Telefono'], 'ci_ruc' => $value['CI_RUC'], 'codigo' => $value['Codigo'], 'cliente' => $value['Cliente'], 'grupo' => $value['Grupo'], 'tdCliente' => $value['TD'], 'Archivo_Foto'=> $value['Archivo_Foto'], 'Archivo_Foto_Url'=> BuscarArchivo_Foto_Estudiante($value['Archivo_Foto']), 'RUC_CI_Rep' => $value['CI_RUC_R'])); //,'dataMatricula'=>$matricula);
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
    $tipo = "'FA','NV'";
    if(isset($_POST['tipo']))
    {
      $tipo = "'".$_POST['tipo']."'";
    }

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
      $datos = $this->facturacion->getCatalogoLineas($emision,$vencimiento,$serie,$tipo);
      if(count($datos)==0)
      {
        return array();
      }  
    }else{
      $datos = $this->facturacion->getCatalogoLineas13($emision,$vencimiento,$tipo);
      if(count($datos)==0)
      {
        return array();
      } 
    }

    $catalogo = [];
    foreach ($datos as $value) {
      $catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC']." ".$value['Codigo'] ,'text'=>utf8_encode($value['Concepto']));
    }    
    return $catalogo;
	}

	public function getCatalogoProductos(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getCatalogoProductos($codigoCliente);
		$catalogo = [];
		foreach ($datos as $value) {
			$catalogo[] = array('mes'=> utf8_encode($value['Mes']),'codigo'=> utf8_encode($value['Codigo_Inv']),'periodo'=> utf8_encode($value['Periodos']),'producto'=>$value['Producto'],'valor'=> utf8_encode($value['Valor']), 'descuento'=> utf8_encode($value['Descuento']),'descuento2'=> utf8_encode($value['Descuento2']),'iva'=> utf8_encode($value['IVA']),'CodigoL'=> utf8_encode($value['Codigo']),'CodigoL'=> utf8_encode($value['Codigo']),'Credito_No'=>$value['Credito_No'],'Codigo_Auto'=>$value['Codigo_Auto']);
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

    $AdoAux = $this->facturas->FechaInicialHistoricoFacturas();
    @$AdoAux = (isset($AdoAux[0]["MinFecha"]))?$AdoAux[0]["MinFecha"]->format("Ymd"):"";
    $FechaInicial = (($AdoAux!="")?PrimerDiaMes($AdoAux,"Ymd"):"20000101");

    Reporte_Cartera_Clientes_SP($FechaInicial, UltimoDiaMes2(date('d/m/Y'),'Ymd'), $codigoCliente);
    $AdoCarteraDB = $this->facturacion->Reporte_Cartera_Clientes_PDF_Data($_SESSION['INGRESO']['CodigoU']);
    $titulo = 'REPORTE CARTERA DE CLIENTES';
    $parametros['desde'] = false;
    $parametros['hasta'] = false;
    $sizetable = 8;
    $mostrar = true;
    $tablaHTML = array();

    $EmailCli = "";
    $EmailCli = Insertar_Mail($EmailCli, $AdoCarteraDB[0]['EmailR']);
    $EmailCli = Insertar_Mail($EmailCli, $AdoCarteraDB[0]['Email2']);
    $EmailCli = Insertar_Mail($EmailCli, $AdoCarteraDB[0]['Email']);

    $contenido[0]['tipo'] = 'texto';
    $contenido[0]['posicion'] = 'top-tabla';
    $contenido[0]['valor'] = 'CLIENTE: '.$AdoCarteraDB[0]['Cliente'];
    $contenido[0]['estilo'] = 'I';
    $contenido[0]['tamaño'] = '9';
    $contenido[0]['separacion'] = '1';
    $contenido[1]['tipo'] = 'texto';
    $contenido[1]['posicion'] = 'top-tabla';
    $contenido[1]['valor'] = 'UBICACION: '.$AdoCarteraDB[0]['Direccion'];
    $contenido[1]['estilo'] = 'I';
    $contenido[1]['tamaño'] = '9';
    $contenido[1]['separacion'] = '1';
    $contenido[2]['tipo'] = 'texto';
    $contenido[2]['posicion'] = 'top-tabla';
    $contenido[2]['valor'] = 'EMAILS: '.$EmailCli;
    $contenido[2]['estilo'] = 'I';
    $contenido[2]['tamaño'] = '9';
    $contenido[3]['tipo'] = 'texto';
    $contenido[3]['posicion'] = 'top-tabla';
    $contenido[3]['valor'] = 'La informacion presente reposa en la base de dato de la Institucion, corte realizado desde '.FechaStrg($FechaInicial,"Ymd").' al '.FechaStrg(date("Ymd"),"Ymd").', cualquier informacion adicional comuniquese a la institucion';
    $contenido[3]['estilo'] = 'I';
    $contenido[3]['tamaño'] = '8';

    $tablaHTML[0]['medidas'] = array(6,6,13,15,17,80,14,8,15,16,16);
    $tablaHTML[0]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L');
    $tablaHTML[0]['datos'] = array('T','TC','Serie','Factura','Fecha','Detalle','Año','Mes','Cargos','Abonos'/*,'Mes No','No'*/);
    $tablaHTML[0]['borde'] = "BT";
    $tablaHTML[0]['estilo'] = 'B';
    $tablaHTML[0]['sizetable'] = $sizetable;

    $count = 1;
    $factura="";
    foreach ($AdoCarteraDB as $value) {
          $tablaHTML[$count]['borde'] = 0;
      $tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
      $tablaHTML[$count]['alineado'] = array('L','L','L','R','L','L','R','R','R','R','R');
      $tablaHTML[$count]['datos'] = array($value['T'],$value['TC'],$value['Serie'],$value['Factura'],$value['Fecha']->format('d/m/Y'),$value['Detalle'], $value['Anio'],str_pad($value['Mes'], 2, "0", STR_PAD_LEFT),number_format($value['Cargos'],2),number_format($value['Abonos'],2)/*,$value['Mes_No'],$value['No']*/);
      if(strpos($value['Detalle'], 'SALDO TOTAL')){
          $tablaHTML[$count]['medidas'] = array(188);
          $tablaHTML[$count]['alineado'] = array('C');
          $tablaHTML[$count]['datos'] = array($value['Detalle']);
          $tablaHTML[$count]['borde'] = $tablaHTML[0]['borde'];
      }
      $count+=1;
    }
    $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,18,$orientacion='P',$download, $tablaHTML[0]);
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
    if (file_exists(dirname(__DIR__,3).'/TEMP/EMPRESA_'.$_SESSION['INGRESO']['item'].'/Historia del cliente.xlsx')) {
      unlink(dirname(__DIR__,3).'/TEMP/EMPRESA_'.$_SESSION['INGRESO']['item'].'/Historia del cliente.xlsx');
    }
    if (file_exists(dirname(__DIR__,3).'/TEMP/REPORTE CARTERA DE CLIENTES.pdf')) {
      unlink(dirname(__DIR__,3).'/TEMP/REPORTE CARTERA DE CLIENTES.pdf');
    }
    $this->historiaClientePDf($_REQUEST['codigoCliente'],false);
    $this->historiaClienteExcel($_REQUEST['codigoCliente'],false);
    $archivos[0] = dirname(__DIR__,3).'/TEMP/EMPRESA_'.$_SESSION['INGRESO']['item'].'/Historia del cliente.xlsx';
    $archivos[1] = dirname(__DIR__,3).'/TEMP/REPORTE CARTERA DE CLIENTES.pdf';
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
		$Grupo_No = $_POST['Grupo_No'];
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
   		$updateCliF = $this->facturacion->updateClientesFacturacion($Grupo_No,$codigoCliente);
   		$updateCli = $this->facturacion->updateClientes($TxtTelefono,$TxtDirS,$TxtDireccion,$TxtEmail,$Grupo_No,$codigoCliente);
      $this->facturacion->Actualiza_Datos_Cliente($_POST);
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

    foreach ($datos as $key => $value) {
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
      $SubTotal_NC = $FA['TxtNC'];
      $Total_Anticipo = $FA['saldoFavor'];
      $Total_Bancos = $FA['TextCheque'];
      $TotalCajaMN = $FA['Total'] - $Total_Bancos - $SubTotal_NC;
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN + $Total_Bancos + $SubTotal_NC;
      $FA['Total_Abonos'] = $Total_Abonos;
      $FA['T'] = G_PENDIENTE;
      $FA['Saldo_MN'] = $FA['Total'] - $Total_Abonos;
      $FA['Porc_IVA'] = $_SESSION['INGRESO']['porc'];
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
      if(strlen($FA['TextBanco'])<=1){
        $TA['Banco'] = strtoupper($FA['DCBanco']);
      }else{
        $TA['Banco'] = strtoupper($FA['TextBanco'].' - '.$FA['Grupo_No']);
      }
      $TA['Cheque'] = $FA['chequeNo'];
      $TA['Abono'] = $Total_Bancos;
      Grabar_Abonos($TA);

      //Abono de Factura
      $TA['Cta'] = $_SESSION['SETEOS']['Cta_CajaG'];
      $TA['Banco'] = "EFECTIVO MN";
      $TA['Cheque'] = strtoupper($FA['Grupo_No']);
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
      
      //Abonos Anticipados Cta_Ant_Cli
       $TA['Cta'] = SinEspaciosIzq($FA['DCAnticipo']);
       if(strlen($FA['TextBanco']) > 1) { $TA['Banco'] = strtoupper($FA['TextBanco']); } else { $TA['Banco'] = "ANTICIPO PENSIONES";};
       $TA['Cheque'] = strtoupper($FA['Grupo_No']);
       $TA['Abono'] = $Total_Anticipo;
       Grabar_Abonos($TA);
     
      //Forma del Abono IVA NC
      if ($TFA['Total_IVA'] > 0) {
        $TA['Cta'] = $Cta_IVA;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "I.V.A.";
        $TA['Abono'] = $TFA['Total_IVA'];
        Grabar_Abonos($TA);
      }
     
      //Abono de Factura
      $TA['T'] = G_NORMAL;
      $TA['TP'] = "TJ";
      $TACta = $Cta;
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['Banco'] = "INTERES POR TARJETA";
      $TA['Cheque'] =  $FA['chequeNo'];
      $TA['Abono'] = intval($FA['TextInteres']);
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
        $FA['Desde'] = $FA['Factura'];
        $FA['Hasta'] = $FA['Factura'];
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
      SetAdoAddNew('Asiento_F');
      SetAdoFields("CODIGO", $producto['Codigo']);
      SetAdoFields("CODIGO_L", $producto['CodigoL']);
      SetAdoFields("PRODUCTO", $producto['Producto']);
      SetAdoFields("CANT", 1);
      SetAdoFields("PRECIO", $producto['Precio']);
      SetAdoFields("Total_Desc", $producto['Total_Desc']);
      SetAdoFields("Total_Desc2", $producto['Total_Desc2']);
      SetAdoFields("TOTAL", $producto['Precio']);
      SetAdoFields("Total_IVA", ($producto['Total'] * ($producto['Iva'] / 100)));
      SetAdoFields("Cta", 'Cuenta');
      SetAdoFields("Codigo_Cliente", $_POST['codigoCliente']);
      SetAdoFields("Mes", $producto['MiMes']);
      SetAdoFields("TICKET", $producto['Periodo']);
      SetAdoFields("CodigoU", $_SESSION['INGRESO']['CodigoU']);

      if(isset($producto['CORTE'])){
        SetAdoFields("CORTE", $producto['CORTE']);
      }
      if(isset($producto['Tipo_Hab'])){
        SetAdoFields("Tipo_Hab", $producto['Tipo_Hab']);
      }

      SetAdoFields("A_No", $Contador);
      $Contador++;
      $stmt = SetAdoUpdate();
    }
    Eliminar_Nulos_SP("Asiento_F");
    return (count($_POST['datos'])==($Contador));
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
          $Cantidad = @(int)$TxtCantidad[$item];
          $Valor = @(float)$TxtValor[$item];
          if ($Cantidad > 0){
            $Mifecha = PrimerDiaMes($MBFechaP[$item]);
            $CodigoInv = @$DCProducto[$item];
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
          $Cantidad = @(int)$TxtCantidad[$item];
          $Valor = @(float)$TxtValor[$item];
          $Total_Desc = @(float)(($TxtDescuento[$item]=="")?0:$TxtDescuento[$item]);
          $Total_Desc2 = @(float)(($TxtDescuento2[$item]=="")?0:$TxtDescuento2[$item]);
          if ($Cantidad > 0 && $Valor > 0){
            $Mifecha = PrimerDiaMes($MBFechaP[$item],'Ymd');
            $CodigoInv = @$DCProducto[$item];
            $CodigoInv = ($CodigoInv!="")?$CodigoInv:G_NINGUNO;
            for ($i=0; $i < $Cantidad; $i++) { 
              $NoMes = ObtenerMesFecha($Mifecha,'Ymd');
              $Anio = ObtenerAnioFecha($Mifecha,'Ymd');
              $peticionesDB++;
              $respuestaDB += $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $CodigoInv, $Valor, $GrupoNo, $NoMes, $Anio, $Mifecha, $Total_Desc, $Total_Desc2);
              $Mifecha = PrimerDiaSeguienteMes($Mifecha,'Ymd');     
            }
          }
        }
      }
      echo json_encode(array("rps" => 1 , "mensaje" => "PROCESO EXITOSO."));
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

  public function ActualizaDatosCliente($post)
  {
    if($this->facturacion->Actualiza_Datos_Cliente($post)){
      return (array("rps" => 1 , "mensaje" => "PROCESO EXITOSO."));
    }else{
      return (array("rps" => 0 , "mensaje" => "No fue posible procesar su solicitud."));
    }
  }

  public function getMBHistorico()
  {
    $AdoAux = $this->facturas->FechaInicialHistoricoFacturas();
    @$AdoAux = (isset($AdoAux[0]["MinFecha"]))?$AdoAux[0]["MinFecha"]->format("Y-m-d"):"";
    $FechaInicial = (($AdoAux!="")?PrimerDiaMes($AdoAux,"Y-m-d"):"2000-01-01");

    return (array("MBHistorico" => $FechaInicial ));
  }

  public function getClienteCodigoMedidor($CMedidor){
    $dataCliente = $this->facturacion->BuscarClienteCodigoMedidor($CMedidor);
    if(count($dataCliente)>0){
      $data = $dataCliente[0];
      $ClienteFacturacion = $this->facturacion->getUltimoRegistroClientes_Facturacion($data['Codigo'], $CMedidor, "JG.01");
      $DetalleFactura = $this->facturacion->getUltimoRegistroDetalleFactura($data['Codigo'], $CMedidor, "JG.01");
      if (count($ClienteFacturacion) > 0 && ($ClienteFacturacion[0]['Periodo'] >= @$DetalleFactura[0]['Periodo'] && $ClienteFacturacion[0]['Num_Mes'] >= @$DetalleFactura[0]['Mes_No'])) {
        $data['ultimaMedida'] = $ClienteFacturacion[0]['Credito_No'];
        $data['fechaUltimaMedida'] = MesesLetras($ClienteFacturacion[0]['Num_Mes']) . "/" . $ClienteFacturacion[0]['Periodo'];
      } elseif (count($DetalleFactura) > 0) {
        $data['ultimaMedida'] = $DetalleFactura[0]['Corte'];
        $data['fechaUltimaMedida'] = MesesLetras($DetalleFactura[0]['Mes_No']) . "/" . $DetalleFactura[0]['Periodo'];
      } else {
        $data['ultimaMedida'] = $data['Acreditacion'];
        $data['fechaUltimaMedida'] = "";
      }

      $data['ultimaMedida'] = (is_numeric($data['ultimaMedida']))?$data['ultimaMedida']:0;
      return array('rps'=>true, 'data'=>$data);
    }else{
      return array('rps'=>false, 'mensaje'=>'Medidor no encontrado');
    }
  }

  public function GuardarConsumoAgua($parametros){
    extract($parametros);
    if($CMedidor != "" && $CMedidor!="."){
      $dataCliente = @$this->getClienteCodigoMedidor($CMedidor)['data'];
      $LecturaAnterior = ((!is_null($dataCliente['ultimaMedida']) && is_numeric($dataCliente['ultimaMedida']))?$dataCliente['ultimaMedida']:0);
      if($Lectura<$LecturaAnterior){return (array("rps" => 0 , "mensaje" => "La lectura actual no puede ser inferior a la anterior"));}
      $rangoValores = $this->facturacion->getCatalogo_Cyber_Tiempo();
      $consumoActual = $Lectura-$LecturaAnterior; 
      $valorMinimo = ($rangoValores[array_key_last($rangoValores)]['Desde'])-1;
      $excedente = (($consumoActual>$valorMinimo)?$consumoActual-$valorMinimo:0);
      $productos = $this->catalogoProductosModel->TVCatalogo("JG","P");

      $Mifecha = date('YmdHis');

      $periodo = $this->facturacion->getPeriodoAbierto();
      if(count($periodo)>0){
        $dataperiodo = explode(" ", $periodo[0]['Detalle']);
        $NoMes = nombre_X_mes($dataperiodo[1]);
        $Anio = $dataperiodo[0];
      }else{
        $NoMes = ObtenerMesFecha($Mifecha,'YmdHis');
        $Anio = ObtenerAnioFecha($Mifecha,'YmdHis');
      }

      if($excedente>0){
        foreach ($rangoValores as $key => $rango) {
          if($consumoActual>= $rango['Desde'] && $consumoActual<= $rango['Hasta']){
            $montoExcedente = $excedente*$rango['Valor'];
            break;
          }
        }
      }
      //insert consumo
      $clave = array_search("JG.01", array_column($productos, "Codigo_Inv"));
      if ($clave !== false) {
        $productoConsumo = $productos[$clave];
        $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $productoConsumo['Codigo_Inv'], $productoConsumo['PVP'], G_NINGUNO, $NoMes, $Anio, $Mifecha, 0, 0, $Lectura,$CMedidor);

        //insert alcantarilado
        $clave = array_search("JG.02", array_column($productos, "Codigo_Inv"));
        if ($clave !== false) {
          $productoAlcantarillado = $productos[$clave];
          $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, $productoAlcantarillado['Codigo_Inv'], $productoAlcantarillado['PVP'], G_NINGUNO, $NoMes, $Anio, $Mifecha, 0, 0,G_NINGUNO,$CMedidor);
        }

        //insert excedente
        if($excedente>0){
          $this->facturacion->insertClientes_FacturacionProductoClienteAnioMes($codigoCliente, "JG.03", $montoExcedente, G_NINGUNO, $NoMes, $Anio, $Mifecha, 0, 0,G_NINGUNO,$CMedidor);
        }
        return (array("rps" => true , "mensaje" => "Consumo registrado con exito."));
      }else{
        return (array("rps" => false , "mensaje" => "No se ha configurado el producto para guardar el consumo."));
      }
    }else{
      return (array("rps" => 0 , "mensaje" => "Debe indicar el medidor."));
    }
  }
}
?>