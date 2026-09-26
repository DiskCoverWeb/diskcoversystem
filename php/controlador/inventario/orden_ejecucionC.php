<?php
require_once(dirname(__DIR__,2).'/modelo/inventario/orden_ejecucionM.php');
require_once(dirname(__DIR__,2).'/modelo/inventario/contrato_trabajo_detalle_constM.php');
require_once(dirname(__DIR__,2).'/modelo/inventario/orden_trabajo_constM.php');

require_once(dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require_once(dirname(__DIR__,2).'/funciones/sp_generales.php');

$controlador = new orden_ejecucionC();
if(isset($_GET['lista_orden_ejecucion']))
{
    echo json_encode($controlador->lista_orden_ejecucion());
}

if(isset($_GET['contratistas']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    echo json_encode($controlador->contratistas($query));
}

if(isset($_GET['contratos']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    if(isset($_GET['ContratosContratista'])){$contratista = $_GET['ContratosContratista'];}
    echo json_encode($controlador->contratos($contratista,$query));
}

if(isset($_GET['lista_semanas']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->lista_semanas($parametros));
}

if(isset($_GET['cargar_lista_subrubros']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_lista_subrubros($parametros));
}

if(isset($_GET['detalleContrato']))
{
    $parametros = $_POST['parametros'];
     $parametros['tipo'] = 'E';
    echo json_encode($controlador->detalleContrato($parametros));
}
if(isset($_GET['detalleContratoEjec']))
{
    $parametros = $_POST['parametros'];
    $parametros['tipo'] = 'A';
    echo json_encode($controlador->detalleContrato($parametros));
}

if(isset($_GET['guardar_subrubro_ejecucion']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_subrubro_ejecucion($parametros));
}

if(isset($_GET['cargar_fecha_periodo']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_fecha_periodo($parametros));
}

if(isset($_GET['guardar_periodo']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->guardar_periodo($parametros));
}

if(isset($_GET['contratistasBuscar']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->contratistasBuscar($parametros));
}

if(isset($_GET['finalizar_ejecucion']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->finalizar_ejecucion($parametros));
}

// control de avances

if(isset($_GET['contratistasAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    echo json_encode($controlador->contratistasAvances($query));
}


if(isset($_GET['contratosAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    $contratista = $_GET['CotraAvance'];
    echo json_encode($controlador->contratosAvances($contratista,$query));
}


if(isset($_GET['rubrosAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    $contrato = $_GET['CotratoAvance'];
    echo json_encode($controlador->rubrosAvances($contrato,$query));
}

if(isset($_GET['mesesAvances']))
{
    $query = false;
    if(isset($_GET['q'])){$query = $_GET['q'];}
    $contrato = $_GET['CtaRubroAvance'];
    echo json_encode($controlador->mesesAvances($contrato,$query));
}


if(isset($_GET['cargar_lista_subrubros_avance']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_lista_subrubros_avance($parametros));
}


if(isset($_GET['cargar_detalle_contratos']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->cargar_detalle_contratos($parametros));
}





class orden_ejecucionC
{
    private $modelo;
    private $contratos;
    private $orden;

    private $ing_des;
    private $sp_generales;

    function __construct(){
        $this->modelo = new orden_ejecucionM();
        $this->contratos = new contrato_trabajo_detalle_constM();
        $this->orden = new orden_trabajo_constM();
        $this->ing_des = new ingreso_descargosM();

        $this->sp_generales = new sp_generales();
    }

    function lista_orden_ejecucion(){

        $result = $this->modelo->detalleContrato_ejecucion();

        // $result = $this->modelo->lista_orden_ejecucion();
        // print_r($result);die();
        return $result;
    }

    function contratistas($query)
    {
       return $this->modelo->contratistas($query);
    }

    function contratistasBuscar($parametros)
    {
        // print_r($parametros);die();
        $codigo = $parametros['contratista'];
       return $this->modelo->contratistas(false,$codigo);
    }

    function contratistasAvances($query)
    {
          return $this->modelo->contratistasAvance($query,false,'.');
    }

    function contratos($contratista,$query)
    {
       $rubro = array();
       $data =  $this->modelo->rubrosXcontratista($query,$contratista);
       foreach ($data as $key => $value) {
           $rubro[] = array('id'=>$value['Rubro'],'text'=>$value['Cuenta'],'data'=>$value);
       }
       return $rubro;
    }

    function lista_semanas($parametros)
    {
        // print_r($parametros);die();
        $rubro =  !isset($parametros['rubro']) ? false : $parametros['rubro'];
        $data = $this->orden->SemanasXcentrosCostocXRubro($parametros['contrato'],$rubro);
        // print_r($data);die();
        return $data;
    }

   function cargar_lista_subrubros($parametros)
    {            
        $tbl = '';
        $CentroCostos = $this->modelo->centrosCostocXRubro($parametros['Contrato'],false,$parametros['semana'],$parametros['contratista']);
        // print_r($CentroCostos);die();
        foreach ($CentroCostos as $key => $value) {
            // print_r($value);die();
            $tbl.='<div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>'.$value['Cuenta'].' - ('.$value['Detalle'].' )</h5>
                        </div>
                         <div class="col-sm-6 text-end">
                         <button type="button" class="btn btn-primary" onclick="generar_comprobante(\''.$value['Rubro'].'\',\''.$value['Centro_Costo'].'\',\''.$value['Fecha_Inicio']->format('Y-m-d').'\',\''.$value['Fecha_Fin']->format('Y-m-d').'\');">Generar Comprobante</button>
                        </div>
                       
                        <div class="col-sm-12">


                <table class="table table-hover">
                    <thead>
                      <th></th>
                      <th>Sub Rubros</th>
                      <th>Orden</th>
                      <th>Costo total de Orden </th>
                      <th>Unidad</th>
                      <th>
                        <div class="row">
                            <div class="col-6">
                                Por Ejecutar 
                            </div>
                            <div class="col-6">
                                Ejecutado
                            </div>
                        </div> 
                      </th>
                      <th>% ejecutado</th>
                      <th>Costo unitario ejecutado</th>
                      <th>Costo total ejecutado</th>
                      <th>Diferencia costo</th>
                     <!-- <th></th> -->
                    </thead><tbody>';
            $data = $this->orden->cargar_lista_subrubros_procesar($parametros['Contrato'],false,$subrubro=false,$value['Centro_Costo'],$parametros['contratista'],$parametros['semana']);
            foreach ($data as $key => $value) {
                // print_r($value);die();
                $tbl.='<tr> 
                            <td>
                                   <!--  <button type="button" class="btn btn-primary btn-sm" onclick="add_periodo(\''.$value['ID'].'\');"><i class="bx bx-save me-0"></i></button> --!>
                            </td>
                            <td>'.$value['Detalle'].'</td> 
                            <td>'.$value['No_Contrato'].'</td>
                            <td>'.$value['Total'].'</td>
                            <td>'.$value['Unidad'].'</td>
                            <td>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="hidden" class="form-control form-control-sm" id="txt_pvp_'.$value['ID'].'" value="'.$value['PVP'].'" readonly />
                                        <input type="text" class="form-control form-control-sm text-end" id="txt_cantidad_'.$value['ID'].'" value="'.$value['Cantidad'].'" readonly />
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-sm text-end classEjecutado" id="txt_ejecucion_'.$value['ID'].'" onblur="calcular_ejecutado('.$value['ID'].')" value="'.$value['Cant_Ejec'].'" />
                                    </div>
                                </div> 
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm text-end" id="txt_porce_ejecucion_'.$value['ID'].'" value="0%" readonly />
                            </td>
                            <td><input type="text" class="form-control form-control-sm text-end" id="txt_ejecutado_pvp_'.$value['ID'].'" value="'.$value['Costo_Unit_Ejec'].'" readonly /></td>
                            <td><input type="text" class="form-control form-control-sm text-end classTotalEjecutado" id="txt_ejecutado_total_'.$value['ID'].'" value="'.$value['Costo_Total_Ejec'].'" readonly /></td>
                            <td><input type="text" class="form-control form-control-sm text-end" id="txt_ejecutado_dif_'.$value['ID'].'"  value="'.$value['Diferencia'].'"readonly /></td>
                            <!-- <td> <button type="button" onclick="add_periodo(\''.$value['ID'].'\')" class="btn btn-primary btn-sm"><i class="bx bx-calendar"></i> Periodos</button></td> -->
                        </tr>';
            }

        // print_r($data);die();
            $tbl.='</table></div> </div>
                    </div>';
        }

        return $tbl;
        // print_r($data);die();
    }

    function detalleContrato($parametros)
    {
        $contrato = $parametros['contrato'];
        return $this->contratos->detalleContrato($contrato,$parametros['tipo']);
        // print_r($parametros);die();
    }

    function guardar_subrubro_ejecucion($parametros)
    {


        $cta_cxc  = Leer_Seteos_Ctas('Cta_CxC_Contratistas') ?? '';
        $cta_multa = Leer_Seteos_Ctas('Cta_Multas_Contratistas') ?? '';
        $cta_cxp =  Leer_Seteos_Ctas('Cta_CxP_Contratistas') ?? '';

        // comprobante_normal 
        $cta_cc = LeerCta($cta_cxc);
        $cta_cp = LeerCta($cta_cxp);
        $cta_multas = LeerCta($cta_multa);


        // para subcuentas
        $cuenta = $this->modelo->catalogo_cuentas($parametros['rubro']);
        $sub = $this->modelo->Catalogo_SubCtas('G',$parametros['centroCosto']);
        $dataSub = array(
                'be'=>$cuenta[0]['Cuenta'],
                'ru'=> '',
                'co'=> $cuenta[0]['Codigo'],// codigo de cuenta cc
                'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                'tic'=> 1, //debito o credito (1 o 2);
                'sub'=> $sub[0]['Cta'], //Codigo se trae catalogo subcuenta
                'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                'fecha_sc'=> date('Y-m-d'), //fecha 
                'fac2'=>0,
                'mes'=> 0,
                'valorn'=> round($parametros['ejecutado_total'],2),//valor de sub cuenta 
                'moneda'=> 1, /// moneda 1
                'Trans'=>$sub[0]['Detalle'],//detalle que se trae del asiento
                'T_N'=> '111',
                't'=> $sub[0]['TC'],                        
              );

        // print_r($dataSub);die();
        $this->ing_des->generar_asientos_SC($dataSub);
        


        // asiento normal
        // asiento al debe
        $cuenta = $this->modelo->catalogo_cuentas($parametros['rubro']);     
        // print_r($cuenta);die();   
        $parametros_debe = array(
                 "va" =>round($parametros['ejecutado_total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>'.',
                  "codigo" => $cuenta[0]['Codigo'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>date('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 1,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '111',
        );
        $this->ing_des->ingresar_asientos($parametros_debe);


        $cuenta = $this->modelo->catalogo_cuentas($cta_cxp);    
        // print_r($cta_cxp);    
            // print_r($cuenta);die();  
                $parametros_haber = array(
                  "va" =>round($parametros['ejecutado_total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>'.',
                  "codigo" => $cuenta[0]['Codigo'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>date('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 2,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '111',
        );
        
        $re =   $this->ing_des->ingresar_asientos($parametros_haber); 


        // en caso de que hayan multas
        if($parametros['multa_total']>0){

             $cuenta = $this->modelo->catalogo_cuentas($cta_cxc);     
            // print_r($cuenta);die();   
            $parametros_debe = array(
                     "va" =>round($parametros['multa_total'],2),//valor que se trae del otal sumado
                      "dconcepto1" =>'.',
                      "codigo" => $cuenta[0]['Codigo'], // cuenta de codigo de 
                      "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                      "efectivo_as" =>date('Y-m-d'), // observacion si TC de catalogo de cuenta
                      "chq_as" => 0,
                      "moneda" => 1,
                      "tipo_cue" => 1,
                      "cotizacion" => 0,
                      "con" => 0,// depende de moneda
                      "t_no" => '111',
            );
            $this->ing_des->ingresar_asientos($parametros_debe);

            $cuenta = $this->modelo->catalogo_cuentas($cta_multa);        
                // print_r($cuenta);die();  
                    $parametros_haber = array(
                      "va" =>round($parametros['multa_total'],2),//valor que se trae del otal sumado
                      "dconcepto1" =>'.',
                      "codigo" => $cuenta[0]['Codigo'], // cuenta de codigo de 
                      "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                      "efectivo_as" =>date('Y-m-d'), // observacion si TC de catalogo de cuenta
                      "chq_as" => 0,
                      "moneda" => 1,
                      "tipo_cue" => 2,
                      "cotizacion" => 0,
                      "con" => 0,// depende de moneda
                      "t_no" => '111',
            );
            $re =   $this->ing_des->ingresar_asientos($parametros_haber); 

        }


        $cliente = json_decode(Leer_Datos_Cliente_SP($parametros['contratista']),true);

        // print_r($cliente);die();
        $data['NumModulo']='05';
        $data['Item']=$_SESSION['INGRESO']['item'];
        $data['Periodo']=$_SESSION['INGRESO']['periodo'];
        $data['Usuario']=$_SESSION['INGRESO']['CodigoU'];
        // $data['CodigoB'] = $cliente['CI_RUC'];
        $data['Beneficiario']=$cliente['Cliente'];
        $data['RUC_CI'] = $cliente['CI_RUC'];
        $data['TD'] = $cliente['TD'];
        $data["Telefono"] = $cliente['Telefono'];
        $data["Direccion"] = $cliente['Direccion'];
        $data["Email"] = $cliente['Email'];
        $data['Concepto']='prueba concepto json';
        $data["T_No"] = '111';
        $data_comprobante = $this->sp_generales->generar_comprobante($data); 

        if(isset($data_comprobante['Ok_Save']) && $data_comprobante['Ok_Save']==1)
        {
            $parametros['Numero'] = $data_comprobante['Numero'];
            $resp =  $this->finalizar_ejecucion($parametros);
            return array('respuesta'=>1,'comprobante'=>$data_comprobante['Numero']);
        }else{
            return array('respuesta'=>-1,'comprobante'=>'0');
        }

        print_r($data_comprobante);die();



    }

    function cargar_fecha_periodo($parametros)
    {
        $data = $this->contratos->Trans_Contratistas($parametros['id']);
        return $data;
        // print_r($data);die();
    }

    function guardar_periodo($parametros)
    {

        // print_r($parametros);die();
        SetAdoAddNew("Entidad_Rubro_Contratista");
        SetAdoFields("Fecha_Inicio_Ejec",$parametros['fechaInicio']);
        SetAdoFields("Fecha_Fin_Ejec",$parametros['fechaFin']);
        SetAdoFields("Observacion",$parametros["observacion"]);


        SetAdoFieldsWhere('ID',$parametros["idrubro"]);
        return SetAdoUpdateGeneric(); 
    }

    function finalizar_ejecucion($parametros)
    {

        $lineas = json_decode($parametros['lineas'],true);
        foreach ($lineas as $key => $value) {
            SetAdoAddNew("Entidad_Rubro_Contratista");
            SetAdoFields("Observacion",$parametros["observacion"]);
            SetAdoFields("TC",'A');
            SetAdoFields("Cant_Ejec",$value['valor']);
            SetAdoFieldsWhere('ID',$value['id']);
            SetAdoUpdateGeneric(); 
        }


        SetAdoAddNew("Trans_Contratistas");
        // SetAdoFields("TP",'N');
        SetAdoFields("TP",'.');
        SetAdoFields("Numero",$parametros['Numero']);

        SetAdoFieldsWhere('Codigo',$parametros["contratista"]);
        SetAdoFieldsWhere('No_Contrato',$parametros["contrato"]);
        SetAdoFieldsWhere('Item',$_SESSION['INGRESO']["item"]);
        SetAdoFieldsWhere('Periodo',$_SESSION['INGRESO']["periodo"]);

        return SetAdoUpdateGeneric(); 


        

        
    }

    function contratosAvances($contratista,$query)
    {

       $lista =  $this->modelo->lista_orden_ejecucion_finalizada($query,$contratista);
       $contratos = array();

       foreach ($lista as $key => $value) {
           $contratos[] = array('id'=>$value['No_Contrato'],'text'=>$value['No_Contrato']);
       }
       return $contratos;
    }


    function rubrosAvances($contratista,$query)
    {

       $lista =  $this->modelo->rubrosXcontratistaAvances($query,false,$contratista);
       $contratos = array();

       // print_r($lista);die();
       foreach ($lista as $key => $value) {
           $contratos[] = array('id'=>$value['Cta'],'text'=>$value['Cuenta']);
       }
       return $contratos;
    }

    function mesesAvances($rubrocta,$query)
    {

        $lista = $this->modelo->Trans_Contratistas_meses($id=false,$contrato=false,$rubrocta);

        $mesesAvances = array();
        foreach ($lista as $key => $value) {
            $mesesAvances[] = array('id'=>$value['Mes'],'text'=> mes_X_nombre($value['Mes']));
        }

        return $mesesAvances;
        print_r($lista);die();
        // print_r($query);die();
        // mes_X_nombre($num)
    }

    function cargar_lista_subrubros_avance($parametros)
    {
        // print_r($parametros);die();
        $data = $this->modelo->rubros_contrato_general(false,$parametros['contratista'],$parametros['contrato'],$parametros['rubro'],$parametros['mes']);

        $total_cont = 0;
        foreach ($data as $key => $value) {
            $total_cont+=$value['Cantidad'];
        }
        // print_r($data);die();

        $registros = $this->modelo->rubrosXcontratistaAll(false,$parametros['contratista'],$parametros['contrato'],$parametros['rubro'],$parametros['mes']);
        $total = 0;
        foreach ($registros as $key => $value) {
            $total+= $value['Cant_Ejec'];
        }

        $porcentaje = number_format( ($total*100)/$total_cont,2,'.','');
        $datos = array('lineas'=>$registros,'total_rubro'=>$total_cont,'total_procesado'=>$total,'porcentaje'=>$porcentaje.'%');
        // print_r($datos);die();

        return $datos;

        // print_r($data);die();
    }

    function cargar_detalle_contratos($parametros)
    {
        $data = $this->modelo->detalleContratoAll($parametros['contrato']);
        return $data;
        // print_r($data);die();
    }
}



?>