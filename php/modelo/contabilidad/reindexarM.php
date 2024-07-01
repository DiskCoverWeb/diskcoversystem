<?php 
include(dirname(__DIR__,2).'/funciones/funciones.php');


class reindexarM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

}
?>