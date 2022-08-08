<?php

if(!class_exists('PDF_MC_Table'))
{
	require('PDF_MC_Table.php');
}
if (!class_exists('FPDF')) {
    //$mi_clase = new MiClase();
   require('fpdf.php');
}


//include(dirname(__DIR__,2)."/php/db/db.php");
//echo dirname(__DIR__,1);

/**
 * 
 */


class cabecera_pdf
{
	private $pdf;
	private $conn;
	private $header_cuerpo;
	private $pdf_sin_cabecera;

	function __construct()
	{
		$this->fpdf = new FPDF();
		$this->pdf = new PDFv();
		$this->pdftable = new PDF_MC();
		$this->pdf_sin_cabecera = new PDF_MC_SIN_HEADER();
		$this->fechafin='';
		$this->fechaini='';
		$this->sizetable ='12';
		$this->conn = cone_ajax();
		
	}	

	function cabecera_reporte($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$sal_hea_body=30,$orientacion='P')
	{	

	    $this->pdf->fechaini = $fechaini; 
	    $this->pdf->fechafin = $fechafin; 
	    $this->pdf->titulo = $titulo;
	    $this->pdf->salto_header_cuerpo = $sal_hea_body;
	    $this->pdf->orientacion = $orientacion;
		$this->pdf->AddPage();
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdf->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdf->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);

		 	 }
		 }
        }
		 $this->pdf->SetFont('Arial','',$sizetable);
		 $this->pdf->WriteHTML($tablaHTML);

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		 if($mostrar==true)
	       {
		    $this->pdf->Output();

	       }else
	       {
		     $this->pdf->Output('D',$titulo.'.pdf',false);

	      }

	}
 
 function cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$sal_hea_body=15,$orientacion='P',$download = true)
	{	

	    $this->pdftable->fechaini = $fechaini; 
	    $this->pdftable->fechafin = $fechafin; 
	    $this->pdftable->titulo = $titulo;
	    $this->pdftable->salto_header_cuerpo = $sal_hea_body;
	    $this->pdftable->orientacion = $orientacion;
	    $estiloRow='';
		 $this->pdftable->AddPage($orientacion);
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdftable->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	if(!isset($value['estilo'])){$value['estilo'] = '';}
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$siz = 11;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	//print_r($value);
		 	 	$this->pdftable->SetFont('Arial',$value['estilo'],$siz);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(4);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$siz = 18;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(4);

		 	 }
		 }
        }
                $this->pdftable->SetFont('Arial','',$sizetable);
		    foreach ($tablaHTML as $key => $value){
		    	if(isset($value['estilo']) && $value['estilo']!='')
		    	{
		    		$this->pdftable->SetFont('Arial',$value['estilo'],$sizetable);
		    		$estiloRow = $value['estilo'];
		    	}else
		    	{
		    		$this->pdftable->SetFont('Arial','',$sizetable);
		    		$estiloRow ='';
		    	}
		    	if(isset($value['borde']) && $value['borde']!='0')
		    	{
		    		$borde=$value['borde'];
		    	}else
		    	{
		    		$borde =0;
		    	}

		    //print_r($value['medida']);
		       $this->pdftable->SetWidths($value['medidas']);
			   $this->pdftable->SetAligns($value['alineado']);
			   //print_r($value['datos']);
			   $arr= $value['datos'];
			   $this->pdftable->Row($arr,4,$borde,$estiloRow);		    	
		    }
		

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$siz = 11;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$siz = 18;
		 	 	if(isset($value['tamaño'])){$siz = $value['tamaño'];}
		 	 	$this->pdftable->SetFont('Arial','',$siz);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		if ($download) {	
		 if($mostrar==true)
	       {
		    $this->pdftable->Output();

	       }else
	       {
		     $this->pdftable->Output('D',$titulo.'.pdf',false);

	      }
		}else{
			$this->pdftable->Output('F',dirname(__DIR__,2).'/php/vista/TEMP/'.$titulo.'.pdf');
		}
		
	}

	function formatoPDFMatricial($HTML,$parametros,$datos_pre,$datos_empresa,$descagar=false)
	{	
		// $orientation='P',$unit='mm', array(45,350)
		$pdf = new FPDF();
		$pdf->setMargins(2,15, 11.7);
		$pdf->SetFont('Courier','',8);
		$pdf->AddPage('P');
		$pdf->Cell(0,0,'Transaccion ('.$parametros['TC'].'): No. '.$datos_pre['lineas'][0]['Serie'].'-'.$datos_pre['lineas'][0]['Factura']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Autorizaion: ');
		$pdf->Ln(5);
		$pdf->SetFont('Courier','',7);
		$pdf->Cell(0,0,$datos_pre['lineas'][0]['Autorizacion']);
		$pdf->SetFont('Courier','',8);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Fecha: '.date('Y-m-d').' - Hora: '.date('H:m:s'));
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Cliente: '.$datos_pre['cliente']['Cliente']);
		if ($datos_empresa[0]['Micro_2021']== '1') {
			$pdf->Ln(5);
			$pdf->Cell(0,0,'MICROEMPRESA');
		}
		if ($datos_empresa[0]['Agente_Retencion']!='.') 
		{
			$pdf->Ln(5);
			$pdf->Cell(0,0,utf8_encode('Agente Retención: '.$datos_empresa[0]['agente_retencion']));
		}		
		$pdf->Ln(5);
		$pdf->Cell(0,0,'R.U.C/C.I.: '.$datos_pre['cliente']['CI_RUC']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Cajero: '.$_SESSION['INGRESO']['Nombre']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Telefono: '.$datos_pre['cliente']['Telefono']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,utf8_decode('Dirección: '.$datos_pre['cliente']['Direccion']));
		$pdf->Ln(5);
		$pdf->Cell(0,0,'Producto/Cant x PVP/Total '.$datos_pre['cliente']['Direccion']);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'--------------------------------------------');
		$pdf->Ln(5);
		foreach ($datos_pre['lineas'] as $key => $value) {
			$pdf->Cell(0,0,$value['Producto']);
			$pdf->Ln(2);
			$pdf->Cell(40,6,$value['Cantidad'].' X '.number_format($value['Precio2'],2),'',0);
			$pdf->Cell(35,6,number_format($value['Total'],2),'',0,'R');
			$pdf->Ln(5);
    	}
    	$pdf->Ln(5);
		$pdf->Cell(0,0,'--------------------------------------------');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'SUBTOTAL:','',0,'R');
		$pdf->Cell(20,6,number_format($datos_pre['tota'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'I.V.A 12%:','',0,'R');
		$pdf->Cell(20,6,number_format($datos_pre['iva'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'TOTAL FACTURA:','',0,'R');
		$pdf->Cell(20,6,number_format($datos_pre['tota'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'EFECTIVO:','',0,'R');
		$pdf->Cell(20,6,number_format($parametros['efectivo'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(55,6,'CAMBIO:','',0,'R');
		$pdf->Cell(20,6,number_format($parametros['saldo'],2),'',0,'R');
		$pdf->Ln(4);
    	$pdf->Cell(0,6,'ORIGINAL: CLIENTE','');
    	$pdf->Ln(4);
    	$pdf->Cell(0,6,'COPIA:    EMISOR','');
		$pdf->Ln(5);
		$pdf->Cell(0,0,'--------------------------------------------');
		$pdf->Ln(5);
		$pdf->Cell(75,0,'Email:'.$datos_pre['cliente']['Email'],'',0,'L');
		$pdf->Ln(5);
		$pdf->Cell(75,0,'Fue un placer atenderle','',0,'C');
		$pdf->Ln(5);
		$pdf->Cell(75,0,'Gracias por su compra','',0,'C');

		if($descagar)
		{
			$pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$datos_pre['lineas'][0]['Autorizacion'].'.pdf');
		}else
		{
     		$pdf->Output();
		}
		
		// $this->FPDF->AddPage('P');
 	//  	$this->pdftable->SetFont('Arial','',18);
 	//  	$this->pdftable->Cell(0,3,'Prueba',0,0,'C');
 	//  	$this->pdftable->Ln(5);
		// //$this->pdftable->WriteHTML($HTML);
		// $this->pdftable->Output();
	}

	function Imprimir_Punto_Venta_Grafico($info,$descagar=true)
	{	
		// print_r($info);die();
		// print_r($_SESSION['INGRESO']);
		// $orientation='P',$unit='mm', array(45,350)
		$pdf = new FPDF();
		$pdf->setMargins(2,5);
		$pdf->SetFont('Arial','B',8);
		$pdf->AddPage('P');
		$pdf->SetX(45);
		$pdf->Cell(25,5,'R.U.C',0,1);
		$pdf->SetX(40);
		$pdf->Cell(25,5,$_SESSION['INGRESO']['RUC'],0,1);		
		$pdf->SetX(40);
		$pdf->Cell(0,0,'Telefono: '.$_SESSION['INGRESO']['Telefono1'],'',1);
		$pdf->Ln(5);	
		$pdf->Cell(70,0,$_SESSION['INGRESO']['Razon_Social'],0,1,'C');
		$pdf->Ln(5);
		$pdf->Cell(70,0,$_SESSION['INGRESO']['Nombre_Comercial'],0,1,'C');
		$pdf->Ln(5);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(0,0,'Direccion Matriz:');
		$pdf->Ln(5);		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(0,0,$_SESSION['INGRESO']['Direccion']);
		$pdf->SetFont('Arial','B',7);
		$pdf->Ln(5);
		$pdf->Cell(0,0,'FECHA DE EMISION: '.$info['factura'][0]['Fecha']->format('Y-m-d'),0,1);
		$pdf->Ln(3);		
		$pdf->Cell(0,0,'DOCUMENTO DE FA No. '.$info['factura'][0]['Serie'].'-'.generaCeros($info['factura'][0]['Factura'],7),0,1);
		$pdf->Ln(5);
		$pdf->SetFont('Arial','',7);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->SetFont('Arial','B',7);
		$pdf->Ln(5);
		$pdf->Cell(70,0,'Razon Social/Nombres y Apellidos: ');
		$pdf->SetFont('Arial','',7);
		$pdf->Ln(5);
		$pdf->Cell(70,0,utf8_encode( $info['factura'][0]['Razon_Social']));
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(18,0,'Identificacion: ');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(20,0,$info['factura'][0]['RUC_CI']);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(15,0,'Telef.:');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(17,0,$info['factura'][0]['Telefono']);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(70,0,'Correo Electronico:');
		$pdf->Ln(3);		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(70,0,$info['factura'][0]['Email']);
		$pdf->Ln(5);		
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(8,0,'Cant.');
		$pdf->Cell(35,0,'P R O D U C T O');
		$pdf->Cell(10,0,'P.V.P.');
		$pdf->Cell(10,0,'T O T A L',0,1);
		$pdf->Ln(3);		
		$pdf->SetFont('Arial','',7);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(3);
		$pdf->SetFont('Arial','',6);

		foreach ($info['lineas'] as $key => $value) {
			$y = $pdf->GetY();
			$pdf->Cell(8,2,$value['Cantidad']);
			$pdf->MultiCell(35,2,$value['Producto']);
			$pdf->SetXY(48,$y);
			$pdf->Cell(10,2,number_format($value['Precio'],2,'.',''));
			$pdf->Cell(10,2,number_format($value['Total'],2,'.',''),0,1);
			$pdf->Ln(4);
			// $pdf->Row($value,null,1);
    	}

		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);

		$pdf->Ln(5);
		$pdf->Cell(40,0,'Cajero: 0702X79');
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(20,0,'SUBTOTAL.');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['SubTotal'],2,'.',''));
		$pdf->Ln(3);

		$pdf->Cell(40,0,'');
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(20,0,'DESCUENTO');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['Descuento'],2,'.',''));
		$pdf->Ln(3);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(40,0,'');
		$pdf->Cell(20,0,'I.V.A. 12%');
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['IVA'],2,'.',''));
		$pdf->Ln(3);

		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(40,0,'');
		$pdf->Cell(20,0,'T O T A L');		
		$pdf->SetFont('Arial','',7);
		$pdf->Cell(10,0,number_format($info['factura'][0]['Total_MN'],2,'.',''));
		$pdf->Ln(5);
		$l = $pdf->GetY();
		$pdf->Line(0,$l,70,$l);
		$pdf->Ln(5);

		$pdf->SetFont('Arial','',11);
		$pdf->Cell(70,0,'GRACIAS POR SU COLABORACION',0,1,'C');
		$pdf->SetFont('Arial','',7);
		$pdf->Ln(3);
		$pdf->Cell(70,0,'www.diskcoversystem.com',0,1,'C');



		if($descagar)
		{
			$pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$info['factura'][0]['Serie'].'-'.generaCeros($info['factura'][0]['Factura'],7).'.pdf');
		}else
		{
     		$pdf->Output();
		}
		
		// $this->FPDF->AddPage('P');
 	//  	$this->pdftable->SetFont('Arial','',18);
 	//  	$this->pdftable->Cell(0,3,'Prueba',0,0,'C');
 	//  	$this->pdftable->Ln(5);
		// //$this->pdftable->WriteHTML($HTML);
		// $this->pdftable->Output();
	}


	function DeudapendientePensionesPDF($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$sal_hea_body=30,$orientacion='P',$download=true)
	{	
		$this->pdf_sin_cabecera->AddPage($orientacion);


	   $this->pdf_sin_cabecera->SetFont('Arial','',$sizetable);
	    foreach ($tablaHTML as $key => $value){
	    	if(isset($value['estilo']) && $value['estilo']!='')
	    	{
	    		$this->pdf_sin_cabecera->SetFont('Arial',$value['estilo'],$sizetable);
	    		$estiloRow = $value['estilo'];
	    	}else
	    	{
	    		$this->pdf_sin_cabecera->SetFont('Arial','',$sizetable);
	    		$estiloRow ='';
	    	}
	    	if(isset($value['borde']) && $value['borde']!='0')
	    	{
	    		$borde=$value['borde'];
	    	}else
	    	{
	    		$borde =0;
	    	}

	    //print_r($value['medida']);
	       $this->pdf_sin_cabecera->SetWidths($value['medidas']);
		   $this->pdf_sin_cabecera->SetAligns($value['alineado']);
		   //print_r($value['datos']);
		   $arr= $value['datos'];
		   $this->pdf_sin_cabecera->Row($arr,4,$borde,$estiloRow,null,$cero=true);		    	
	    }
		
		// print_r($_SESSION['INGRESO']);die();
		$this->pdf_sin_cabecera->Ln(30);
		$this->pdf_sin_cabecera->Cell(0,3,'--------------------------------------',0,0,'L');	
		$this->pdf_sin_cabecera->Ln(5);	
		$this->pdf_sin_cabecera->Cell(0,3,strtoupper($_SESSION['INGRESO']['Nombre']),0,0,'L');
		$this->pdf_sin_cabecera->Ln(5);
		$this->pdf_sin_cabecera->Cell(0,3,strtoupper('COLECTURIA'),0,0,'L');					
		

		if ($download) 
		{	
		 if($mostrar==true)
	       {
	       	//muestra en pantalla
		    $this->pdf_sin_cabecera->Output();
	       }else
	       {
	       	//descarga el pdf
		     $this->pdf_sin_cabecera->Output('D',$titulo.'.pdf',false);

	      }
		}else{
			//descarga en temporales
			$this->pdf_sin_cabecera->Output('F',dirname(__DIR__,2).'/php/vista/TEMP/'.$titulo.'.pdf');
		}
		
	}



	function cabecera_reporte_colegio($titulo,$nombre,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$email=false,$sal_hea_body=30)
	{	


	   $cid=$this->conn;
	   $sql = "SELECT * from Catalogo_Periodo_Lectivo where Item='".$_SESSION['INGRESO']['item']."'";
	   $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }   

	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
		    //echo $row[0];
	      }
	      //print_r($result[0]);
	      $_SESSION['INGRESO']['Nombre_Comercial'] =utf8_decode($result[0]['Institucion1']);
	      $_SESSION['INGRESO']['noempr']=utf8_decode($result[0]['Institucion2']);
	      $_SESSION['INGRESO']['Logo_Tipo'] = $result[0]['Logo_Tipo'];
	     // return $result;
//print($result[0]['Logo_Tipo']);

	    $this->pdf->fechaini = $fechaini; 
	    $this->pdf->fechafin = $fechafin; 
	    $this->pdf->titulo = $nombre;
	    $this->pdf->salto_header_cuerpo = $sal_hea_body;

		 $this->pdf->AddPage();
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdf->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	// $this->pdf->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);

		 	 }
		 }
        }
		 $this->pdf->SetFont('Arial','',$sizetable);
		 $this->pdf->WriteHTML($tablaHTML);

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		 if($mostrar==true)
	       {
		    $this->pdf->Output();

	       }else
	      { // $this->pdf->Output('D',$titulo.'.pdf',false);
		    if(file_exists(dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf'))
		      {
		      	 //unset('../../php/vista/TEMP/'.$titulo.'.pdf');
		      	unlink(dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf');  
		      }

		     if($email != 'false')
		     {
		       $this->pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf');	
		     }
		     else
		     {
		      $this->pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$titulo.'.pdf');
		      $this->pdf->Output('D',$titulo.'.pdf',false);
		     }

	      }

	}
  }


class PDFv extends FPDF
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;

    function Header()
    {
   
  // print($_SESSION['INGRESO']['Logo_Tipo']);
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }

         $this->Image($src,10,3,35,20); 
         $this->SetFont('Times','b',12);
         $this->SetXY(10,10);

		$this->Cell(0,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,0,'C');
		$this->SetFont('Times','I',13);
		$this->Ln(5);
		$this->Cell(0,3,strtoupper($_SESSION['INGRESO']['noempr']),0,0,'C');				
		$this->Ln(5);


		$this->SetFont('Times','I',11);
		$this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].' Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'C');

		$this->Ln(5);		
		$this->SetFont('Arial','b',12);

		$this->Cell(0,3,$this->titulo,0,0,'C');
		
		if($this->fechaini !='' && $this->fechaini != null  && $this->fechafin !='' && $this->fechafin != null){
		   $this->SetFont('Arial','b',10);
		   $this->Ln(5);		
		   $this->Cell(0,3,'DESDE: '.$this->fechaini.' HASTA:'.$this->fechafin,0,0,'C');
		   $this->Ln(10);	
		}

		if($this->orientacion == 'P')
		{
		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',182,3,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(155,5);
        $this->Cell(9,2,'Hora: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(155,8);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(155,11);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(155,14);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		$this->Line(20, 35, 210-20, 35); 
        $this->Line(20, 36, 210-20, 36);
		$this->Ln($this->salto_header_cuerpo);
	}else
	{

		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',482,3,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(255,5);
        $this->Cell(9,2,'Horas: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(255,8);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(255,11);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(255,14);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		$this->Line(20, 35, 300-20, 35); 
        $this->Line(20, 36, 300-20, 36);
		$this->Ln($this->salto_header_cuerpo);

	}

 }

}

class PDF_MC extends PDF_MC_Table
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;

    function Header()
    {
   
   // print($_SESSION['INGRESO']['Logo_Tipo']);
    	
			      $this->SetTextColor(0,0,0);
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover_web";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }

         $this->Image($src,10,18,35,20); 
         $this->SetFont('Times','b',9);
         $this->SetXY(10,20);

         // print_r($_SESSION['INGRESO']);die();

         if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['Nombre_Comercial'])
         {
			$this->Cell(0,3,$_SESSION['INGRESO']['Razon_Social'],0,0,'C');
			$this->SetFont('Times','I',9);
			$this->Ln(5);
			$this->Cell(0,3,strtoupper($_SESSION['INGRESO']['Nombre_Comercial']),0,0,'C');				
			$this->Ln(5);
		}else
		{
			$this->Cell(0,3,$_SESSION['INGRESO']['Razon_Social'],0,0,'C');
			$this->SetFont('Times','I',9);
			$this->Ln(5);
		}


		$this->SetFont('Times','I',8);
		$this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].' Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'C');

		$this->Ln(5);		
		$this->SetFont('Arial','b',8);

		$this->Cell(0,3,$this->titulo,0,0,'C');
		
		if($this->fechaini !='' && $this->fechaini != null  && $this->fechafin !='' && $this->fechafin != null){
		   $this->SetFont('Arial','b',10);
		   $this->Ln(5);		
		   $this->Cell(0,3,'DESDE: '.$this->fechaini.' HASTA:'.$this->fechafin,0,0,'C');
		   $this->Ln(10);	
		}

		if($this->orientacion == 'P')
		{
		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',175,18,15,5); 
		$this->Ln(2);

		$this->SetFont('Arial','b',5);
		$this->SetXY(190,19);
        $this->Cell(10,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',5);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		 $this->SetFont('Arial','b',5);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(175,23);
        $this->Cell(9,2,'Hora: ',0,0,'L');
		$this->SetFont('Arial','',5);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);				
		$this->SetXY(175,26);
		$this->SetFont('Arial','b',5);		
        $this->Cell(8,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',5);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(175,29);
		$this->SetFont('Arial','b',5);	
        $this->Cell(8,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',5);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
        $this->Ln(2);
		$this->SetXY(175,32);
		$this->SetFont('Arial','b',5);	
        $this->Cell(10,2,'https://www.diskcoversystem.com',0,0,'L');
		$this->Line(20, 45, 210-20, 45); 
        $this->Line(20, 46, 210-20, 46);
		$this->Ln($this->salto_header_cuerpo);
	}else
	{

		  //inicio--------logo superior derecho//		
		// 175,18,15,5); 
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',270,18,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(240,19);
        $this->Cell(9,2,'Hora: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(240,23);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(240,26);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(240,29);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
        $this->Ln(2);
		$this->SetXY(240,32);
		$this->SetFont('Arial','b',5);	
        $this->Cell(10,2,'https://www.diskcoversystem.com',0,0,'L');
		$this->Line(20, 40, 300-20, 40); 
        $this->Line(20, 41, 300-20, 41);
		$this->Ln($this->salto_header_cuerpo);

	}

 }
}

class PDF_MC_SIN_HEADER extends PDF_MC_Table
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;


    function Header()
    {
   
   // print($_SESSION['INGRESO']['Logo_Tipo']);
    	
			      $this->SetTextColor(0,0,0);
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover_web";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		   			}

		   		}

		   	}
		  }

		  // print_r($_SESSION['INGRESO']);die();

         $this->Image($src,10,3,35,20); 
         $this->SetFont('Times','I',9);
         $this->SetXY(50,3);
		 $this->Cell(0,3,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		 $this->Ln(5);		 
         $this->SetX(50);
		 $this->Cell(0,3,strtoupper($_SESSION['INGRESO']['Nombre_Comercial']),0,0,'L');				
		 $this->Ln(5);
		 $this->SetX(50);
		 $this->Cell(0,3,strtoupper('R.U.C: '.$_SESSION['INGRESO']['RUC']),0,0,'L');				
		 $this->Ln(3);
		 $this->SetX(50);
		 $this->SetFont('Times','I',7);
		 $this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'])),0,0,'L');
		 $this->Ln(3);
		 $this->SetX(50);
    	 $this->SetFont('Times','I',7);
		 $this->Cell(0,3,ucfirst(strtolower('Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'L');		 
		 $this->Ln(3);
		 $this->SetX(50);
    	 $this->SetFont('Times','I',7);
		 $this->Cell(0,3,ucfirst(strtolower('QUITO-ECUADOR')),0,0,'L');
		 $this->Ln(8);
 }
}


?>