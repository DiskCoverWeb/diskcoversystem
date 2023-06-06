<?php
$studentId  = (isset($_GET['id']))?$_GET['id']:"";
$file  = (isset($_GET['file']))?$_GET['file']:die('Parametro file obligatorio');

$archivo = $file.'.key';
$token = file_get_contents($archivo);
if ($token == false) {
  die('Error al obtener token.');
}

$data = ConsultarDataEstudianteIdukay($studentId, $token);
function ConsultarDataEstudianteIdukay($studentId, $token)
{
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://staging-api.idukay.net/api/students?select=_id+user+relatives+relational_data&global_search=' . $studentId.'&populate=true',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 3,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_SSL_VERIFYPEER=> false,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'accept: */*',
      'Type: API',
      'authorization: Bearer '.$token,
    ),
  ));

  $response = curl_exec($curl);
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);

  if ($response === false) {
    die(json_encode(['response'=> false, 'httpCode'=>$httpCode]));
  } else {
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        return (count($json["response"])>0)?$json["response"][0]:false;
    } else {
      die(json_encode(['response'=> false, 'httpCode'=>$httpCode]));
    }
  }
}

function pintarNodo($nodo,$keyS, $echo, $bucle=0)
{
	// if($bucle>15){
	// 	die('bluceeeee');
	// }
	//echo "<pre>llego";print_r($nodo);echo "</pre>";
	//echo "<pre>keyS";print_r($keyS);echo "</pre>";
	if (is_array($nodo)){
		//echo "<pre>SI es array</pre>";
		$echo .="<ul id='nodo-$keyS'>";
			foreach ($nodo as $key2 => $nodo2){
				//echo "<pre>HACEMOS LLAMAD pintarNodo $key2";print_r($nodo2);echo "</pre>";
				$echo .= pintarNodo($nodo2,$key2, "",$bucle++);
			}
		$echo .="</ul>";
	}else{
	//echo "<pre>no es array pintamos ";print_r($nodo);echo "</pre>";
		$echo ="<li name='$keyS'>$nodo</li>";
	}
	return $echo;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>DiskCover System login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
		<style type="text/css">
			li{
				list-style: none;
			}
		</style>
	</head>
	<body>
		<?php if ($data){ ?>
			<?php foreach ($data as $keyP => $nodoP): ?>
				<?php echo 	pintarNodo($nodoP,$keyP, "") ?>
			<?php endforeach ?>
		<?php }else{echo "false";} ?>

	</body>
</html>