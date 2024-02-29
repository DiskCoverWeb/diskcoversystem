<?php

/** 
 * AUTOR DE RUTINA	: Dallyana Vanegas
 * FECHA CREACION	: 16/02/2024
 * FECHA MODIFICACION : 29/02/2024
 * DESCIPCION : Clase controlador para Agencia
 */

include(dirname(__DIR__, 2) . '/modelo/inventario/registro_beneficiarioM.php');

$controlador = new registro_beneficiarioC();

if (isset($_GET['LlenarSelect'])) {
    $valores = $_POST['valores'];
    echo json_encode($controlador->LlenarSelect($valores));
}

if (isset($_GET['LlenarDatosCliente'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->LlenarDatosCliente($query));
}

if (isset($_GET['seleccionarClienteConRUCVisc'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->seleccionarClienteConRUCVisc($parametros['RUC'], $parametros['Cliente']));
}

if (isset($_GET['guardarAsignacion'])) {

    $params = array(
        'Cliente' => $_POST['Cliente'],
        'CI_RUC' => $_POST['CI_RUC'],
        'Codigo' => $_POST['Codigo'],
        'Actividad' => $_POST['Actividad'],
        'CodigoA' => $_POST['CodigoA'],
        'Representante' => $_POST['Representante'],
        'CI_RUC_R' => $_POST['CI_RUC_R'],
        'Telefono_R' => $_POST['Telefono_R'],
        'Contacto' => $_POST['Contacto'],
        'Profesion' => $_POST['Profesion'],
        'Fecha_Cad' => $_POST['Fecha_Cad'],
        'Hora_Ent' => $_POST['Hora_Ent'],
        'Direccion' => $_POST['Direccion'],
        'Email' => $_POST['Email'],
        'Email2' => $_POST['Email2'],
        'Lugar_Trabajo' => $_POST['Lugar_Trabajo'],
        'Telefono' => $_POST['Telefono'],
        'TelefonoT' => $_POST['TelefonoT'],
        'CodigoA2' => $_POST['CodigoA2'],
        'Fecha_Registro' => $_POST['Fecha_Registro'],
        'Hora_Registro' => $_POST['Hora_Registro'],
        'Envio_No' => $_POST['Envio_No'],
        'No_Soc' => $_POST['No_Soc'],
        'Area' => $_POST['Area'],
        'Acreditacion' => $_POST['Acreditacion'],
        'Tipo_Dato' => $_POST['Tipo_Dato'],
        'Cod_Fam' => $_POST['Cod_Fam'],
        'Observaciones' => $_POST['Observaciones']
    );

    if (isset($_FILES['Evidencias']) && $_FILES['Evidencias']['error'] == UPLOAD_ERR_OK) {
        $archivo = $_FILES['Evidencias'];
        $carpetaDestino = dirname(__DIR__, 3) . "/TEMP/EVIDENCIA_" . $_SESSION['INGRESO']['Entidad'] .
            "/EVIDENCIA_" . $_SESSION['INGRESO']['item'] . "/";
        $carpetaDestino = str_replace(' ', '_', $carpetaDestino);

        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $nombreArchivoDestino = $carpetaDestino . basename($archivo['name']);

        if (file_exists($nombreArchivoDestino)) {
            echo json_encode([
                "res" => '0',
                "mensaje" => "Ya existe un archivo con el nombre '"
                    . $archivo['name'] . "'. Por favor, cambie el nombre del archivo."
            ]);
        } else {
            if (move_uploaded_file($archivo['tmp_name'], $nombreArchivoDestino)) {
                $params['NombreArchivo'] = $archivo['name'];
                echo json_encode($controlador->guardarAsignacion($params));
            } else {
                echo json_encode(["res" => '0', "mensaje" => "No se ha cargado ningún archivo", "datos" => $parametros]);
            }
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

    function LlenarSelect($valores)
    {
        foreach ($valores as $valor) {
            $datos = $this->modelo->LlenarSelect($valor);
            if (empty($datos)) {
                $datos = "No se encontraron datos para mostrar";
            }
            $resultado = array(
                "valor" => $valor,
                "datos" => $datos
            );
            $resultados[] = $resultado;
        }
        return $resultados;
    }

    function LlenarDatosCliente($query): array
    {
        try {
            $datos = $this->modelo->LlenarDatosCliente($query);
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }
            foreach ($datos as $valor) {
                $fecha = $valor['Fecha_Cad']->format('Y-m-d');

                $clientes[] = [
                    'id' => $valor['Codigo'],
                    'text' => $valor['Cliente'],
                    'CodigoA' => $valor['CodigoA'],
                    'CI_RUC' => $valor['CI_RUC'],
                    'Representante' => $valor['Representante'],
                    'Fecha_Cad' => $fecha,
                    //'Hora_Ent' => ['Hora_Ent'],
                    'CI_RUC_R' => $valor['CI_RUC_R'],
                    'Telefono_R' => $valor['Telefono_R'],
                    'Contacto' => $valor['Contacto'],
                    'Profesion' => $valor['Profesion'],
                    'Direccion' => $valor['Direccion'],
                    'Email' => $valor['Email'],
                    'Email2' => $valor['Email2'],
                    'Lugar_Trabajo' => $valor['Lugar_Trabajo'],
                    'Telefono' => $valor['Telefono'],
                    'TelefonoT' => $valor['TelefonoT']
                ];
                $rucs[] = [
                    'id' => $valor['Codigo'],
                    'text' => $valor['CI_RUC'],
                    'CodigoA' => $valor['CodigoA'],
                    'Cliente' => $valor['Cliente'],
                    'Representante' => $valor['Representante'],
                    'Fecha_Cad' => $fecha,
                    //'Hora_Ent' => ['Hora_Ent'],
                    'CI_RUC_R' => $valor['CI_RUC_R'],
                    'Telefono_R' => $valor['Telefono_R'],
                    'Contacto' => $valor['Contacto'],
                    'Profesion' => $valor['Profesion'],
                    'Direccion' => $valor['Direccion'],
                    'Email' => $valor['Email'],
                    'Email2' => $valor['Email2'],
                    'Lugar_Trabajo' => $valor['Lugar_Trabajo'],
                    'Telefono' => $valor['Telefono'],
                    'TelefonoT' => $valor['TelefonoT']
                ];
            }
            return ['clientes' => $clientes, 'rucs' => $rucs];
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