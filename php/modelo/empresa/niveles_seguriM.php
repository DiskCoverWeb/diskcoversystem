<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
// require_once(dirname(__DIR__)."/db/db.php");
require_once(dirname(__DIR__,2)."/db/db1.php");
/**
 * 
 */
class niveles_seguriM
{
	private $conn ;
	private $db;
	function __construct()
	{
	   $this->conn = cone_ajax();
	   $this->db = new db();
	   // $this->dbs=Conectar::conexionSQL();
	}

	function modulos_todo()
	{
		$sql="SELECT * 
		    FROM modulos 
		    WHERE modulo <> '".G_NINGUNO."' and modulo <> 'VS'
		    ORDER BY aplicacion "; 
		    $datos=[];
		 $fila = $this->db->datos($sql,'MY SQL');
		 // print_r($sql);die();
		 foreach ($fila as $key => $value) {
		 	// print_r($value);die();
				$datos[]=['modulo'=>$value['modulo'],'aplicacion'=>$value['aplicacion']];				
			}

	      return $datos;
	}

	function entidades($valor)
	{
		$sql ="SELECT Nombre_Entidad,ID_Empresa,RUC_CI_NIC FROM entidad  WHERE RUC_CI_NIC <> '.' AND Nombre_Entidad LIKE '%".$valor."%' 
		    ORDER BY Nombre_Entidad";
		$enti=$this->db->datos($sql,'MY SQL');

		// print_r($enti);die();
		$datos[] = array();
		foreach ($enti as $key => $value) {
			$datos[]=['id'=>$value['ID_Empresa'],'text'=>$value['Nombre_Entidad'],'RUC'=>$value['RUC_CI_NIC'],'data'=>$value];				
		}		
	    return $datos;
	}

	function entidades_usuario($ci_nic)
	{
		$sql ="SELECT AU.Nombre_Usuario,AU.Usuario,AU.Clave, AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC As Codigo_Entidad
				FROM acceso_empresas AS AE,acceso_usuarios AS AU, entidad AS E
				WHERE AU.CI_NIC ='".$ci_nic."'
				AND AE.ID_Empresa = E.ID_Empresa 
				AND AE.CI_NIC = AU.CI_NIC
				GROUP BY AU.Nombre_Usuario,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC,AU.Usuario,AU.Clave
				ORDER BY E.Nombre_Entidad ";
		$resp=$this->db->datos($sql,'MY SQL');
		$datos=[];
		foreach ($resp as $key => $value) {
		
				$datos[]=['id'=>$value['Codigo_Entidad'],'text'=>$value['Nombre_Entidad'],'RUC'=>$value['Codigo_Entidad'],'Usuario'=>$value['Usuario'],'Clave'=>$value['Clave'],'Email'=>$value['Email']];				
		}		
	    return $datos;
	}

	function entidades_usuarios($ci_nic)
	{
		$sql ="SELECT AU.Nombre_Usuario,AU.Usuario,AU.Clave, AU.CI_NIC ,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC As Codigo_Entidad
				FROM acceso_empresas AS AE,acceso_usuarios AS AU, entidad AS E
				WHERE AE.ID_Empresa ='".$ci_nic."'
				AND AE.ID_Empresa = E.ID_Empresa 
				AND AE.CI_NIC = AU.CI_NIC
				GROUP BY AU.Nombre_Usuario,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC,AU.Usuario,AU.Clave
				ORDER BY E.Nombre_Entidad ";
		$datos=[];		
		$resp=$this->db->datos($sql,'MY SQL');
		foreach ($resp as $key => $value) {
				$datos[]=['id'=>$value['Codigo_Entidad'],'text'=>$value['Nombre_Entidad'],'RUC'=>$value['Codigo_Entidad'],'Usuario'=>$value['Usuario'],'Clave'=>$value['Clave'],'Email'=>$value['Email'],'CI_NIC'=>$value['CI_NIC'], 'Nombre_Usuario'=>$value['Nombre_Usuario']];			
		}
	    return $datos;
	}

	function empresas($entidad)
	{
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_empresa = ".$entidad." AND Item <> '".G_NINGUNO."' ORDER BY Empresa";
		// print_r($sql);die();
		$resp = $this->db->datos($sql,'MY SQL');
		  $datos=[];
		foreach ($resp as $key => $value) {
				//$datos[]=['id'=>utf8_encode($filas['Item']),'text'=>utf8_encode($filas['Empresa'])];
				$datos[]=['id'=>$value['Item'],'text'=>$value['Empresa']];			
		 }
	      return $datos;
	}
	function empresas_datos($entidad,$Item)
	{
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto   FROM lista_empresas WHERE ID_Empresa=".$entidad." AND Item = '".$Item."' AND Item <> '".G_NINGUNO."' ORDER BY Empresa";
		$resp = $this->db->datos($sql,'MY SQL');
		// print_r($sql);die();
		  $datos=[];
		foreach ($resp as $key => $value) {
		
					$datos[]=['id'=>$value['ID'],'text'=>$value['Empresa'],'host'=>$value['IP_VPN_RUTA'],'usu'=>$value['Usuario_DB'],'base'=>$value['Base_Datos'],'Puerto'=>$value['Puerto'],'Item'=>$value['Item']];				
		 }

	      return $datos;
	}
	function usuarios($entidad,$query)
	{
		$sql = "SELECT  ID,CI_NIC,Nombre_Usuario,Usuario,Clave,Email FROM acceso_usuarios WHERE SUBSTRING(CI_NIC,1,6)  <> 'ACCESO' AND  Nombre_Usuario LIKE '%".$query."%' ";
		if($entidad)
		{
			$sql.="AND ID_Empresa='".$entidad."'";
		}
		 $datos[]=array('id'=>'0','text'=>'TODOS','CI'=>'0','usuario'=>'TODOS','clave'=>'0');
		 // print_r($sql);die();
		 $resp = $this->db->datos($sql,'MY SQL');
		foreach ($resp as $key => $value) {
		
			$datos[]=['id'=>$value['CI_NIC'],'text'=>$value['Nombre_Usuario'],'CI'=>$value['CI_NIC'],'usuario'=>$value['Usuario'],'clave'=>$value['Clave'],$value['Email']];					
		 }

	      return $datos;


	}

	function acceso_empresas($entidad,$empresas,$usuario)
	{
		$sql = "SELECT * FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Item='".$empresas."' AND CI_NIC = '".$usuario."'";
		$resp = $this->db->datos($sql,'MY SQL');
		 $datos=[];
		foreach ($resp as $key => $value) {
				$datos[]=array('id'=>$value['ID'],'Modulo'=>$value['Modulo'],'item'=>$value['Item']);				
		 }
	      return $datos;

	}
	function acceso_empresas_($entidad,$empresas,$usuario)
	{
		$sql = "SELECT * FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Item='".$empresas."' AND CI_NIC = '".$usuario."'";
		 $datos=[];
		 // print_r($sql);
		 $resp = $this->db->datos($sql,'MY SQL');
		 foreach ($resp as $key => $value) {
		 		$datos[]=array('id'=>$value['ID'],'Modulo'=>$value['Modulo'],'item'=>$value['Item']);				
		 }
	      return $datos;

	}
	function datos_usuario($entidad,$usuario)
	{
		$sql = "SELECT CI_NIC,Usuario,Clave,Nivel_1 as 'n1',Nivel_2 as 'n2',Nivel_3 as 'n3',Nivel_4 as 'n4',Nivel_5 as 'n5',Nivel_6 as 'n6',Nivel_7 as 'n7',Supervisor,Cod_Ejec,Email FROM acceso_usuarios WHERE  CI_NIC = '".$usuario."'";
        $resp = $this->db->datos($sql,'MY SQL');
		// // print_r($sql);die();
		//  $datos=array();
		//  if($cid)
		//  {
		//  	$consulta=$cid->query($sql) or die($cid->error);
		//  	while($filas=$consulta->fetch_assoc())
		// 	{
		// 		$datos =$filas;			
		// 	}
		//  }
		 // print_r($datos);die();
	      return $resp;

	}

	function actualizar_correo($correo,$ci_nic){
		$sql = "UPDATE acceso_usuarios set Email = '".$correo ."' WHERE CI_NIC = '".$ci_nic."'";
		$resp = $this->db->String_Sql($sql,'MY SQL');
	}

	function guardar_acceso_empresa($modulos,$entidad,$empresas,$usuario)
	{	
	    // $delet = $this->delete_modulos($entidad,$empresas,$usuario);
	    // if($delet==1)
	    // {
	    $regis = $this->acceso_empresas_($entidad,$empresas,$usuario);
	    $modulo = explode(',',$modulos);
	    $valor = '';
	    $existe = 0;
	    if(count($regis)>0)
	    {
	       foreach ($modulo as $key => $value) {
	    	   foreach ($regis as $key1 => $value1) {
	    	    if($value == $value1['Modulo'])
	    		   {
	    		   	$existe = 1;
	    		   	break;
	    		   }	    		  
	    	   }
	    	    if($existe == 0)
	    	       {
	    	   	    $valor.= '('.$entidad.',"'.$usuario.'","'.$value.'","'.$empresas.'"),';
	    	       }
	    	   $existe =0;	    	   
	       }   	
	    }else
	    {
	    	foreach ($modulo as $key => $value) {
	    	  $valor.= '('.$entidad.',"'.$usuario.'","'.$value.'","'.$empresas.'"),';
	       }
	    }

	 	if($valor != "")
	  	{
	  		$valor = substr($valor, 0,-1);
	  	    $sql = "INSERT INTO acceso_empresas (ID_Empresa,CI_NIC,Modulo,item) VALUES ".$valor;
	  	   return $this->db->String_Sql($sql,'MY SQL');
	    }
	    return 1;
	// }


	}
	function update_acceso_usuario($niveles,$usuario,$clave,$entidad,$CI_NIC,$email)
	{
	   $sql = "UPDATE acceso_usuarios SET TODOS = 1, Nivel_1 =".$niveles['1'].", Nivel_2 =".$niveles['2'].", Nivel_3 =".$niveles['3'].", Nivel_4 =".$niveles['4'].",Nivel_5 =".$niveles['5'].", Nivel_6=".$niveles['6'].", Nivel_7=".$niveles['7'].", Supervisor = ".$niveles['super'].", Usuario = '".$usuario."',Clave = '".$clave."',Email='".$email."' WHERE CI_NIC = '".$CI_NIC."';";
	  return $this->db->String_Sql($sql,'MY SQL');

	}
	function delete_modulos($entidad,$empresas=false,$usuario,$modulo=false)
	{
		$sql = "DELETE FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." ";
		if($empresas)
		{
			$sql.=" AND Item='".$empresas."'";
		}
			$sql.=" AND CI_NIC = '".$usuario."'";
		if($modulo)
		{
			 $sql.=" AND Modulo='".$modulo."'";
		}
		return $this->db->String_Sql($sql,'MY SQL');

	}

	function bloquear_usuario($entidad,$CI_NIC)
	{
	   $sql = "UPDATE acceso_usuarios SET TODOS=0 WHERE ID_Empresa = '".$entidad."' AND CI_NIC = '".$CI_NIC."';";
	   return  $this->db->String_Sql($sql,'MY SQL');
	}

	function nuevo_usuario($parametros)
	{
	   $sql = "INSERT INTO acceso_usuarios (TODOS,Clave,Usuario,CI_NIC,ID_Empresa,Nombre_Usuario) VALUES (1,'".$parametros['cla']."','".$parametros['usu']."','".$parametros['ced']."','".$parametros['ent']."','".$parametros['nom']."')";
	   return $this->db->String_Sql($sql,'MY SQL');
	}

	function crear_como_cliente_SQLSERVER($parametros)
	{
		$registrado = true;
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_Empresa = '".$parametros['ent']."' AND Base_Datos <>'.'";
		 // if($cid)
		 // {
		 // 	$consulta=$cid->query($sql) or die($cid->error);
		 // 	while($filas=$consulta->fetch_assoc())
			// {
			// 	$datos[] =$filas;			
			// }
		 // }
		 $datos = $this->db->datos($sql,'MY SQL');
		 $insertado = false;
		// print_r($datos);die();
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		 	     // print_r($value['IP_VPN_RUTA'].'-'.$value['Usuario_DB'].'-'.$value['Contrasena_DB'].'-'.$value['Base_Datos'].'-'.$value['Puerto']);die();


		 	     $sql = "INSERT INTO Clientes(T,FA,Codigo,Fecha,Cliente,TD,CI_RUC,FactM,Descuento,RISE,Especial)VALUES('N',0,'".$parametros['ced']."','".date('Y-m-d')."','".$parametros['nom']."','C','".$parametros['ced']."',0,0,0,0);";
		 	     $sql.="INSERT INTO Accesos (TODOS,Clave,Usuario,Codigo,Nombre_Completo,Nivel_1,Nivel_2,Nivel_3,Nivel_4,Nivel_5,Nivel_6,Nivel_7,Supervisor,EmailUsuario) VALUES (1,'".$parametros['cla']."','".$parametros['usu']."','".$parametros['ced']."','".$parametros['nom']."','".$parametros['n1']."','".$parametros['n2']."','".$parametros['n3']."','".$parametros['n4']."','".$parametros['n5']."','".$parametros['n6']."','".$parametros['n7']."','".$parametros['super']."','".$parametros['email']."')";
		 	     // print_r($sql);die();
		 	    $stmt = sqlsrv_query($cid2, $sql);
	            if($stmt === false)  
	        	    {  
	        	    	// print_r('fallo');die();
	        		    // echo "Error en consulta PA.\n";
	        		    // print_r($sql);die();
	        		    return -1;
		               die( print_r( sqlsrv_errors(), true));  
	                }else
	                {

	        	    	// print_r('si');die();
	            	    cerrarSQLSERVERFUN($cid2);
	            	    $insertado = true;
	                }     
	        }     
		 }
		 if($insertado == true)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }

	}


	function existe_en_SQLSERVER($parametros)
	{
        $registrado = true;
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_Empresa = '".$parametros['entidad']."' AND Base_Datos <>'.'";
		 // if($cid)
		 // {
		 // 	$consulta=$cid->query($sql) or die($cid->error);
		 // 	while($filas=$consulta->fetch_assoc())
			// {
			// 	$datos[] =$filas;			
			// }
		 // }
		$datos = $this->db->datos($sql,'MY SQL');
		 $insertado = false;
		// print_r($datos);die();
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = $this->db->modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);
		 	     // print_r($cid2);die();

		 	     $sql = "SELECT * FROM Accesos WHERE Codigo = '".$parametros['CI_usuario']."'";
		 	     // print_r($sql);die();
		 	     $stmt = sqlsrv_query($cid2, $sql);
		 	     $result = array();	
		 	     if($stmt===false)
		 	     {
		 	     	// print_r('fallo');die();
		 	     	return -2;
		 	     }else{

		 	     	// print_r('consulto');die();
		 	        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		 	     	   {
		 	     		   $result[] = $row;
		 	     	   }

		 	     	// print_r($result);die();
		 	     	 if(count($result)>0)
		 	     	 {

		 	     	// print_r('existe');die();
		 	     	 	$sql = "UPDATE Accesos SET Nivel_1 =".$parametros['n1'].", Nivel_2 =".$parametros['n2'].", Nivel_3 =".$parametros['n3'].", Nivel_4 =".$parametros['n4'].",Nivel_5 =".$parametros['n5'].", Nivel_6=".$parametros['n6'].", Nivel_7=".$parametros['n7'].", Supervisor = ".$parametros['super'].", Usuario = '".$parametros['usuario']."',Clave = '".$parametros['pass']."',EmailUsuario='".$parametros['email']."' WHERE Codigo = '".$parametros['CI_usuario']."';";

		 	     	 	  $sql = str_replace('false',0, $sql);
		 	     	 	  $sql = str_replace('true',1, $sql);
		 	     	 	  // print_r($sql);die();
		 	     	 	 $stmt2 = sqlsrv_query($cid2, $sql);
		 	     	 	  if( $stmt2 === false)                         
	                          {  
		                        echo "Error en consulta PA.\n";  
		                        return '';
		                        die( print_r( sqlsrv_errors(), true));  
	                          }else
	                          {
	                          	$insertado = true;
	                          }	 	    

		 	     	 }else
		 	     	 {

		 	     	// print_r('no existe');die();
		 	     	 	$parametros_ing = array();
		 	            $parametros_ing['ent']	  = $parametros['entidad'];     	 	 
		 	            $parametros_ing['cla'] = $parametros['pass'];
		 	            $parametros_ing['usu'] = $parametros['usuario'];
		 	            $parametros_ing['ced'] = $parametros['CI_usuario'];
		 	            $parametros_ing['nom'] = $parametros['nombre'];
		 	            $parametros_ing['n1'] = $parametros['n1'];
		 	            $parametros_ing['n2'] = $parametros['n2'];
		 	            $parametros_ing['n3'] = $parametros['n3'];
		 	            $parametros_ing['n4'] = $parametros['n4'];
		 	            $parametros_ing['n5'] = $parametros['n5'];
		 	            $parametros_ing['n6'] = $parametros['n6'];
		 	            $parametros_ing['n7'] = $parametros['n7'];
		 	            $parametros_ing['super'] = $parametros['super'];
		 	            $parametros_ing['email'] = $parametros['email'];
		 	            // print_r($parametros_ing);die();
		 	     	 	 if($this->crear_como_cliente_SQLSERVER($parametros_ing)==1)
		 	     	 	 {
		 	     	 	 	$insertado = true;
		 	     	 	 }
		 	     	 }
		 	     }
	        }     
		 }
		 if($insertado == true)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }


	}

	function usuario_existente($usuario,$clave,$entidad)
	{
	   $sql = "SELECT * FROM acceso_usuarios WHERE Usuario = '".$usuario."' AND Clave = '".$clave."' AND ID_Empresa = '".$entidad."'";
	   $res = $this->db->existe_registro($sql,'MY SQL');
	   if($res!=1)
	   {
	   	return -1;
	   }else{ return $resp; }
	}


	function buscar_ruc($ruc)
	{
	   $sql = "SELECT Item,Empresa as 'emp',L.RUC_CI_NIC as 'ruc',Estado,L.ID_Empresa,Nombre_Entidad as 'Entidad',E.RUC_CI_NIC as 'Ruc_en' FROM lista_empresas L
	          LEFT JOIN entidad E ON  L.ID_Empresa = E.ID_Empresa
	          WHERE L.RUC_CI_NIC = '".$ruc."'";
       // $sql2 = "SELECT Item,Empresa as 'emp',RUC_CI_NIC as 'ruc',Estado FROM lista_empresas WHERE RUC_CI_NIC = '".$ruc."'";
	   $entidad = $this->db->datos($sql,'MY SQL');
		 return $entidad;

	}

	function accesos_modulos($entidad,$usuario)
	{

		$sql="SELECT Item,Modulo FROM acceso_empresas WHERE ID_Empresa = '".$entidad."' AND CI_NIC = '".$usuario."'";
		$datos = $this->db->datos($sql,'MY SQL');
	      return $datos;
	}

	function Empresa_data()
   {   			
	   $sql = "SELECT * FROM Empresas where Item='".$_SESSION['INGRESO']['item']."'";
	   $datos = $this->db->datos($sql);
	   return $datos;
   }
   function usuarios_registrados_entidad($entidad)
   {
   	$sql="SELECT DISTINCT AE.CI_NIC,Nombre_Usuario,Email FROM acceso_empresas AE
   	INNER JOIN acceso_usuarios AU ON AE.CI_NIC = AU.CI_NIC 
   	WHERE AE.ID_Empresa = '".$entidad."'";
   	$datos = $this->db->datos($sql,'MY SQL');
	 return $datos;
   }
	
}
?>