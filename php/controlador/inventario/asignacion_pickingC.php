<?php
require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_pickingM.php");
require_once(dirname(__DIR__, 2) . "/modelo/inventario/asignacion_osM.php");

$controlador = new asignacion_pickingC();

if (isset($_GET['Beneficiario'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->Beneficiario($query));
}

if(isset($_GET['datosExtra'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->datosExtra($parametros));
}

if(isset($_GET['cargarOrden'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->cargarOrden($parametros));
}
/*
if(isset($_GET['addAsignacion'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->addAsignacion($parametros));
}
if(isset($_GET['eliminarLinea'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminarLinea($parametros));
}
if(isset($_GET['Codigo_Inv_stock'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Codigo_Inv_stock($parametros));
}
if(isset($_GET['llenarCamposPoblacion'])){
    $valor = $_POST['valor'];
    echo json_encode($controlador->llenarCamposPoblacion($valor));
}
if(isset($_GET['GuardarAsignacion'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->GuardarAsignacion($parametros));
}
if(isset($_GET['tipo_asignacion'])){
    // $parametros = $_POST['parametros'];
    echo json_encode($controlador->tipo_asignacion());
}

*/



class asignacion_pickingC
{
    private $modelo;
    private $asignacion;

    public function __construct()
    {

        $this->modelo = new asignacion_pickingM();
        $this->asignacion = new asignacion_osM();

    }

    function Beneficiario($query)
    {

    	$datos = $this->modelo->tipoBeneficiario($query);
    	$lista = array();
    	foreach ($datos as $key => $value) {
            $dia = BuscardiasSemana($value['Dia_Ent']);
            $value['Dia_Ent']  = $dia[0];
    		$lista[] = array('id'=>$value['Codigo'].'-'.$value['No_Hab'],'text'=>$value['Cliente'].' ('.$value['Tipo Asignacion'].')','data'=>$value);    		
    	}
    	return $lista;
    }



    function cargarOrden($parametros)
    {
        $tr = '';
        $cantidad = 0;
        $res = array();
        $datos = $this->asignacion->listaAsignacion($parametros['beneficiario'],'K',$parametros['tipo']);

        $detalle = '';
        $ddlGrupoPro = '';
        $total = 0;
        // print_r($datos);die();
        foreach ($datos as $key => $value) {
            $detalle.='<div class="row mb-3">                                    
                   <div class="col-sm-4">   
                        <b>Grupo de productos</b>
                        <h4>'.$value['Producto'].'</h4>
                    </div>
                    <div class="col-sm-4" style="padding:0px">                      
                        <b>Cantidad parcial a distribuir</b>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control input-xs" value="'.number_format($value['Cantidad'],2,'.','').'" readonly="">
                            <div class="input-group-addon input-xs">
                                <b>Dif:</b>
                            </div>
                            <input type="text" class="form-control input-xs">
                            
                        </div>
                    </div>              
                    <div class="col-sm-4">                      
                        <b>Comentario de asignacion</b>
                        <input type="text" class="form-control input-xs" value="'.$value['Procedencia'].'">
                    </div>                     
                </div>';
            $ddlGrupoPro.= '<option value="'.$value['Codigo'].'" >'.$value['Producto'].'</option>';
            $total =  $total+number_format($value['Cantidad'],2,'.','');
        }
        $detalle.='<div class="row">                                    
                    <div class="col-sm-4 text-center">
                        <label>Total</label>
                    </div>
                    <div class="col-sm-4" style="padding:0px">      
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control input-xs" value="'.$total.'" readonly>
                            <div class="input-group-addon input-xs">
                                <b>Dif:</b>
                            </div>
                            <input type="text" class="form-control input-xs" readonly>
                            
                        </div>
                    </div>              
                    <div class="col-sm-4">                      
                    </div>                     
                </div>';

        $res = array('detalle'=>$detalle,'ddl'=>$ddlGrupoPro,'total'=>$total);

        return $res;
    }

   /* function addAsignacion($parametros)
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
        SetAdoFields("No_Hab",$parametros['asignacion']);
        
        return SetAdoUpdate();
    }

    function eliminarLinea($parametros)
    {
        return $this->modelo->eliminarLinea($parametros['id']);
        // print_r($parametros);die();
    }

    function Codigo_Inv_stock($parametros)
    {
        $CodigoDeInv = $parametros['codigo'];
        $FechaInventario = date('Y-m-d');
        $datos = Leer_Codigo_Inv($CodigoDeInv,$FechaInventario);
        return $datos;
    }

    function llenarCamposPoblacion($parametros)
    {
        $tr = '';
        $poblacion = $this->modelo->tipo_poblacion();
        $datos = $this->modelo->llenarCamposPoblacion($parametros);
        if (count($datos)>0) {
            $tr = '';
            foreach ($poblacion as $key => $value) {
                $clave = array_search($value['Cmds'], array_column($datos, 'Cmds'));
                if($clave=='') 
                    { $item['Hombres']=0; $item['Mujeres']=0; $item['Total']=0;}else{
                $item = $datos[$clave];
            }   
                // print_r($item);die();
                $tr.='<tr><td colspan="2">'.$value['Proceso'].'</td><td>'.$item['Hombres'].'</td><td>'.$item['Mujeres'].'</td><td>'.$item['Total'].'</td></tr>';
            }
        }
        return $tr;

    }

    function tipo_asignacion()
    {
        $datos = $this->modelo->tipo_asignacion();
        foreach ($datos as $key => $value) {
            $lista[] = array('ID' =>$value['Cmds'] ,'Proceso'=>$value['Proceso'],'Picture'=>$value['Picture'] );
        }
        return $lista;
    }

    function GuardarAsignacion($parametros)
    {
        // print_r($parametros);die();
        SetAdoAddNew('Detalle_Factura');
        SetAdoFields('T','K');      
       
        SetAdoFieldsWhere('CodigoU',$_SESSION['INGRESO']['CodigoU']);
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']['item']);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']['periodo']); 
        SetAdoFieldsWhere('CodigoC',$parametros['beneficiario']);  
        SetAdoFieldsWhere('Fecha',$parametros['fecha']);  
        return SetAdoUpdateGeneric();
    }*/

}