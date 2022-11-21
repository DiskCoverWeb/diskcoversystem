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

if(isset($_GET['provincias']))
{	
    $pais = '';
    if(isset($_POST['pais']))
    {
        $pais = $_POST['pais'];
    }
	echo json_encode(provincia_todas($pais));	
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
if(isset($_GET['informacion_empre']))
{	
    $para = $_POST['parametros'];
    $res = $controlador->info_empresa($para);

    // echo json_encode($res);
    print_r($res);die();
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
    function llamardb($l1)
    {

    }

}



?>