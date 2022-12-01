<?php
include(dirname(__DIR__,2).'/modelo/empresa/crear_empresaM.php');

$controlador = new crear_empresaC();

if(isset($_GET['llamar'])){
    $ll='';
    if(isset($_POST['item']))
    {
        $ll=$_POST['item'];
    }
    echo json_encode($controlador->llamardb($ll));	
}
if(isset($_GET['guardar_empresa']))
{
    print_r ($_POST);die();
    // $razon1 = '';
    // if(isset($_POST['razon1']));
    // {
    //     $razon1 = $_POST['razon1'];
    // }
    // echo json_encode($controlador->guardardb_empresa($razon1));
    $query = $_POST;
	echo json_encode($controlador->guardardb_empresas($query));
}
if(isset($_GET['delete']))
{
    // print_r($_POST);die();
    $id='';
    if(isset($_POST['id']));
    {
        $id =$_POST['id'];
    }
	echo json_encode($controlador->delete_empresas($id));
}
if(isset($_GET['naciones']))
{
  //echo 'hola';
  echo json_encode(naciones_todas());
  //print_r(provincia_todas());
}
if(isset($_GET['provincias']))
{
	//echo 'hola';
  $pais = '';
  if(isset($_POST['pais']))
  {
    $pais = $_POST['pais'];
  }
	echo json_encode(provincia_todas($pais));
	//print_r(provincia_todas());
}
if(isset($_GET['ciudad']))
{
	echo json_encode(todas_ciudad($_POST['idpro']));
}
if(isset($_GET['empresas']))
{	
    $dato = '';
    if(isset($_GET['q']))
    {
        $dato = $_GET['q'];
    }
	echo json_encode($controlador->lista_empresas($dato));	
}
if(isset($_GET['usuario']))
{	
    // $dato = '';
    // if(isset($_GET['q']))
    // {
    //     $dato = $_GET['q'];
    // }
	// echo json_encode($controlador->lista_usuario($dato));
    //print_r($_POST);die();
    $query = $_POST;
	echo json_encode($controlador->lista_usuario($query));	
}
if(isset($_GET['informacion_empre']))
{	
    $para = $_POST['parametros'];
    $res = $controlador->info_empresa($para);

    // echo json_encode($res);
//    print_r($res);die();
}
if(isset($_GET['formulario']))
{	
    // $para = $_POST;
    $para = '';
    // print_r($para);die();
    $res = $controlador->formulario1($para);

    echo json_encode($res);
    //  print_r($res);die();
}
class crear_empresaC 
{
    private $modelo;
    function __construct()
	{
        $this->modelo = new crear_empresaM();
    }

    function info_empresa($parametros)
    {        
        if($parametros ['id']=='empresa' )
        {
            return 'empresa1';
        }
        else{
            return 'no empresa';
        }
        
    }
    function formulario1($dato)
    {
        $datos = $this->modelo->lista_empresas($dato);        
        $lis = array();
        foreach ($datos as $key => $value) {
            $lis[] = array(
                'id'=>$value['Item'],
                'Fecha'=>$value['Fecha'],
                'Ciudad'=>$value['Ciudad'],
                'Pais'=>$value['Pais'],
                'Empresa'=>$value['Empresa'],
                'Gerente'=>$value['Gerente'],
                'RUC'=>$value['RUC'],
                'Telefono1'=>$value['Telefono1'],
                'Telefono2'=>$value['Telefono2'],
                'FAX'=>$value['FAX'],
                'Direccion'=>$value['Direccion'],
                'SubDir'=>$value['SubDir'],
                'Logo_Tipo'=>$value['Logo_Tipo'],
                'Alto'=>$value['Alto'],
                'Servicio'=>$value['Servicio'],
                'S_M'=>$value['S_M'],
                'Cta_Caja'=>$value['Cta_Caja'],
                'Cotizacion'=>$value['Cotizacion'],
                'Email'=>$value['Email'],
                'Contador'=>$value['Contador'],
                'CodBanco'=>$value['CodBanco'],
                'Num_Meses'=>$value['Num_Meses'],
                'Nombre_Comercial'=>$value['Nombre_Comercial'],
                'Mod_Fact'=>$value['Mod_Fact'],
                'Mod_Fecha'=>$value['Mod_Fecha'],
                'Num_CD'=>$value['Num_CD'],
                'Num_CE'=>$value['Num_CE'],
                'Num_CI'=>$value['Num_CI'],
                'Plazo_Fijo'=>$value['Plazo_Fijo'],
                'Det_Comp'=>$value['Det_Comp'],
                'CI_Representante'=>$value['CI_Representante'],                
                'TD'=>$value['TD'],
                'RUC_Contador'=>$value['RUC_Contador'],
                'CPais'=>$value['CPais'],
                'No_Patronal'=>$value['No_Patronal'],
                'Dec_PVP'=>$value['Dec_PVP'],
                'Dec_Costo'=>$value['Dec_Costo'],
                'CProv'=>$value['CProv'],
                'Grabar_PV'=>$value['Grabar_PV'],
                'Separar_Grupos'=>$value['Separar_Grupos'],
                'Credito'=>$value['Credito'],
                'Rol_2_Pagina'=>$value['Rol_2_Pagina'],
                'Cant_Item_PV'=>$value['Cant_Item_PV'],
                'Copia_PV'=>$value['Copia_PV'],
                'Encabezado_PV'=>$value['Encabezado_PV'],
                'Calcular_Comision'=>$value['Calcular_Comision'],
                'Formato_Inventario'=>$value['Formato_Inventario'],
                'Formato_Activo'=>$value['Formato_Activo'],
                'Cant_Ancho_PV'=>$value['Cant_Ancho_PV'],
                'Grafico_PV'=>$value['Grafico_PV'],
                'Referencia'=>$value['Referencia'],
                'Fecha_Rifa'=>$value['Fecha_Rifa'],
                'Rifa'=>$value['Rifa'],
                'Monto_Minimo'=>$value['Monto_Minimo'],
                'Num_ND'=>$value['Num_ND'],
                'Num_NC'=>$value['Num_NC'],
                'Cierre_Vertical'=>$value['Cierre_Vertical'],
                'Tipo_Carga_Banco'=>$value['Tipo_Carga_Banco'],
                'Comision_Ejecutivo'=>$value['Comision_Ejecutivo'],
                'Seguro'=>$value['Seguro'],
                'Nombre_Banco'=>$value['Nombre_Banco'],
                'Impresora_Rodillo'=>$value['Impresora_Rodillo'],
                'Impresora_Defecto'=>$value['Impresora_Defecto'],
                'Papel_Impresora'=>$value['Papel_Impresora'],
                'Marca_Agua'=>$value['Marca_Agua'],
                'Seguro2'=>$value['Seguro2'],
                'Cta_Banco'=>$value['Cta_Banco'],
                'Mod_PVP'=>$value['Mod_PVP'],
                'Abreviatura'=>$value['Abreviatura'],
                'Registrar_IVA'=>$value['Registrar_IVA'],
                'Imp_Recibo_Caja'=>$value['Imp_Recibo_Caja'],
                'Det_SubMod'=>$value['Det_SubMod'],
                'Establecimientos'=>$value['Establecimientos'],
                'Email_Conexion'=>$value['Email_Conexion'],
                'Actualizar_Buses'=>$value['Actualizar_Buses'],
                'Email_Contabilidad'=>$value['Email_Contabilidad'],
                'Cierre_Individual'=>$value['Cierre_Individual'],
                'Email_Respaldos'=>$value['Email_Respaldos'],
                'Imp_Ceros'=>$value['Imp_Ceros'],
                'Tesorero'=>$value['Tesorero'],
                'CIT'=>$value['CIT'],
                'Dec_IVA'=>$value['Dec_IVA'],
                'Dec_Cant'=>$value['Dec_Cant'],
                'Razon_Social'=>$value['Razon_Social'],
                'Formato_Cuentas'=>$value['Formato_Cuentas'],
                'Ambiente'=>$value['Ambiente'],
                'Ruta_Certificado'=>$value['Ruta_Certificado'],
                'Clave_Certificado'=>$value['Clave_Certificado'],
                'Web_SRI_Recepcion'=>$value['Web_SRI_Recepcion'],
                'Codigo_Contribuyente_Especial'=>$value['Codigo_Contribuyente_Especial'],
                'Email_Conexion_CE'=>$value['Email_Conexion_CE'],
                'Obligado_Conta'=>$value['Obligado_Conta'],
                'Por_CxC'=>$value['Por_CxC'],
                'Estado'=>$value['Estado'],
                'No_Autorizar'=>$value['No_Autorizar'],
                'Email_Procesos'=>$value['Email_Procesos'],
                'Email_CE_Copia'=>$value['Email_CE_Copia'],
                'Firma_Digital'=>$value['Firma_Digital'],
                'Combo'=>$value['Combo'],
                'Fecha_Igualar'=>$value['Fecha_Igualar'],
                'Ret_Aut'=>$value['Ret_Aut'],
                'LeyendaFAT'=>$value['LeyendaFAT'],
                'Signo_Dec'=>$value['Signo_Dec'],
                'Signo_Mil'=>$value['Signo_Mil'],
                'Fecha_CE'=>$value['Fecha_CE'],
                'Centro_Costos'=>$value['Centro_Costos'],
                'smtp_Servidor'=>$value['smtp_Servidor'],
                'smtp_Puerto'=>$value['smtp_Puerto'],
                'smtp_UseAuntentificacion'=>$value['smtp_UseAuntentificacion'],
                'smtp_SSL'=>$value['smtp_SSL'],
                'Serie_FA'=>$value['Serie_FA'],
                'Email_Contraseña'=>$value['Email_Contraseña'],
                'Email_Contraseña_CE'=>$value['Email_Contraseña_CE'],                
                'X'=>$value['X'],
                'Debo_Pagare'=>$value['Debo_Pagare'],
                'smtp_Secure'=>$value['smtp_Secure'],
                'Cartera'=>$value['Cartera'],
                'Cant_FA'=>$value['Cant_FA'],
                'Fecha_P12'=>$value['Fecha_P12'],
                'Tipo_Plan'=>$value['Tipo_Plan'],
                'ID'=>$value['ID'],
                'RUC_Operadora'=>$value['RUC_Operadora'],
            );
        }
        return $lis; 
    }

    function lista_empresas($dato)
    {
        $datos = $this->modelo->lista_empresas($dato);

        $lis = array();
        foreach ($datos as $key => $value) {
            $lis[] = array('id'=>$value['Item'],'text'=>$value['Empresa']);
        }
        return $lis;    
    }
    function lista_usuario($dato)
    {
        //print_r($dato);die();
        $datos = $this->modelo->usuario($dato);
        $lis = array();
        foreach ($datos as $key => $value) {
            $lis[] = array('id'=>$value['Codigo'],'text'=>$value['Usuario']);
        }
        return $lis;    
    }
    function llamardb($l1)
    {
        $datos = $this->modelo->lista_empresas(false,$l1);
        // print_r($datos);die();
        return $datos;
    }
    function guardardb_empresa($parametro)
    {
        //  print_r($parametro);die();
        $resp = $this->modelo->lista_empresas(trim($parametro['item']));
        $datos[0]['campo'] = 'Razon_Social'; 
        $datos[0]['dato'] = $parametro['TxtRazonSocial']; 
        // // // $datos[1]['campo'] = 'Nombre'; 
        // // // $datos[1]['dato'] = $parametros['nombre']; 
        
        if($parametro['TxtItem']!='')
		{
			$campoWhere[0]['campo'] = 'Item';
			$campoWhere[0]['valor'] = $parametro['TxtItem'];
			$re = update_generico($datos,'Empresa',$campoWhere);
		}else
		{
			print_r($resp);die();
			if(count($resp)==0)
		      {
			    $re = insert_generico('Empresas',$datos); // optimizado pero falta 
			  }else{
			  	return 2;
			  }
		}

        // insert_generico('Empresas',$datos);
    }
    function delete_empresas($id)
	{
        // print_r('---'$id);die();
		return $this->modelo->delete_empresa($id);
	}
    function guardardb_empresas($parametros)  //para un solo dato string
    {
        //print_r($parametros);die();
        //$datos[0]['campo'] = 'Razon_Social'; 
        //$datos[0]['dato'] = $razon;
        //DATOS PRINCIPALES
        $datos[0]['dato'] = $parametros['TxtEmpresa']; 
        $datos[0]['campo'] = 'Empresa'; 
	    $datos[1]["dato"] = $parametros["TxtRazonSocial"];
	    $datos[1]["campo"] = 'Razon_Social';
	    $datos[2]["dato"] = $parametros["TxtNomComercial"];
	    $datos[2]["campo"] = 'Nombre_Comercial';
	    $datos[3]["dato"] = $parametros["TxtRuc"];
	    $datos[3]["campo"] = 'RUC';
        $datos[4]["dato"] = $parametros["TxtRepresentanteLegal"];
	    $datos[4]["campo"] = 'Gerente';
	    $datos[5]["dato"] = $parametros["TxtCI"];
	    $datos[5]["campo"] = 'CI_Representante';
	    $datos[6]["dato"] = $parametros["TxtDirMatriz"];
	    $datos[6]["campo"] = 'Direccion';
	    $datos[7]["dato"] = $parametros["TxtEsta"];
	    $datos[7]["campo"] = 'Establecimientos';
	    $datos[8]["dato"] = $parametros["TxtTelefono"];
	    $datos[8]["campo"] = 'Telefono1';
	    $datos[9]["dato"] = $parametros["TxtTelefono2"];
	    $datos[9]["campo"] = 'Telefono2';
	    $datos[10]["dato"] = $parametros["TxtFax"];
	    $datos[10]["campo"] = 'FAX';
	    $datos[11]["dato"] = $parametros["TxtMoneda"];
	    $datos[11]["campo"] = 'S_M';
	    $datos[12]["dato"] = $parametros["TxtNPatro"];
	    $datos[12]["campo"] = 'No_Patronal';
	    $datos[13]["dato"] = $parametros["TxtCodBanco"];
	    $datos[13]["campo"] = 'CodBanco';
	    $datos[14]["dato"] = $parametros["TxtTipoCar"];
	    $datos[14]["campo"] = 'Tipo_Carga_Banco';
	    $datos[15]["dato"] = $parametros["TxtAbrevi"];
	    $datos[15]["campo"] = 'Abreviatura';
	    $datos[16]["dato"] = $parametros["TxtEmailEmpre"];
	    $datos[16]["campo"] = 'Email';
	    $datos[17]["dato"] = $parametros["TxtEmailConta"];
	    $datos[17]["campo"] = 'Email_Contabilidad';
	    $datos[18]["dato"] = $parametros["TxtEmailRespa"];
	    $datos[18]["campo"] = 'Email_Respaldos';
	    $datos[19]["dato"] = $parametros["TxtSegDes1"];
	    $datos[19]["campo"] = 'Seguro';
	    $datos[20]["dato"] = $parametros["TxtSegDes2"];
	    $datos[20]["campo"] = 'Seguro2';
	    $datos[21]["dato"] = $parametros["TxtSubdir"];
	    $datos[21]["campo"] = 'SubDir';
	    $datos[22]["dato"] = $parametros["TxtNombConta"];
	    $datos[22]["campo"] = 'Contador';
	    $datos[23]["dato"] = $parametros["TxtRucConta"];
	    $datos[23]["campo"] = 'RUC_Contador';
	    $datos[24]["dato"] = $parametros["ddl_obli"];
	    $datos[24]["campo"] = 'Obligado_Conta';
	    $datos[25]["dato"] = $parametros["ddl_naciones"];
	    $datos[25]["campo"] = 'CPais';
	    $datos[26]["dato"] = $parametros["prov"];
	    $datos[26]["campo"] = 'Prov';
	    $datos[27]["dato"] = $parametros["ddl_ciudad"];
	    $datos[27]["campo"] = 'Ciudad';
        //PROCESOS GENERALES        
        if($parametros['ckASDAS']=='false')
		{
			$dato[28]['campo']='Det_SubMod';
            $dato[28]['dato']=0;
		}else
        {
            $datos[28]["campo"] = 'Det_SubMod';
            $datos[28]["dato"] = 1;
        }
        $datos[29]["dato"] = $parametros["TxtServidorSMTP"];
        $datos[29]["campo"] = 'smtp_Servidor';
	    $datos[30]["dato"] = $parametros["TxtPuerto"];
	    $datos[30]["campo"] = 'smtp_Puerto';
	    $datos[31]["dato"] = $parametros["TxtPVP"];
	    $datos[31]["campo"] = 'Dec_PVP';
	    $datos[32]["dato"] = $parametros["TxtCOSTOS"];
	    $datos[32]["campo"] = 'Dec_Costo';
	    $datos[33]["dato"] = $parametros["TxtIVA"];
	    $datos[33]["campo"] = 'Dec_IVA';
	    $datos[34]["dato"] = $parametros["TxtCantidad"];
		$datos[34]["campo"] = 'Dec_Cant';

        //COMPROBANTES ELECTRÓNICOS
		$datos[35]["dato"] = $parametros["TxtContriEspecial"];
		$datos[35]["campo"] = 'Codigo_Contribuyente_Especial';
		$datos[36]["dato"] = $parametros["TxtWebSRIre"];
		$datos[36]["campo"] = 'Web_SRI_Recepcion';
		$datos[37]["dato"] = $parametros["TxtWebSRIau"];
		$datos[37]["campo"] = 'Web_SRI_Autorizado';
		$datos[38]["dato"] = $parametros["TxtEXTP12"];
		$datos[38]["campo"] = 'Ruta_Certificado';
        $datos[39]["dato"] = $parametros["TxtContraExtP12"];
		$datos[39]["campo"] = 'Clave_Certificado';
		$datos[40]["dato"] = $parametros["TxtEmailGE"];
		$datos[40]["campo"] = 'Email_Conexion';
		$datos[41]["dato"] = $parametros["TxtContraEmailGE"];
		$datos[41]["campo"] = 'Email_Contraseña';
		$datos[42]["dato"] = $parametros["TxtEmaiElect"];
		$datos[42]["campo"] = 'Email_Conexion_CE';

        $datos[43]["dato"] = $parametros["TxtContraEmaiElect"];
		$datos[43]["campo"] = 'Email_Contraseña_CE';
		$datos[44]["dato"] = $parametros["TxtCopiaEmai"];
		$datos[44]["campo"] = 'Email_Procesos';
        $datos[45]["dato"] = $parametros["TxtRUCOpe"];
		$datos[45]["campo"] = 'RUC_Operadora';
		$datos[46]["dato"] = $parametros["txtLeyendaDocumen"];
		$datos[46]["campo"] = 'LeyendaFA';
		$datos[47]["dato"] = $parametros["txtLeyendaImpresora"];
		$datos[47]["campo"] = 'LeyendaFAT';

        if($parametros['TxtItem']!='')
	    {
	    	$where[0]['campo'] = 'Item'; 
	    	$where[0]['valor'] = $parametros['TxtItem'];
	    	return update_generico($datos,'Empresas',$where);
	    }else
	    {
	    	// $resp = $this->modelo->lista_empresas(trim(false,$parametros['TxtItem']));
		    // if(count($resp)>0){return -2;}
		
	    	$r = insert_generico('Empresas',$datos);
	    	if($r==null)
	    	{
	    		return 1;
	    	}else
	    	{
	    		return -1;
	    	}
	    }        
    }

}



?>