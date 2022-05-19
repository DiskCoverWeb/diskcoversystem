<?php
    error_reporting(E_ALL);
ini_set('display_errors', '1');  
	//require('fpdf.php');
	require('PDF_MC_Table.php');

	if(isset($_POST['nombrep']))
	{
		$nombre=addslashes($_POST['nombrep']);
		$tabla=addslashes($_POST['sep']);
		$pagina=addslashes($_POST['paginap']);
		$campo=addslashes($_POST['campo']);
		$valor=addslashes($_POST['valor']);
		$empresa=addslashes($_POST['empresa']);
		$rif=addslashes($_POST['rif']);
		$va = split("=",$valor);
		//echo $nombre.' 2 '.$tabla.' 3 '.$pagina.' 4 '.$campo.' 5 '.$va[1];
		//die();
	}
	
class PDF extends PDF_MC_Table
{
	
}//Fin de la clase
//para imprimir factura y prefactura en formato pequeño
function imprimirDocElPF($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,$param=null,$tipof=null,$conec=null,$ruta=null)
{
	if($ruta==null)
	{
		$ruta='../ajax/TEMP/';
	}
	if($tipof!=null)
	{
		$tipo = $tipof;
	}
	else
	{
		$tipo = 'PF';
	}
	// datos que se deben llenar desde la base de datos 
	$fecha=date("Y-m-d");
	$hoy = date('H:m:s');
	//obtenemos informacion
	if($conec!=null)
	{
		$cid=$conec;
	}
	else
	{
		$cid=cone_ajaxSQL();
	}
	$sql="SELECT * FROM  Empresas WHERE
		Item = '".$_SESSION['INGRESO']['item']."' 
	";
	
		//echo $sql;
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$nome=$row[6];
		$ruce=$row[8];
		$tele=$row[9];
		$dire=$row[12];
		$suc=$row[20];
		if($suc=='' or $suc==null)
		{
			$suc=$dire;
		}			
	}
	//datos cliente
	//$cid=cone_ajaxSQL();
	if($tipo == 'PF')
	{
		$sql=" SELECT TOP(1) * FROM  Clientes
		WHERE  (CI_RUC LIKE '%9999999%')
		";
	}
	else
	{
		$sql=" SELECT * FROM  Clientes
		WHERE  (CI_RUC = '".$param[0]['ruc']."')
		";
	}
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$cli=$row[5];
		$direcc=$row[17];	
		$email=$row[14];
		$telef=$row[20];
	}
	if($tipo == 'PF')
	{
		$sql="SELECT  *
		FROM            Asiento_F
		WHERE        (HABIT = '".$param[0]['mesa']."')";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$preciot=0;
		$iva=0;
		$tota=0;
		$i=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$precio=$row[9];
			$tot=$row[10];
			$pre=$row[4];
			$tota=$tot+$tota;
			$iva=$iva+$row[7];
			$cantidad=$row[1];
			$detalle=$row[3];
			$preciot=$preciot+$precio;
			$lineas[$i]['cant'] = $cantidad;
			if($row[7]==0)
			{
				$lineas[$i]['detalle']= $detalle.' (E)';
			}
			else
			{
				$lineas[$i]['detalle']= $detalle;
			}
			$lineas[$i]['pvp']=$pre;
			$lineas[$i]['total']=$precio;
			$i++;
		}
		$datos = array('numfactura' => '0','numautorizacio' =>'0','fechafac'=>$fecha,
		'horafac' => $hoy,'razon'=>'.','ci'=>'0',
		'telefono' =>$telef,'email' =>$email,'subtotal'=>$preciot,'dto'=>'0.00','iva'=>$iva,'totalfac'=>$tota );
	}
	else
	{
		$sql="SELECT  *
		FROM     Detalle_Factura
		WHERE    (Item = '".$_SESSION['INGRESO']['item']."') AND (Serie = '".$param[0]['serie']."') 
		AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Factura = '".$param[0]['factura']."')";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$preciot=0;
		$iva=0;
		$tota=0;
		$i=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$precio=$row[10];
			$tot=$row[11]+$row[12];
			$pre=$row[10];
			$tota=$tot+$tota;
			$iva=$iva+$row[12];
			$cantidad=$row[9];
			$detalle=$row[8];
			$preciot=$preciot+($precio*$cantidad);
			$lineas[$i]['cant'] = $cantidad;
			if($row[12]==0)
			{
				$lineas[$i]['detalle']= $detalle.' (E)';
			}
			else
			{
				$lineas[$i]['detalle']= $detalle;
			}
			$lineas[$i]['pvp']=$pre;
			$lineas[$i]['total']=$precio*$cantidad;
			$i++;
		}
		$datos = array('numfactura' => $param[0]['factura'],'numautorizacio' =>'0','fechafac'=>$fecha,
		'horafac' => $hoy,'razon'=>$cli,'ci'=>$param[0]['ruc'],
		'telefono' =>$telef,'email' =>$email,'subtotal'=>$preciot,'dto'=>'0.00','iva'=>$iva,'totalfac'=>$tota );
		/*$datos = array('numfactura' => '0','numautorizacio' =>'0','fechafac'=>$fecha,
		'horafac' => $hoy,'razon'=>$cli,'ci'=>'1234567890',
		'telefono' =>'09999999999','email' =>'example@example.com','subtotal'=>'450.00','dto'=>'0.00','iva'=>'54.00','totalfac'=>'504.00' );
		
		$lineas = array(
		'0'=>array('cant' => '2','detalle'=>' servicio de mantenimineto','pvp'=>'450.00','total'=>'450.00' ),
		'1'=>array('cant' => '3','detalle'=>' servicio de mantenimineto de servidores','pvp'=>'450.00','total'=>'450.00' ),
        '2'=>array('cant' => '3','detalle'=>' servicio de mantenimineto de servidores','pvp'=>'450.00','total'=>'450.00' ),  );*/
	}
	
// fin de datos de la base de datos




    $pdf = new FPDF('P','mm',array(90, 300));
    $pdf->AddPage();
    $salto = 5;
    // Logo
    //$pdf->Image('../../img/jpg/logo_doc.jpg',3,3,35,20);
	//../../img/jpg/logo_doc.jpg
	
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$src = __DIR__ . '/../../img/jpg/logo_doc.jpg'; 
		if (@getimagesize($src)) 
		{
			$pdf->Image(__DIR__ . '/../../img/jpg/logo_doc.jpg',3,3,28,20,'','https://www.discoversystem.com');
		}
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',3,3,35,20,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',3,3,35,20,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',3,3,35,20,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',3,3,35,20,'','https://www.discoversystem.com');
	}
	
    // Arial bold 15
    $pdf->SetFont('Arial','B',12);
    // Título
    $pdf->Cell(30);
    $pdf->Cell(0,5,'RUC',0,0,'C');
    $pdf->Ln($salto);
    $pdf->Cell(30);
    $pdf->Cell(0,5,$ruce,0,0);
    $pdf->Ln($salto);
    $pdf->Cell(30);
    $pdf->Cell(0,5,'Telefono:'.$tele.'',0,0);
    $pdf->Ln($salto);
    $pdf->Cell(0,5,$nome,0,0,'C');
    //$pdf->Ln($salto);
    //$pdf->Cell(0,5,$nome,0,0,'C');


    $pdf->Ln($salto);
    $pdf->SetFont('Arial','B',8);
    $pdf->Ln($salto);
    $pdf->MultiCell(70,3,$dire);
    $pdf->Ln(2);
    $pdf->Cell(0,3,'OBLIGADO A LLEVAR CONTABILIDAD: SI',0,0);
    $pdf->Ln($salto);
    $pdf->Cell(0,0,'',1,0);



    $pdf->SetFont('');
    $pdf->Ln(1);
    if($tipo == 'F')
    {    	
     $pdf->Cell(0,5,'FACTURA No:'.$datos['numfactura'],0,0);
    }
    else
    {
     $pdf->Cell(0,5,'PREFACTURA No:',0,0);    	
    }
    $pdf->Ln($salto);
    $pdf->MultiCell(70,3,'NUMERO DE AUTORIZACION '.$datos['numautorizacio'],0,'L');
    $pdf->Ln(1);
    $pdf->Cell(35,5,'FECHA:'.$datos['fechafac'],0,0);
    $pdf->Cell(35,5,'HORA:'.$datos['horafac'],0,0);
    $pdf->Ln($salto);
    $pdf->Cell(40,5,'AMBIENTE:Produccion',0,0);
    $pdf->Cell(30,5,'EMISION:Normal',0,0);
    $pdf->Ln($salto);  
	
    $pdf->MultiCell(70,3,'CLAVE DE ACCESO '.$datos['numautorizacio'],0,'L');
    $pdf->Ln(3);   
    $pdf->Cell(0,0,'',1,0);
    
    $pdf->Ln(3);   
    $pdf->MultiCell(70,3,'Razon social/Nombres y Apellidos: '.$datos['razon'],0,'L');
    $pdf->Cell(35,5,'Identificacion:'.$datos['ci'],0,0);
    $pdf->Cell(35,5,'Telefono:'.$datos['telefono'],0,0);
    $pdf->Ln($salto);
    $pdf->Cell(0,0,'Email.'.$datos['email'],0,0);
    $pdf->Ln(3);
    $pdf->Cell(0,0,'',1,0); 

    $pdf->Ln(1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10,2,'Cant',0,0);
    $pdf->Cell(28,2,'PRODUCTO',0,0,'C');
    $pdf->Cell(15,2,'P.V.P',0,0,'R');
    $pdf->Cell(17,2,'TOTAL',0,0,'R');
    $pdf->Ln($salto);
   
//    se cargan las lineas de la factura
    foreach ($lineas as $value) {
   
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(10,3,$value['cant'],0,0);
     $y2 = $pdf->GetY();
    $pdf->MultiCell(28,3,$value['detalle'],0,'L');
    $y = $pdf->GetY();
    $pdf->SetXY(48,$y2);
    $pdf->Cell(15,3,number_format($value['pvp'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']),0,0,'R');
    $pdf->Cell(17,3,number_format($value['total'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']),0,0,'R');
    $pdf->SetY($y);
    $pdf->Ln(3);
    $pdf->Cell(0,0,'',1,0); 
    $pdf->Ln(3);

    }
// fin de carga de lineas de factura

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(35,2,'Cajero',0,0,'L');
    $pdf->Cell(17,2,'SUBTOTAL :',0,0,'L');
    $pdf->Cell(17,2,number_format($datos['subtotal'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']),0,0,'R');
    $pdf->Ln(3);

    $pdf->Cell(35,2,'',0,0,'L');
    $pdf->Cell(17,2,'DESCUENTOS :',0,0,'L');
    $pdf->Cell(17,2,number_format($datos['dto'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']),0,0,'R');
    $pdf->Ln(3);


    $pdf->Cell(35,2,'',0,0,'L');
    $pdf->Cell(17,2,'I.V.A 12% :',0,0,'L');
    $pdf->Cell(17,2,number_format($datos['iva'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']),0,0,'R');
    $pdf->Ln(3);


    $y2 = $pdf->GetY();
    $pdf->MultiCell(35,2,'Su factura sera enviada al correo electronico registrado',0,'L');
    $y = $pdf->GetY();
    $pdf->SetXY(45,$y2);
    $pdf->Cell(17,2,'TOTAL :',0,0,'L');
    $pdf->Cell(17,2,number_format($datos['totalfac'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']),0,0,'R');
	 $pdf->Ln(3);
	 $pdf->SetXY(45,$y);
	 //$pdf->SetY($y);
	 if($tipo == 'PF')
    {  
		$pdf->Cell(17,2,'Propina :',0,0,'L');
		$pdf->Cell(17,2,'',0,0,'R');
		$pdf->SetY($y+2);
	}
    $pdf->Ln(3);
    $pdf->Cell(0,0,'',1,0); 
    $pdf->Ln($salto);
	
	//agregamos las lineas para los datos
	if($tipo == 'PF')
    {   
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(0,5,'Datos de factura',0,0,'C');
		$pdf->Ln($salto);
		$pdf->SetFont('Arial','B',10);
		/*$pdf->Cell(0,5,'Nombre  CI/RUC  Correo   Telf  DIR',0,0,'C');
		//$pdf->Cell(0,5,'TIPO PAGO                MONTO    ',0,0,'C');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);*/
		$pdf->Cell(0,5,'Nombre',0,0,'L');
		//$pdf->Cell(0,5,'TIPO PAGO                MONTO    ',0,0,'C');
		
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Cell(0,5,'CI/RUC',0,0,'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Cell(0,5,'Correo',0,0,'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Cell(0,5,'Telefono',0,0,'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Cell(0,5,'Direccion',0,0,'L');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		
    }
    
    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,5,'Fue un placer atenderle',0,0,'C');
	$src = __DIR__ . '/../../img/png/cara_feliz.png'; 
	if (@getimagesize($src)) 
	{
		$y = $pdf->GetY();
		$x = $pdf->GetX();
		$pdf->Image(__DIR__ . '/../../img/png/cara_feliz.png',($x-15),$y,5,5,'','https://www.discoversystem.com');
		$pdf->Image(__DIR__ . '/../../img/png/copa.png',($x-10),$y,5,5,'','https://www.discoversystem.com');
	}
    $pdf->Ln($salto);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(0,5,'www.cofradiadelvino.com',0,0,'C');
    $pdf->Ln($salto);
	
	
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		//echo dirname(__DIR__,1).'/php/vista/appr/ajax/TEMP/'.$id.'.pdf'."";
		//die();
		//$pdf->Output('TEMP/'.$id.'.pdf','F'); 
		//$pdf->Output('../ajax/TEMP/'.$id.'.pdf','F'); 
		$pdf->Output($ruta.$id.'.pdf','F'); 
	}
}
//imprimir doc electronico
/* $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
 function imprimirDocElP($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null,$param=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();


	$i=0;
	if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		//echo $autorizacion->fechaAutorizacion."<br>";
		//sustituimos
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		//echo etiqueta_xml($resultado,"<ruc>"); 
		//echo etiqueta_xml($resultado,"<razonSocial>"); 
		//echo $resultado;
		//$auto=simplexml_load_string($resultado);
		//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial','B',30);
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
	}
	else
	{
		$logo="diskcover";
	}
	$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	/*
	SELECT        TOP (1) Periodo, TL, Codigo, Concepto, Fact, CxC, Cta_Venta, Logo_Factura, Largo, Ancho, Item, Individual, Espacios, Pos_Factura, Fact_Pag, Pos_Y_Fact, Serie, Autorizacion, Vencimiento, Fecha, Secuencial, ItemsxFA, 
                    Grupo_I, Grupo_F, CxC_Anterior, Imp_Mes, Nombre_Establecimiento, Direccion_Establecimiento, Telefono_Estab, Logo_Tipo_Estab, Tipo_Impresion, ID, X
				FROM            Catalogo_Lineas
				WHERE        (LEN(Autorizacion) >= 13) AND (Periodo = '.') AND (Item = '001') AND (Fact = 'FA') AND (Fecha <= '2020-03-11') AND (Vencimiento >= '2020-03-11')
	
	SELECT        Opc, Grupo, Item, Fecha, Ciudad, Pais, Empresa, Gerente, RUC, Telefono1, Telefono2, FAX, Direccion, SubDir, Logo_Tipo, Alto, Servicio, S_M, Cta_Caja, Cotizacion, Sucursal, Email, Contador, CodBanco, Num_CD, 
		 Num_CE, Num_CI, Nombre_Comercial, Mod_Fact, Mod_Fecha, Plazo_Fijo, Det_Comp, CI_Representante, TD, RUC_Contador, CPais, No_Patronal, Dec_PVP, Dec_Costo, CProv, Grabar_PV, Num_Meses, Separar_Grupos, Credito, 
		 Medio_Rol, Sueldo_Basico, Cant_Item_PV, Copia_PV, Encabezado_PV, Calcular_Comision, Formato_Inventario, Cant_Ancho_PV, Grafico_PV, Formato_Activo, Num_ND, Num_NC, Referencia, Fecha_Rifa, Rifa, Monto_Minimo, 
		 Rol_2_Pagina, Cierre_Vertical, Tipo_Carga_Banco, Comision_Ejecutivo, Seguro, Nombre_Banco, Impresora_Rodillo, Costo_Bancario, Impresora_Defecto, Papel_Impresora, Marca_Agua, Seguro2, Cta_Banco, Mod_PVP, 
		 Abreviatura, Registrar_IVA, Imp_Recibo_Caja, Det_SubMod, Establecimientos, Email_Conexion, Email_Contraseña, Actualizar_Buses, Email_Contabilidad, Cierre_Individual, Email_Respaldos, Imp_Ceros, Tesorero, CIT, 
		 Razon_Social, Dec_IVA, Dec_Cant, Ambiente, Ruta_Certificado, Clave_Certificado, Web_SRI_Recepcion, Web_SRI_Autorizado, Codigo_Contribuyente_Especial, Formato_Cuentas, Email_Conexion_CE, Email_Contraseña_CE, 
		 No_ATS, Obligado_Conta, No_Autorizar, Email_Procesos, Email_CE_Copia, Estado, Firma_Digital, ID, SP, Combo, Por_CxC, Fecha_Igualar, Ret_Aut, LeyendaFA, Signo_Dec, Signo_Mil, Fecha_CE, LeyendaFAT, Centro_Costos, 
		 smtp_Servidor, smtp_Puerto, smtp_UseAuntentificacion, smtp_SSL
	FROM            Empresas
	*/
	//datos empresa
	$cid=cone_ajaxSQL();
	$sql="SELECT * FROM  Empresas WHERE
		Item = '".$_SESSION['INGRESO']['item']."' 
	";
	
		//echo $sql;
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$nome=$row[6];
		$ruce=$row[8];
		$dire=$row[12];
		$suc=$row[20];
		if($suc=='' or $suc==null)
		{
			$suc=$dire;
		}			
	}
	//datos cliente
	$cid=cone_ajaxSQL();
	$sql=" SELECT * FROM  Clientes
	WHERE  (CI_RUC = '".$param[0]['ruc']."')
	";
	
		//echo $sql;
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$cli=$row[5];
		$direcc=$row[17];	
	}
	$sql="SELECT  *
	FROM            Asiento_F
	WHERE        (HABIT = '".$param[0]['mesa']."')";
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$preciot=0;
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$precio=$row[9];
		$preciot=$preciot+$precio;
	}
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	//texto 
	//ruc
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,150));
	$arr=array('R.U.C.:     ', $ruce);
	$pdf->Row($arr,10);
	 //factura
	 $pdf->SetXY(285, 47);
	 $pdf->SetWidths(array(140));
	$arr=array('Factura No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr=array('');
	/*$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
	etiqueta_xml($resultado,"<secuencial>"));*/
	$pdf->Row($arr,10);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr=array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr=array('');
	//$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array('EMISIÓN:');
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if(etiqueta_xml($resultado,"<ambiente>")==2)
	{
		$arr=array('PRODUCCIÓN');
	}
	else
	{
		$arr=array('PRUEBA');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr,10);
	/*$pdf->SetXY(410, 180);
	$pdf->SetWidths(array(275));
	$arr=array('000000000000000000000000000000000000000000000000');
	$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code=$atrib['numeroAutorizacion'];
	$code='11';
	$pdf->SetXY(285,109);
	$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr=array($code);
	$pdf->Row($arr,10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array($nome);
	$pdf->Row($arr,10);
	
	
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array( $nome);
	//$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Matríz');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	
	$arr=array($dire);
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Sucursal');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr=array($suc);
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array('Razón social/nombres y apellidos:','','Identificación:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	
	//$arr=array(utf8_encode($param[0]['nombrec']),'',etiqueta_xml($resultado,"<identificacionComprador>"));
	$arr=array(utf8_encode($cli),'',utf8_encode($param[0]['ruc']));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	//para direccion
	//$arr1=etiqueta_xml($resultado,"<campoAdicional");
	$arr1=$direcc;
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
		$adi=$direcc;
	}
	$arr=array('Dirección: '.$adi,
	'Fecha emisión: '.etiqueta_xml($resultado,"<fechaEmision>"),'Fecha pago: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(270,155,100));
	if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	$arr=array('FORMA DE PAGO: COBRO '.$cli,
	'MONTO: '.$mon.'  '.$preciot
	,'Condición de venta: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
	$arr=array("Codigo Unitario","Codigo Auxiliar","Cantidad Total","Cantidad Bonif.","Descripción",
	"Lote","Precio Unitario","Valor Descuento","Desc. %","Valor Total");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<codigoPrincipal");
	if(is_array($arr1))
	{
		$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
		if($arr2=='')
		{
			$arr2=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr2[$i]='';
			}
		}
		$arr3=etiqueta_xml($resultado,"<cantidad");
		if($arr3=='')
		{
			$arr3=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr3[$i]='';
			}
		}
		$arr4='';
		$arr5=etiqueta_xml($resultado,"<descripcion");
		if($arr5=='')
		{
			$arr5=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr5[$i]='';
			}
		}
		$arr6='';
		$arr7=etiqueta_xml($resultado,"<precioUnitario");
		if($arr7=='')
		{
			$arr7=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr7[$i]='';
			}
		}
		$arr8=etiqueta_xml($resultado,"<descuento");
		if($arr8=='')
		{
			$arr8=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr8[$i]='';
			}
		}
		$arr9='';
		$arr10=etiqueta_xml($resultado,"<precioTotalSinImpuesto");
		if($arr10=='')
		{
			$arr10=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr10[$i]='';
			}
		}
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
			$pdf->SetAligns(array("L","L","R","R","L","L","R","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($arr1[$i],$arr2[$i],$arr3[$i],$arr4,$arr5[$i],$arr6,
			$arr7[$i],$arr8[$i],$arr9,$arr10[$i]);
			$pdf->Row($arr,10,1);
		}
	}
	else
	{
		$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
		$pdf->SetAligns(array("L","L","R","R","L","L","R","R","R","R"));
		$arr=array(etiqueta_xml($resultado,"<codigoPrincipal>"),etiqueta_xml($resultado,"<codigoAuxiliar>"),
		etiqueta_xml($resultado,"<cantidad>"),'',
		etiqueta_xml($resultado,"<descripcion>"),'',
		etiqueta_xml($resultado,"<precioUnitario>"),etiqueta_xml($resultado,"<descuento>"),
		'',etiqueta_xml($resultado,"<precioTotalSinImpuesto>"));
		$pdf->Row($arr,10,1);
	}
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(140,40,95,46));
	$arr=array("INFORMACIÓN ADICIONAL","Fecha","Deltalle del pago","Monto Abono");
	$pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(140,60,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			if($i==1)
			{
				$adi='Telefono: ';
			}
			if($i==2)
			{
				$adi='Email: ';
			}
			if($i!=0)
			{
				$pdf->SetWidths(array(140));
				$arr=array($adi.$arr1[$i]);
				$pdf->Row($arr,10);
			}
		}
	}
	else
	{
		$pdf->SetWidths(array(140));
		$pdf->Row($arr,10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y+5);
	$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5);
	$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	$pdf->SetWidths(array(319));
	$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');
	$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, ($y-10));
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y-9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1=porcion_xml($resultado,"<totalImpuesto>","</totalImpuesto>");
	$imp=0;
	$ba0=0;
	$bai=0;
	$vimp0=0;
	$vimp1=0;
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			//echo $arr1[$i].'<br>';
			$arr2=etiqueta_xml($arr1[$i],"<tarifa");
			if(is_array($arr2))
			{
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr2.' fff <br>';
				if($i==1)
				{
					$imp=$arr2;
				}
			}
			$arr3=etiqueta_xml($arr1[$i],"<baseImponible");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr3.' fff <br>';
				if($i==0)
				{
					$ba0=$arr3;
				}
				if($i==1)
				{
					$bai=$arr3;
				}
			}
			$arr4=etiqueta_xml($arr1[$i],"<valor");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr4.' fff <br>';
				if($i==0)
				{
					$vimp0=$arr4;
				}
				if($i==1)
				{
					$vimp1=$arr4;
				}
			}
		}
	}
	//die();
	$arr=array("SUBTOTAL ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y-9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	
	$arr=array($bai);
	$pdf->Row($arr,10);
	
	$y=$y-10+11;//365
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//380
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("TOTAL DESCUENTO:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<totalDescuento>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//395
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//410
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//425
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//440
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("ICE:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//455
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp1);
	$pdf->Row($arr,10);
	
	$y=$y+11;//470
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//485
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("PROPINA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<propina>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//500
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("VALOR TOTAL:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<importeTotal>"));
	$pdf->Row($arr,10);
	//echo ' ddd '.$imp1;
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
//imprimir doc electronico
/* $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
 function imprimirDocEl($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();


	/*$pdf->SetFillColor(80, 150, 200);
	$pdf->Rect(20, 50, 95, 20, 'F');
	$pdf->Line(20, 50, 10, 40);
	$pdf->SetXY(20, 50);
	$pdf->Cell(15, 6, '10, 10', 0 , 1); //Celda


	$pdf->SetFillColor(80, 150, 200);
	$pdf->Rect(20, 50, 95, 20, 'F');
	$pdf->Line(20, 50, 10, 40);
	$pdf->SetXY(20, 50);
	$pdf->Cell(15, 6, '10, 10', 0 , 1); //Celda

	//Amarillo
	$pdf->SetFillColor(255, 215, 0);
	$pdf->Rect(110, 10, 45 , 20, 'F');
	$pdf->Line(110, 10, 115, 15);
	$pdf->SetXY(115, 15);
	$pdf->Cell(15, 6, '110, 10', 0 , 1);
	//Verde
	$pdf->SetFillColor(0, 128, 0);
	$pdf->Rect(160, 10, 40 , 20, 'F');
	$pdf->Line(160, 10, 165, 15);
	$pdf->SetXY(165, 15  );
	$pdf->Cell(15, 6, '160, 10', 0 , 1);
	//========================================
	 
	//========================================
	//  Segundo bloque - 1 rectángulo       ==
	//========================================
	//Salmón
	$pdf->SetFillColor(255, 99, 71);
	$pdf->Rect(10, 35, 190, 140, 'F');
	$pdf->Line(10, 35, 15, 40);
	$pdf->SetXY(15, 40);
	$pdf->Cell(15, 6, '10, 35', 0 , 1);
	//========================================
	 
	//========================================
	//  Tercer bloque - 2 rectángulos       ==
	//========================================
	//Rosa
	$pdf->SetFillColor(255, 20, 147);
	$pdf->Rect(10, 180, 90, 50, 'F');
	$pdf->Line(10, 180, 15, 185);
	$pdf->SetXY(15, 185);
	$pdf->Cell(15, 6, '10, 180', 0 , 1);
	//Café
	$pdf->SetFillColor(233, 150, 122);
	$pdf->Rect(110, 180, 90, 50, 'F');
	$pdf->Line(110, 180, 115, 185);
	$pdf->SetXY(115, 185);
	$pdf->Cell(15, 6, '110, 180', 0 , 1);
	//========================================
	 
	//========================================
	//  Cuarto bloque - 6 rectángulos       ==
	//========================================
	//Verde
	$pdf->SetFillColor(124, 252, 0);
	$pdf->Rect(10, 235, 40, 25, 'F');
	$pdf->Line(10, 235, 15, 240);
	$pdf->SetXY(15, 240);
	$pdf->Cell(15, 6, '10, 235', 0 , 1);
	//Café
	$pdf->SetFillColor(160 ,82, 40);
	$pdf->Rect(60, 235, 40, 25, 'F');
	$pdf->Line(60, 235, 65, 240);
	$pdf->SetXY(65, 240);
	$pdf->Cell(15, 6, '60, 235', 0 , 1);
	//Marrón
	$pdf->SetFillColor(128, 0 ,0);
	$pdf->Rect(10, 265, 40, 25, 'F');
	$pdf->Line(10, 265, 15, 270);
	$pdf->SetXY(15, 270);
	$pdf->Cell(15, 6, '10, 265', 0 , 1);
	//Morado
	$pdf->SetFillColor(153, 50, 204);
	$pdf->Rect(60, 265, 40, 25, 'F');
	$pdf->Line(60, 265, 65, 270);
	$pdf->SetXY(65, 270);
	$pdf->Cell(15, 6, '60, 265', 0 , 1);
	//Azul
	$pdf->SetFillColor(0, 191, 255);
	$pdf->Rect(110, 235, 90, 25, 'F');
	$pdf->Line(110, 235, 115, 240);
	$pdf->SetXY(115, 240);
	$pdf->Cell(15, 6, '110, 235', 0 , 1);
	//Verde
	$pdf->SetFillColor(173, 255, 47);
	$pdf->Rect(110, 265, 90, 25, 'F');
	$pdf->Line(110, 265, 115, 270);
	$pdf->SetXY(115, 270);
	$pdf->Cell(15, 6, '110, 265', 0 , 1);
	$pdf->AddPage();

	$miCabecera = array('Nombre de campo', 'Apellido', 'Matrícula campo');
	 
	$misDatos = array(
				array('nombre' => 'Esperbeneplatoledo', 'apellido' => 'Martínez', 'matricula' => '20420423'),
				array('nombre' => 'Araceli', 'apellido' => 'Morales', 'matricula' =>  '204909'),
				array('nombre' => 'Georginadavabulus', 'apellido' => 'Galindo', 'matricula' =>  '2043442'),
				array('nombre' => 'Luis', 'apellido' => 'Dolores', 'matricula' => '20411122'),
				array('nombre' => 'Mario', 'apellido' => 'Linares', 'matricula' => '2049990'),
				array('nombre' => 'Viridianapaliragama', 'apellido' => 'Badillo', 'matricula' => '20418855'),
				array('nombre' => 'Yadiramentoladosor', 'apellido' => 'García', 'matricula' => '20443335')
				);
				
	 $pdf->Ln(10);
	$pdf->tablaHorizontal($miCabecera, $misDatos);

	$pdf->AddPage();
	$pdf->cabeceraHorizontal(array('fgfdgfdgfdgfdgdfgffdgfdgfdgfdfdfd'));
	$pdf->Ln(50);
	$pdf->SetWidths(array(70,80,80,80,80,80,80,80,70));
	$pdf->SetAligns(array('C','C','C','C','C','C','C','C','C'));
	$arr=array('Csssssssssssssssssssssssssssssssssssssssssssssssssss','C','C','C','C','C','C','C','C');
	$pdf->Row($arr,13);

	$pdf->AddPage();*/
	//logo
	$i=0;
	if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		//echo $autorizacion->fechaAutorizacion."<br>";
		//sustituimos
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		//echo etiqueta_xml($resultado,"<ruc>"); 
		//echo etiqueta_xml($resultado,"<razonSocial>"); 
		//echo $resultado;
		//$auto=simplexml_load_string($resultado);
		//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial','B',28);
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
	}
	else
	{
		$logo="diskcover";
	}
	$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	//texto 
	//ruc
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,150));
	$arr=array('R.U.C.     ', etiqueta_xml($resultado,"<ruc>"));
	$pdf->Row($arr,10);
	 //factura
	 $pdf->SetXY(285, 47);
	 $pdf->SetWidths(array(140));
	$arr=array('Factura No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
	etiqueta_xml($resultado,"<secuencial>"));
	$pdf->Row($arr,10);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr=array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array('EMISIÓN:');
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if(etiqueta_xml($resultado,"<ambiente>")==2)
	{
		$arr=array('PRODUCCIÓN');
	}
	else
	{
		$arr=array('PRUEBA');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr,10);
	/*$pdf->SetXY(410, 180);
	$pdf->SetWidths(array(275));
	$arr=array('000000000000000000000000000000000000000000000000');
	$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code=$atrib['numeroAutorizacion'];
	$pdf->SetXY(285,109);
	$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr=array($code);
	$pdf->Row($arr,10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<razonSocial>"));
	$pdf->Row($arr,10);
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Matríz');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirMatriz>"));
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Sucursal');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirEstablecimiento>"));
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array('Razón social/nombres y apellidos:','','Identificación:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	$arr=array(etiqueta_xml($resultado,"<razonSocialComprador>"),'',etiqueta_xml($resultado,"<identificacionComprador>"));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	//para direccion
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
	}
	$arr=array('Dirección: '.$adi,
	'Fecha emisión: '.etiqueta_xml($resultado,"<fechaEmision>"),'Fecha pago: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(270,155,100));
	if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	$arr=array('FORMA DE PAGO: '.etiqueta_xml($resultado,"<razonSocialComprador>"),
	'MONTO: '.$mon.'  '.etiqueta_xml($resultado,"<importeTotal>")
	,'Condición de venta: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
	$arr=array("Codigo Unitario","Codigo Auxiliar","Cantidad Total","Cantidad Bonif.","Descripción",
	"Lote","Precio Unitario","Valor Descuento","Desc. %","Valor Total");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<codigoPrincipal");
	if(is_array($arr1))
	{
		$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
		if($arr2=='')
		{
			$arr2=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr2[$i]='';
			}
		}
		$arr3=etiqueta_xml($resultado,"<cantidad");
		if($arr3=='')
		{
			$arr3=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr3[$i]='';
			}
		}
		$arr4='';
		$arr5=etiqueta_xml($resultado,"<descripcion");
		if($arr5=='')
		{
			$arr5=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr5[$i]='';
			}
		}
		$arr6='';
		$arr7=etiqueta_xml($resultado,"<precioUnitario");
		if($arr7=='')
		{
			$arr7=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr7[$i]='';
			}
		}
		$arr8=etiqueta_xml($resultado,"<descuento");
		if($arr8=='')
		{
			$arr8=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr8[$i]='';
			}
		}
		$arr9='';
		$arr10=etiqueta_xml($resultado,"<precioTotalSinImpuesto");
		if($arr10=='')
		{
			$arr10=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr10[$i]='';
			}
		}
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
			$pdf->SetAligns(array("L","L","R","R","L","L","R","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($arr1[$i],$arr2[$i],$arr3[$i],$arr4,$arr5[$i],$arr6,
			$arr7[$i],$arr8[$i],$arr9,$arr10[$i]);
			$pdf->Row($arr,10,1);
		}
	}
	else
	{
		$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
		$pdf->SetAligns(array("L","L","R","R","L","L","R","R","R","R"));
		$arr=array(etiqueta_xml($resultado,"<codigoPrincipal>"),etiqueta_xml($resultado,"<codigoAuxiliar>"),
		etiqueta_xml($resultado,"<cantidad>"),'',
		etiqueta_xml($resultado,"<descripcion>"),'',
		etiqueta_xml($resultado,"<precioUnitario>"),etiqueta_xml($resultado,"<descuento>"),
		'',etiqueta_xml($resultado,"<precioTotalSinImpuesto>"));
		$pdf->Row($arr,10,1);
	}
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(140,40,95,46));
	$arr=array("INFORMACIÓN ADICIONAL","Fecha","Deltalle del pago","Monto Abono");
	$pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(140,60,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			if($i==1)
			{
				$adi='Telefono: ';
			}
			if($i==2)
			{
				$adi='Email: ';
			}
			if($i!=0)
			{
				$pdf->SetWidths(array(140));
				$arr=array($adi.$arr1[$i]);
				$pdf->Row($arr,10);
			}
		}
	}
	else
	{
		$pdf->SetWidths(array(140));
		$pdf->Row($arr,10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y+5);
	$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5);
	$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	$pdf->SetWidths(array(319));
	$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');
	$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, ($y-10));
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y-9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1=porcion_xml($resultado,"<totalImpuesto>","</totalImpuesto>");
	$imp=0;
	$ba0=0;
	$bai=0;
	$vimp0=0;
	$vimp1=0;
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			//echo $arr1[$i].'<br>';
			$arr2=etiqueta_xml($arr1[$i],"<tarifa");
			if(is_array($arr2))
			{
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr2.' fff <br>';
				if($i==1)
				{
					$imp=$arr2;
				}
			}
			$arr3=etiqueta_xml($arr1[$i],"<baseImponible");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr3.' fff <br>';
				if($i==0)
				{
					$ba0=$arr3;
				}
				if($i==1)
				{
					$bai=$arr3;
				}
			}
			$arr4=etiqueta_xml($arr1[$i],"<valor");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr4.' fff <br>';
				if($i==0)
				{
					$vimp0=$arr4;
				}
				if($i==1)
				{
					$vimp1=$arr4;
				}
			}
		}
	}
	//die();
	$arr=array("SUBTOTAL ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y-9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	
	$arr=array($bai);
	$pdf->Row($arr,10);
	
	$y=$y-10+11;//365
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//380
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("TOTAL DESCUENTO:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<totalDescuento>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//395
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//410
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//425
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//440
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("ICE:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//455
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp1);
	$pdf->Row($arr,10);
	
	$y=$y+11;//470
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//485
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("PROPINA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<propina>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//500
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("VALOR TOTAL:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<importeTotal>"));
	$pdf->Row($arr,10);
	//echo ' ddd '.$imp1;
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}

//
 function imprimirDocEle_fac($datos,$detalle,$educativo,$matri=null,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();
	$i=0;
	/*if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		$pdf->SetFont('Arial','B',30);
		*/
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));

	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}

	// print_r($datos);die();
	$tam = 9;
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,140));
	$arr=array('R.U.C.');//mio
	$pdf->Row($arr,10);
	$pdf->SetXY(425, 35);
	$pdf->SetWidths(array(140));
	$arr=array( $_SESSION['INGRESO']['RUCEnt']);//mio
	$pdf->Row($arr,10);
	 //factura
	$pdf->SetXY(285, 47);
	$pdf->SetWidths(array(140));
	$arr=array('Factura No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$ptoEmi = substr($datos[0]['Serie'],0,3);
	$Serie = substr($datos[0]['Serie'],0,-3);
	$arr=array($Serie.'-'.$ptoEmi.'-'.generaCeros($datos[0]['Factura'],$tam));//mio
	$pdf->Row($arr,10);
  // print_r($datos);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr=array(utf8_decode('FECHA Y HORA DE AUTORIZACIÓN:'));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr=array($datos[0]['Fecha_Aut']->format('Y-m-d  h:m:s'));//mio
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array(utf8_decode('EMISIÓN:'));
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente

	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if($_SESSION['INGRESO']['Ambiente']==1)
	{
	  $arr=array('Produccion');
	}else if($_SESSION['INGRESO']['Ambiente']==2)
	{
	  $arr=array('Prueba');
	}else
	{
		 $arr=array('');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array(utf8_decode('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO'));
	$pdf->Row($arr,10);
	if($datos[0]['Clave_Acceso'] != $datos[0]['Autorizacion'])
	 {
	$code=$datos[0]['Autorizacion'];
	//$pdf->SetXY(285,109);
	//$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',9);
	$pdf->SetXY(285, 110);
	$pdf->SetWidths(array(275));
	//$arr=array($code);
	//$pdf->Row($arr,10);
		$pdf->Cell(10,10,$code);
	 }else if($datos[0]['Clave_Acceso'] > 39)
	 {	 	
	    $code=$datos[0]['Clave_Acceso'];
	    $pdf->SetXY(285,109);
	    $pdf->Code128(290,109,$code,260,20);

	    //$pdf->Write(5,'C set: "'.$code.'"');
	    $pdf->SetFont('Arial','',7);
	    $pdf->SetXY(285, 130);
	    $pdf->SetWidths(array(275));
	    //$arr=array($code);
	    //$pdf->Row($arr,10);
	    $pdf->Cell(10,10,$code);
	 }

	
	/******************/
	/******************/
	/******************/
	
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array(utf8_decode($_SESSION['INGRESO']['Nombre_Comercial']));//mio
	$pdf->Row($arr,10);
//print_r($datos);
	
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array(utf8_decode($_SESSION['INGRESO']['Razon_Social']));//mio
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array(utf8_decode('Dirección Matríz'));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr=array(utf8_decode($_SESSION['INGRESO']['Direccion']));//mio
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array(utf8_decode('Dirección Sucursal'));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	if($_SESSION['INGRESO']['Sucursal']==0 || $_SESSION['INGRESO']['Sucursal']==1 )
	{
	  $arr=array(utf8_decode($_SESSION['INGRESO']['Direccion']));//mio
	}else
	{
	  $arr=array(utf8_decode($_SESSION['INGRESO']['Sucursal']));//mio
	}
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	if($_SESSION['INGRESO']['Obligado_Conta'] == 'NO')
	{
		$arr=array('NO');
	}else
	{
		$arr=array('SI');//mio
	}
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array(utf8_decode('Razón social/nombres y apellidos:'),'',utf8_decode('Identificación:'));//mio
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	$arr=array(utf8_decode($datos[0]['Razon_Social']),'',$datos[0]['RUC_CI']);//mio
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	
	//para direccion
	/*$arr1="<campoAdicional";
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
	}*/

	$arr=array(utf8_decode('Dirección: '.$datos[0]['Direccion_RS']),
	utf8_decode('Fecha emisión: '.$datos[0]['Fecha']->format('Y-m-d')),'Fecha pago: '.$datos[0]['Fecha']->format('Y-m-d'));//mio
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(270,155,100));
	if('DOLAR'=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	$arr=array('FORMA DE PAGO: '.$datos[0]['Forma_Pago'],
	'MONTO: '.$datos[0]['Total_MN'].'  '
	,utf8_decode('Condición de venta: '.$datos[0]['Fecha_C']->format('Y-m-d')));
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
	$arr=array("Codigo Unitario","Codigo Auxiliar","Cantidad Total","Cantidad Bonif.",utf8_decode("Descripción"),
	"Lote","Precio Unitario","Valor Descuento","Desc. %","Valor Total");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
 //print_r($detalle);

     foreach ($detalle as $key => $value) {
     		
     	 	$pdf->SetWidths(array(55,55,45,45,110,45,45,40,40,45));
			$pdf->SetAligns(array("L","L","R","R","L","L","R","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($value['Codigo'],$value['CodigoU'],$value['Cantidad'],'',$value['Producto'],'',sprintf("%01.2f", $value['Precio']),$value['Total_Desc'],'',$value['Total']);
			$pdf->Row($arr,10,1);    	
     }
   
	
	
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(140,40,95,46));
	$arr=array(utf8_decode("INFORMACIÓN ADICIONAL"),"Fecha","Deltalle del pago","Monto Abono");
    $pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(140,60,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
// datos adicionales
	$pdf->SetWidths(array(140));
	//print_r($educativo);

	if($matri != null)
	{
	if(isset($educativo[0]['Telefono_RS']) && $educativo[0]['Telefono_RS'] != '.' && $educativo[0]['Telefono_RS'] != '')
	{	
	$arr=array('Telefono: '.$educativo[0]['Telefono_RS']);
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(140));
    }
    if(isset( $educativo[0]['Lugar_Trabajo_R']) && $educativo[0]['Lugar_Trabajo_R'] != '.' && $educativo[0]['Lugar_Trabajo_R'] != '')
	{
	$arr=array('Direccion: '.$educativo[0]['Lugar_Trabajo_R']);
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(140));
    }
     if( isset($educativo[0]['Email_R']) && $educativo[0]['Email_R'] != '.' && $educativo[0]['Email_R'] != '')
	{	
	$arr=array('Emial: '.$educativo[0]['Email_R']);
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(140));
    }
    }else
    {
     if($educativo[0]['Telefono'] != '.' && $educativo[0]['Telefono'] != '')
	{		
    $arr=array('Telefono: '.$educativo[0]['Telefono']);
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(140));
    }
    if($educativo[0]['DirecionT'] != '.' && $educativo[0]['DirecionT'] != '')
	{		
	$arr=array('Direccion: '.$educativo[0]['DirecionT']);
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(140));
     }
	if($educativo[0]['Email'] != '.' && $educativo[0]['Email'] != '')
	{	
	
	$arr=array('Email: '.$educativo[0]['Email']);
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(140));
    }

    }


	//fecha
	$pdf->SetXY(181, $y+5);
	$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5);
	$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	$pdf->SetWidths(array(319));
	$arr=array($_SESSION['INGRESO']['LeyendaFA']);	
	$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, ($y-10));
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y-9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));

	//obtenemos valor


//	$arr1="<totalImpuesto";
	//$arr1=array("<totalImpuesto>","</totalImpuesto>");
	$sub_con_iva =0;
	$sub_sin_iva =0;
	 foreach ($detalle as $key => $value) {
     	
     	//print_r($value['Total_IVA']);
     	 	if($value['Total_IVA'] != .0000)
     	 	{
     	 		$sub_con_iva+=$value['Total'];
     	 	}else
     	 	{
     	 		$sub_sin_iva+=$value['Total'];

     	 	}
     }
	$imp= round($datos[0]['Porc_IVA']*100);
	$ba0=$sub_sin_iva;
	$bai=$sub_con_iva;
	$vimp0=0;
	$vimp1=$datos[0]['IVA'];
	$descu = $datos[0]['Descuento']+$datos[0]['Descuento2'];
	//print_r($datos);

	//die();
	$arr=array("SUBTOTAL ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y-9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $bai);
	$pdf->Cell(10,10,$formateado);

	$y=$y-10+11;//365
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(sprintf("%01.2f", $ba0));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(10,10,$formateado);
 	//echo $formateado;
	//str_pad($ba0, 2, '0', STR_PAD_RIGHT);
	//exit();
	//$pdf->Row($arr,10);
	
	$y=$y+11;//380
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("TOTAL DESCUENTO:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $descu);
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//395
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//410
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//425
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $ba0);
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//440
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("ICE:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//455
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $vimp1);
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//470
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $vimp1);
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//485
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("PROPINA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", "0.00");
	$pdf->Cell(10,10,$formateado);
	
	$y=$y+11;//500
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("VALOR TOTAL:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(545, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$formateado = sprintf("%01.2f", $datos[0]['Total_MN']);
	$pdf->Cell(10,10,$formateado);
	//echo ' ddd '.$imp1;
	//die();*/
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
/* imprimirDocElNC
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
 function imprimirDocElNC($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i=0;
	if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		//echo $autorizacion->fechaAutorizacion."<br>";
		//sustituimos
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		//echo etiqueta_xml($resultado,"<ruc>"); 
		//echo etiqueta_xml($resultado,"<razonSocial>"); 
		//echo $resultado;
		//$auto=simplexml_load_string($resultado);
		//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial','B',30);
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
	}
	else
	{
		$logo="diskcover";
	}
	$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	//texto 
	//ruc
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,150));
	$arr=array('R.U.C.:     ', etiqueta_xml($resultado,"<ruc>"));
	$pdf->Row($arr,10);
	 //factura
	 $pdf->SetXY(285, 47);
	 $pdf->SetWidths(array(140));
	$arr=array('Nota de credito No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
	etiqueta_xml($resultado,"<secuencial>"));
	$pdf->Row($arr,10);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr=array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array('EMISIÓN:');
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if(etiqueta_xml($resultado,"<ambiente>")==2)
	{
		$arr=array('PRODUCCIÓN');
	}
	else
	{
		$arr=array('PRUEBA');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr,10);
	/*$pdf->SetXY(410, 180);
	$pdf->SetWidths(array(275));
	$arr=array('000000000000000000000000000000000000000000000000');
	$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code=$atrib['numeroAutorizacion'];
	$pdf->SetXY(285,109);
	$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr=array($code);
	$pdf->Row($arr,10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<razonSocial>"));
	$pdf->Row($arr,10);
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Matríz');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirMatriz>"));
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Sucursal');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirEstablecimiento>"));
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array('Razón social/nombres y apellidos:','','Identificación:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	$arr=array(etiqueta_xml($resultado,"<razonSocialComprador>"),'',etiqueta_xml($resultado,"<identificacionComprador>"));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	//para direccion
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
	}
	$arr=array('Dirección: '.$adi,
	'Fecha emisión: '.etiqueta_xml($resultado,"<fechaEmision>"),'Fecha pago: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(270,155,100));
	if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	$arr=array('Comprobante que se modifica, Factura No. '.etiqueta_xml($resultado,"<numDocModificado>"),
	'MONTO: '.$mon.'  '.etiqueta_xml($resultado,"<importeTotal>")
	,'Fecha emisión (comprobante a modificar): '.etiqueta_xml($resultado,"<fechaEmisionDocSustento>"));
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(65,215,65,65,60,55));
	$arr=array("Codigo Unitario","Descripción","Cantidad Total",
	"Precio Unitario","Valor Descuento","Valor Total");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<codigoInterno");
	if(is_array($arr1))
	{
		/*$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
		if($arr2=='')
		{
			$arr2=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr2[$i]='';
			}
		}*/
		$arr3=etiqueta_xml($resultado,"<cantidad");
		if($arr3=='')
		{
			$arr3=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr3[$i]='';
			}
		}
		//$arr4='';
		$arr5=etiqueta_xml($resultado,"<descripcion");
		if($arr5=='')
		{
			$arr5=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr5[$i]='';
			}
		}
		//$arr6='';
		$arr7=etiqueta_xml($resultado,"<precioUnitario");
		if($arr7=='')
		{
			$arr7=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr7[$i]='';
			}
		}
		$arr8=etiqueta_xml($resultado,"<descuento");
		if($arr8=='')
		{
			$arr8=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr8[$i]='';
			}
		}
		//$arr9='';
		$arr10=etiqueta_xml($resultado,"<precioTotalSinImpuesto");
		if($arr10=='')
		{
			$arr10=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr10[$i]='';
			}
		}
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			$pdf->SetWidths(array(65,215,65,65,60,55));
			$pdf->SetAligns(array("L","L","R","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($arr1[$i],$arr3[$i],$arr5[$i],
			$arr7[$i],$arr8[$i],$arr10[$i]);
			$pdf->Row($arr,10,1);
		}
	}
	else
	{
		$pdf->SetWidths(array(65,215,65,65,60,55));
		$pdf->SetAligns(array("L","L","R","R","R","R"));
		$arr=array(etiqueta_xml($resultado,"<codigoInterno>"),
		etiqueta_xml($resultado,"<descripcion>"),
		etiqueta_xml($resultado,"<cantidad>"),
		etiqueta_xml($resultado,"<precioUnitario>"),etiqueta_xml($resultado,"<descuento>"),
		etiqueta_xml($resultado,"<precioTotalSinImpuesto>"));
		$pdf->Row($arr,10,1);
	}
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(140,40,95,46));
	$arr=array("INFORMACIÓN ADICIONAL","Fecha","Deltalle del pago","Monto Abono");
	$pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(140,60,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			if($i==1)
			{
				$adi='Telefono: ';
			}
			if($i==2)
			{
				$adi='Email: ';
			}
			if($i!=0)
			{
				$pdf->SetWidths(array(140));
				$arr=array($adi.$arr1[$i]);
				$pdf->Row($arr,10);
			}
		}
	}
	else
	{
		$pdf->SetWidths(array(140));
		$pdf->Row($arr,10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y+5);
	$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5);
	$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	$pdf->SetWidths(array(319));
	$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');
	$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, ($y-10));
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y-9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1=porcion_xml($resultado,"<totalImpuesto>","</totalImpuesto>");
	$imp=0;
	$ba0=0;
	$bai=0;
	$vimp0=0;
	$vimp1=0;
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			//echo $arr1[$i].'<br>';
			$arr2=etiqueta_xml($arr1[$i],"<tarifa");
			if(is_array($arr2))
			{
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr2.' fff <br>';
				if($i==1)
				{
					$imp=$arr2;
				}
			}
			$arr3=etiqueta_xml($arr1[$i],"<baseImponible");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr3.' fff <br>';
				if($i==0)
				{
					$ba0=$arr3;
				}
				if($i==1)
				{
					$bai=$arr3;
				}
			}
			$arr4=etiqueta_xml($arr1[$i],"<valor");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr4.' fff <br>';
				if($i==0)
				{
					$vimp0=$arr4;
				}
				if($i==1)
				{
					$vimp1=$arr4;
				}
			}
		}
	}
	//die();
	$arr=array("SUBTOTAL ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y-9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	
	$arr=array($bai);
	$pdf->Row($arr,10);
	
	$y=$y-10+11;//365
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//380
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("TOTAL DESCUENTO:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<totalDescuento>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//395
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//410
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//425
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//440
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("ICE:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//455
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp1);
	$pdf->Row($arr,10);
	
	$y=$y+11;//470
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//485
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("PROPINA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<propina>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//500
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("VALOR TOTAL:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<valorModificacion>"));
	$pdf->Row($arr,10);
	//echo ' ddd '.$imp1;
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
/* imprimirDocElRE
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
 function imprimirDocElRE($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i=0;
	if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		//echo $autorizacion->fechaAutorizacion."<br>";
		//sustituimos
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		//echo etiqueta_xml($resultado,"<ruc>"); 
		//echo etiqueta_xml($resultado,"<razonSocial>"); 
		//echo $resultado;
		//$auto=simplexml_load_string($resultado);
		//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial','B',30);
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
	}
	else
	{
		$logo="diskcover";
	}
	$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	//texto 
	//ruc
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,150));
	$arr=array('R.U.C.:     ', etiqueta_xml($resultado,"<ruc>"));
	$pdf->Row($arr,12);
	 //retencion
	 $pdf->SetFont('Arial','B',9);
	 $pdf->SetXY(285, 47);
	 $pdf->SetWidths(array(180));
	$arr=array('COMPROBANTE DE RETENCION No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',9);
	$pdf->SetXY(460, 47);
	$pdf->SetWidths(array(140));
	$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
	etiqueta_xml($resultado,"<secuencial>"));
	$pdf->Row($arr,10);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 58);
	$pdf->SetWidths(array(140));
	$arr=array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 58);
	$pdf->SetWidths(array(140));
	$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array('EMISIÓN:');
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if(etiqueta_xml($resultado,"<ambiente>")==2)
	{
		$arr=array('PRODUCCIÓN');
	}
	else
	{
		$arr=array('PRUEBA');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr,10);
	/*$pdf->SetXY(410, 180);
	$pdf->SetWidths(array(275));
	$arr=array('000000000000000000000000000000000000000000000000');
	$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code=$atrib['numeroAutorizacion'];
	$pdf->SetXY(285,109);
	$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr=array($code);
	$pdf->Row($arr,10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<razonSocial>"));
	$pdf->Row($arr,10);
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Matríz');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirMatriz>"));
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Sucursal');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirEstablecimiento>"));
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array('Razón social/nombres y apellidos:','','Identificación:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	$arr=array(etiqueta_xml($resultado,"<razonSocialSujetoRetenido>"),'',etiqueta_xml($resultado,"<identificacionSujetoRetenido>"));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	//para direccion
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
	}
	$arr=array('Dirección: '.$adi,
	'','');
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(270,155,100));
	if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	//para obtener el numero de documento
	$resul1= etiqueta_xml($resultado,"<numDocSustento");
	
	if(is_array($resul1))
	{
		$resul2= substr($resul1[0], 0, 6);
		$resul3= substr($resul1[0], 7, strlen($resul1[0]));
	}
	else
	{
		$resul2= substr($resul1, 0, 6);
		$resul3= substr($resul1, 7, strlen($resul1));
	}
	$arr=array('Documento Tipo Factura No. '.$resul2.'-'.$resul3,
	'   '
	,'Fecha emisión: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(75,200,65,60,65,60));
	$arr=array("Impuesto","Descripción",
	"Codigo Retención","Base Imponible","Porcentaje Retenido","Valor Retenido");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<codigo>");
	//echo $arr1[0].' aaa '.$arr1[1];
	//die();
	$valor_ret=0;
	if(is_array($arr1))
	{
		//para colocar texto segun formato del SRI
		$arr11=array();
		for ($i=0;$i<count($arr1);$i++)
		{
			$arr11[$i]=impuesto_re($arr1[$i]);
		}
		
		$arr2=etiqueta_xml($resultado,"<codigoRetencion");
		if($arr2=='')
		{
			$arr2=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr2[$i]='';
			}
		}
		else
		{
			$arr22=array();
			for ($i=0;$i<count($arr2);$i++)
			{
				$arr22[$i]=concepto_re($arr2[$i]);
			}
		}
		$arr3=etiqueta_xml($resultado,"<codigoRetencion");
		if($arr3=='')
		{
			$arr3=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr3[$i]='';
			}
		}
		$arr4=etiqueta_xml($resultado,"<baseImponible");
		if($arr4=='')
		{
			$arr4=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr4[$i]='';
			}
		}
		$arr5=etiqueta_xml($resultado,"<porcentajeRetener");
		if($arr5=='')
		{
			$arr5=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr5[$i]='';
			}
		}
		$arr6=etiqueta_xml($resultado,"<valorRetenido");
		if($arr6=='')
		{
			$arr6=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr6[$i]='';
			}
		}
		//sumar totales
		for ($i=0;$i<count($arr6);$i++)
		{
			$valor_ret=$valor_ret+$arr6[$i];
		}
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			$pdf->SetWidths(array(75,200,65,60,65,60));
			$pdf->SetAligns(array("L","L","L","R","R","R"));
			//$arr=array($arr1[$i]);
			//$arr=array('','','','','','');
			$arr=array($arr11[$i],$arr22[$i],$arr3[$i],$arr4[$i],$arr5[$i],$arr6[$i]);
			$pdf->Row($arr,10,1);
		}
	}
	else
	{
		$pdf->SetWidths(array(75,200,65,60,65,60));
		$pdf->SetAligns(array("L","L","L","R","R","R"));
		$arr=array(impuesto_re(etiqueta_xml($resultado,"<codigo>")),concepto_re(etiqueta_xml($resultado,"<codigoRetencion>")),
		etiqueta_xml($resultado,"<codigoRetencion>"),
		etiqueta_xml($resultado,"<baseImponible>"),
		etiqueta_xml($resultado,"<porcentajeRetener>"),etiqueta_xml($resultado,"<valorRetenido>"));
		$pdf->Row($arr,10,1);
		$valor_ret=etiqueta_xml($resultado,"<valorRetenido>");
	}
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(321));
	$arr=array("INFORMACIÓN ADICIONAL");
	$pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(321,30,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			if($i==1)
			{
				$adi='Telefono: ';
			}
			if($i==2)
			{
				$adi='Email: ';
			}
			if($i!=0 and $i<=2)
			{
				$pdf->SetWidths(array(140));
				$arr=array($adi.$arr1[$i]);
				$pdf->Row($arr,10);
			}
		}
	}
	else
	{
		$pdf->SetWidths(array(140));
		$pdf->Row($arr,10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y+5);
	//$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5+2);
	$arr11=porcion_xml($resultado,'<campoAdicional nombre="Comprobante No">','</campoAdicional>');
	//$arr11=etiqueta_xml($resultado,'<campoAdicional nombre="Comprobante No">');
	//echo $arr11[0];
	//die();
	$pdf->SetWidths(array(140));
	$arr=array('Tipo Comprobante: '.$arr11[0]);
	$pdf->Row($arr,10);
	//$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	//$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	//$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	//$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	//$pdf->SetWidths(array(319));
	/*$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');*/
	//$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	//$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, ($y-10));
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y-9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1=porcion_xml($resultado,"<totalImpuesto>","</totalImpuesto>");
	$imp=0;
	$ba0=0;
	$bai=0;
	$vimp0=0;
	$vimp1=0;
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			//echo $arr1[$i].'<br>';
			$arr2=etiqueta_xml($arr1[$i],"<tarifa");
			if(is_array($arr2))
			{
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr2.' fff <br>';
				if($i==1)
				{
					$imp=$arr2;
				}
			}
			$arr3=etiqueta_xml($arr1[$i],"<baseImponible");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr3.' fff <br>';
				if($i==0)
				{
					$ba0=$arr3;
				}
				if($i==1)
				{
					$bai=$arr3;
				}
			}
			$arr4=etiqueta_xml($arr1[$i],"<valor");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr4.' fff <br>';
				if($i==0)
				{
					$vimp0=$arr4;
				}
				if($i==1)
				{
					$vimp1=$arr4;
				}
			}
		}
	}
	//die();
	$arr=array("TOTAL RETENIDO ");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y-9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	
	$arr=array($valor_ret);
	$pdf->Row($arr,10);
	
	/*$y=$y-10+11;//365
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//380
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("TOTAL DESCUENTO:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<totalDescuento>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//395
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//410
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//425
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//440
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("ICE:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//455
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp1);
	$pdf->Row($arr,10);
	
	$y=$y+11;//470
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//485
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("PROPINA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<propina>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//500
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("VALOR TOTAL:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<importeTotal>"));
	$pdf->Row($arr,10);*/
	//echo ' ddd '.$imp1;
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
/* imprimirDocElGR
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
 function imprimirDocElGR($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i=0;
	if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		//echo $autorizacion->fechaAutorizacion."<br>";
		//sustituimos
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		//echo etiqueta_xml($resultado,"<ruc>"); 
		//echo etiqueta_xml($resultado,"<razonSocial>"); 
		//echo $resultado;
		//$auto=simplexml_load_string($resultado);
		//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial','B',30);
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
	}
	else
	{
		$logo="diskcover";
	}
	$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	//texto 
	//ruc
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,150));
	$arr=array('R.U.C.:     ', etiqueta_xml($resultado,"<ruc>"));
	$pdf->Row($arr,10);
	 //factura
	 $pdf->SetFont('Arial','B',10);
	 $pdf->SetXY(285, 49);
	 $pdf->SetWidths(array(160));
	$arr=array('GUIA DE REMISION No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',11);
	$pdf->SetXY(425, 49);
	$pdf->SetWidths(array(160));
	$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
	etiqueta_xml($resultado,"<secuencial>"));
	$pdf->Row($arr,10);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr=array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array('EMISIÓN:');
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if(etiqueta_xml($resultado,"<ambiente>")==2)
	{
		$arr=array('PRODUCCIÓN');
	}
	else
	{
		$arr=array('PRUEBA');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr,10);
	/*$pdf->SetXY(410, 180);
	$pdf->SetWidths(array(275));
	$arr=array('000000000000000000000000000000000000000000000000');
	$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code=$atrib['numeroAutorizacion'];
	$pdf->SetXY(285,109);
	$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr=array($code);
	$pdf->Row($arr,10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<razonSocial>"));
	$pdf->Row($arr,10);
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Matríz');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirMatriz>"));
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Sucursal');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirEstablecimiento>"));
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array('Razón social/nombres y apellidos:','','Identificación:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	//para buscar cliente
	$cliente=etiqueta_xml($resultado,"<numDocSustento>");
	/*$resul2= substr($cliente, 0, 7);
	$resul3= substr($cliente, 7, strlen($cliente));
	echo $resul2.' '.$resul3;
	die();*/
	//$cliente1=array();
	$cliente1 = explode("-", $cliente);
	//echo $cliente1[0].' '.$cliente1[1].' '.$cliente1[2];
	//die();
	$cliente=buscar_cli($cliente1[0].$cliente1[1],$cliente1[2]);
	$arr=array($cliente[0],'',$cliente[1]);
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	//para direccion
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
	}
	$arr=array('Dirección: '.$adi,
	'','');
	$pdf->Row($arr,10);
	/*$pdf->SetWidths(array(270,155,100));
	if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	$arr=array('Comprobante que se modifica, Factura No. '.etiqueta_xml($resultado,"<numDocModificado>"),
	'MONTO: '.$mon.'  '.etiqueta_xml($resultado,"<importeTotal>")
	,'Fecha emisión (comprobante a modificar): '.etiqueta_xml($resultado,"<fechaEmisionDocSustento>"));
	$pdf->Row($arr,10);*/
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	/*************************************************
	****************************************************
	***************datos de comprobante****************
	****************************************************
	**************************************************/
	$y=$y1+4;
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Comprobante de venta: '.etiqueta_xml($resultado,"<numDocSustento>"),'','Fecha Emisión: '.etiqueta_xml($resultado,"<fechaEmisionDocSustento>"));
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Número de autorización: '.etiqueta_xml($resultado,"<numAutDocSustento>"),'','');
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Motivo del traslado: '.etiqueta_xml($resultado,"<motivoTraslado>"),'','');
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//$pdf->SetWidths(array(270,185,80));
	$pdf->cabeceraHorizontal(array(' '),40,182,525,($pdf->GetY()-180),20,5);
	
	$y=$y1+3;
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Razón social/nombres y apellidos: ','','Identificación(Transportista)');
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$pdf->SetWidths(array(260,175,90));
	$arr=array(etiqueta_xml($resultado,"<razonSocialTransportista>"),'',etiqueta_xml($resultado,"<rucTransportista>"));
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Punto de partida: '.etiqueta_xml($resultado,"<dirPartida>"),'','Identificación:');
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Fecha inicio transporte: '.etiqueta_xml($resultado,"<fechaIniTransporte>"),
	'Fecha fin transporte: '.etiqueta_xml($resultado,"<fechaFinTransporte>"),'Placa: '.etiqueta_xml($resultado,"<placa>"));
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$y1=$pdf->GetY();
	//$pdf->SetWidths(array(260,175,90));
	$pdf->cabeceraHorizontal(array(' '),40,215,525,($pdf->GetY()-215),20,5);
	
	$y=$y1+3;
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Razón social/nombres y apellidos: ','','Identificación');
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$pdf->SetWidths(array(260,175,90));
	$arr=array(etiqueta_xml($resultado,"<razonSocialDestinatario>"),'',etiqueta_xml($resultado,"<identificacionDestinatario>"));
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$pdf->SetWidths(array(260,175,90));
	$arr=array('Destino (punto de llegada): '.etiqueta_xml($resultado,"<dirDestinatario>"),'','');
	$pdf->SetFont('Arial','',6);
	$pdf->Row($arr,10);
	
	$y1=$pdf->GetY();
	//$pdf->SetWidths(array(270,185,80));
	$pdf->cabeceraHorizontal(array(' '),40,256,525,($pdf->GetY()-256),20,5);
//	$y1=$pdf->GetY()+10;
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(85,85,85,270));
	$arr=array("Codigo Unitario","Codigo Auxiliar","Cantidad","Descripción");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<codigoInterno");
	if(is_array($arr1))
	{
		$arr2=etiqueta_xml($resultado,"<codigoAdicional");
		if($arr2=='')
		{
			$arr2=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr2[$i]='';
			}
		}
		$arr3=etiqueta_xml($resultado,"<cantidad");
		if($arr3=='')
		{
			$arr3=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr3[$i]='';
			}
		}
		//$arr4='';
		$arr5=etiqueta_xml($resultado,"<descripcion");
		if($arr5=='')
		{
			$arr5=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr5[$i]='';
			}
		}
		
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			$pdf->SetWidths(array(85,85,85,270));
			$pdf->SetAligns(array("L","L","R","L"));
			//$arr=array($arr1[$i]);
			$arr=array($arr1[$i],$arr2[$i],$arr3[$i],
			$arr5[$i]);
			$pdf->Row($arr,10,1);
		}
	}
	else
	{
		$pdf->SetWidths(array(85,85,85,270));
		$pdf->SetAligns(array("L","L","R","L"));
		$arr=array(etiqueta_xml($resultado,"<codigoInterno>"),
		etiqueta_xml($resultado,"<codigoAdicional>"),
		etiqueta_xml($resultado,"<cantidad>"),
		etiqueta_xml($resultado,"<descripcion>"));
		$pdf->Row($arr,10,1);
	}
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(525));
	$arr=array("INFORMACIÓN ADICIONAL");
	$pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(525,40,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			if($i==1)
			{
				$adi='Telefono: ';
			}
			if($i==2)
			{
				$adi='Email: ';
			}
			if($i!=0)
			{
				$pdf->SetWidths(array(250));
				$arr=array($adi.$arr1[$i]);
				$pdf->Row($arr,10);
			}
		}
	}
	else
	{
		$pdf->SetWidths(array(250));
		$pdf->Row($arr,10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y+5);
	//$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5);
	//$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	//$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	/*$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	$pdf->SetWidths(array(319));
	$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');
	$pdf->Row($arr,8);*/
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	
	//echo ' ddd '.$imp1;
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
/* imprimirDocElNV
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
 function imprimirDocElNV($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i=0;
	if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		//echo $autorizacion->fechaAutorizacion."<br>";
		//sustituimos
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		//echo etiqueta_xml($resultado,"<ruc>"); 
		//echo etiqueta_xml($resultado,"<razonSocial>"); 
		//echo $resultado;
		//$auto=simplexml_load_string($resultado);
		//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial','B',30);
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
	}
	else
	{
		$logo="diskcover";
	}
	$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	//texto 
	//ruc
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,150));
	$arr=array('R.U.C.:     ', etiqueta_xml($resultado,"<ruc>"));
	$pdf->Row($arr,10);
	 //factura
	 $pdf->SetXY(285, 47);
	 $pdf->SetWidths(array(140));
	$arr=array('Nota de credito No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
	etiqueta_xml($resultado,"<secuencial>"));
	$pdf->Row($arr,10);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr=array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array('EMISIÓN:');
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if(etiqueta_xml($resultado,"<ambiente>")==2)
	{
		$arr=array('PRODUCCIÓN');
	}
	else
	{
		$arr=array('PRUEBA');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr,10);
	/*$pdf->SetXY(410, 180);
	$pdf->SetWidths(array(275));
	$arr=array('000000000000000000000000000000000000000000000000');
	$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code=$atrib['numeroAutorizacion'];
	$pdf->SetXY(285,109);
	$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr=array($code);
	$pdf->Row($arr,10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<razonSocial>"));
	$pdf->Row($arr,10);
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Matríz');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirMatriz>"));
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Sucursal');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirEstablecimiento>"));
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array('Razón social/nombres y apellidos:','','Identificación:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	$arr=array(etiqueta_xml($resultado,"<razonSocialComprador>"),'',etiqueta_xml($resultado,"<identificacionComprador>"));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	//para direccion
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
	}
	$arr=array('Dirección: '.$adi,
	'Fecha emisión: '.etiqueta_xml($resultado,"<fechaEmision>"),'Fecha pago: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(270,155,100));
	if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	$arr=array('Comprobante que se modifica, Factura No. '.etiqueta_xml($resultado,"<numDocModificado>"),
	'MONTO: '.$mon.'  '.etiqueta_xml($resultado,"<importeTotal>")
	,'Fecha emisión (comprobante a modificar): '.etiqueta_xml($resultado,"<fechaEmisionDocSustento>"));
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(65,215,65,65,60,55));
	$arr=array("Codigo Unitario","Descripción","Cantidad Total",
	"Precio Unitario","Valor Descuento","Valor Total");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<codigoInterno");
	if(is_array($arr1))
	{
		/*$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
		if($arr2=='')
		{
			$arr2=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr2[$i]='';
			}
		}*/
		$arr3=etiqueta_xml($resultado,"<cantidad");
		if($arr3=='')
		{
			$arr3=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr3[$i]='';
			}
		}
		//$arr4='';
		$arr5=etiqueta_xml($resultado,"<descripcion");
		if($arr5=='')
		{
			$arr5=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr5[$i]='';
			}
		}
		//$arr6='';
		$arr7=etiqueta_xml($resultado,"<precioUnitario");
		if($arr7=='')
		{
			$arr7=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr7[$i]='';
			}
		}
		$arr8=etiqueta_xml($resultado,"<descuento");
		if($arr8=='')
		{
			$arr8=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr8[$i]='';
			}
		}
		//$arr9='';
		$arr10=etiqueta_xml($resultado,"<precioTotalSinImpuesto");
		if($arr10=='')
		{
			$arr10=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr10[$i]='';
			}
		}
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			$pdf->SetWidths(array(65,215,65,65,60,55));
			$pdf->SetAligns(array("L","L","R","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($arr1[$i],$arr3[$i],$arr5[$i],
			$arr7[$i],$arr8[$i],$arr10[$i]);
			$pdf->Row($arr,10,1);
		}
	}
	else
	{
		$pdf->SetWidths(array(65,215,65,65,60,55));
		$pdf->SetAligns(array("L","L","R","R","R","R"));
		$arr=array(etiqueta_xml($resultado,"<codigoInterno>"),
		etiqueta_xml($resultado,"<descripcion>"),
		etiqueta_xml($resultado,"<cantidad>"),
		etiqueta_xml($resultado,"<precioUnitario>"),etiqueta_xml($resultado,"<descuento>"),
		etiqueta_xml($resultado,"<precioTotalSinImpuesto>"));
		$pdf->Row($arr,10,1);
	}
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(140,40,95,46));
	$arr=array("INFORMACIÓN ADICIONAL","Fecha","Deltalle del pago","Monto Abono");
	$pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(140,60,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			if($i==1)
			{
				$adi='Telefono: ';
			}
			if($i==2)
			{
				$adi='Email: ';
			}
			if($i!=0)
			{
				$pdf->SetWidths(array(140));
				$arr=array($adi.$arr1[$i]);
				$pdf->Row($arr,10);
			}
		}
	}
	else
	{
		$pdf->SetWidths(array(140));
		$pdf->Row($arr,10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y+5);
	$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5);
	$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	$pdf->SetWidths(array(319));
	$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');
	$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, ($y-10));
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y-9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1=porcion_xml($resultado,"<totalImpuesto>","</totalImpuesto>");
	$imp=0;
	$ba0=0;
	$bai=0;
	$vimp0=0;
	$vimp1=0;
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			//echo $arr1[$i].'<br>';
			$arr2=etiqueta_xml($arr1[$i],"<tarifa");
			if(is_array($arr2))
			{
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr2.' fff <br>';
				if($i==1)
				{
					$imp=$arr2;
				}
			}
			$arr3=etiqueta_xml($arr1[$i],"<baseImponible");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr3.' fff <br>';
				if($i==0)
				{
					$ba0=$arr3;
				}
				if($i==1)
				{
					$bai=$arr3;
				}
			}
			$arr4=etiqueta_xml($arr1[$i],"<valor");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr4.' fff <br>';
				if($i==0)
				{
					$vimp0=$arr4;
				}
				if($i==1)
				{
					$vimp1=$arr4;
				}
			}
		}
	}
	//die();
	$arr=array("SUBTOTAL ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y-9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	
	$arr=array($bai);
	$pdf->Row($arr,10);
	
	$y=$y-10+11;//365
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//380
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("TOTAL DESCUENTO:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<totalDescuento>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//395
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//410
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//425
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//440
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("ICE:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//455
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp1);
	$pdf->Row($arr,10);
	
	$y=$y+11;//470
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//485
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("PROPINA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<propina>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//500
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("VALOR TOTAL:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<valorModificacion>"));
	$pdf->Row($arr,10);
	//echo ' ddd '.$imp1;
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
/* imprimirDocElNV
   $stmt= variable con datos xml $id id del campo $formato formato del reporte aqui es pdf
   $nombre_archivo ruta y nombre del archivo xml generado $va 0 para saber si lee de una variable o 1 un archivo xml
   $imp1 paqra saber si se descarga o no null o 1 descarga 0 es para temp y adjuntar al correo*/
 function imprimirDocElND($stmt,$id=null,$formato=null,$nombre_archivo=null,$va=null,$imp1=null)
{
	$pdf = new PDF('P','pt','LETTER');
	$pdf->AliasNbPages('TPAG');
	$pdf->SetTopMargin(5);
	$pdf->SetLeftMargin(41);
	$pdf->SetRightMargin(20);
	$pdf->AddPage();

	//logo
	$i=0;
	if($va==1)
	{
		$autorizacion = simplexml_load_file($nombre_archivo);
	}
	else
	{
		$stmt = str_replace("ï»¿", "", $stmt);
		$autorizacion =simplexml_load_string($stmt);
	}
		$atrib=$autorizacion->attributes();
		
		//echo $autorizacion->fechaAutorizacion."<br>";
		//sustituimos
		$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
		$resultado = str_replace("]]>", "", $resultado);
		
		//echo etiqueta_xml($resultado,"<ruc>"); 
		//echo etiqueta_xml($resultado,"<razonSocial>"); 
		//echo $resultado;
		//$auto=simplexml_load_string($resultado);
		//echo ' ccc '.$auto->factura->infoTributaria;
	//die();
	$pdf->SetFont('Arial','B',30);
	$x=41;
	$pdf->SetXY($x, 20);
	//$pdf->SetWidths(array(250));
	if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		//si es jpg
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.jpg',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es gif
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.gif',40,22,80,40,'','https://www.discoversystem.com');
		}
		//si es png
		$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
		if (@getimagesize($src)) 
		{ 
			$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
		}
	}
	else
	{
		$logo="diskcover";
		$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,22,80,40,'','https://www.discoversystem.com');
	}
	/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
	{
		$logo=$_SESSION['INGRESO']['Logo_Tipo'];
	}
	else
	{
		$logo="diskcover";
	}
	$pdf->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',40,20,80,40,'','http://www.fpdf.org');*/
	//$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo.'.png');
	//$arr=array('NO TIENE LOGO');
	//$pdf->Row($arr,13);
	//panel
	$pdf->cabeceraHorizontal(array(' '),285,30,280,115,20,5);
	//texto 
	//ruc
	$pdf->SetFont('Arial','B',13);
	$pdf->SetXY(285, 35);
	$pdf->SetWidths(array(70,150));
	$arr=array('R.U.C.:     ', etiqueta_xml($resultado,"<ruc>"));
	$pdf->Row($arr,10);
	 //factura
	 $pdf->SetXY(285, 47);
	 $pdf->SetWidths(array(140));
	$arr=array('Nota de credito No.');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',11);
	$pdf->SetXY(425, 47);
	$pdf->SetWidths(array(140));
	$arr=array(etiqueta_xml($resultado,"<estab>").'-'.etiqueta_xml($resultado,"<ptoEmi>").'-'.
	etiqueta_xml($resultado,"<secuencial>"));
	$pdf->Row($arr,10);
	//fecha y hora
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 61);
	$pdf->SetWidths(array(140));
	$arr=array('FECHA Y HORA DE AUTORIZACIÓN:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 61);
	$pdf->SetWidths(array(140));
	$arr=array($autorizacion->fechaAutorizacion);
	$pdf->Row($arr,10);
	//emisión
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 73);
	$pdf->SetWidths(array(140));
	$arr=array('EMISIÓN:');
	$pdf->Row($arr,13);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 73);
	$pdf->SetWidths(array(140));
	$arr=array('NORMAL:');
	$pdf->Row($arr,10);
	//ambiente
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 85);
	$pdf->SetWidths(array(140));
	$arr=array('AMBIENTE: ');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(425, 85);
	$pdf->SetWidths(array(140));
	if(etiqueta_xml($resultado,"<ambiente>")==2)
	{
		$arr=array('PRODUCCIÓN');
	}
	else
	{
		$arr=array('PRUEBA');
	}
	$pdf->Row($arr,10);
	
	//clave de acceso barcode y numero
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(285, 97);
	$pdf->SetWidths(array(280));
	$arr=array('NÚMERO DE AUTORIZACIÓN Y CLAVE DE ACCESO');
	$pdf->Row($arr,10);
	/*$pdf->SetXY(410, 180);
	$pdf->SetWidths(array(275));
	$arr=array('000000000000000000000000000000000000000000000000');
	$pdf->Row($arr,13);*/
	//C set <claveAcceso>
	//$code=etiqueta_xml($resultado,"<claveAcceso>");
	$code=$atrib['numeroAutorizacion'];
	$pdf->SetXY(285,109);
	$pdf->Code128(290,109,$code,260,20);

	//$pdf->Write(5,'C set: "'.$code.'"');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(285, 130);
	$pdf->SetWidths(array(275));
	$arr=array($code);
	$pdf->Row($arr,10);
	/******************/
	/******************/
	/******************/
	$pdf->cabeceraHorizontal(array(' '),40,70,242,75,20,5);
	//razon social
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 75);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<razonSocial>"));
	$pdf->Row($arr,10);
	//nombre comercial
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY($x, 87);
	$pdf->SetWidths(array(280));
	$arr=array( etiqueta_xml($resultado,"<nombreComercial"));
	$pdf->Row($arr,10);
	//direccion matriz
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 97);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Matríz');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 107);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirMatriz>"));
	$pdf->Row($arr,10);
	//direccion sucursal
	$pdf->SetFont('Arial','B',8);
	$pdf->SetXY($x, 117);
	$pdf->SetWidths(array(140));
	$arr=array('Dirección Sucursal');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($x, 127);
	$pdf->SetWidths(array(280));
	$arr=array(etiqueta_xml($resultado,"<dirEstablecimiento>"));
	$pdf->Row($arr,10);
	//contab
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY($x, 135);
	$pdf->SetWidths(array(260));
	$arr=array('Obligatorio a llevar a contabilidad:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetXY(165, 135);
	$pdf->SetWidths(array(20));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,10);
	//$pdf->AddPage();
	//datos cliente
	/******************/
	/******************/
	/**************110****/
	//$pdf->cabeceraHorizontal(array(' '),20,185,574,90,20,5);
	$pdf->SetFont('Arial','B',6);
	$pdf->SetXY(41, 149);
	//para posicion automatica
	$y=35;
	$pdf->SetWidths(array(270,185,80));
	$arr=array('Razón social/nombres y apellidos:','','Identificación:');
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,185,80));
	$arr=array(etiqueta_xml($resultado,"<razonSocialComprador>"),'',etiqueta_xml($resultado,"<identificacionComprador>"));
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',6);
	$pdf->SetWidths(array(270,155,100));
	//para direccion
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		$adi='';
		for ($i=0;$i<count($arr1);$i++)
		{
			
			
			if($i==0)
			{
				$adi=$arr1[$i];
			}
			//echo $arr1[$i];
		}
	}
	else
	{
		$adi='';
	}
	$arr=array('Dirección: '.$adi,
	'Fecha emisión: '.etiqueta_xml($resultado,"<fechaEmision>"),'Fecha pago: '.etiqueta_xml($resultado,"<fechaEmision>"));
	$pdf->Row($arr,10);
	$pdf->SetWidths(array(270,155,100));
	if(etiqueta_xml($resultado,"<moneda")=='DOLAR')
	{
		$mon='USD';
	}
	else
	{
		$mon='USD';
		//se busca otras monedas
	}
	//die();
	$arr=array('Comprobante que se modifica, Factura No. '.etiqueta_xml($resultado,"<numDocModificado>"),
	'MONTO: '.$mon.'  '.etiqueta_xml($resultado,"<importeTotal>")
	,'Fecha emisión (comprobante a modificar): '.etiqueta_xml($resultado,"<fechaEmisionDocSustento>"));
	$pdf->Row($arr,10);
	$y1=$pdf->GetY();
	//echo $pdf->GetY().' '.$y;
	/*******************************************
	***********************************************
	**************fin cabecera********************
	***********************************************
	*********************************************/
	//die();
	$pdf->cabeceraHorizontal(array(' '),40,148,525,($pdf->GetY()-148),20,5);
	//datos factura
	/******************/
	/******************/
	/******************/
	$pdf->SetFont('Arial','B',6);
	$y=$y1+4;
	//$y=$y+188;//258
	$pdf->SetXY(41, $y);
	$pdf->SetWidths(array(65,215,65,65,60,55));
	$arr=array("Codigo Unitario","Descripción","Cantidad Total",
	"Precio Unitario","Valor Descuento","Valor Total");
	$pdf->Row($arr,10,1);
	$pdf->SetFont('Arial','',6);
	//imprimir detalles factura (hacer ciclo)
	//$pdf->SetXY(20, 313);
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<codigoInterno");
	if(is_array($arr1))
	{
		/*$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
		if($arr2=='')
		{
			$arr2=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr2[$i]='';
			}
		}*/
		$arr3=etiqueta_xml($resultado,"<cantidad");
		if($arr3=='')
		{
			$arr3=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr3[$i]='';
			}
		}
		//$arr4='';
		$arr5=etiqueta_xml($resultado,"<descripcion");
		if($arr5=='')
		{
			$arr5=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr5[$i]='';
			}
		}
		//$arr6='';
		$arr7=etiqueta_xml($resultado,"<precioUnitario");
		if($arr7=='')
		{
			$arr7=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr7[$i]='';
			}
		}
		$arr8=etiqueta_xml($resultado,"<descuento");
		if($arr8=='')
		{
			$arr8=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr8[$i]='';
			}
		}
		//$arr9='';
		$arr10=etiqueta_xml($resultado,"<precioTotalSinImpuesto");
		if($arr10=='')
		{
			$arr10=array();
			//llenamos array vacio
			for ($i=0;$i<count($arr1);$i++)
			{
				$arr10[$i]='';
			}
		}
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			$pdf->SetWidths(array(65,215,65,65,60,55));
			$pdf->SetAligns(array("L","L","R","R","R","R"));
			//$arr=array($arr1[$i]);
			$arr=array($arr1[$i],$arr3[$i],$arr5[$i],
			$arr7[$i],$arr8[$i],$arr10[$i]);
			$pdf->Row($arr,10,1);
		}
	}
	else
	{
		$pdf->SetWidths(array(65,215,65,65,60,55));
		$pdf->SetAligns(array("L","L","R","R","R","R"));
		$arr=array(etiqueta_xml($resultado,"<codigoInterno>"),
		etiqueta_xml($resultado,"<descripcion>"),
		etiqueta_xml($resultado,"<cantidad>"),
		etiqueta_xml($resultado,"<precioUnitario>"),etiqueta_xml($resultado,"<descuento>"),
		etiqueta_xml($resultado,"<precioTotalSinImpuesto>"));
		$pdf->Row($arr,10,1);
	}
	//informacion adicional
	$pdf->SetFont('Arial','B',6);
	//echo $pdf->GetY();
	//die();
	$y=$pdf->GetY();
	$pdf->SetXY(41, $y+5);
	$pdf->SetWidths(array(140,40,95,46));
	$arr=array("INFORMACIÓN ADICIONAL","Fecha","Deltalle del pago","Monto Abono");
	$pdf->Row($arr,10,1);
	
	$y=$pdf->GetY()-5;//377
	$pdf->SetFont('Arial','',7);
	//depende del valor de coordenada 'y' del detalle
	//informacion adicional
	$pdf->SetXY($x, $y+5);
	$pdf->Cell(140,60,'','1',1,'Q');
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY($x, ($y+8));
	$pdf->SetWidths(array(40));
	//verificamos si es una o varias etiquetas
	$arr1=etiqueta_xml($resultado,"<campoAdicional");
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			$adi='';
			if($i==1)
			{
				$adi='Telefono: ';
			}
			if($i==2)
			{
				$adi='Email: ';
			}
			if($i!=0)
			{
				$pdf->SetWidths(array(140));
				$arr=array($adi.$arr1[$i]);
				$pdf->Row($arr,10);
			}
		}
	}
	else
	{
		$pdf->SetWidths(array(140));
		$pdf->Row($arr,10);
	}
	//$arr=array(etiqueta_xml($resultado,"<campoAdicional"));
	/*die();
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);
	$pdf->SetWidths(array(40));
	$arr=array(etiqueta_xml($resultado,"<obligadoContabilidad>"));
	$pdf->Row($arr,13);*/
	//fecha
	$pdf->SetXY(181, $y+5);
	$pdf->Cell(40,60,'','1',1,'Q');
	//detalle pago
	$pdf->SetXY(221, $y+5);
	$pdf->Cell(95,60,'','1',1,'Q');
	//monto abono
	$pdf->SetXY(316, $y+5);
	$pdf->Cell(46,60,'','1',1,'Q');
	
	//leyenda final
	$pdf->SetFont('Arial','',5);
	$pdf->SetXY($x, ($y+65));
	$pdf->Cell(321,46,'','1',1,'Q');
	$pdf->SetXY($x, ($y+68));
	$pdf->SetWidths(array(319));
	$arr=array('Para consultas, requerimientos o reclamos puede contactarse a nuestro Centro de Atención al Cliente Teléfono: 02-6052430, o escriba al correo
prisma_net@hotmail.es; para Transferencia o Depósitos hacer en El Banco Pichincha: Cta. Ahr. 4245946100 a Nombre de Walter Vaca Prieto/Cta. Cte
3422225804, a Nombre de PRISMANET PROFESIONAL S.A.');
	$pdf->Row($arr,8);
	//subtotales
	//depende del valor de coordenada 'y' del detalle
	$pdf->SetFont('Arial','',7);
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, ($y-10));
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y-9));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	//obtenemos valor
	//$arr1=etiqueta_xml($resultado,"<totalImpuesto");
	$arr1=porcion_xml($resultado,"<totalImpuesto>","</totalImpuesto>");
	$imp=0;
	$ba0=0;
	$bai=0;
	$vimp0=0;
	$vimp1=0;
	if(is_array($arr1))
	{
		for ($i=0;$i<count($arr1);$i++)
		{
			//echo $arr1[$i].'<br>';
			$arr2=etiqueta_xml($arr1[$i],"<tarifa");
			if(is_array($arr2))
			{
				echo 'array';
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr2.' fff <br>';
				if($i==1)
				{
					$imp=$arr2;
				}
			}
			$arr3=etiqueta_xml($arr1[$i],"<baseImponible");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr3.' fff <br>';
				if($i==0)
				{
					$ba0=$arr3;
				}
				if($i==1)
				{
					$bai=$arr3;
				}
			}
			$arr4=etiqueta_xml($arr1[$i],"<valor");
			if(is_array($arr3))
			{
				/*for ($j=0;$j<count($arr2);$j++)
				{
					echo $arr2[$j].' ggg <br>';
				}*/
			}
			else
			{
				//echo $arr4.' fff <br>';
				if($i==0)
				{
					$vimp0=$arr4;
				}
				if($i==1)
				{
					$vimp1=$arr4;
				}
			}
		}
	}
	//die();
	$arr=array("SUBTOTAL ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y-9));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	
	$arr=array($bai);
	$pdf->Row($arr,10);
	
	$y=$y-10+11;//365
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//380
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("TOTAL DESCUENTO:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<totalDescuento>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//395
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL NO OBJETO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//410
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL EXENTO DE IVA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//425
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("SUBTOTAL SIN IMPUESTOS:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($ba0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//440
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("ICE:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array("000.00");
	$pdf->Row($arr,10);
	
	$y=$y+11;//455
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA ".$imp."%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp1);
	$pdf->Row($arr,10);
	
	$y=$y+11;//470
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("IVA 0%:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array($vimp0);
	$pdf->Row($arr,10);
	
	$y=$y+11;//485
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("PROPINA:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<propina>"));
	$pdf->Row($arr,10);
	
	$y=$y+11;//500
	$pdf->SetFont('Arial','B',7);
	$pdf->SetXY(365, $y);
	$pdf->Cell(201,11,'','1',1,'Q');
	$pdf->SetXY(365, ($y+1));
	$pdf->SetWidths(array(170));
	$pdf->SetAligns(array("L"));
	$arr=array("VALOR TOTAL:");
	$pdf->Row($arr,10);
	$pdf->SetFont('Arial','',7);
	$pdf->SetXY(510, ($y+1));
	$pdf->SetWidths(array(55));
	$pdf->SetAligns(array("R"));
	$arr=array(etiqueta_xml($resultado,"<valorModificacion>"));
	$pdf->Row($arr,10);
	//echo ' ddd '.$imp1;
	//die();
	if($imp1==null or $imp1==1)
	{
		$pdf->Output();
	}
	if($imp1==0)
	{
		$pdf->Output('TEMP/'.$id.'.pdf','F'); 
	}
}
?>




