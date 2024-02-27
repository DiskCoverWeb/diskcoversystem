<?php
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
    $params = $_POST['params'];
    echo json_encode($controlador->LlenarSelect($params));
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
            $res = [];
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }
            foreach ($datos as $valor) {
                $clientes[] = [
                    'id' => $valor['Codigo'],
                    'text' => $valor['Cliente'],
                    'CodigoA' => $valor['CodigoA'],
                    'CI_RUC' => $valor['CI_RUC'],
                    'Representante' => $valor['Representante'],
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

    function guardarAsignacion($parametros){
        $this->modelo->guardarAsignacion($parametros);
    }
}
?>