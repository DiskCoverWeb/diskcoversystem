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

if(isset($_GET['datosExtra'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->datosExtra($parametros));
}

if(isset($_GET['listaAsignacion'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->listaAsignacion($parametros));
}
if(isset($_GET['addAsignacion'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->addAsignacion($parametros));
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

    function datosExtra($parametros){
        try{
            $consulta = '(';
            foreach($parametros as $value){
                //if value == '.' ignore the value
                if($value == '.'){
                    continue;
                }
                $consulta .= "'" . $value . "',";
            }
            //remove the last comma
            if(substr($consulta, -1) == ','){
                $consulta = substr($consulta, 0, -1);
            }
            $consulta .= ')';
            //if consulta is equals to () return ('.')
            if($consulta === '()'){
                $consulta = "('.')";
            }
            $datos = $this->modelo->datosExtra($consulta);
            if(count($datos) == 0){
                throw new Exception('No se encontraron datos');
            }
            $res = array();
            return array('result' => '1', 'datos' => $datos);
        }catch(Exception $e){
            return array('result' => '0', 'message' => $e->getMessage());
        }
    }


    function listaAsignacion($parametros)
    {
        $tr = '';
        $datos = $this->modelo->listaAsignacion($parametros['beneficiario']);
        foreach ($datos as $key => $value) {
            $tr.='<tr>
                    <td>'.($key+1).'</td>
                    <td>'.$value['Producto'].'</td>
                    <td>'.$value['Cantidad'].'</td>
                    <td>'.$value['Procedencia'].'</td>
                    <td><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                </tr>';
        }

        return $tr;
        // print_r($datos);die();
    }

    function addAsignacion($parametros)
    {

        $producto = Leer_Codigo_Inv($parametros['Codigo'],$parametros['FechaAte']);

        // print_r($parametros);die();
        SetAdoAddNew("Detalle_Factura");
        SetAdoFields("TC","OP");
        SetAdoFields("CodigoC",$parametros['beneficiarioCodigo']);
        SetAdoFields("Procedencia",$parametros['Comentario']);
        SetAdoFields("Codigo",$parametros['Codigo']);
        SetAdoFields("Producto",$parametros['Producto']);
        SetAdoFields("Cantidad",$parametros['Cantidad']);
        SetAdoFields("Precio",number_format($producto['datos']['PVP'],2,'','.'));
        SetAdoFields("Total",number_format($producto['datos']['PVP']*$parametros['Cantidad'],2,'','.'));
        SetAdoFields("Fecha",$parametros['FechaAte']);
        SetAdoFields("Item",$_SESSION['INGRESO']['item']);
        SetAdoFields("CodigoU",$_SESSION['INGRESO']['CodigoU']);
        SetAdoFields("Periodo",$_SESSION['INGRESO']['periodo']);
        
        return SetAdoUpdate();
    }



}