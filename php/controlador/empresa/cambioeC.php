<?php
require_once(dirname(__DIR__,2)."/modelo/empresa/cambioeM.php"); 
/**
 * 
 */
$controlador = new cambioeC();

if(isset($_GET['ciudad']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->ciudad($parametros));
}
if(isset($_GET['empresas']))
{
	$query = ''; $ciu = ''; $ent = '';
	if(isset($_GET['q'])){ $query = $_GET['q'];	}
	if(isset($_GET['ciu'])){ $ciu = $_GET['ciu'];	}
	if(isset($_GET['ent'])){ $ent = $_GET['ent'];	}
	echo json_encode($controlador->empresas($query,$ent,$ciu));
}
if(isset($_GET['datos_empresa']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_empresa($parametros));
}

if(isset($_GET['editar_datos_empresa']))
{
	$parametros = $_POST;
	echo json_encode($controlador->editar_datos_empresa($parametros));
}

if(isset($_GET['mensaje_masivo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->mensaje_masivo($parametros));
}
if(isset($_GET['mensaje_grupo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->mensaje_grupo($parametros));
}
if(isset($_GET['mensaje_indi']))
{
	$parametros = $_POST;
	echo json_encode($controlador->mensaje_indi($parametros));
}
if(isset($_GET['guardar_masivo']))
{
	$parametros = $_POST;
	echo json_encode($controlador->guardar_masivo($parametros));
}
if(isset($_GET['subdireccion']))
{
    $query = $_POST['txtsubdi'];
	echo json_encode($controlador->TextSubDir_LostFocus($query));
}
if(isset($_GET['asignar_clave']))
{
	$parametros = $_POST;
	echo json_encode($controlador->asignar_clave($parametros));
}
if(isset($_GET['provincias']))
{
  $pais = '';
  if(isset($_POST['pais']))
  {
    $pais = $_POST['pais'];
  }
	echo json_encode(provincia_todas($pais));
}
if(isset($_GET['ciudad2']))
{
	echo json_encode(todas_ciudad($_POST['idpro']));
}
if(isset($_GET['cargar_imagen']))
{
	echo json_encode($controlador->guardar_foto($_FILES,$_POST));
}
if(isset($_GET['cargar_firma']))
{
	echo json_encode($controlador->guardar_firma($_FILES,$_POST));
}

if(isset($_GET['ddl_estados']))
{
	echo json_encode($controlador->estados());
}

if(isset($_GET['ddl_nacionalidades']))
{
	echo json_encode(naciones_todas());
}
if(isset($_GET['cargar_imgs']))
{
	echo json_encode($controlador->cargar_imgs());
}
if(isset($_GET['guardarTipoContribuyente']))
{
	echo json_encode($controlador->guardarTipoContribuyente($_POST['parametros']));
}

class cambioeC 
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new cambioeM();
	}

	function ciudad($parametros)
	{
		$IDempresa = $parametros['entidad'];
		$datos = $this->modelo->ciudad($IDempresa);
		// print_r($datos);die();
		if(count($datos)>0)
		{
			$resp[0] = array('codigo'=>'0','nombre'=>'Seleccione Ciudad');
			$resp[1] = array('codigo'=>'','nombre'=>'Todos');
			foreach ($datos as $key => $value) {
				$resp[] = array('codigo'=>$value['Ciudad'],'nombre'=>$value['Ciudad']);
			}
	    }else
	    {
	    	$resp[0] = array('codigo'=>'0','nombre'=>'Ciudad no encontrada');
	    }
		return $resp;

	}

	function empresas($query,$ent,$ciu)
	{
		$datos = $this->modelo->entidad($query,$ent,$ciu);
		// print_r($dato);die();
		if(count($datos)>0)
		{
				foreach ($datos as $key => $value) {
				$resp[] = array('id'=>$value['ID'],'text'=>$value['Empresa'],'CI'=>$value['RUC_CI_NIC'],'data'=>$value);
			}
	    }else
	    {
	    	$resp[0] = array('id'=>'','text'=>'Empresa no encontrada');
	    }
		return $resp;
	}

	function  TextSubDir_LostFocus($query)
{
    $TextSubDir = TextoValido($query);
    $dato = $this->modelo->consulta_empresa();
    if(count($dato)>0)
    {
        if($TextSubDir == G_NINGUNO  )
        {
            $NumEmpSubDir = 0;
            if(count($dato) > 0)
            {
                $NumEmpSubDir = intval($dato[0]["Item"]);
            }
            $NumEmpSubDir = $NumEmpSubDir + 1;
            $TextSubDir = "EMPRE".generaCeros($NumEmpSubDir,3);
            return $TextSubDir;
        }else
        {
            $dato2 = $this->modelo->consulta_empresa($TextSubDir);
            if(count($dato2)>0)
            {
                if($_SESSION['INGRESO']['item'] <> $dato2[0]["Item"] )
                {
                    return null;
                }

            }
        }
    }
}


	function datos_empresa($parametros)
	{
		$ID = $parametros['empresas'];
		$sms = $parametros['sms'];
		// print_r($parametros);die();
		$datosEmp = array();
		$contribuyente = array();
		$datosEmp[0] = array();
		$datos = $this->modelo->datos_empresa($ID);
		// print_r($datos);die();
		$CI = '.';
		if(count($datos)>0)
		{
			$contribuyente = $this->modelo->tipoContribuyente($datos[0]['RUC_CI_NIC']);
			$empresaSQL = '';
			$empresaSQL2 = '';
			$empresaSQL3 = '';
			if($datos[0]['IP_VPN_RUTA']!='.' && $datos[0]['Base_Datos'] !='.' && $datos[0]['Usuario_DB']!='.' && $datos[0]['Contrasena_DB']!='.' && $datos[0]['Tipo_Base']!='.')
			{
				$datosEmp = $this->modelo->datos_sql_terceros($datos[0],$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
				if(count($datosEmp)>0)
				{
					$url = dirname(__DIR__,3)."/img/logotipos/".$datosEmp[0]['Logo_Tipo'].".png";
			    	if(!file_exists($url))
			    	{
			    		$datosEmp[0]['Logo_Tipo'] = '.';
			    	}	
				}
			}
		}
			
		return array('empresa1'=>$datos,'empresa2'=>$datosEmp,'tipoContribuyente'=>$contribuyente);
	}

	function estados()
	{
		$datos = $this->modelo->estado();
		$rep = '<option value="">No existe estados</option>';
		if(count($datos)>0)
		{
			$rep ='<option value="">Seleccione estado</option>';
			foreach ($datos as $key => $value) {
				$rep.='<option value="'.$value['Estado'].'">'.$value['Descripcion'].'</option>';
			}
		}
		return $rep;	
	}

	function editar_datos_empresa($parametros)
	{
		// print_r($parametros);die();
		$contribuyente = $this->modelo->tipoContribuyente($parametros['TxtRuc']);
		if(count($contribuyente)==0)
		{
			$this->modelo->ingresar_tipo_contribuyente($parametros['TxtRuc']);
		}else
		{
			return $this->modelo->editar_tipo_contribuyente($parametros);
		}
		$resp = $this->modelo->editar_datos_empresaMYSQL($parametros);
		if($parametros['txt_sqlserver']==1)
		{
			$resp = $this->modelo->editar_catalogoLineas_empresa($parametros);
			if($resp==1)
			{
				return $this->modelo->editar_datos_empresa($parametros);
			}else
			{
				return $resp;
			}
		}
		return $resp;

	}
	function  mensaje_masivo($parametros)
	{
		return $this->modelo->mensaje_masivo($parametros);

	}
	function  mensaje_grupo($parametros)
	{
		return $this->modelo->mensaje_grupo($parametros);

	}
	function  mensaje_indi($parametros)
	{
		return $this->modelo->mensaje_indi($parametros);

	}
	function  guardar_masivo($parametros)
	{
		return $this->modelo->guardar_masivo($parametros);

	}

	function asignar_clave($parametros)
	{
		// print_r($parametros);die();
		return $this->modelo->asignar_clave($parametros);
	}
	function guardar_foto($file,$post)
	{
	    $ruta= dirname(__DIR__,3).'/img/logotipos/';//ruta carpeta donde queremos copiar las imágenes
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }
	    if($this->validar_formato_img($file)==1)
	    {
	         $uploadfile_temporal=$file['file_img']['tmp_name'];
	         // $tipo = explode('/', $file['file_img']['type']);
	         $nombre = $file['file_img']['full_path'];
	         $name = explode('.',$nombre);
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	         	$em[0]['IP_VPN_RUTA'] = $post['Servidor'];
	         	$em[0]['Usuario_DB'] = $post['Usuario'];
	         	$em[0]['Contrasena_DB']= $post['Clave'];
	         	$em[0]['Base_Datos']= $post['Base'];
	         	$em[0]['Puerto']= $post['Puerto'];
	         	$r = $this->modelo->actualizar_foto($name[0],$post['ci_ruc'],$em);
	         	// print_r($r);die();
	         	if($r==1)
	         	{
	         		move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	                return 1;
	         	}else
	         	{
	         		return -3;
	         	}	           
	         }
	         else
	         {
	           return -1;
	         } 
	     }else
	     {
	      return -2;
	     }

	}
	function guardar_firma($file,$post)
	{
		// print_r($file);die();
		// print_r($post);die();
	    $ruta= dirname(__DIR__,2).'/comprobantes/certificados/';//ruta carpeta donde queremos copiar las imágenes
	    if (!file_exists($ruta)) {
	       mkdir($ruta, 0777, true);
	    }
	    if($this->validar_formato_firma($file)==1)
	    {
	         $uploadfile_temporal=$file['file_firma']['tmp_name'];
	         // $tipo = explode('/', $file['file_img']['type']);
	         $nombre = str_replace(' ','_', $file['file_firma']['full_path']);
	         $nuevo_nom=$ruta.$nombre;
	         if (is_uploaded_file($uploadfile_temporal))
	         {
	         	$em[0]['IP_VPN_RUTA'] = $post['Servidor'];
	         	$em[0]['Usuario_DB'] = $post['Usuario'];
	         	$em[0]['Contrasena_DB']= $post['Clave'];
	         	$em[0]['Base_Datos']= $post['Base'];
	         	$em[0]['Puerto']= $post['Puerto'];
	         	$this->modelo->actualizar_firma($nombre,$post['ci_ruc'],$em);
	           move_uploaded_file($uploadfile_temporal,$nuevo_nom);
	          
	           return 1;
	         }
	         else
	         {
	           return -1;
	         } 
	     }else
	     {
	      return -2;
	     }

	}
	function validar_formato_img($file)
	{
	    switch ($file['file_img']['type']) {
	      case 'image/jpeg':
	      case 'image/pjpeg':
	      case 'image/gif':
	      case 'image/png':
	         return 1;
	        break;      
	      default:
	        return -1;
	        break;
	    }

	}
	function validar_formato_firma($file)
	{
	    switch ($file['file_firma']['type']) {
	      case 'application/x-pkcs12':
	         return 1;
	        break;      
	      default:
	        return -1;
	        break;
	    }

	}

	function cargar_imgs()
	{
		$opciones = '';
        $directorio = dirname(__DIR__,3).'/img/logotipos'; 
            // print_r($directorio);die();
			$archivos = scandir($directorio);
			foreach ($archivos as $archivo) {
			    $rutaArchivo = $directorio . '/' . $archivo;
			    if (is_file($rutaArchivo) && pathinfo($rutaArchivo, PATHINFO_EXTENSION) === 'png') {
			    	$opciones.='<option value="'.$archivo.'">'.$archivo.'</option>';
			    }
			}   
		return $opciones;             
	}


	function guardarTipoContribuyente($parametros)
	{
	 	return $this->modelo->editar_tipo_contribuyente($parametros);
	}

}

?>