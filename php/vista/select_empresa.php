<?php
// include('../db/chequear_seguridad.php');
require_once("../controlador/panel.php");

$pag = new select_empresa();
if(isset($_GET['consultar']))
{
	echo json_encode($pag->lista_empresas());
}
if(isset($_GET['cargar']))
{
	$parametros = $_POST['parametros'];
	// print_r($parametros);die();
	echo json_encode($pag->cargar_credenciales($parametros['ID'],$parametros['EMPRESA'],$parametros['ITEM']));
}


class select_empresa
{
	
	function __construct()
	{

	}

	function lista_empresas()
	{
		$empresa= getEmpresas($_SESSION['INGRESO']['IDEntidad']);
		$i=0;
		$num = count($empresa);
		if($num > 1)
		{
			$html = '
			<div class="box-body">
				<div class="col-lg-12 col-xs-9">						
					<select class="form-control select2" name="sempresa" id="sempresa" onchange="empresa_seleccionada()">							  
					  <option value="0-0">Seleccione empresa</option>';	
					  foreach ($empresa as $valor) 
			         {
				       $v_empre=$valor['ID'].'-'.$valor['Item'];
					   $html.='<option value='.$valor['ID'].'-'.$valor['Item'].'>'.$valor['Empresa'].'</option>';

			         }         
			         
				$html.='</select>
				</div>
		 </div>';
		 return $html;
		}else if($num == 1)
		{				
		   $resp = $this->cargar_credenciales($empresa[0]['ID'].'-'.$empresa[0]['Item'],$empresa[0]['Empresa'],$empresa[0]['Item']);
		   if($resp==1)
		   {
			$html='<script>$(document).ready(function(){	
				window.location="modulos.php";
			  })
			</script>';
			return $html;
		  }
		}else
		{
			$html = '<div class="box-body text-center"><div class="col-lg-12 col-xs-9"><img src="../../img/NO_ASIGNADO.gif" style="width: 50%;"></div></div>';
			return $html;
		}

	}

	function cargar_credenciales($ID,$Empresa,$Item)
	{
		variables_sistema($ID,$Empresa,$Item);
		return 1;
	} 
}
	
?>