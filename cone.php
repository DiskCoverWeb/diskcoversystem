<?php
$serverName = "localhost, 11433";
$connectionOptions = array(
    "Database" => "DiskCover_Prismanet",
    "Uid" => "sa",
    "PWD" => "disk2017Cover"
);
//Establishes the connection
$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn)
{
    echo "Connected!";
}
else
{
	echo "error";
}

//phpinfo();
/*
$ip = $server;

			$output = shell_exec("ping $ip");
			 
			if (strpos($output, "recibidos = 0")) {
				echo 'No hay conexión';
			} else {
				echo 'Conectado';
				die();
			}

*/

?>
