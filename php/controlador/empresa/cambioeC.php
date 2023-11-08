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
		$datos = $this->modelo->datos_empresa($ID);
		$CI = '.';
		if(count($datos)>0)
		{
			$empresaSQL = '';
			$empresaSQL2 = '';
			$empresaSQL3 = '';
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
		                    <div class="input-group">
		                    	<input type="text" name="TxtEXTP12" id="TxtEXTP12" class="form-control input-sm" value="'.$datosEmp[0]['Ruta_Certificado'].'">
		                    	<span class="input-group-addon input-xs"><input type="file"  id="file_firma" name="file_firma" /></span>
		                    	<span class="input-group-btn">
									<button type="button" class="btn btn-info btn-flat btn-sm" onclick="subir_firma()">Subir firma</button>
								</span>
		                    </div>
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

		            // -----------------------------------------------procesos generales------------------------------------
		                // print_r($datosEmp);die();
		                $empresaSQL2='<div class="row">
                    <div class="col-sm-2">
                        <label>EMPRESA:</label>
                        <label>'.$datosEmp[0]['Item'].'</label>
                    </div>
                    <div class="col-sm-10">                                
                        <input type="text" name="TxtEmpresa" id="TxtEmpresa" class="form-control input-xs" value="'.$datosEmp[0]['Empresa'].'">
                    </div>
                    <div class="col-sm-2">
                        <label hidden="hidden">ITEM:</label>
                    </div>
                    <div class="col-sm-10">                                
                        <input type="hidden" name="TxtItem" id="TxtItem" class="form-control input-xs" value="'.$datosEmp[0]['Item'].'">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label>RAZON SOCIAL:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name="TxtRazonSocial" id="TxtRazonSocial" class="form-control input-xs" value="'.$datosEmp[0]['Razon_Social'].'">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label>NOMBRE COMERCIAL:</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" name="TxtNomComercial" id="TxtNomComercial" class="form-control input-xs" value="'.$datosEmp[0]['Nombre_Comercial'].'">
                    </div>
                </div>                
                <div class="row">
                    <div class="col-sm-2">
                        <label>RUC:</label>
                        <input type="text" name="TxtRuc" id="TxtRuc" class="form-control input-xs" value="'.$datosEmp[0]['RUC'].'" onkeyup="num_caracteres('.'\'TxtRuc\''.',13)" autocomplete="off">
                    </div>
                    <div class="col-sm-2">
                        <label>OBLIG</label>
                        <select class="form-control input-xs" id="ddl_obli" name="ddl_obli">
                            <option value="">Seleccione</option>';
                            $optionsi ='';
                            $optionno = '';
                            if($datosEmp[0]['Obligado_Conta']!='.' && $datosEmp[0]['Obligado_Conta']!='')
                            {
	                            if($datosEmp[0]['Obligado_Conta']=='SI')
	                            {
	                            	$optionsi = 'selected';
	                            	$optionno = '';
	                            }else
	                            {
	                            	$optionno = 'selected';
	                            	$optionsi = '';
	                            }
	                        }

                           $empresaSQL2.='
                            <option value="SI" '.$optionsi.'>SI</option>
                            <option value="NO" '.$optionno.'>NO</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>REPRESENTANTE LEGAL:</label>
                        <input type="text" name="TxtRepresentanteLegal" id="TxtRepresentanteLegal" class="form-control input-xs" value="'.$datosEmp[0]['Gerente'].'">
                    </div>
                    <div class="col-sm-2">
                        <label>C.I/PASAPORTE</label>
                        <input type="text" name="TxtCI" id="TxtCI" class="form-control input-xs" value="'.$datosEmp[0]['CI_Representante'].'" onkeyup="num_caracteres('.'\'TxtCI\''.',10)" autocomplete="off">
                    </div>
                </div>                    
                <div class="row">
                    <div class="col-sm-4">
                        <label>NACIONALIDAD</label>
                        <select class="form-control input-xs" id="ddl_naciones" name="ddl_naciones" onchange="provincias(this.value)">
                            <option value="">Seleccione</option>';
                            $naciones = naciones_todas();
                            foreach ($naciones as $key => $value) {
                            	$optionCpais = '';
                            	if($value['Codigo']==$datosEmp[0]['CPais']){ $optionCpais = 'selected';}
                            	$empresaSQL2.='<option value="'.$value['Codigo'].'" '.$optionCpais.'>'.$value['Descripcion_Rubro'].'</option>';
                            }
                        $empresaSQL2.='</select>
                    </div>
                    <div class="col-sm-4">
                        <label>PROVINCIA</label>
                        <select class="form-control input-xs"  id="prov" name="prov" onchange="ciudad_l(this.value)">
                            <option value="">Seleccione una provincia</option>';
                             $naciones = provincia_todas($datosEmp[0]['CPais']);
                            foreach ($naciones as $key => $value) {
                            	$optionCpais = '';
                            	if($value['Codigo']==$datosEmp[0]['CProv']){ $optionCpais = 'selected';}
                            	$empresaSQL2.='<option value="'.$value['Codigo'].'" '.$optionCpais.'>'.$value['Descripcion_Rubro'].'</option>';
                            }
                        $empresaSQL2.='
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>CIUDAD</label>
                        <select class="form-control input-xs" id="ddl_ciudad" name="ddl_ciudad">
                            <option value="">Seleccione una ciudad</option>';
                            $naciones = todas_ciudad($datosEmp[0]['CProv']);
                            foreach ($naciones as $key => $value) {
                            	$optionCpais = '';
                            	if($value['Descripcion_Rubro']==$datosEmp[0]['Ciudad']){ $optionCpais = 'selected';}
                            	$empresaSQL2.='<option value="'.$value['Codigo'].'" '.$optionCpais.'>'.$value['Descripcion_Rubro'].'</option>';
                            }
                        $empresaSQL2.='
                        </select>
                    </div>                        
                </div>                    
                <div class="row">
                    <div class="col-sm-10">
                        <label>DIRECCION MATRIZ:</label>
                        <input type="text" name="TxtDirMatriz" id="TxtDirMatriz" class="form-control input-xs" value="'.$datosEmp[0]['Direccion'].'">
                    </div>
                    <div class="col-sm-2">
                        <label>ESTA.</label>
                        <input type="text" name="TxtEsta" id="TxtEsta" class="form-control input-xs" value="'.$datosEmp[0]['Establecimientos'].'">
                    </div>                        
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <label>TELEFONO:</label>
                        <input type="text" name="TxtTelefono" id="TxtTelefono" class="form-control input-xs" value="'.$datosEmp[0]['Telefono1'].'">
                    </div>
                    <div class="col-sm-2">
                        <label>TELEFONO 2:</label>
                        <input type="text" name="TxtTelefono2" id="TxtTelefono2" class="form-control input-xs" value="'.$datosEmp[0]['Telefono2'].'">
                    </div>
                    <div class="col-sm-1">
                        <label>FAX:</label>
                        <input type="text" name="TxtFax" id="TxtFax" class="form-control input-xs" value="'.$datosEmp[0]['FAX'].'">
                    </div>
                    <div class="col-sm-1">
                        <label>MONEDA</label>
                        <input type="text" name="TxtMoneda" id="TxtMoneda" class="form-control input-xs" value="USD">
                    </div>
                    <div class="col-sm-2">
                        <label>NO. PATRONAL:</label>
                        <input type="text" name="TxtNPatro" id="TxtNPatro" class="form-control input-xs" value="'.$datosEmp[0]['No_Patronal'].'">
                    </div>
                    <div class="col-sm-1">
                        <label>COD.BANCO</label>
                        <input type="text" name="TxtCodBanco" id="TxtCodBanco" class="form-control input-xs" value="'.$datosEmp[0]['CodBanco'].'">
                    </div>
                    <div class="col-sm-1">
                        <label>TIPO CAR.</label>
                        <input type="text" name="TxtTipoCar" id="TxtTipoCar" class="form-control input-xs" value="'.$datosEmp[0]['Tipo_Carga_Banco'].'">
                    </div>
                    <div class="col-sm-2">
                        <label>ABREVIATURA</label>
                        <input type="text" name="TxtAbrevi" id="TxtAbrevi" class="form-control input-xs" value="'.$datosEmp[0]['Abreviatura'].'">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label>EMAIL DE LA EMPRESA:</label>
                        <input type="text" name="TxtEmailEmpre" id="TxtEmailEmpre" class="form-control input-xs" value="'.$datosEmp[0]['Email'].'">
                    </div>                        
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label>EMAIL DE CONTABILIDAD:</label>
                        <input type="text" name="TxtEmailConta" id="TxtEmailConta" class="form-control input-xs" value="'.$datosEmp[0]['Email_Contabilidad'].'">
                    </div>                        
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>EMAIL DE RESPALDO:</label>
                        <input type="text" name="TxtEmailRespa" id="TxtEmailRespa" class="form-control input-xs" value="'.$datosEmp[0]['Email_Respaldos'].'">
                    </div>
                    <div class="col-sm-4 text-center">
                        <label>SEGURO DESGRAVAMEN %</label>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <input type="text" name="TxtSegDes1" id="TxtSegDes1" class="form-control input-xs" value="'.$datosEmp[0]['Seguro'].'">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="TxtSegDes2" id="TxtSegDes2" class="form-control input-xs" value="'.$datosEmp[0]['Seguro2'].'">
                            </div>
                        </div>                        
                    </div>
                    <div class="col-sm-2">
                        <label>SUBDIR:</label>
                        <input type="text" name="TxtSubdir" id="TxtSubdir" class="form-control input-xs" value="'.$datosEmp[0]['SubDir'].'" onblur="subdireccion()" onkeyup="mayusculas('.'\'TxtSubdir\''.'",this.value);">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-10">
                        <label>NOMBRE DEL CONTADOR</label>
                        <input type="text" name="TxtNombConta" id="TxtNombConta" class="form-control input-xs" value="'.$datosEmp[0]['Contador'].'">
                    </div>
                    <div class="col-sm-2">
                        <label>RUC CONTADOR:</label>
                        <input type="text" name="TxtRucConta" id="TxtRucConta" class="form-control input-xs" value="'.$datosEmp[0]['RUC_Contador'].'" onkeyup="num_caracteres('.'\'TxtRucConta\''.'",13)" autocomplete="off">
                    </div>
                </div>';

		            // -----------------------------------------------datos procesos generales------------------------------------
                	$ASDAS=''; $MFNV=''; $MPVP=''; $IRCF=''; $IMR=''; $IRIP=''; $PDAC=''; $RIAC='';
                  	if($datosEmp[0]['Det_SubMod']==1){  $ASDAS='checked'; }
	                if($datosEmp[0]['Mod_Fact']==1){  $MFNV='checked'; }
	                if($datosEmp[0]['Mod_PVP']==1){ $MPVP='checked'; }
	                if($datosEmp[0]['Imp_Recibo_Caja']==1){ $IRCF = 'checked'; }
	                if($datosEmp[0]['Medio_Rol']==1){ $IMR = 'checked'; }
	                if($datosEmp[0]['Rol_2_Pagina']==1){ $IRIP = 'checked'; }
	                if($datosEmp[0]['Det_Comp']==1){ $PDAC = 'checked'; }
	                if($datosEmp[0]['Registrar_IVA']==1){  $RIAC = 'checked'; }

	                // if(response[0]['Sucursal']!=0)
	                // {
	                //     $('#FCMS').prop('checked',true);
	                // }


	                //DS   IS  ES  NDS  NCS
	                //NUMERACION DE COMPROBANTES
	                if($datosEmp[0]['Num_CD']==1){ $DM = 'checked'; $DS= ''; }else{ $DS= 'checked'; $DM = '';}

	                if($datosEmp[0]['Num_CI']==1){ $IM='checked';  $IS='';  }else{ $IS='checked';  $IM='';   }

	                if($datosEmp[0]['Num_CE']==1){ $EM='checked';  $ES='';  }else{ $ES='checked';  $EM='';  }

	                if($datosEmp[0]['Num_ND']==1){ $NDM='checked'; $NDS=''; }else{ $NDS='checked'; $NDM='';  }

	                if($datosEmp[0]['Num_NC']==1){ $NCM='checked'; $NCS=''; }else{ $NCS='checked'; $NCM=''; }


	                $Autenti ='';$SSL='';$Secure = '';
	                if($datosEmp[0]['smtp_UseAuntentificacion']==1){ $Autenti = 'checked'; }

	                if($datosEmp[0]['smtp_SSL']==1){ $SSL = 'checked';}

	                if($datosEmp[0]['smtp_Secure']==1){ $Secure = 'checked'; }

	               /* if($datosEmp[0]['Obligado_Conta']!= '')
	                {
	                    $('#ddl_obli').val(response[0]['Obligado_Conta']);
	                }
*/
	                $empresaSQL3 = '<div class="row">
                    <div class="col-md-4" style="background-color:#ffe0c0">                                   
                    <!-- setesos -->
                        <label>|Seteos Generales|</label>
                        <div class="checkbox">
                            <label><input type="checkbox" name="ASDAS" id="ASDAS" '.$ASDAS.'>Agrupar Saldos Detalle Auxiliar de Submodulos</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="MFNV" id="MFNV" '.$MFNV.'>Modificar Facturas o Notas de Venta</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="MPVP" id="MPVP" '.$MPVP.'>Modificar Precio de Venta al Público</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="IRCF" id="IRCF" '.$IRCF.'>Imprimir Recibo de Caja en Facturación</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="IMR" id="IMR" '.$IMR.'>Imprimir Medio Rol</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="IRIP" id="IRIP" '.$IRIP.'>Imprimir dos Roles Individuales por pagina</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="PDAC" id="PDAC" '.$PDAC.'>Procesar Detalle Auxiliar de Comprobantes</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="RIAC" id="RIAC" '.$RIAC.'>Registrar el IVA en el Asiento Contable</label>
                        </div>
                        <div class="checkbox">
                            <label><input type="checkbox" name="FCMS" id="FCMS">Funciona como Matriz de Sucursales</label>
                        </div>
                    </div>
                    <div class="col-md-4">                                        
                        <label>LOGO TIPO </label>
                        <!-- llenar con contenuido de la carpeta logotipos -->

                        <input type="text" name="TxtXXXX" id="TxtXXXX" class="form-control input-xs" value="XXXXXXXXXX">
                        <div class="form-group" rows="11">                                        
                            <select class="form-control" onchange="cargar_img()" id="ddl_img" name="ddl_img" row="11" multiple>';

                            $directorio = dirname(__DIR__,3).'/img/logotipos'; 
                            // print_r($directorio);die();
							$archivos = scandir($directorio);
							foreach ($archivos as $archivo) {
							    $rutaArchivo = $directorio . '/' . $archivo;
							    if (is_file($rutaArchivo) && pathinfo($rutaArchivo, PATHINFO_EXTENSION) === 'png') {
							    	$empresaSQL3.='<option value="'.$archivo.'">'.$archivo.'</option>';
							    }
							}                                                                         
                          $empresaSQL3.='</select>                                                
                        </div>
                        <div class="row">
                        	<div class="col-sm-10">
                        	    <input type="file" id="file_img" name="file_img" />
                        	</div>
                        	<div class="col-sm-2">
                        	    <button type="button" class="btn btn-primary btn-sm" id="subir_imagen" onclick="subir_img()" >Cargar</button>                        	
                        	</div>
                        </div>
                        
                    </div>
                    <div class="col-md-4">                                        
                        <div class="box-body">';
                        if($datosEmp[0]['Logo_Tipo']=='' || $datosEmp[0]['Logo_Tipo']=='.')
                        {
                        	$empresaSQL3.='<img src="../../img/logotipos/sin_img.jpg" id="img_logo" name="img_logo" style="width:100%;border:1px solid"/>';
                        }else{
                        	$url = dirname(__DIR__,3)."/img/logotipos/".$datosEmp[0]['Logo_Tipo'].".png";
                        	// print_r($url);die();
                        	if(file_exists($url))
                        	{
                        	$empresaSQL3.='<img src="../../img/logotipos/'.$datosEmp[0]['Logo_Tipo'].'.png" id="img_logo" name="img_logo" style="width:100%;border:1px solid"/>';
                        	}else
                        	{
                        		$empresaSQL3.='<img src="../../img/logotipos/sin_img.jpg" id="img_logo" name="img_logo" style="width:100%;border:1px solid"/>';
                        	}
                        }
                       $empresaSQL3.='</div>                                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label>|Numeración de Comprobantes|</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm1" id="DM" '.$DM.' onclick="DiariosM()">Diarios por meses</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm1" id="DS" '.$DS.' onclick="DiariosS()">Diarios secuenciales</label>                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm2" id="IM" '.$IM.' onclick="IngresosM()">Ingresos por meses</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm2" id="IS" '.$IS.' onclick="IngresosS()">Ingresos secuenciales</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm3" id="EM" '.$EM.' onclick="EgresosM()">Egresos por meses</label>
                            </div>
                            <div class="col-sm-6">
                                <label> <input type="radio" name="dm3" id="ES" '.$ES.' onclick="EgresosS()">Egresos secuenciales</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm4" id="NDM" '.$NDM.' onclick="NDPM()">N/D por meses</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm4" id="NDS" '.$NDS.' onclick="NDPS()">N/D secuenciales</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm5" id="NCM" '.$NCM.' onclick="NCPM()">N/C por meses</label>
                            </div>
                            <div class="col-sm-6">
                                <label><input type="radio" name="dm5" id="NCS" '.$NCS.' onclick="NCPS()">N/C secuenciales</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">                        
                        <div class="row">
                            <div class="col-sm-12" style="background-color:#ffffc0">
                                <b>|Servidor de Correos|</b>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-10" style="background-color:#ffffc0">
                                <b>Servidor SMTP</b>
                                <input type="text" name="TxtServidorSMTP" id="TxtServidorSMTP" class="form-control input-xs" value="'.$datosEmp[0]['smtp_Servidor'].'">
                            </div>
                            <div class="col-sm-2" style="background-color:#ffffc0">
                                <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
                                <button type="button" class="btn btn-default" title="Grabar Empresa" onclick="()">
                                    <img src="../../img/png/grabar.png">
                                </button>
                            </div>
                            </div>
                        </div>
                        <div class="row" style="background-color:#ffffc0">
                            <div class="col-sm-3">
                                <input type="checkbox" name="Autenti" id="Autenti" '.$Autenti.'>Autentificación
                            </div>
                            <div class="col-sm-2">
                                <input type="checkbox" name="SSL" id="SSL" '.$SSL.'>SSL
                            </div>
                            <div class="col-sm-2">
                                <input type="checkbox" name="Secure" id="Secure" '.$Secure.'>SECURE
                            </div>
                            <div class="col-sm-1">
                                PUERTO
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="TxtPuerto" id="TxtPuerto" class="form-control input-xs" value="'.$datosEmp[0]['smtp_Puerto'].'">                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <input type="checkbox" name="AsigUsuClave" id="AsigUsuClave" onclick="MostrarUsuClave()">ASIGNA USUARIO Y CLAVE DEL REPRESENTANTE LEGAL
                            </div>
                            <div class="col-sm-2">
                                <label id="lblUsuario" style="display:none" >USUARIO</label>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="TxtUsuario" id="TxtUsuario" class="form-control input-xs" value="USUARIO"  style="display:none" > 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <input type="checkbox"  name="CopSeEmp" id="CopSeEmp" onclick="MostrarEmpresaCopia()">COPIAR SETEOS DE OTRA EMPRESA
                            </div>
                            <div class="col-sm-2">
                                <label id="lblClave"  style="display:none" >CLAVE</label>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" name="TxtClave" id="TxtClave" class="form-control input-xs" value=""  style="display:none" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <select class="form-control input-xs" id="ListaCopiaEmpresa" name="ListaCopiaEmpresa">
                                    <option value="">Empresa</option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
                
                <div class="row">
                    <div class="col-md-4" style="background-color:#c0ffc0">
                        <label>|Cantidad de Decimales en|</label>
                    </div>                                    
                </div>
                <div class="row">
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        P.V.P
                        <input type="text" name="TxtPVP" id="TxtPVP" class="form-control input-xs" value="'.$datosEmp[0]['Dec_PVP'].'">
                    </div>
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        COSTOS
                        <input type="text" name="TxtCOSTOS" id="TxtCOSTOS" class="form-control input-xs" value="'.$datosEmp[0]['Dec_Costo'].'">
                    </div>
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        I.V.A
                        <input type="text" name="TxtIVA" id="TxtIVA" class="form-control input-xs" value="'.$datosEmp[0]['Dec_IVA'].'">
                    </div>
                    <div class="col-md-1" style="background-color:#c0ffc0">
                        CANTIDAD
                        <input type="text" name="TxtCantidad" id="TxtCantidad" class="form-control input-xs" value="'.$datosEmp[0]['Dec_Cant'].'">
                    </div>
                </div>
                <script> autocompletarCempresa();</script>';

                // print_r($datosEmp);die();
		        }
			}

		$CI = $datos[0]['RUC_CI_NIC'];
			foreach ($datos as $key => $value) 
			{				
				// print_r($value);die();
			
			/*$op ='';
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';		*/
			$op = '          
<div class="col-md-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs" id="myTabs">
            <li class="active"><a href="#tab_0" data-toggle="tab">Datos Principales</a></li>
            <li><a href="#tab_1" data-toggle="tab">Datos Principales</a></li>
            <li><a href="#tab_2" data-toggle="tab">Procesos Generales</a></li>
            <li><a href="#tab_3" data-toggle="tab">Comprobantes Electrónicos</a></li>                        
        </ul>        
        <div class="tab-content">
        	<div class="tab-pane active" id="tab_0">
        		<div class="row">
	        		<div class="col-md-4">
						<div class="form-group">
						    <label for="Estado">Estado</label>
						    <select class="form-control input-sm" name="Estado" id="Estado" >
								<option value='.$value['Estado'].'>'.$value['Estado'].'</option>
							    <option value="0">Seleccione Estado</option>';
							    $op.= $this->estados();
						$op.='</select>
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
						  <label for="FechaR">Renovación</label>
						   
						  <input type="date" class="form-control input-sm" id="FechaR" name="FechaR" placeholder="FechaR" 
						  value='.$value['Fecha'].' 
						  onKeyPress="return soloNumeros(event)"  maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
						  <label for="Fecha">Comp. Electronico</label>
						   
						  <input type="date" class="form-control input-sm" id="Fecha" name="Fecha" placeholder="Fecha" 
						  value="'.$value['Fecha_CE'].'" onKeyPress="return soloNumeros(event)" 
						  maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
						</div>
					</div>
					<div class="col-md-2" style="display:none">
						<div class="form-group">
						  <label for="Fecha">Fecha VPN</label>
						   
						  <input type="date" class="form-control input-sm" id="FechaV" name="FechaV" placeholder="FechaV" 
						  value="'.$value['Fecha_VPN'].'"   onKeyPress="return soloNumeros(event)" maxlength="10"onkeyup="validar_year_mayor(this.id)" onblur="validar_year_menor(this.id)">
						</div>
					</div>
					<div class="col-md-2">
						<div class="form-group">
						  <label for="Fecha_DB">BD</label>
						  <input type="date" class="form-control input-sm" id="FechaDB" name="FechaDB" value="'.$value['Fecha_DB'].'">
						</div>
					</div>
					<div class="col-md-2">
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
        		</div>
        	</div>
            <!-- DATOS PRINCIPALES INICIO -->
            <div class="tab-pane" id="tab_1">
            '.$empresaSQL2.'                
            </div>
            <!-- DATOS PRINCIPALES FIN -->
            <!-- PROCESOS GENERALES INICIO -->
            <div class="tab-pane" id="tab_2">                                
              '.$empresaSQL3.'  
            </div>
            <!-- PROCESOS GENERALES FIN -->
            <!-- COMPROBANTES ELECTRONICOS INICIO-->
            <div class="tab-pane" id="tab_3">
               <div class="row">'.$empresaSQL.'</div>	               
            </div>
            <!-- COMPROBANTES ELECTRONICOS FIN-->  
            </form>                  
        </div>
    </div>
</div>            ';	
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
		// print_r($parametros);die();
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

}

?>