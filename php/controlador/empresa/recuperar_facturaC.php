<?php
include(dirname(__DIR__,2).'/modelo/empresa/recuperar_facturaM.php');
include(dirname(__DIR__,2).'/comprobantes/SRI/autorizar_sri.php');

$controlador = new recuperar_facturaC();

if(isset($_GET['recuperar_factura']))
{	    
	$parametros = $_POST['parametros'];
    echo json_encode($controlador->recuperar($parametros));
}
if(isset($_GET['empresas']))
{
    $query = ''; $ciu = ''; $ent = '';
    if(isset($_GET['q'])){ $query = $_GET['q']; }
    if(isset($_GET['ciu'])){ $ciu = $_GET['ciu'];   }
    if(isset($_GET['ent'])){ $ent = $_GET['ent'];   }
    echo json_encode($controlador->empresas($query,$ent,$ciu));
}
class recuperar_facturaC 
{
    private $modelo;
    function __construct()
	{
        $this->modelo = new recuperar_facturaM();
        $this->sri = new autorizacion_sri();
    }

    function empresas($query,$ent,$ciu)
    {
        $datos = $this->modelo->entidad($query,$ent,$ciu);
        // print_r($dato);die();
        if(count($datos)>0)
        {
                foreach ($datos as $key => $value) {
                $resp[] = array('id'=>$value['Item'],'text'=>$value['Empresa'],'CI'=>$value['RUC_CI_NIC'],'data'=>$value);
            }
        }else
        {
            $resp[0] = array('id'=>'','text'=>'Empresa no encontrada');
        }
        return $resp;
    }


    function recuperar($parametros)
    {
    	$recuperar = 1;
        $TFA = array();
        $item = generaCeros($parametros['item'],2);
        $entidad = generaCeros($parametros['entidad'],3);
        $periodo = $_SESSION['INGRESO']['periodo']; //'.';
       
        $lista_faltantes = array(); 

        $serie_lineas = $this->modelo->catalogo_lineas($TC='FA',$item,$periodo,$SerieFactura=false);
        foreach ($serie_lineas as $key => $value) {
            // print_r($value);die();
            $facturas_TD =  $this->modelo->lista_facturas_faltantes($item,$periodo,$desde=false,$hasta=false,$value['Serie']);
            if(count($facturas_TD)>0)
            {
                foreach ($facturas_TD as $key => $value) {                    
                    array_push($lista_faltantes,$value);
                }
               
            }   
        }

        print_r($lista_faltantes);die();

        if(count($lista_faltantes)>0)
        {
            foreach ($lista_faltantes as $key => $value)
            {
                if($value['Documento']!='' || $value['Documento']!=null)
                {   
                    // print_r($value);die();
                    $factura = $this->modelo->facturas_a_recuperar($item,$periodo,$value['Serie'],$value['Documento'],$value['Clave_Acceso'],$desde=false,$hasta=false);
                    // print_r($factura);die();
                    if (count($factura)>0) 
                    {
                        $respuesta = $this->sri->recuperar_xml_a_factura($factura[0]['Documento_Autorizado'],$value['Clave_Acceso'],$entidad,$item);
                        // print_r($respuesta);die();
                        if($respuesta==-2)
                        {
                            return -2;
                        }
                            $data =  $this->sri->recuperar_cliente_xml_a_factura($factura[0]['Documento_Autorizado'],$value['Clave_Acceso'],$entidad,$item);
                        $lineas = $this->sri->catalogo_lineas_sri('FA',$value['Serie'],$factura[0]['Fecha']->format('Y-m-d') ,$factura[0]['Fecha']->format('Y-m-d'),1);
                        if(count($lineas)==0)
                        {
                            $lineas = $this->sri->catalogo_lineas_sri('FA',$value['Serie'],date('Y-m-d'),date('Y-m-d'),1);
                        }

                        if($respuesta==1)
                        {
                            // print_r('sdasdasdasd');die();
                            $TFA['Factura'] = $value['Documento'];
                            $TFA['TC'] = 'FA';
                            $TFA['Serie'] = $value['Serie'];
                            $TFA['Autorizacion'] = $value['Clave_Acceso'];
                            $TFA['CodigoC'] = $data['Codigo'];
                            $TFA['ClaveAcceso'] = $value['Clave_Acceso'];
                            $TFA['Cta_CxP'] = $lineas[0]['CxC'];
                            $TFA['Porc_IVA'] = $_SESSION['INGRESO']['porc'];
                            $TFA['Fecha'] = $data['Fecha'];
                            // print_r($TFA);die();
                            if(Grabar_Factura1($TFA)!=1)
                            {
                                $respuesta =-1;
                            }
                        }else
                        {
                            //print_r('dasdas');die();
                            echo 'no se pudo recuperar lineas de factura';
                        }
                    }
                }
                
                // if($key==20)
                // {
                     // print_r('diez');die();
                // }
            }
            // print_r('uno');die();
            return $respuesta;
        }else
        {
            return -3;
        }


        // print_r($factura);die();
    }
    

   

}



?>