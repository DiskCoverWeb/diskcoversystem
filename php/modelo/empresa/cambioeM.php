<?php 

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

/**
 * 
 */
class cambioeM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();
	}

	function ciudad($IDempresa)
	{
		$sql ="SELECT Ciudad
			  FROM lista_empresas
			  WHERE ID_Empresa = '".$IDempresa."' group by Ciudad";
	    return $this->db->datos($sql,'MYSQL');

	}

	function entidad($query=false,$IDempresa,$ciudad=false)
	{

		if($ciudad)
		{
			$sql = "SELECT *
				  FROM lista_empresas
				  WHERE ID_Empresa = '".$IDempresa."' AND Ciudad='".$ciudad."' ";

		}else{
			
			$sql = "SELECT *
				  FROM lista_empresas
				  WHERE ID_Empresa = '".$IDempresa."' ";
		}
		if($query)
		{
			$sql.=" and Empresa like '".$query."%' ";
		}

		$sql.='ORDER BY Empresa;';		
		return $this->db->datos($sql,'MYSQL');
	}

	function datos_empresa($ID)
	{		
		$sql = "SELECT *
		  FROM lista_empresas
		  WHERE ID = '".$ID."';";
		return $this->db->datos($sql,'MYSQL');
	}
	function estado()
	{
		$sql = 'SELECT Estado,Descripcion FROM lista_estados';
		return $this->db->datos($sql,'MYSQL');
	}

	function editar_datos_empresa($parametros)
	{

		$sql = "UPDATE lista_empresas set 
		Estado='".$parametros['Estado']."',
		Mensaje='".$parametros['Mensaje']."',
		Fecha_CE='".$parametros['Fecha']."' ,
		IP_VPN_RUTA='".$parametros['Servidor']."',
		Base_Datos='".$parametros['Base']."' ,
		Usuario_DB='".$parametros['Usuario']."',
		contrasena_DB='".$parametros['Clave']."' ,
		Tipo_Base='".$parametros['Motor']."',
	    Puerto='".$parametros['Puerto']."',
	    Fecha='".$parametros['FechaR']."',
	    Fecha_VPN='".$parametros['FechaV']."',
	    Fecha_DB='".$parametros['FechaDB']."',
	    Fecha_P12='".$parametros['FechaP12']."' 
	    WHERE ID='".$parametros['empresas']."' ";

	    // print_r($sql);die();
	    $resp = $this->db->String_Sql($sql,'MYSQL');

	    return array('res'=>$resp,'empresa'=>$parametros['empresas']);
	}
	function mensaje_masivo($parametros)
	{		
		// print_r($parametros);die();
		$sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."'; ";
		return $this->db->String_Sql($sql,'MYSQL');
	}
	function mensaje_grupo($parametros)
	{		
		
		if($parametros['ciudad']=='')
		{
			 $sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."' WHERE ID_Empresa='".$parametros['entidad']."' ";
		}
		else
		{
			 $sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."' WHERE ID_Empresa='".$parametros['entidad']."'  AND Ciudad='".$parametros['ciudad']."'";
		}
		return $this->db->String_Sql($sql,'MYSQL');
	}
	function mensaje_indi($parametros)
	{
		$sql = "UPDATE lista_empresas set Mensaje='".$parametros['Mensaje']."' WHERE ID_Empresa='".$parametros['entidad']."' AND ID='".$parametros['empresas']."'";
		return $this->db->String_Sql($sql,'MYSQL');
	}
	function guardar_masivo($parametros)
	{
		$sql = "UPDATE lista_empresas set Fecha='".$parametros['FechaR']."' , Fecha_VPN='".$parametros['FechaV']."' , Fecha_CE='".$parametros['Fecha']."'  
		WHERE ID_Empresa='".$parametros['entidad']."'";
		return $this->db->String_Sql($sql,'MYSQL');
	}



}

?>