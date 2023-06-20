<?php

require_once(dirname(__DIR__,2)."/php/funciones/funciones.php");

if(!isset($_GET['id'])){
	die('Parametro obligatorio');
}else if(strlen($_GET['id']) != 26){
	die('Parametro con formato incorrecto');
}

$id_ruc_item = $_GET['id'];
$studentId  = substr($id_ruc_item, 0, 10);
$ruc = substr($id_ruc_item, 10, 13);
$item = substr($id_ruc_item, -3);

$token = getTokenEmpresa($ruc, $item);
if(count($token)==0){
	die('Institución no asignada');
}else{
	$token = $token[0]['Token'];
}

$data = ConsultarDataEstudianteIdukay($studentId, $token);
echo json_encode(formatearDatos($data));
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
      $respuesta = array("relational_data"=>[], 'user'=>[], 'relatives'=>[], 'years'=>[]);
			if(count($json["response"])>0){

				$unix_timestamp = @$json["response"][0]["user"]["birthday"];
				$date = date("Y-m-d", $unix_timestamp);

				if(isset($json["response"][0]["relational_data"]['years'])){
					$relational_data["relational_data"] = @$json["response"][0]["relational_data"];
					$relational_data["relational_data"] = end($relational_data["relational_data"]['years']);
					$respuesta["relational_data"]['years'] = @$relational_data["relational_data"];
				}
				if(isset($json["response"][0]['user'])){
					$respuesta['user']['_id']= @$json["response"][0]['user']['_id'];
					$respuesta['user']['id_card']= @$json["response"][0]['user']['id_card'];
					$respuesta['user']['birthday']= $date;
					$respuesta['user']['gender']= @$json["response"][0]['user']['gender'];
					$respuesta['user']['name']= @$json["response"][0]['user']['name'];
					$respuesta['user']['second_name']= @$json["response"][0]['user']['second_name'];
					$respuesta['user']['surname']= @$json["response"][0]['user']['surname'];
					$respuesta['user']['second_surname']= @$json["response"][0]['user']['second_surname'];
				}

				$respuesta["relatives"]["parent"]= @$json["response"][0]["relatives"][0]["parent"];
				if(isset($json["response"][0]["years"])){
					$respuesta["years"]= end($json["response"][0]["years"]);
				}
				ksort($respuesta);
				return $respuesta;
			}else{
				die(json_encode(['response'=> 'false', 'httpCode'=>$httpCode]));
			}
    } else {
      die(json_encode(['response'=> 'false', 'httpCode'=>$httpCode]));
    }
  }
}

function formatearDatos($datos)
{
	foreach ($datos as $key => &$valor) {
        if (is_array($valor)) {
        	if(count($valor)>0){
            	$valor = formatearDatos($valor);
        	}else{
        		$valor = ".";
        	}
        } else {
            if (is_string($valor)) {
                $valor = ($valor=="")?".":$valor;
            } else {
                $valor = var_export($valor, true);
            }
        }
    }
    return $datos;
}



// function formatearDatos($datos)
// {
// 	$new_array = [];
// 	foreach ($datos as $key => $nodo) {
// 		if (is_array($nodo)){
// 			$new_array = formatearDatos($nodo);
// 		}else{
// 			$new_array[$key] = var_export($nodo, true);
// 		}
// 	}
// 	return $new_array;
// }

function getTokenEmpresa($ruc, $item)
{
	$conn = new db();
	$sql = "SELECT Token
					FROM lista_empresas
					WHERE RUC_CI_NIC=$ruc
					AND Item=$item
					AND LENGTH(Token)>1";
	return $conn->datos($sql,'MYSQL');
}
?>