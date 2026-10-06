<?php
require_once dirname(__DIR__, 2) . '/db/session_guard.php';
require_once(dirname(__DIR__,2).'/modelo/inventario/resumen_existenciasM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
// require(dirname(__DIR__,3).'/lib/excel/plantilla2.php');


$controlador = new resumen_existenciasC();


if (isset($_GET['Listatabla'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Listatabla($parametros));
}

if (isset($_GET['Beneficiario_new'])) {
    $query = '';
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
    }
    echo json_encode($controlador->agregar_nuevo_beneficiario($query));
}

if(isset($_GET['cargarOrden'])){
    $parametros = $_POST['param'];
    echo json_encode($controlador->cargarOrden($parametros));
}

if(isset($_GET['DCBodega'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    echo json_encode($controlador->DCBodega($query));
}

if(isset($_GET['DCTInv'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    echo json_encode($controlador->DCTInv($query));
}
if(isset($_GET['DCTipoBusqueda'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    $opcion = isset($_GET['cbx']) ? $_GET['cbx']:"";
    $dcInv =  isset($_GET['DCInvSelec']) ? $_GET['DCInvSelec']:"";
    echo json_encode($controlador->DCTipoBusqueda($opcion,$dcInv,$query));
}

if(isset($_GET['DCCtaInv'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    $opcion = isset($_GET['cbx']) ? $_GET['cbx']:"";
    echo json_encode($controlador->DCCtaInv($opcion,$query));
}

if(isset($_GET['DCSubModulo'])){
    $query = isset($_GET['q']) ? $_GET['q']:"";
    $opcion = isset($_GET['cbx']) ? $_GET['cbx']:"";
    echo json_encode($controlador->DCSubModulo($opcion,$query));
}

if(isset($_GET['Resumen_QR'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_QR($parametros));
}

if(isset($_GET['Resumen_Barras'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_Barras($parametros));
}

if(isset($_GET['Resumen_Lote'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Resumen_Lote($parametros));
}
if(isset($_GET['Stock'])){
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Stock($parametros));
}

if(isset($_GET['reporte_PDF']))
{
    $filtros = $_POST;
    // print_r($filtros);die();
    if(count($filtros)>0)
    {
        $parametros = array(
            'inicial'=>isset($_POST['txt_inicial']) ? $_POST['txt_inicial']:'',
            'final'=>  isset($_POST['txt_final']) ? $_POST['txt_final']:'',
            'cbxpro'=>  isset($_POST['rbx_producto']) ? $_POST['rbx_producto']:'',
            'CheqBod'=>  isset($_POST['CheqBod']) ? $_POST['CheqBod']:'false',
            'CheqProducto'=>  isset($_POST['CheqProducto']) ? $_POST['CheqProducto']:'false',
            'CheqMonto'=>  isset($_POST['CheqMonto']) ?  $_POST['CheqMonto'] : 'false',
            'CheqExist'=>  isset($_POST['CheqExist']) ?  $_POST['CheqExist'] : 'false',
            'DCTipoBusqueda'=>  isset($_POST['DCTipoBusqueda']) ? $_POST['DCTipoBusqueda']:'',
            'TxtMonto'=>  isset($_POST['TxtMonto']) ? $_POST['TxtMonto'] :'0' ,
            'DCInv'=>  isset($_POST['DCTInv']) ? $_POST['DCTInv']:'',
            'tipo_consulta'=>$_POST['tipo_consulta'],
            'DCBodega'=> isset($_POST['DCBodega']) ? $_POST['DCBodega']:'',
            'CheqGrupo'=> isset($_POST['CheqGrupo']) ? $_POST['CheqGrupo']:'false',
        );
        echo json_encode($controlador->reporte_PDF($parametros));
    }else{
        echo "
            <script type='text/javascript'>
                alert('Ocurrió un error o no hay datos para mostrar.');
                window.close();
            </script>
            ";
            exit;
    }
}

if(isset($_GET['reporte_excel']))
{
    $filtros = $_POST;
    // print_r($filtros);die();
    if(count($filtros)>0)
    {
        $parametros = array(
            'inicial'=>isset($_POST['txt_inicial']) ? $_POST['txt_inicial']:'',
            'final'=>  isset($_POST['txt_final']) ? $_POST['txt_final']:'',
            'cbxpro'=>  isset($_POST['rbx_producto']) ? $_POST['rbx_producto']:'',
            'CheqBod'=>  isset($_POST['CheqBod']) ? $_POST['CheqBod']:'false',
            'CheqProducto'=>  isset($_POST['CheqProducto']) ? $_POST['CheqProducto']:'false',
            'CheqMonto'=>  isset($_POST['CheqMonto']) ?  $_POST['CheqMonto'] : 'false',
            'CheqExist'=>  isset($_POST['CheqExist']) ?  $_POST['CheqExist'] : 'false',
            'DCTipoBusqueda'=>  isset($_POST['DCTipoBusqueda']) ? $_POST['DCTipoBusqueda']:'',
            'TxtMonto'=>  isset($_POST['TxtMonto']) ? $_POST['TxtMonto'] :'0' ,
            'DCInv'=>  isset($_POST['DCTInv']) ? $_POST['DCTInv']:'',
            'tipo_consulta'=>$_POST['tipo_consulta'],
            'DCBodega'=> isset($_POST['DCBodega']) ? $_POST['DCBodega']:'',
            'CheqGrupo'=> isset($_POST['CheqGrupo']) ? $_POST['CheqGrupo']:'false',
        );
        echo json_encode($controlador->reporte_excel($parametros));
    }else{
        echo "
            <script type='text/javascript'>
                alert('Ocurrió un error o no hay datos para mostrar.');
                window.close();
            </script>
            ";
            exit;
    }
    // print_r($_POST);
    // print_r('expression');die();
    // $datos = urldecode($_POST['datos']);
    // parse_str($datos, $filtros);
    // print_r($datos);
    // print_r($filtros);die();
}




class resumen_existenciasC
{
    private $modelo;
    private $sri;
    private $egresos;
    private $pdf;
    private $excel;

    function __construct()
    {
        $this->modelo = new resumen_existenciasM();
        $this->pdf = new cabecera_pdf();  
    }

    function Listatabla($parametros)
    {
        $data = $this->modelo->Listatabla();
        return $data;
        // print_r($data);die();
    }
    function DCBodega($query)
    {
        $lista = array();
        $data =  $this->modelo->DCBodega($query);
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['CodBod'],'text'=>$value['Bodegas']);
        }

        return $lista;
    }

    function DCTInv($query)
    {
        $lista = array();
        $data =  $this->modelo->DCTInv($query);
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto']);
        }

        return $lista;
    }
    function DCTipoBusqueda($opcion,$dcInv,$query)
    {
        $lista = array();
        switch ($opcion) {
            case '1':
            $data =  $this->modelo->Catalogo_Marcas($query);
                break;
            case '2':            
            $data =  $this->modelo->Trans_Kardex_barras($query);
                break;
        
            case '3':
            $data =  $this->modelo->Trans_Kardex_lote($query);
                break;
        
            case '4':
            if($dcInv=="")
            {
               $data = $this->modelo->DCTInv();
               $dcInv = $data[0]['Codigo_Inv'];
               // print_r($data);die();
            }
            $data = $this->modelo->Catalogo_Productos($dcInv);
                break;
        }

        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['Producto']);
        }

        return $lista;
    }

    function DCCtaInv($opcion,$query)
    {
        if($opcion==1) 
        {
            $data = $this->modelo->DCCtaInvOp1($query);
           
        }else{
            $data = $this->modelo->DCCtaInvOp2($query);
        }

        $lista = array();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Cta_Inv'],'text'=>$value['Cuenta']);
        }
        return $lista;


        // print_r($data);die();

    }
    function DCSubModulo($opcion,$query)
    {
        if($opcion==1)
        {
            $data = $this->modelo->DCSubModuloOp1($query);
  
        }else{
            $data = $this->modelo->DCSubModuloOp2($query);
        }

        $lista = array();
        foreach ($data as $key => $value) {
            $lista[] = array('id'=>$value['Codigo'],'text'=>$value['SubModulo']);
        }
        return $lista;
        // print_r($data);die();
    }

    function Stock($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Total = 0;

        $QTipoInv = false;
        $Cod_Bodega = G_NINGUNO;
        Control_Procesos("I", "Proceso Stock de Inventario, del ".$parametros['inicial'].' al '.$parametros['final']);
        Reporte_Resumen_Existencias_SP($parametros['inicial'],$parametros['final'],$Cod_Bodega);

        // print_r($parametros);die();

      if($parametros['CheqProducto']=="true")
      {
         $Opcion = 2;
        // print_r($parametros);die();
        // print_r('expression');die();
        //RatonReloj
        //MiTiempo = Time
        //DGQuery.Visible = False
        //Progreso_Barra.Mensaje_Box = "Procesando Resumen de Existencia"
        //Progreso_Iniciar
        if($parametros['CheqBod']==false){ $Cod_Bodega = G_NINGUNO; }else{ $Cod_Bodega = $parametros['DCBodega']; }

        $data =  $this->modelo->Stock($parametros['CheqMonto'],$parametros['TxtMonto']);

      }else{
         $Opcion = 1;
         $SQL_Tipo_Busqueda_CP = $this->SQL_Tipo_Busqueda_CP($parametros);
         $data = $this->modelo->Stock_Catalogo_Productos($parametros['CheqGrupo'],$SQL_Tipo_Busqueda_CP);
    }

    foreach ($data as $key => $value) {
        $Debitos +=  number_format($value["Entradas"] * $value["Costo_Unit"], 2,'.','');
        $Creditos +=  number_format($value["Salidas"] * $value["Costo_Unit"], 2,'.','');
        $Total +=  number_format($value["Valor_Total"], 2,'.','');
    }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Total'=>$Total);


//   SQLDec = "Costo_Unit " & CStr(Dec_Costo) & "|Total 2|."
//   Select_Adodc_Grid DGQuery, AdoDetKardex, sSQL, SQLDec
//  'MsgBox Opcion & vbCrLf & SQLDec & vbCrLf & Cod_Bodega
//   Total = 0
//   Debitos = 0
//   Creditos = 0
//   DGQuery.Visible = False
//   With AdoDetKardex.Recordset
//    If .RecordCount > 0 Then
//        Do While Not .EOF
//           If OpcProducto.value <> 1 Then
//              If .fields("TC") <> "I" Then
//                  Debitos = Debitos + Redondear(.fields("Entradas") * .fields("Costo_Unit"), 2)
//                  Creditos = Creditos + Redondear(.fields("Salidas") * .fields("Costo_Unit"), 2)
//                  Total = Total + Redondear(.fields("Valor_Total"), 2)
//              End If
//           End If
//          .MoveNext
//        Loop
//    End If
//   End With
//   DGQuery.Visible = True
//  'Total = Debitos - Creditos
//   LabelTot.Caption = Format(Total, "#,##0.00")
//   DGQuery.Visible = True
//   RatonNormal
// End Sub
    }


    function Resumen_QR($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;

        $data = $this->modelo->Resumen_QR($parametros['inicial'],$parametros['final']);
        foreach ($data as $key => $value) {
            $Debitos+= $value['Entradas'];
            $Creditos+= $value['Salidas'];
            $Stock_Inv+= $value['Stock_QR'];
        }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Stock_Inv'=>$Stock_Inv);
    }

    function Resumen_Barras($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;
        $SQL_Tipo_Busqueda = $this->SQL_Tipo_Busqueda($parametros);

        $data = $this->modelo->Resumen_Barras($parametros['inicial'],$parametros['final'],$SQL_Tipo_Busqueda);
        foreach ($data as $key => $value) {
            $Debitos+= $value['Entradas'];
            $Creditos+= $value['Salidas'];
            $Stock_Inv+= $value['Stock_Lote'];
        }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Stock_Inv'=>$Stock_Inv);

    }

    function Resumen_Lote($parametros)
    {
        $Debitos = 0;
        $Creditos = 0;
        $Stock_Inv = 0;
        $SQL_Tipo_Busqueda = $this->SQL_Tipo_Busqueda($parametros);

        $data = $this->modelo->Resumen_Lote($parametros['inicial'],$parametros['final'],$SQL_Tipo_Busqueda);
        foreach ($data as $key => $value) {
            $Debitos+= $value['Entradas'];
            $Creditos+= $value['Salidas'];
            $Stock_Inv+= $value['Stock_Lote'];
        }
        return array('data'=>$data,'Debitos'=>$Debitos,'Creditos'=>$Creditos,'Stock_Inv'=>$Stock_Inv);

    }

    function SQL_Tipo_Busqueda($parametros)
    {
        // print_r($parametros);die();
        $BSQL = " ";
        $CodigoInv = G_NINGUNO;
        $Cod_Bodega = $parametros['DCBodega'];
        // $data = $this->DCTipoBusqueda($parametros['cbxpro'],$parametros['DCInv'],"");

        if($parametros['cbxpro']=='4')
        {
            // if(count($data)>0)
            // {
                $CodigoInv = $parametros['DCTipoBusqueda'];
            // }
        }else{
             $CodigoInv = $parametros['DCTipoBusqueda'];
        }        
          
         if( $parametros['CheqBod'] <> 'false'){$BSQL = $BSQL." AND TK.CodBodega = '".$Cod_Bodega."' "; }

        if($parametros['CheqProducto'] <> 'false')
        {
             if($parametros['cbxpro']==2){
                $BSQL.= " AND TK.Codigo_Barra = '".$CodigoInv."' ";
             }else if($parametros['cbxpro']==3){
                $BSQL.= " AND TK.Lote_No = '".$CodigoInv."' ";
             }else{
                $BSQL.=" AND TK.Codigo_Inv = '".$CodigoInv."' ";
            }
        }
          
          if($parametros['CheqMonto'] <> 'false'){ $BSQL.=" AND CP.Stock_Actual = ".$parametros['TxtMonto']." "; }
          if($parametros['CheqExist']=='false'){ $BSQL.= " AND CP.Valor_Total <> 0 ";}
        //  // 'MsgBox BSQL
        return $BSQL;
    }

    function SQL_Tipo_Busqueda_CP($parametros)
    {
        // print_r($parametros);die();
        $BSQL = " ";
        $CodigoInv = G_NINGUNO;
        // $data = $this->DCTipoBusqueda($parametros['cbxpro'],$parametros['DCInv'],"");

        if($parametros['cbxpro']=='4')
        {
            // if(count($data)>0)
            // {
                $CodigoInv = $parametros['DCTipoBusqueda'];
            // }
        }else{
             $CodigoInv = $parametros['DCTipoBusqueda'];
        }        
                 
        if($parametros['CheqMonto'] <> 'false'){ $BSQL.=" AND CP.Stock_Actual = ".$parametros['TxtMonto']." "; }
        if($parametros['CheqExist']=='false'){ $BSQL.= " AND CP.Valor_Total <> 0 ";}
        //  // 'MsgBox BSQL
        return $BSQL;
    }

    function reporte_PDF($filtros)
    {
        $lista =array();
        $medidas = array();
        $alineado = array();
        // print_r($filtros);die();
        switch ($filtros['tipo_consulta']) {
            case 'QR':
            $data = $this->Resumen_QR($filtros);
            $medidas = array(20,49,35,50,20,18,18,18,18,25);
            $alineado = array('L','L','L','L','R','R','R','R','R','R');
            $lista = $data['data'];
                break;
            case 'BARRAS':
            $data = $this->Resumen_Barras($filtros);
            $medidas = array(20,49,35,50,20,18,18,18,18,25);
            $alineado = array('L','L','L','L','R','R','R','R','R','R');

            $lista = $data['data'];
                break;
            case 'LOTE':
            $data = $this->Resumen_Lote($filtros);
            $lista = $data['data'];
            $medidas = array(20,35,35,15,18,18,20,15,18,15,15,15,15,15,15);
            $alineado = array('L','L','L','L','L','L','L','L','R','R','R','R','R','R','R');

                break;
            case 'AGRUPADO':
            case 'STOCK':
            $data = $this->Stock($filtros);

            $lista = $data['data'];
            $medidas = array(10,18,40,15,20,20,20,20,20,20,15,25,25);
            $alineado = array('L','L','L','L','R','R','R','R','R','R','R','L','L');

                break;            
            default:
             $lista  = $this->Listatabla($filtros);            
                // $lista = $data['data'];
             $medidas = array(10,28,60,15,20,20,20,20,20,20,30);
             $alineado = array('L','L','L','L','R','R','R','R','R','R','R');

                break;
        }

        // print_r($lista);die();

        $head = array();
        foreach ($lista[0] as $key => $value) {
            array_push($head, $key);
        }


        $tablaHTML = array();
        $tablaHTML[0]['medidas']=$medidas;
        $tablaHTML[0]['alineado']=$alineado;
        $tablaHTML[0]['datos']=$head;
        $tablaHTML[0]['estilo']='BI';
        $tablaHTML[0]['borde'] = '1';

        $i =  1;
        foreach ($lista as $key => $value) {

            $body = array();
            foreach ($head as $key2 => $value2) {
                if(!is_object($value[$value2]))
                {
                    array_push($body, $value[$value2]);
                }else{
                array_push($body, $value[$value2]->format('Y-m-d'));
                }
            }



            $tablaHTML[$i]['medidas']= $tablaHTML[0]['medidas'];
            $tablaHTML[$i]['alineado']= $tablaHTML[0]['alineado'];
            $tablaHTML[$i]['datos']= $body;
            // $tablaHTML[$i]['estilo']='BI';
            $tablaHTML[$i]['borde'] = '1';

            $i++;
        }

        $this->pdf->cabecera_reporte_MC('R E S U M E N   D E   E X I S T E N C I A S',$tablaHTML,$contenido=false,$image=false,$filtros['inicial'],$filtros['final'],$sizetable=10,$mostrar=true,25,'L');


        print_r($data);die();
    }



    function reporte_excel($filtros)
    {
        $tablaHTML = array();

         $lista =array();
        // print_r($filtros);die();
        $lista =array();
        $medidas = array();
        $alineado = array();
        // print_r($filtros);die();
        switch ($filtros['tipo_consulta']) {
            case 'QR':
            $data = $this->Resumen_QR($filtros);
            $medidas = array(20,49,35,50,20,18,18,18,18,25);
            $alineado = array('L','L','L','L','R','R','R','R','R','R');
            $lista = $data['data'];
                break;
            case 'BARRAS':
            $data = $this->Resumen_Barras($filtros);
            $medidas = array(20,49,35,50,20,18,18,18,18,25);
            $alineado = array('L','L','L','L','R','R','R','R','R','R');

            $lista = $data['data'];
                break;
            case 'LOTE':
            $data = $this->Resumen_Lote($filtros);
            $lista = $data['data'];
            $medidas = array(20,35,35,15,18,18,20,15,18,15,15,15,15,15,15);
            $alineado = array('L','L','L','L','L','L','L','L','R','R','R','R','R','R','R');

                break;
            case 'AGRUPADO':
            case 'STOCK':
            $data = $this->Stock($filtros);

            $lista = $data['data'];
            $medidas = array(10,18,40,15,20,20,20,20,20,20,15,25,25);
            $alineado = array('L','L','L','L','R','R','R','R','R','R','R','L','L');

                break;            
            default:
               $lista  = $this->Listatabla($filtros);            
                // $lista = $data['data'];
             $medidas = array(10,28,60,15,20,20,20,20,20,20,30);
             $alineado = array('L','L','L','L','R','R','R','R','R','R','R');

                break;
        }


        // print_r($lista);die();

        $head = array();
        foreach ($lista[0] as $key => $value) {
            array_push($head, $key);
        }


        $tablaHTML = array();
        $tablaHTML[0]['medidas']=$medidas;
        $tablaHTML[0]['datos']=$head;        
        $tablaHTML[0]['tipo'] ='C';

        $i =  1;
        foreach ($lista as $key => $value) {

            $body = array();
            foreach ($head as $key2 => $value2) {
                if(!is_object($value[$value2]))
                {
                    array_push($body, $value[$value2]);
                }else{
                array_push($body, $value[$value2]->format('Y-m-d'));
                }
            }

            $tablaHTML[$i]['medidas']= $tablaHTML[0]['medidas'];
            $tablaHTML[$i]['datos']= $body;

            $i++;
        }
        
        excel_generico('R E S U M E N   D E   E X I S T E N C I A S',$tablaHTML,$url=false);
    }
}
?>