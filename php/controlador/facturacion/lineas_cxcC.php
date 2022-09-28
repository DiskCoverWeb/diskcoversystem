<?php 
require_once(dirname(__DIR__,2)."/modelo/facturacion/lineas_cxcM.php");
require_once(dirname(__DIR__,3)."/lib/fpdf/generar_codigo_barras.php");


$controlador = new lineas_cxcC();
if(isset($_GET['TVcatalogo']))
{
  $parametros = $_POST['parametros'];
  echo json_encode($controlador->TVcatalogo($parametros));
}
if(isset($_GET['guardar']))
{
	$parametros = $_POST;
  echo json_encode($controlador->GrabarArticulos($parametros));
}
if(isset($_GET['detalle']))
{
  $id = $_POST['id'];
  echo json_encode($controlador->detalle_linea($id));
}


/**
 * 
 */
class lineas_cxcC
{
	private $modelo;
	private $barras;
	function __construct()
	{
		$this->modelo = new lineas_cxcM();
		$this->barras = new generar_codigo_barras();
	}

	function TVcatalogo($parametros)
	{

    // print_r($parametros);die();
    $nl = $parametros['nivel'];
	  $h='';
		if($nl!='')
		{

      if($nl==1)
      {
        $datos = $this->modelo->nivel1();
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A1_'.$key).'" for="A1_'.$key.'">'.$value['Autorizacion'].'</label>
                  <input type="checkbox" id="A1_'.$key.'" onclick="TVcatalogo(2,\'A1_'.$key.'\',\''.$value['Autorizacion'].'\',\'\',\'\')" />
                 <ol id="hijos_'.str_replace('.','_','A1_'.$key).'"></ol></li>';
        }
      }

      if($nl==2)
      {
        $datos = $this->modelo->nivel2($parametros['auto']);
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A2_'.$key).'" for="A2_'.$key.'">'.$value['Serie'].'</label>
                  <input type="checkbox" id="A2_'.$key.'" onclick="TVcatalogo(3,\'A2_'.$key.'\',\''.$parametros['auto'].'\',\''.$value['Serie'].'\',\'\')" />
                 <ol id="hijos_'.str_replace('.','_','A2_'.$key).'"></ol></li>';
        }
      }

      if($nl==3)
      {
        $datos = $this->modelo->nivel3($parametros['auto'],$parametros['serie']);
        foreach ($datos as $key => $value) {
           $h.= '<li  title="Presione Suprimir para eliminar">
                  <label id="label_'.str_replace('.','_','A3_'.$key).'" for="A3_'.$key.'">'.$value['Fact'].'</label>
                  <input type="checkbox" id="A3_'.$key.'" onclick="TVcatalogo(4,\'A3_'.$key.'\',\''.$parametros['auto'].'\',\''.$parametros['serie'].'\',\''.$value['Fact'].'\')" />
                 <ol id="hijos_'.str_replace('.','_','A3_'.$key).'"></ol></li>';
        }
      }

      if($nl==4)
      {
        $datos = $this->modelo->nivel4($parametros['auto'],$parametros['serie'],$parametros['fact']);
        foreach ($datos as $key => $value) {
          $h.='<li class="file" id="label_'.str_replace('.','_','A4_'.$key).'" title=""><a href="#" onclick="detalle_linea(\''.$value['ID'].'\',\'A4_'.$key.'\')">'.$value['Concepto'].'</a></li>';


           // $h.= '<li  title="Presione Suprimir para eliminar">
           //        <label id="label_'.str_replace('.','_','A4_'.$key).'" for="A4_'.$key.'">'.$value['Concepto'].'</label>
           //        <input type="checkbox" id="A4_'.$key.'" onclick="detalle_linea(\''.$value['ID'].'\')" />
           //       <ol id="hijos_'.str_replace('.','_','A2_'.$key).'"></ol></li>';
        }
      }

		}else
		{
			$codigo = 'A';
		  $detalle = 'AUTORIZACIONES';

			 $h = '<li  title="Presione Suprimir para eliminar">
							    <label id="label_'.str_replace('.','_','A').'" for="A">AUTORIZACIONES</label>
							    <input type="checkbox" id="A" onclick="TVcatalogo(1,\'A\',\'\',\'\',\'\')" />
							   <ol id="hijos_'.str_replace('.','_','A').'"></ol></li>';
		}

		return $h;
	}
	function detalle_linea($id)
	{
    if(is_numeric($id))
    {
      $datos = $this->modelo->Catalogo_Lineas($id);
    }
		return $datos;
	}
	function  GrabarArticulos($parametros)
	{
		// print_r($parametros);die();
          $Codigo = $parametros['TextCodigo'];
          $TxtLargo = TextoValido($parametros['TxtLargo']);
          $TxtAncho = TextoValido($parametros['TxtAncho']);
          $TxtAncho =  TextoValido($parametros['TxtEspa']);
          $TxtPosFact  =  TextoValido($parametros['TxtPosFact']);
           if($parametros['CTipo']== ""){$parametros['CTipo'] = "FA";} 
  // If BoxMensaje = vbYes Then
          $datos = $this->modelo->validar_codigo($Codigo);

          if(count($datos) <= 0 ){
              control_procesos("F","Creación de Punto de Venta de ".$parametros['CTipo']."-".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
              control_procesos( "F","Creación de Fecha de Vencimiento de ".$parametros['TextCodigo']." ".$parametros['MBFechaVenc']);
              control_procesos( "F","Creación de Autorización de ".$parametros['TextCodigo']." ".$parametros['TxtNumAutor']);
              control_procesos("F","Creación de Serie de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
              control_procesos( "F", "Creación de Secuencial Inicial de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerietres1']);
              $datosC[0]['campo'] = 'Codigo';
              $datosC[0]['dato'] = $parametros['TextCodigo'];
              $datosC[1]['campo'] = 'Item';
              $datosC[1]['dato'] = intval($_SESSION['INGRESO']['item']);
              $datosC[2]['campo'] = 'Periodo';
              $datosC[2]['dato'] = $_SESSION['INGRESO']['periodo'];
              $datosC[3]['campo'] = 'TL';
              $datosC[3]['dato'] = 1; 
              // $this->modelo->insert('Catalogo_Lineas',$datosC);             
              $Codigo = "A.".$parametros['TxtNumAutor'].".".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'].".".$parametros['CTipo'].".".$parametros['TextCodigo'];
              $Cuenta = $parametros['TextLinea'];
          }else{
              control_procesos("F", "Modificación de Punto de Venta de ".$parametros['CTipo']."-".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);
               $this->modelo->elimina_linea($Codigo);              
               $datos1 = $this->modelo->validar_codigo($Codigo);
              if($parametros['MBFechaVenc'] <> $datos[0]["Vencimiento"]->format('Y-m-d')){ control_procesos("F", "Modifico: Fecha de Vencimiento de ".$parametros['TextCodigo']." ".$parametros['MBFechaVenc']);}
              if($parametros['TxtNumAutor'] <> $datos[0]["Autorizacion"]){ control_procesos("F", "Modifico: Autorización de ".$parametros['TextCodigo']." ".$parametros['TxtNumAutor']);}
              if($parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'] <> $datos[0]["Serie"]){ control_procesos("F", "Modifico: Serie de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos']);}
              if($parametros['TxtNumSerietres1'] <> $datos[0]["Secuencial"]){ control_procesos("F","Modifico: Secuencial Inicial de ".$parametros['TextCodigo']." ".$parametros['TxtNumSerietres1']);}
              $datosC[0]['campo'] = 'Codigo';
              $datosC[0]['dato'] = $parametros['TextCodigo'];
              $datosC[1]['campo'] = 'Item';
              $datosC[1]['dato'] = $_SESSION['INGRESO']['item'];
              $datosC[2]['campo'] = 'Periodo';
              $datosC[2]['dato'] = $_SESSION['INGRESO']['periodo'];
              $datosC[3]['campo'] = 'TL';
              $datosC[3]['dato'] = 1;
          }
              
          $datosC[4]['campo'] = "Concepto"; 
          $datosC[4]['dato'] = $parametros['TextLinea'];
          $datosC[5]['campo'] = "CxC"; 
          $datosC[5]['dato'] = substr($parametros['MBoxCta'],0,-1);
          $datosC[6]['campo'] = "CxC_Anterior"; 
          $datosC[6]['dato'] = substr($parametros['MBoxCta_Anio_Anterior'],0,-1);
          $datosC[7]['campo'] = "Cta_Venta"; 
          $datosC[7]['dato'] = substr($parametros['MBoxCta_Venta'],0,-1);          
          $datosC[8]['campo'] = "Logo_Factura"; 
          $datosC[8]['dato'] = $parametros['TxtLogoFact'];
          $datosC[9]['campo'] = "Largo"; 
          $datosC[9]['dato'] = $parametros['TxtLargo'];
          $datosC[10]['campo'] = "Ancho"; 
          $datosC[10]['dato'] = $parametros['TxtAncho'];
          $datosC[11]['campo'] = "Espacios"; 
          $datosC[11]['dato'] = $parametros['TxtEspa'];
          $datosC[12]['campo'] = "Pos_Factura"; 
          $datosC[12]['dato'] = $parametros['TxtPosFact'];
          $datosC[13]['campo'] = "Pos_Y_Fact"; 
          $datosC[13]['dato'] = $parametros['TxtPosY'];
          $datosC[14]['campo'] = "Fact_Pag"; 
          $datosC[14]['dato'] = $parametros['TxtNumFact'];
          $datosC[15]['campo'] = "ItemsxFA"; 
          $datosC[15]['dato'] = $parametros['TxtItems'];
          $datosC[16]['campo'] = "Fact"; 
          $datosC[16]['dato'] = $parametros['CTipo'];
          // 'SRI
          $datosC[17]['campo'] = "Fecha"; 
          $datosC[17]['dato'] = $parametros['MBFechaIni'];
          $datosC[18]['campo'] = "Vencimiento"; 
          $datosC[18]['dato'] = $parametros['MBFechaVenc'];
          $datosC[19]['campo'] = "Secuencial"; 
          $datosC[19]['dato'] = $parametros['TxtNumSerietres1'];
          $datosC[20]['campo'] = "Autorizacion"; 
          $datosC[20]['dato'] = $parametros['TxtNumAutor'];
          $datosC[21]['campo'] = "Serie"; 
          $datosC[21]['dato'] = $parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'];
          $datosC[22]['campo'] = "Nombre_Establecimiento"; 
          $datosC[22]['dato'] = $parametros['TxtNombreEstab'];
          $datosC[23]['campo'] = "Direccion_Establecimiento"; 
          $datosC[23]['dato'] = $parametros['TxtDireccionEstab'];
          $datosC[24]['campo'] = "Telefono_Estab"; 
          $datosC[24]['dato'] = $parametros['TxtTelefonoEstab'];
          $datosC[25]['campo'] = "Logo_Tipo_Estab"; 
          $datosC[25]['dato'] = $parametros['TxtLogoTipoEstab'];
          if(isset($parametros['CheqMes']) && $parametros['CheqMes'] <> 'false' ){
               $datosC[26]['campo'] = "Imp_Mes"; 
               $datosC[26]['dato'] = 1;
          }

          insert_generico('Catalogo_Lineas',$datosC);    
    // print_r('dd');die();
     
    $datos = $this->modelo->facturas_formato($Codigo,$parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos'],$parametros['TxtNumAutor']);
    
         if(count($datos)<=0)
           {
            // ingresa
               $datosF[0]['campo'] =  "Cod_CxC"; 
               $datosF[0]['dato'] = $parametros['TextCodigo'];
               $datosF[1]['campo'] =  "Item"; 
               $datosF[1]['dato'] = $_SESSION['INGRESO']['item'];
               $datosF[2]['campo'] =  "Periodo";
               $datosF[2]['dato'] = $_SESSION['INGRESO']['periodo'];
               $datosF[3]['campo'] =  "Concepto"; 
               $datosF[3]['dato'] = $parametros['TextLinea'];
               $datosF[4]['campo'] =  "Formato_Factura";
               $datosF[4]['dato'] = $parametros['TxtLogoFact'];
               $datosF[5]['campo'] =  "Largo"; 
               $datosF[5]['dato'] = $parametros['TxtLargo'];
               $datosF[6]['campo'] =  "Ancho"; 
               $datosF[6]['dato'] = $parametros['TxtAncho'];
               $datosF[7]['campo'] =  "Espacios";
               $datosF[7]['dato'] = $parametros['TxtEspa'];
               $datosF[8]['campo'] =  "Pos_Factura"; 
               $datosF[8]['dato'] = $parametros['TxtPosFact'];
               $datosF[9]['campo'] =  "Pos_Y_Fact"; 
               $datosF[9]['dato'] = $parametros['TxtPosY'];
               $datosF[10]['campo'] =  "Fact_Pag"; 
               $datosF[10]['dato'] = $parametros['TxtNumFact'];
               $datosF[11]['campo'] =  "TC"; 
               $datosF[11]['dato'] = $parametros['CTipo'];
              // 'SRI
               $datosF[12]['campo'] =  "Fecha_Inicio"; 
               $datosF[12]['dato'] = $parametros['MBFechaIni'];
               $datosF[13]['campo'] =  "Fecha_Final"; 
               $datosF[13]['dato'] = $parametros['MBFechaVenc'];
               $datosF[14]['campo'] =  "Autorizacion"; 
               $datosF[14]['dato'] = $parametros['TxtNumAutor'];
               $datosF[15]['campo'] =  "Serie"; 
               $datosF[15]['dato'] = $parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'];
               $datosF[16]['campo'] =  "Nombre_Establecimiento"; 
               $datosF[16]['dato'] = $parametros['TxtNombreEstab'];
               $datosF[17]['campo'] =  "Direccion_Establecimiento"; 
               $datosF[17]['dato'] = $parametros['TxtDireccionEstab'];
               $datosF[18]['campo'] =  "Telefono_Estab"; 
               $datosF[18]['dato'] = $parametros['TxtTelefonoEstab'];
               $datosF[19]['campo'] =  "Logo_Tipo_Estab"; 
               $datosF[19]['dato'] = $parametros['TxtLogoTipoEstab'];               
               insert_generico('Facturas_Formatos',$datosF);
           }else
           {
            // actualiza
               $datosF[0]['campo'] =  "Cod_CxC"; 
               $datosF[0]['dato'] = $parametros['TextCodigo'];
               $datosF[1]['campo'] =  "Item"; 
               $datosF[1]['dato'] = $_SESSION['INGRESO']['item'];
               $datosF[2]['campo'] =  "Periodo"; 
               $datosF[2]['dato'] = $_SESSION['INGRESO']['periodo'];
               $datosF[3]['campo'] =  "Concepto"; 
               $datosF[3]['dato'] = $parametros['TextLinea'];
               $datosF[4]['campo'] =  "Formato_Factura"; 
               $datosF[4]['dato'] = $parametros['TxtLogoFact'];
               $datosF[5]['campo'] =  "Largo"; 
               $datosF[5]['dato'] = $parametros['TxtLargo'];
               $datosF[6]['campo'] =  "Ancho"; 
               $datosF[6]['dato'] = $parametros['TxtAncho'];
               $datosF[7]['campo'] =  "Espacios"; 
               $datosF[7]['dato'] = $parametros['TxtEspa'];
               $datosF[8]['campo'] =  "Pos_Factura";
               $datosF[8]['dato'] = $parametros['TxtPosFact'];
               $datosF[9]['campo'] =  "Pos_Y_Fact"; 
               $datosF[9]['dato'] = $parametros['TxtPosY'];
               $datosF[10]['campo'] =  "Fact_Pag"; 
               $datosF[10]['dato'] = $parametros['TxtNumFact'];
               $datosF[11]['campo'] =  "TC"; 
               $datosF[11]['dato'] = $parametros['CTipo'];
              // 'SRI
               $datosF[12]['campo'] =  "Fecha_Inicio"; 
               $datosF[12]['dato'] = $parametros['MBFechaIni'];
               $datosF[13]['campo'] =  "Fecha_Final"; 
               $datosF[13]['dato'] = $parametros['MBFechaVenc'];
               $datosF[14]['campo'] =  "Autorizacion"; 
               $datosF[14]['dato'] = $parametros['TxtNumAutor'];
               $datosF[15]['campo'] =  "Serie"; 
               $datosF[15]['dato'] = $parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'];
               $datosF[16]['campo'] =  "Nombre_Establecimiento"; 
               $datosF[16]['dato'] = $parametros['TxtNombreEstab'];
               $datosF[17]['campo'] =  "Direccion_Establecimiento"; 
               $datosF[17]['dato'] = $parametros['TxtDireccionEstab'];
               $datosF[18]['campo'] =  "Telefono_Estab"; 
               $datosF[18]['dato'] = $parametros['TxtTelefonoEstab'];
               $datosF[19]['campo'] =  "Logo_Tipo_Estab"; 
               $datosF[19]['dato'] = $parametros['TxtLogoTipoEstab'];

               $where[0]['campo'] = 'ID';
               $where[0]['valor'] = $datos[0]['ID'];

               // print_r($datosF);
               // print_r($where);
               // die();
               update_generico($datosF,'Facturas_Formatos',$where);
               // print_r('paso');die();

           }
    // 'Numeracion de FA,NC,GR,ETC
     switch ($parametros['CTipo']) {
          case 'NC':
               $datos = $this->modelo->NC($parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
               break;
           case 'GR':
               $datos = $this->modelo->GR($parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
               break;                
          default:
               $datos = $this->modelo->FACTURAS($parametros['CTipo'],$parametros['TxtNumSerieUno'],$parametros['TxtNumSerieDos']);
               break;
     }
    
   
      if(count($datos)>0) {
        foreach ($datos as $key => $value) {
            $datos2 = $this->modelo->codigos($value['Periodo'],$value['Item'],$value['TC'],$value['Serie_X']);
            if(count($datos2)>0)
            {
                $datos3[0]['campo'] ='Numero';
                $datos3[0]['dato'] = $value['TC_No']+1;
                
                $where[0]['campo'] = 'ID';
                $where[0]['valor'] = $datos2[0]['ID'];
                
                update_generico($datos3,'Codigos',$where);
            }else
            {
                  $datos3[0]['campo'] =  "Item"; 
                  $datos3[0]['campo'] = $value['Item'];
                  $datos3[1]['campo'] =  "Periodo"; 
                  $datos3[1]['campo'] = $value['Periodo'];
                  $datos3[2]['campo'] =  "Concepto"; 
                  $datos3[2]['campo'] = $value['TC']."_SERIE_".$value['Serie_X'];
                  $datos3[3]['campo'] =  "Numero"; 
                  $datos3[3]['campo'] = $value['TC_No']+1;
                  insert_generico('Codigos',$datos3);
            }
            
        }
      }else{
          $datos[0]['campo'] =  "Item"; 
          $datos[0]['dato'] = $_SESSION['INGRESO']['item'];
          $datos[1]['campo'] =  "Periodo"; 
          $datos[1]['dato'] = $_SESSION['INGRESO']['periodo'];
          $datos[2]['campo'] =  "Concepto"; 
          $datos[2]['dato'] = $parametros['CTipo']."_SERIE_".$parametros['TxtNumSerieUno'].$parametros['TxtNumSerieDos'];
          $datos[3]['campo'] =  "Numero"; 
          $datos[3]['dato'] = intval($parametros['TxtNumSerietres1']);
          insert_generico('Codigos',$datos);
      }  

      return 1;
  //Llenar_CxC
  //MsgBox "El proceso de Grabación se realizó con éxito"*/

	}
}

?>