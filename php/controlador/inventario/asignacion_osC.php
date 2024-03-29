<?php
require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_osM.php");

$controlador = new asignacion_osC();

if (isset($_GET['Beneficiario'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->tipoBeneficiario($query));
}







class asignacion_osC
{
    private $modelo;

    public function __construct()
    {

        $this->modelo = new asignacion_osM();

    }

    /**
     * 
     * @return array
     * @throws Exception Cuando no se encuentran datos
     */
    public function tipoBeneficiario($query): array
    {
        try {
            $datos = $this->modelo->tipoBeneficiario($query);
            $res = [];
            if (count($datos) == 0) {
                throw new Exception('No se encontraron datos');
            }
            foreach ($datos as $value) {
                $res[] = [
                    'id' => $value['Codigo'],
                    'text' => $value['Cliente'],
                    'CodigoA' => $value['CodigoA'],
                    'CI_RUC' => $value['CI_RUC'],
                    'Fecha_Atencion' => $value['Fecha_Registro']->format('Y-m-d'),
                    'Dia_Entrega' => $value['Fecha_Registro']->format('d'),
                    'Hora_Entrega' => $value['Fecha_Registro']->format('H:i'),
                    'Envio_No' => $value['Envio_No'],
                    'Beneficiario' => $value['Beneficiario'],
                    'No_Soc' => $value['No_Soc'],
                    'Area' => $value['Area'],
                    'Acreditacion' => $value['Acreditacion'],
                    'Tipo' => $value['Tipo'],
                    'Cod_Fam' => $value['Cod_Fam'],
                    'Salario' => $value['Salario'],
                    'CodigoACD' => $value['CodigoACD'],
                    'Descuento' => $value['Descuento'],
                    'Evidencias' => $value['Evidencias']
                ];
            }
            return ['results' => $res]; // Ajuste aquí para coincidir con el formato de Select2
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }


}