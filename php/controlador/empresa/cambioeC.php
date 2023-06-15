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

if(isset($_GET['asignar_clave']))
{
	$parametros = $_POST;
	echo json_encode($controlador->asignar_clave($parametros));
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

	function datos_empresa($parametros)
	{
		$ID = $parametros['empresas'];
		$sms = $parametros['sms'];
		$datos = $this->modelo->datos_empresa($ID);
		$CI = '.';
		if(count($datos)>0)
		{
			$empresaSQL = '';
			if($datos[0]['IP_VPN_RUTA']!='.' && $datos[0]['Base_Datos'] !='.' && $datos[0]['Usuario_DB']!='.' && $datos[0]['Contrasena_DB']!='.' && $datos[0]['Tipo_Base']!='.')
			{
				$datosEmp = $this->modelo->datos_sql_terceros($datos[0],$datos[0]['IP_VPN_RUTA'],$datos[0]['Usuario_DB'],$datos[0]['Contrasena_DB'],$datos[0]['Base_Datos'],$datos[0]['Puerto']);
				if($datosEmp!='-2' && $datosEmp!='-1')
				{
						// print_r($datosEmp);die();
						$empresaSQL = '<!--------------------------seccion com´probante------------------->
						<div class="col-sm-3"><label>WEBSERVICE SRI RECEPCION</label></div>';
		                if($datosEmp[0]['Ambiente']==1)
		                {                                
			                 $empresaSQL.='<div class="col-sm-2">                                    
			                   <label> <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="" onclick="AmbientePrueba()" checked>
			                    Ambiente de Prueba</label>
			                </div>
			                <div class="col-sm-3">
			                    <label><input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" onclick="AmbienteProduccion()">
			                    Ambiente de Producción</label>
			                </div>';
		            	}else
		            	{
		            		 $empresaSQL.='<div class="col-sm-2">                                    
		                    <label><input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="" onclick="AmbientePrueba()">
		                    Ambiente de Prueba</label>
		                </div>
		                <div class="col-sm-3">
		                    <label><input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" onclick="AmbienteProduccion()" checked>
		                    Ambiente de Producción</label>
		                </div>';
		            	}
		                $empresaSQL.='<div class="col-sm-2">Contribuyente Especial</div>
		                <div class="col-sm-2">
		                    <input type="text" name="TxtContriEspecial" id="TxtContriEspecial" class="form-control input-xs" value="'.$datosEmp[0]['Codigo_Contribuyente_Especial'].'">
		                </div>
		                 <div class="col-sm-12">
		                    <input type="text" name="TxtWebSRIre" id="TxtWebSRIre" class="form-control input-xs" value="'.$datosEmp[0]['Web_SRI_Recepcion'].'">
		                </div>
		                <div class="col-sm-12">
		                    <label>WEBSERVICE SRI AUTORIZACIÓN</label>
		                        <input type="text" name="TxtWebSRIau" id="TxtWebSRIau" class="form-control input-xs" value="'.$datosEmp[0]['Web_SRI_Autorizado'].'">
		                </div>           
		                <div class="col-sm-10">
		                    <label>CERTIFICADO FIRMA ELECTRONICA (DEBE SER EN FORMATO DE EXTENSION P12</label>
		                    <input type="text" name="TxtEXTP12" id="TxtEXTP12" class="form-control input-xs" value="'.$datosEmp[0]['Ruta_Certificado'].'">
		                </div>
		                <div class="col-sm-2">
		                    <label>CONTRASEÑA:</label>
		                    <input type="text" name="TxtContraExtP12" id="TxtContraExtP12" class="form-control input-xs" value="'.$datosEmp[0]['Clave_Certificado'].'">
		                </div>
		                <div class="col-sm-10">
		                    <label>EMAIL PARA PROCESOS GENERALES:</label>
		                    <input type="text" name="TxtEmailGE" id="TxtEmailGE" class="form-control input-xs" value="'.$datosEmp[0]['Email_Conexion'].'">
		                </div>
		                <div class="col-sm-2">
		                    <label>CONTRASEÑA:</label>
		                    <input type="text" name="TxtContraEmailGE" id="TxtContraEmailGE" class="form-control input-xs" value="'.$datosEmp[0]['Email_Contraseña'].'">
		                </div>
		                <div class="col-sm-10">
		                    <label>EMAIL PARA DOCUMENTOS ELECTRONICOS:</label>
		                    <input type="text" name="TxtEmaiElect" id="TxtEmaiElect" class="form-control input-xs" value="'.$datosEmp[0]['Email_Conexion_CE'].'">
		                </div>
		                <div class="col-sm-2">
		                    <label>CONTRASEÑA:</label>
		                    <input type="text" name="TxtContraEmaiElect" id="TxtContraEmaiElect" class="form-control input-xs" value="'.$datosEmp[0]['Email_Contraseña_CE'].'">
		                </div>
		                <div class="col-sm-10">';
		                if($datosEmp[0]['Email_CE_Copia']==1 && $datosEmp[0]['Email_Procesos']!='')
		                {
		                	$empresaSQL.='<label><input type="checkbox" checked id="rbl_copia" name="rbl_copia">Enviar Copia de Email</label>';
		                }else
		                {
		                	$empresaSQL.='<label><input type="checkbox" id="rbl_copia" name="rbl_copia">Enviar Copia de Email</label>';		                	
		                }

		                $empresaSQL.='<input type="text" name="TxtCopiaEmai" id="TxtCopiaEmai" class="form-control input-xs" value="'.$datosEmp[0]['Email_Procesos'].'">
		                </div>
		                <div class="col-sm-2">
		                    <label>RUC Operadora</label>
		                    <input type="text" name="TxtRUCOpe" id="TxtRUCOpe" class="form-control input-xs" value="'.$datosEmp[0]['RUC_Operadora'].'">
		                </div>
		                <div class="col-sm-12">                            
		                        <label>LEYENDA AL FINAL DE LOS DOCUMENTOS ELECTRONICOS</label>
		                        <textarea name="txtLeyendaDocumen" id="txtLeyendaDocumen"class="form-control" rows="2" resize="none">'.$datosEmp[0]['LeyendaFA'].'</textarea>                            
		                </div>
		                <div class="col-sm-12">
		                    <label>LEYENDA AL FINAL DE LA IMPRESION EN LA IMPRESORA DE PUNTO DE VENTA DE DOCUMENTOS ELECTRÓNICOS</label><br>                            
		                    <textarea name="txtLeyendaImpresora" id="txtLeyendaImpresora"class="form-control" rows="2" resize="none">'.$datosEmp[0]['LeyendaFAT'].'</textarea>
		                </div>';
		        }
			}

		$CI = $datos[0]['RUC_CI_NIC'];
			foreach ($datos as $key => $value) 
			{				
				// print_r($value);die();
			$op ='<div class="col-md-3">
					<div class="form-group">
					    <label for="Estado">Estado</label>
					    <select class="form-control input-sm" name="Estado" id="Estado" >
							<option value='.$value['Estado'].'>'.$value['Estado'].'</option>
						    <option value="0">Seleccione Estado</option>';
						    $op.= $this->estados();
					$op.='</select>
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="FechaR">Fecha Renovación(dia-mes-año)</label>
					   
					  <input type="date" class="form-control input-sm" id="FechaR" name="FechaR" placeholder="FechaR" 
					  value='.$value['Fecha'].' 
					  onKeyPress="return soloNumeros(event)"  maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="Fecha">Fecha Comp. Electronico(dia-mes-año)</label>
					   
					  <input type="date" class="form-control input-sm" id="Fecha" name="Fecha" placeholder="Fecha" 
					  value="'.$value['Fecha_CE'].'" onKeyPress="return soloNumeros(event)" 
					  maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="Fecha">Fecha VPN(dia-mes-año)</label>
					   
					  <input type="date" class="form-control input-sm" id="FechaV" name="FechaV" placeholder="FechaV" 
					  value="'.$value['Fecha_VPN'].'"   onKeyPress="return soloNumeros(event)" maxlength="10"onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="Fecha_DB">Fecha BD</label>
					  <input type="date" class="form-control input-sm" id="FechaDB" name="FechaDB" value="'.$value['Fecha_DB'].'">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="Fecha_P12">Fecha P12</label>
					  <input type="date" class="form-control input-sm" id="FechaP12" name="FechaP12" value="'.$value['Fecha_P12'].'">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="Servidor">Servidor</label>
					  <input type="text" class="form-control input-sm" id="Servidor" name="Servidor" placeholder="Servidor" value="'.$value['IP_VPN_RUTA'].'">
					</div>
				</div>
				<div class="col-md-3">
					<div class="form-group">
					  <label for="Base">Base</label>
					  <input type="text" class="form-control input-sm" id="Base" name="Base" placeholder="Base" value="'.$value['Base_Datos'].'">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <label for="Usuario">Usuario</label>
					   
					  <input type="text" class="form-control input-sm" id="Usuario" name="Usuario" placeholder="Usuario" value="'.$value['Usuario_DB'].'">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <label for="Clave">Clave</label>
					  <input type="text" class="form-control input-sm" id="Clave" name="Clave" placeholder="Clave" value="'.$value['Contrasena_DB'].'">
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="form-group">
					  <label for="Motor">Motor BD</label>
					  <input type="text" class="form-control input-sm" id="Motor" name="Motor" placeholder="Motor" value="'.$value['Tipo_Base'].'">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <label for="Puerto">Puerto</label>
					   
					  <input type="text" class="form-control input-sm" id="Puerto" name="Puerto" placeholder="Puerto" value="'.$value['Puerto'].'">
					</div>
				</div>				
				<div class="col-md-2">
					<div class="form-group">
					  <label for="Plan">Plan</label>
					   
					  <input type="text" class="form-control input-sm" id="Plan" name="Plan" placeholder="Plan" value="'.$value['Tipo_Plan'].'">
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="form-group">
					  <label for="Mensaje">Mensaje</label>
					  <input type="text" class="form-control input-sm" id="Mensaje" name="Mensaje" placeholder="Mensaje" value="';
					   if($sms!=''){ $op.= $sms;}else{ $op.= $value['Mensaje'];} 
					   $op.='">
					</div>
				</div>
				'.$empresaSQL.'		
			</div>               
                    ';
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';			
		}
		}else
		{
			$op='<div id="alerta" class="alert alert-warning visible">Empresa no encontrada</div>';
		}

		
		return array('datos'=>$op,'ci'=>$CI);
	}

	function estados()
	{
		$datos = $this->modelo->estado();
		$rep = '<option value="">No existe estados</option>';
		if(count($datos)>0)
		{
			$rep ='';
			foreach ($datos as $key => $value) {
				$rep.='<option value="'.$value['Estado'].'">'.$value['Descripcion'].'</option>';
			}
		}
		return $rep;	
	}

	function editar_datos_empresa($parametros)
	{
		return $this->modelo->editar_datos_empresa($parametros);

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

}

?>