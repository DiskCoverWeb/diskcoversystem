<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A. * 
 * 
 * modificcado por javier farinango
 *  * 
 */
// require_once("../db/db.php");
require_once(dirname(__DIR__,1)."/db/db1.php");

class usuario_model{
	private $dbs;
	private $ID_Entidad="";
	
	// nuevas variables javier farinango
	private $db1;
 
    public function __construct(){
		//parent::__construct();
       // $this->db=Conectar::conexion();
        $this->db1 = new db();
      //  $this->contacto=array();
    }

    function SeteoCta()
    {
    	  $sql= "SELECT *
            FROM Ctas_Proceso 
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            ORDER BY T_No ";
        return  $this->db1->datos($sql);
    }

	
	// Metdo devuelve true o false para ingresar a la sesccion de pagina de administracion
	function Ingresar($usuario,$pass,$entidad){
		$query = "SELECT * 
		          FROM acceso_usuarios AU
		          INNER JOIN acceso_empresas AE ON AU.CI_NIC = AE.CI_NIC 
		          WHERE AU.Usuario = '".$usuario."' 
		          and AE.ID_Empresa = '".$entidad."' 
		          and AU.Clave = '".$pass."'
		          LIMIT 1";
		$datos = $this->db1->datos($query,$tipo='MY SQL');
		if(count($datos)>0)
		{
			// variables de entidad y usuario logueado en mysql
			$_SESSION['INGRESO']['CodigoU'] = $datos[0]['CI_NIC'] ;
			$_SESSION['INGRESO']['item'] =  $datos[0]['Item'];
			$_SESSION['INGRESO']['Nombre_Completo']=$datos[0]['Nombre_Usuario'];
			$_SESSION['autentificado'] = 'VERDADERO';
			$_SESSION['INGRESO']['IDEntidad'] = $datos[0]['ID_Empresa'];
			$_SESSION['INGRESO']['Entidad_No'] = $datos[0]['ID_Empresa'];
			$_SESSION['INGRESO']['Nombre'] = $datos[0]['Nombre_Usuario'];
			$_SESSION['INGRESO']['Id'] = $datos[0]['CI_NIC'];
			$_SESSION['INGRESO']['Clave'] = $datos[0]['Clave'];
			$_SESSION['INGRESO']['Mail'] = $datos[0]['Usuario'];
	// print_r($datos);die();
			return 'panel.php';
		}else
		{
			return -1;
		}
	}


	function ValidarEntidad1($entidad){		
		$num = strlen($entidad);
		if($num==10 || $num == 13 && is_numeric($entidad))
		{
			$query = "SELECT ID_Empresa,Nombre_Entidad
					  FROM entidad
					  WHERE RUC_CI_NIC = '".$entidad."';";

		// print_r($query);die();
			$datos = $this->db1->datos($query,$tipo='MY SQL');
			if(count($datos)>0)
			{
				return array('respuesta'=>1,'entidad'=>$datos[0]['ID_Empresa'],'Nombre'=>$datos[0]['Nombre_Entidad']);
			}else
			{
				//retorna -1 cuando no se encuentra la empresa 			
				return array('respuesta'=>-1,'entidad'=>'','Nombre'=>'');
			}
		}else
		{
			//retorna -2 cuando no tiene el formato o la extencion correcta
			return array('respuesta'=>-2,'entidad'=>'','Nombre'=>'');

		// print_r($num);die();
		}
	}


	/*
	 * Validamos la entrada de correo
	 * electronico
	 * @param [String mail]
	 */
	function ValidarUser1($usuario,$entidad){

		$query = "SELECT * 
		          FROM acceso_usuarios AU
		          INNER JOIN acceso_empresas AE ON AU.CI_NIC = AE.CI_NIC 
		          WHERE AU.Usuario = '".$usuario."' and AE.ID_Empresa = '".$entidad."' LIMIT 1";
		 // print_r($query);die();
	    $datos = $this->db1->datos($query,$tipo='MY SQL');
	    if(count($datos)>0)
	    {
	    	if($datos[0]['TODOS']==1)
	    	{
	    		return array('respuesta'=>1);
	    	}else
	    	{
	    		return array('respuesta'=>-2);
	    	}
	    }else
	    {
	    	return array('respuesta'=>-1);

	    }
	    // print_r($datos);die();
	}


		
	/*
	 * Returna el IP de usuario
	 * @return [string] [devuel la io del usuario]
	 */
	private function IPuser() {
		$returnar ="";
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		 $returnar=$_SERVER['HTTP_X_FORWARDED_FOR'];}
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		 $returnar=$_SERVER['HTTP_CLIENT_IP'];}
	if(!empty($_SERVER['REMOTE_ADDR'])){
		 $returnar=$_SERVER['REMOTE_ADDR'];}
	return $returnar;
	}

	
		

	function getEmpresas($id_entidad){
	    $empresa_2 = array();	    
		$empresa= array();
		$items = '';
		$sql2 = "SELECT  DISTINCT Item 
		         FROM acceso_empresas 
		         WHERE ID_Empresa = '".$id_entidad."' 
		         AND CI_NIC = '".$_SESSION['INGRESO']['Id']."';";
		  $datos = $this->db1->datos($sql2,$tipo='MY SQL');
        foreach ($datos as $key => $value) {
        	$items.='"'.$value['Item'].'",';
        }
        $items = substr($items,0,-1);
        
		if($items!="")
		{
		$sql = "SELECT * 
				FROM `lista_empresas`  where							 
				 `ID_Empresa`='".$id_entidad."' 
				 AND Item in (".$items.");";
				// print_r($sql);die();
		 $empresa = $this->db1->datos($sql,$tipo='MY SQL');

		// print_r($sql);die();
	  }
	  // print_r($empresa);die();
        return $empresa;
	}


	//devuelve empresa seleccionada por id 
	function getEmpresasId($id_empresa){

		    $sql = "SELECT * 
					FROM lista_empresas L
					INNER JOIN entidad E ON L.ID_Empresa = E.ID_Empresa
					WHERE IP_VPN_RUTA<>'.' 
					AND Base_Datos<>'.' 
					AND Usuario_DB<>'.' 
					AND Contrasena_DB<>'.' 
					AND Tipo_Base<>'.' 
					AND Puerto<>'0' 
					AND L.ID=".$id_empresa.";";
					// print_r($sql);die();
		$datos = $this->db1->datos($sql,$tipo='MY SQL');
		return $datos;
	}

	function getEmpresasId_sin_sqlserver($id_empresa){
		    $sql = "SELECT * 
		    FROM lista_empresas E
		    LEFT JOIN entidad L ON E.ID_Empresa = L.ID_Empresa 
		    WHERE  E.ID=".$id_empresa.";";
			// print_r($sql);die();
			$datos = $this->db1->datos($sql,$tipo='MY SQL');
		    return $datos;
	}


	//consultaperido nuevo  modificado 
	function get_periodo($Opcem=false)
	{
		if($Opcem)
		{
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."'  
			AND periodo='.'";
			 If ($Opcem=='1')
			   {
			   $sql= $sql." AND Detalle = 'Balance Mes' ";
			   }else
				{
					$sql= $sql." AND Detalle = 'Balance' ";
				}
		}else
		{			
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."' 
			AND periodo='.' ";
		}

		if( $_SESSION['INGRESO']['Tipo_Base']=='MY SQL')
		{
			return $datos = $this->db1->datos($sql,$tipo='MY SQL');
		}else
		{
			return $datos = $this->db1->datos($sql);

		}
	}
	function datos_empresa($item,$nombre)
	{
		$sql="SELECT * 
			FROM Empresas 
			where Item='".$_SESSION['INGRESO']['item']."' ";
		if( $_SESSION['INGRESO']['Tipo_Base']=='MY SQL')
		{			
			return $datos = $this->db1->datos($sql,$tipo='MY SQL');
		}else
		{			
		    $datos = $this->db1->datos($sql);
		    $sql2 = "select * from Acceso_Sucursales where Sucursal<>'.' ";
		    //las fechas estaban quemadas desde un inicio de sql3

		   //  $sql3="SELECT ROUND((Porc/100), 2) AS porc FROM Tabla_Por_ICE_IVA WHERE IVA <> '0' 
				 // AND Fecha_Inicio <= '20200408' AND Fecha_Final >= '20200408'
				 // ORDER BY Porc ";
		   $sql3 = "SELECT ROUND((Porc/100), 2) AS porc
                FROM Tabla_Por_ICE_IVA 
                WHERE IVA <> 0 
                AND Fecha_Inicio <= '".date('Y-m-d')."' 
                AND Fecha_Final >= '".date('Y-m-d')."'
                ORDER BY Porc DESC ";

		    $suc = $this->db1->datos($sql2);
		    $porc = $this->db1->datos($sql3);

		    // print_r($porc);die();
		    $datos[0]['porc'] = 0;
		    $datos[0]['Sucursal'] = 0;
		    if(count($suc)>0)
		    {
		    	$datos[0]['Sucursal'] = 1;
		    }
		    if(count($porc))
		    {
		       $datos[0]['porc'] = $porc[0]['porc'];
		    }

			return $datos;
		}

	}

	function getAccesoEmpresasSQL()
	{
		// print_r($_SESSION['INGRESO']);die();
		$permiso=array();
		$_SESSION['INGRESO']['modulo']=array();
		$sql="SELECT * 
		FROM Acceso_Empresa 
		WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' ";

			// print_r($sql);die();
		if($_SESSION['INGRESO']['Tipo_Base']!='MY SQL')
		{
			$datos = $this->db1->datos($sql);

		}else
		{
			$datos = $this->db1->datos($sql,$tipo='MY SQL');

		}

			// print_r($datos);die();
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				// print_r($value);die();
			  $_SESSION['INGRESO']['accesoe']='1';
			  $_SESSION['INGRESO']['modulo'][$key]=$value['Modulo'];
		    }

		}else
		{
			$_SESSION['INGRESO']['accesoe']='TODOS';
			$_SESSION['INGRESO']['modulo'][0]='TODOS';
		}
		

	}

	//----------------- funcion que no se an tocado 
		//detalle ddl usuario en sql server


	function getUsuarioSQL()
	{
		$usuario=array();
		$sql="SELECT * FROM Accesos
				WHERE        (Usuario = '".$_SESSION['INGRESO']['Mail']."') 
				AND (Clave = '".$_SESSION['INGRESO']['Clave']."') ";
		
		// echo $sql;
		$stmt = false;
		if($this->dbs!='')
		{
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		if( $stmt === false)  
		{  
			 //echo "Error en consulta PA.\n";  
			 echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'Error en consulta PA.',
								footer: ''
							})*/
							alert('Error en consulta');
					</script>";
			 if($_SESSION['INGRESO']['ERROR']==1)
			 {
				die( print_r( sqlsrv_errors(), true)); 
			 }
			 die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				
				$usuario[$i]['CodigoU']=$obj->Codigo;		
				$usuario[$i]['Nombre_Completo']=$obj->Nombre_Completo;	
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			if($i==0)
			{
				$usuario[$i]['CodigoU']='';		
				$usuario[$i]['Nombre_Completo']='';	
			}
			sqlsrv_close( $this->dbs );
		}
        return $usuario;
	}
	//consultar datos usuarios mysql
	function getUsuarioMYSQL()
	{
		$usuario=array();
		$sql="SELECT    * FROM acceso_usuarios
				WHERE        (Usuario = '".$_SESSION['INGRESO']['Mail']."') 
				AND (Clave = '".$_SESSION['INGRESO']['Clave']."')";
		//echo $sql;
		$consulta=$this->db->query($sql);
		if($consulta)
		{
		while($filas=$consulta->fetch_assoc()){
            $usuario[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
      }
        return $usuario;
	}

	function getAccesoEmpresasSQL1()
	{
		// $permiso=array();
		// $_SESSION['INGRESO']['modulo']=array();
		// $sql="SELECT    * FROM Acceso_Empresa 
		// 		WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' ";
		// $stmt = false;
		// if($this->dbs!='')
		// {
		// 	$stmt = sqlsrv_query( $this->dbs, $sql);
		// }
		if( $stmt === false)  
		{  
			 // //echo "Error en consulta PA.\n";  
			 // echo "<script>
				// 			/*Swal.fire({
				// 				type: 'error',
				// 				title: 'Fallo',
				// 				text: 'Error en consulta PA.',
				// 				footer: ''
				// 			})*/
				// 			alert('Error en consulta');
				// 	</script>";
			 // if($_SESSION['INGRESO']['ERROR']==1)
			 // {
				// die( print_r( sqlsrv_errors(), true)); 
			 // }
			 // die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				//echo "entro 1";
				$permiso[$i]['Modulo']=$obj->Modulo;	
				//echo " mmm ".$permiso[$i]['Modulo'].' ind= '.$i.'<br>';
				$permiso[$i]['Item']=$obj->Item;					
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			//no existe
			if($i==0)
			{
				//echo "entro 2";
				$permiso[$i]['Modulo']='TODOS';
				$permiso[$i]['Item']='TODOS';
				$_SESSION['INGRESO']['accesoe']='TODOS';
				$_SESSION['INGRESO']['modulo'][$i]='TODOS';
			}
			else
			{
				//hacemos ciclo para buscar si puede acceder a la empresa y que modulos
				$j=0;
				for($i=0;$i<count($permiso);$i++)
				{
					if($permiso[$i]['Item']==$_SESSION['INGRESO']['item'])
					{
						//echo $permiso[$i]['Item']." ".$_SESSION['INGRESO']['item']."<br>";
						$_SESSION['INGRESO']['accesoe']='1';
						$_SESSION['INGRESO']['modulo'][$j]=$permiso[$i]['Modulo'];
						//echo ' per '.$permiso[$i]['Modulo'].' '.$_SESSION['INGRESO']['modulo'][$j].' ind= '.$i.'<br>';
						$j++;
					}
				}
			}
			sqlsrv_close( $this->dbs );
		}
		//die();
        return $permiso;
	}
	//consultar datos usuarios mysql
	function getAccesoEmpresasMYSQL()
	{
		$usuario=array();
		$sql="SELECT * 
		FROM acceso_empresas 
		WHERE CI_NIC='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."'
		 ";
		// echo $sql;die();
		$consulta=$this->db->query($sql);
		if($consulta)
		{
          $i=0;
			while($filas=$consulta->fetch_assoc()) 
			{
				//echo "entro 1";
				$usuario[$i]['Modulo']=$filas['Modulo'];	
				//echo " mmm ".$permiso[$i]['Modulo'].' ind= '.$i.'<br>';
				$usuario[$i]['Item']=$filas['Item'];					
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			//no existe
			if($i==0)
			{
				//echo "entro 2";
				$usuario[$i]['Modulo']='TODOS';
				$usuario[$i]['Item']='TODOS';
				$_SESSION['INGRESO']['accesoe']='TODOS';
				$_SESSION['INGRESO']['modulo'][$i]='TODOS';
			}
			else
			{
				//hacemos ciclo para buscar si puede acceder a la empresa y que modulos
				$j=0;
				for($i=0;$i<count($usuario);$i++)
				{
					if($usuario[$i]['Item']==$_SESSION['INGRESO']['item'])
					{
						//echo $permiso[$i]['Item']." ".$_SESSION['INGRESO']['item']."<br>";
						$_SESSION['INGRESO']['accesoe']='1';
						$_SESSION['INGRESO']['modulo'][$j]=$usuario[$i]['Modulo'];
						//echo ' per '.$permiso[$i]['Modulo'].' '.$_SESSION['INGRESO']['modulo'][$j].' ind= '.$i.'<br>';
						$j++;
					}
				}
			}
        } 

        return $usuario;
	}
	//consultar modulo
	function getModuloSQL()
	{
		$permiso=array();
		$sql="SELECT * FROM Modulos
		ORDER BY Aplicacion ";
		
		//echo $sql;
		$stmt = false;
		if($this->dbs!='')
		{
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		if( $stmt === false)  
		{  
			 //echo "Error en consulta PA.\n";  
			 echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'Error en consulta PA.',
								footer: ''
							})*/
							alert('Error en consulta');
					</script>";
			 if($_SESSION['INGRESO']['ERROR']==1)
			 {
				die( print_r( sqlsrv_errors(), true)); 
			 }
			 die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				//echo "entro 1";
				$permiso[$i]['Modulo']=$obj->Modulo;	
				$permiso[$i]['Aplicacion']=$obj->Aplicacion;					
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			sqlsrv_close( $this->dbs );
		}
		//die();
        return $permiso;
	}
	//consultar datos modulos mysql
	function getModuloMYSQL()
	{
		$usuario=array();
		$sql="SELECT    * FROM Acceso_Empresa 
				WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."'
		 ";
		//echo $sql;
		$consulta=$this->db->query($sql);
		while($filas=$consulta->fetch_assoc()){
            $usuario[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $usuario;
	}
	function cerrarSQLSERVER(){
		sqlsrv_close( $this->dbs );
	}
	///fin de unciones que no se ana tocado

	function modulos_registrados()
	{
		$usuario=array();
		$sql="SELECT A.Modulo as 'modulo',M.Aplicacion as 'apli',M.link as 'link',M.icono as 'icono' 
		FROM acceso_empresas A 
		JOIN modulos M on A.Modulo = M.modulo 
		WHERE CI_NIC='".$_SESSION['INGRESO']['Id']."' 
		AND Item='".$_SESSION['INGRESO']['item']."' 
		AND ID_Empresa='".$_SESSION['INGRESO']['IDEntidad']."' ";
		//echo $sql;
		$datos = $this->db1->datos($sql,$tipo='MY SQL');
		return $datos;
	}

	function modulos_todos()
	{
		$usuario=array();
		 $this->db=Conectar::conexion('MYSQL');
		$sql="select Modulo,Aplicacion,link,icono from modulos where Modulo !='VS' ";
		// echo $sql;
		$consulta=$this->db->query($sql);
		while($filas=$consulta->fetch_assoc()){
            $usuario[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $usuario;

	}

	function IngClave($parametros)
	{

    $ClaveGeneral = '';
	  $IngClaves_Caption  = '';
		$sql = "SELECT * 
		FROM Accesos
		WHERE Usuario = '".$parametros['tipo']."' ";
		
		$datos = $this->db1->datos($sql);
		if(count($datos)>0)
		{
			 $ClaveGeneral = $datos[0]["Clave"];
	   	 $IngClaves_Caption = $datos[0]["Nombre_Completo"];
		}
	   return array('clave'=>$ClaveGeneral,'nombre'=>$IngClaves_Caption);
	}

	function IngClave_MYSQL($parametros)
	{

    $ClaveGeneral = '';
	  $IngClaves_Caption  = '';
		$sql = "SELECT * 
		FROM acceso_usuarios
		WHERE Usuario = '".$parametros['tipo']."' ";
		
		$datos = $this->db1->datos($sql,'MY SQL');
		if(count($datos)>0)
		{
			 $ClaveGeneral = $datos[0]["Clave"];
	   	 $IngClaves_Caption = $datos[0]["Nombre_Usuario"];
		}
	   return array('clave'=>$ClaveGeneral,'nombre'=>$IngClaves_Caption);
	}

}
?>