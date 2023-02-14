<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/lista_retencionesM.php");
require_once(dirname(__DIR__,2)."/modelo/facturacion/punto_ventaM.php");
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
if(!class_exists('enviar_emails'))
{
	require(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
}
require(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");

$controlador = new lista_retencionesC();

if(isset($_GET['tabla']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_facturas($parametros));
}



/**
 * 
 */
class lista_retencionesC
{	
	private $modelo;
    private $email;
    public $pdf;
    private $punto_venta;
    
	public function __construct(){
    	$this->modelo = new lista_retencionesM();
		$this->pdf = new cabecera_pdf();
		$this->email = new enviar_emails();
		$this->empresaGeneral = Empresa_data();
		$this->sri = new autorizacion_sri();
		$this->punto_venta = new punto_ventaM();
        //$this->modelo = new MesaModel();
    }

     function tabla_facturas($parametros)
    {

    	// print_r($parametros);die();
    	$codigo = $parametros['ci'];
    	$tbl = $this->modelo->retenciones_emitidas_tabla($codigo,$parametros['desde'],$parametros['hasta'],$parametros['serie']);
    	$tr='';
    	foreach ($tbl as $key => $value) {
    		 $exis = $this->sri->catalogo_lineas('RE',$value['Serie_Retencion']);
    		 $autorizar = '';$anular = '';
    		 $cli_data = Cliente($value['IdProv']);
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
    		 
    		$tr.='<tr>
            <td>
            <div class="input-group-btn">
								<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Acciones
								<span class="fa fa-caret-down"></span></button>
								<ul class="dropdown-menu">
								<li><a href="#" onclick="Ver_factura(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['IdProv'].'\',\''.$value['AutRetencion'].'\')"><i class="fa fa-eye"></i> Ver factura</a></li>';
								if(count($exis)>0 && strlen($value['AutRetencion'])==13 && $parametros['tipo']!='')
    		 				{
									$tr.='<li><a href="#" onclick="autorizar(\''.$value['TC'].'\',\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['Fecha']->format('Y-m-d').'\')" ><i class="fa fa-paper-plane"></i>Autorizar</a></li>';
								}
								if($value['T']!='A' && $parametros['tipo']!='')
    		 				{
									$tr.='<li><a href="#" onclick="anular_factura(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['IdProv'].'\')"><i class="fa fa-times-circle"></i>Anular factura</a></li>';
								}
								$tr.='<li><a href="#" onclick=" modal_email_fac(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['IdProv'].'\',\''.$email.'\')"><i class="fa fa-envelope"></i> Enviar Factura por email</a></li>
								<li><a href="#" onclick="descargar_fac(\''.$value['SecRetencion'].'\',\''.$value['Serie_Retencion'].'\',\''.$value['IdProv'].'\')"><i class="fa fa-download"></i> Descargar Factura</a></li>';
								if(strlen($value['AutRetencion'])>13)
								{
								 $tr.='<li><a href="#" onclick="descargar_xml(\''.$value['AutRetencion'].'\')"><i class="fa fa-download"></i> Descargar XML</a></li>';
								}
								 $tr.='
								</ul>
						</div>


            </td>
            <td>'.$value['T'].'</td>
            <td>'.$value['Cliente'].'</td>
            <td>'.$value['TD'].'</td>
            <td>'.$value['Serie_Retencion'].'</td>
            <td>'.$value['AutRetencion'].'</td>
            <td>'.$value['SecRetencion'].'</td>
            <td>'.$value['Fecha']->format('Y-m-d').'</td>
            <td class="text-right">'.$value['TD'].'</td>
            <td class="text-right">'.$value['TD'].'</td>
            <td class="text-right">'.$value['TD'].'</td>
            <td class="text-right">'.$value['TD'].'</td>
            <td class="text-right">'.$value['TD'].'</td>
            <td class="text-right">'.$value['TD'].'</td>
            <td>'.$value['CI_RUC'].'</td>
            <td>'.$value['TD'].'</td>
          </tr>';
    	}

    	// print_r($tr);die();

    	return $tr;
    }

}


?>