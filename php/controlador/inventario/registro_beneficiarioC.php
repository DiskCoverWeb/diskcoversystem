<?php

/** 
 * AUTOR DE RUTINA	: Dallyana Vanegas
 * FECHA CREACION	: 16/02/2024
 * FECHA MODIFICACION : 21/03/2024
 * DESCIPCION : Clase controlador para Agencia
 */

include (dirname(__DIR__, 2) . '/modelo/inventario/registro_beneficiarioM.php');

$controlador = new registro_beneficiarioC();

if (isset ($_GET['LlenarSelect'])) {
    $valores = $_POST['valores'];
    echo json_encode($controlador->LlenarSelect($valores));
}

if (isset ($_GET['ObtenerColor'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->ObtenerColor($valor));
}

if (isset ($_GET['actualizarSelectDonacion'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->actualizarSelectDonacion($valor));
}

if (isset ($_GET['LlenarCalendario'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->LlenarCalendario($valor));
}

if (isset ($_GET['descargarArchivo'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->descargarArchivo($valor));
}

if (isset ($_GET['LlenarSelectDiaEntrega'])) {
    echo json_encode($controlador->LlenarSelectDiaEntrega());
}

if (isset ($_GET['LlenarSelectRucCliente'])) {
    $query = '';
    if (isset ($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->LlenarSelectRucCliente($query));
}

if (isset ($_GET['LlenarTipoDonacion'])) {
    $query = '';
    if (isset ($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->LlenarTipoDonacion($query));
}

if (isset ($_GET['LlenarSelects_Val'])) {
    $query = '';
    $valor = isset ($_GET['valor']) ? $_GET['valor'] : 0;
    if (isset ($_GET['query'])) {
        $query = $_GET['query'];
    }
    $valor2 = isset ($_GET['valor2']) ? $_GET['valor2'] : false;
    echo json_encode($controlador->LlenarSelects_Val($query, $valor, $valor2));
}

if (isset ($_GET['llenarCamposInfo'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposInfo($valor));
}
if (isset ($_GET['llenarCamposInfoAdd'])) {
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposInfoAdd($valor));
}

if (isset ($_GET['guardarAsignacion'])) {

    $params = array(
        'Cliente' => $_POST['Cliente'],
        'CI_RUC' => $_POST['CI_RUC'],
        'Codigo' => $_POST['Codigo'],
        'Actividad' => $_POST['Actividad'],
        'Calificacion' => $_POST['Calificacion'],
        'CodigoA' => $_POST['CodigoA'],
        'Representante' => $_POST['Representante'],
        'CI_RUC_R' => $_POST['CI_RUC_R'],
        'Telefono_R' => $_POST['Telefono_R'],
        'Contacto' => $_POST['Contacto'],
        'Profesion' => $_POST['Profesion'],
        'Dia_Ent' => $_POST['Dia_Ent'],
        'Hora_Ent' => $_POST['Hora_Ent'],
        'Direccion' => $_POST['Direccion'],
        'Email' => $_POST['Email'],
        'Email2' => $_POST['Email2'],
        'Lugar_Trabajo' => $_POST['Lugar_Trabajo'],
        'Telefono' => $_POST['Telefono'],
        'TelefonoT' => $_POST['TelefonoT'],
        'CodigoA2' => $_POST['CodigoA2'],
        'Dia_Ent2' => $_POST['Dia_Ent2'],
        'Hora_Registro' => $_POST['Hora_Registro'],
        'Envio_No' => $_POST['Envio_No'],
        'No_Soc' => $_POST['No_Soc'],
        //'Area' => $_POST['Area'],
        'Acreditacion' => $_POST['Acreditacion'],
        'Tipo_Dato' => $_POST['Tipo_Dato'],
        'Cod_Fam' => $_POST['Cod_Fam'],
        'Observaciones' => $_POST['Observaciones']
    );

    if (isset ($_FILES['Evidencias']) && $_FILES['Evidencias']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['Evidencias'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/EVIDENCIA_" . $_SESSION['INGRESO']['Entidad'] .
            "/EVIDENCIA_" . $_SESSION['INGRESO']['item'] . "/";
        $carpetaDestino = str_replace(' ', '_', $carpetaDestino);

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreArchivoOriginal = pathinfo($archivo['name'], PATHINFO_FILENAME);
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivoDestino = $carpetaDestino . $nombreArchivoOriginal . '.' . $extension;

        $contador = 1;
        while (file_exists($nombreArchivoDestino)) {
            $nombreArchivoDestino = $carpetaDestino . $nombreArchivoOriginal . '_' . $contador . '.' . $extension;
            $contador++;
        }

        if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
            $params['NombreArchivo'] = $nombreArchivoOriginal;
            echo json_encode($controlador->guardarAsignacion($params));
        } else {
            echo json_encode(["res" => '0', "mensaje" => "No se ha cargado ningún archivo", "datos" => $parametros]);
        }
    }

}

class registro_beneficiarioC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new registro_beneficiarioM();
    }

    function LlenarCalendario($valor)
    {
        $datos = $this->modelo->LlenarCalendario($valor);
        if (empty ($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function llenarCamposInfo($valor)
    {
        $datos = $this->modelo->llenarCamposInfo($valor);
        if (empty ($datos)) {
            $datos = 0;
        }
        return $datos;

    }
    function llenarCamposInfoAdd($valor)
    {
        $datos = $this->modelo->llenarCamposInfoAdd($valor);
        if (empty ($datos)) {
            $datos = 0;
        }
        return $datos;

    }

    function LlenarSelectDiaEntrega()
    {
        $datos = $this->modelo->LlenarSelectDiaEntrega();
        if (empty ($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function ObtenerColor($valor)
    {
        $datos = $this->modelo->ObtenerColor($valor);
        if (empty ($datos)) {
            $datos = 0;
        }
        return $datos;
    }

    function descargarArchivo($valor)
    {
        $base = dirname(__DIR__, 3);
        $directorio = "/TEMP/EVIDENCIA_" . $_SESSION['INGRESO']['Entidad'] .
            "/EVIDENCIA_" . $_SESSION['INGRESO']['item'] . "/";
        $directorio = str_replace(' ', '_', $directorio);
        $carpetaDestino = $base . $directorio;

        $carpetaDestino = str_replace(' ', '_', $carpetaDestino);

        $archivos = scandir($carpetaDestino);
        foreach ($archivos as $archivo) {
            $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
            if ($nombreArchivo === $valor) {
                return ["response" => 1, "Dir" => $directorio, "Nombre" => $archivo];
            }
        }
        return ["response" => 0];
    }

    function obtenerCamposComunes($valor)
    {
        return [
            'id' => $valor['Codigo'],
            'Cliente' => $valor['Cliente'],
            'CI_RUC' => $valor['CI_RUC'],
        ];
    }

    function LlenarSelectRucCliente($query): array
    {
        try {
            $datos = $this->modelo->LlenarSelectRucCliente($query);
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }
            $clientes = [];
            $rucs = [];

            foreach ($datos as $valor) {
                $clienteFields = $this->obtenerCamposComunes($valor);
                $clienteFields['text'] = $valor['Cliente'];
                $clientes[] = $clienteFields;

                $rucFields = $this->obtenerCamposComunes($valor);
                $rucFields['text'] = $valor['CI_RUC'];
                $rucs[] = $rucFields;
            }

            return ['clientes' => $clientes, 'rucs' => $rucs];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function LlenarTipoDonacion($query): array
    {
        try {
            $datos = $this->modelo->LlenarTipoDonacion($query);
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }
            foreach ($datos as $valor) {
                $tipoDonacion[] = [
                    'id' => $valor['Codigo'],
                    'text' => $valor['Concepto'],
                ];
            }
            return ['respuesta' => $tipoDonacion];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function LlenarSelects_Val($query, $valor, $valor2): array
    {
        try {
            $datos = $this->modelo->LlenarSelects_Val($query, $valor, $valor2);

            if (empty ($datos)) {
                throw new Exception('No se encontraron datos');
            }

            $respuesta = [];
            foreach ($datos as $dato) { {
                    if ($valor2) {
                        $id = substr($dato['Codigo'], -3);
                        $respuesta[] = [
                            'id' => $id,
                            'text' => $dato['Concepto'],
                        ];
                    } else {
                        $respuesta[] = [
                            'id' => $dato['Cmds'],
                            'text' => $dato['Proceso'],
                            'color' => $dato['Color'],
                            'picture' => $dato['Picture'],
                        ];
                    }
                }
            }
            $val = $valor2 ? 1 : 2;
            return ['val' => $val, 'respuesta' => $respuesta];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function guardarAsignacion($parametros)
    {
        $datos = $this->modelo->guardarAsignacion($parametros);
        return array("res" => '1', "mensaje" => "Se registro correctamente", "datos" => $datos);
    }
}
?>