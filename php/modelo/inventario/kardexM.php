<?php
require_once dirname(__DIR__, 2) . '/db/session_guard.php';
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class kardexM
{
  private $db ;
  function __construct()
  {
      $this->db = new db();
  }

  function ExecuteDB($sSQL)
  {
       return $this->db->String_Sql($sSQL);
  }

  function SelectDB($sSQL)
  {
       return $this->db->datos($sSQL);
  }
  
  function ListarProductos($tipo,$codigoProducto,$query=false){
    $sSQL = "";
    switch ($tipo) {
      case "I":
        $sSQL = "SELECT Codigo_Inv + '  ' + Producto As NomProd 
          FROM Catalogo_Productos 
          WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";

        if($query){
          $sSQL .= "AND (Codigo_Inv LIKE '%".$query."%' OR Producto LIKE '%".$query."%') ";
        }
        $sSQL .= "AND TC = 'I' 
          ORDER BY Codigo_Inv ";
        break;

      case "P":
        $Codigo = trim($codigoProducto);
        $sSQL = "SELECT Producto,Codigo_Inv,Unidad,Minimo,Maximo 
          FROM Catalogo_Productos 
          WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
          AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";

        if($Codigo!=""){
          $sSQL .= "AND SUBSTRING(Codigo_Inv, 1, " . strlen($Codigo) . ") = '" . $Codigo . "' ";
        }

        $sSQL .= "AND TC = 'P' 
          AND Cta_Inventario <> '.' 
          AND Cta_Inventario <> '0' 
          ORDER BY Producto,Codigo_Inv ";
        break;  
    }

    return $this->db->datos($sSQL);
  }

  function Listar_Articulos($SoActivos = false, $ejecutar=false, $query=false) {
    $sSQL = "SELECT Codigo_Inv as codigo, Producto as nombre "
        . "FROM Catalogo_Productos "
        . "WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' "
        . "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";
    
    if ($SoActivos) {
        $sSQL .= "AND T = 'N' ";
    }

    if($query){
      $sSQL .= "AND Producto LIKE '%".$query."%' ";
    }
    
    $sSQL .= "AND TC = 'P' "
        . "AND INV <> " . intval(0) . " "
        . "AND LEN(Cta_Inventario) > 1 "
        . "AND LEN(Cta_Costo_Venta) > 1 "
        . "ORDER BY Codigo_Inv ";
    
    if($ejecutar){
        return $this->db->datos($sSQL);
    }else{
        return $sSQL;
    }
}

  function Consultar_Kardex($Codigo, $Codigo1, $FechaIni, $FechaFin, $ModoConsulta){
    $sSQL  =  "SELECT K.Codigo_Inv, K.Codigo_Barra, SUM(Entrada) As Entradas, SUM(Salida) As Salidas, SUM(Entrada-Salida) As Stock_Kardex 
              FROM Trans_Kardex As K, Comprobantes As C 
              WHERE K.Fecha BETWEEN '".$FechaIni."' AND '".$FechaFin."' 
              AND K.Codigo_Inv = '".$Codigo."'
              AND K.T = '".G_NORMAL."' 
              AND K.Item = '".$_SESSION['INGRESO']['item']."' 
              AND K.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
    if ($ModoConsulta === 'BODEGA') {
      $sSQL  .= "AND K.CodBodega = '".$Codigo1."' ";
    }
    $sSQL  .= "AND K.TP = C.TP 
            AND K.Fecha = C.Fecha 
            AND K.Numero = C.Numero 
            AND K.Item = C.Item 
            AND K.Periodo = C.Periodo 
            GROUP BY K.Codigo_Inv, K.Codigo_Barra
            HAVING SUM(Entrada-Salida) >=1 
            ORDER BY K.Codigo_Inv, K.Codigo_Barra ";
    $_SESSION['DGKardex']['sSQL'] = $sSQL;
    return grilla_generica_new($sSQL);
  }


  function bodegas($query=false, $tipo='BODEGA'){
    if ($tipo === 'BARRA') {
      $sql = "SELECT Codigo_Barra AS Codigo, Codigo_Barra AS Nombre
              FROM Trans_Kardex
              WHERE Item = '".$_SESSION['INGRESO']['item']."'
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              AND Entrada > 0 ";
      if($query){
        $sql .= "AND Codigo_Barra LIKE '%".$query."%' ";
      }
      $sql .= "GROUP BY Codigo_Barra ORDER BY Codigo_Barra";
    } else {
      $sql="SELECT Bodega AS Nombre, CodBod AS Codigo
            FROM Catalogo_Bodegas
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
      if($query){
        $sql .= "AND (CodBod LIKE '%".$query."%' OR Bodega LIKE '%".$query."%') ";
      }
      $sql .= "ORDER BY CodBod";
    }
    return $this->db->datos($sql);
  }

  function funcionInicio(){
    $sql = "UPDATE Catalogo_Productos 
            SET X = '.' 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'"; 
    $stmt = $this->db->datos($sql);

    $sql = "UPDATE Catalogo_Productos 
            SET X = 'M' 
            FROM Catalogo_Productos As CP, Trans_Kardex As TK 
            WHERE CP.Item = '".$_SESSION['INGRESO']['item']."' 
            AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND CP.Item = TK.Item 
            AND CP.Periodo = TK.Periodo 
            AND CP.Codigo_Inv = TK.Codigo_Inv" ;
    $stmt = $this->db->datos($sql);

    $sql = "SELECT Codigo_Inv 
            FROM Catalogo_Productos 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC = 'P' 
            AND X = 'M' 
            GROUP BY Codigo_Inv 
            ORDER BY Codigo_Inv";
    $datos = $this->db->datos($sql);
    $primeravez = true;

    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
        $Codigo1 =CodigoCuentaSup($value['Codigo_Inv']);
        $Codigo = CodigoCuentaSup($value['Codigo_Inv']);
        $Codigo = CodigoCuentaSup($Codigo);
        if ($Codigo != $Codigo1) {
          while (strlen($Codigo) > 1) {
            $sSQL = "UPDATE Catalogo_Productos 
                      SET X = 'M' 
                      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
                      AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                      AND X <> 'M' 
                      AND Codigo_Inv = '".$Codigo."'";     
            $stmt = sqlsrv_query( $this->db, $sSQL);
            $Codigo = CodigoCuentaSup($Codigo);
          }
        }
    }

  }

  function ActualizarSerieTK($CodigoP, $ID_Reg){
    $sSQL = "UPDATE Trans_Kardex " .
            "SET Serie_No = '" . $CodigoP . "' " .
            "WHERE ID = " . $ID_Reg . " " .
            "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
    return $this->db->String_Sql($sSQL);
  }

  function ActualizarSerieDF($CodigoP, $TC, $Serie, $Factura, $CodigoInv){
    $sSQL = "UPDATE Detalle_Factura " .
            "SET Serie_No = '" . $CodigoP . "' " .
            "WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND TC = '" . $TC . "' " .
            "AND Serie = '" . $Serie . "' " .
            "AND Factura = " . $Factura . " " .
            "AND Codigo = '" . $CodigoInv . "'";
    return $this->db->String_Sql($sSQL);
  }
  
  function CambiaCodigodeBarraTK($CodigoB, $ID_Reg){
    $sSQL = "UPDATE Trans_Kardex " .
    "SET Codigo_Barra = '" . $CodigoB . "' " .
    "WHERE ID = " . $ID_Reg . " " .
    "AND Item = '" . $_SESSION['INGRESO']['item'] . "' " .
    "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' ";
    
    return $this->db->String_Sql($sSQL);
  }
  function CambiaCodigodeBarraDF($CodigoB, $TC, $Serie, $Factura, $CodigoInv){
    $sSQL = "UPDATE Detalle_Factura " .
            "SET Codigo_Barra = '" . $CodigoB . "' " .
            "WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND TC = '" . $TC . "' " .
            "AND Serie = '" . $Serie . "' " .
            "AND Factura = " . $Factura . " " .
            "AND Codigo = '" . $CodigoInv . "' ";
    return $this->db->String_Sql($sSQL);
  }

  function ConfirmarCambiar_ArticuloTK($DCArt, $ID_Reg){
    $sSQL = "UPDATE Trans_Kardex 
            SET Codigo_Inv = '" . $DCArt . "'
            WHERE ID = " . $ID_Reg . "
            AND Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
    
    return $this->db->String_Sql($sSQL);
  }
  function ConfirmarCambiar_ArticuloDF($DCArt, $TC, $Serie, $Factura, $CodigoInv){
    $sSQL = "UPDATE Detalle_Factura 
            SET Codigo = '" . $DCArt. "'
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
            AND TC = '" . $TC . "'
            AND Serie = '" . $Serie . "'
            AND Factura = " . $Factura . "
            AND Codigo = '" . $CodigoInv . "'";
    return $this->db->String_Sql($sSQL);
  }

  function Consultar_Tipo_De_Kardex($Codigo, $Codigo1, $FechaIni, $FechaFin, $EsKardexIndividual, $GrupoInv, $ModoConsulta){
    if ($ModoConsulta === 'BARRA') {
      $stock = 'TK.Stock_Barra';
    } elseif ($ModoConsulta === 'BODEGA') {
      $stock = 'TK.Stock_Bod';
    } else {
      $stock = 'TK.Existencia';
    }
    $sSQL = "SELECT TK.Codigo_Inv, CP.Producto, CP.Unidad, TK.CodBodega AS Bodega, TK.Codigo_Barra, TK.Fecha, TK.TP, TK.Numero, TK.Entrada, TK.Salida, " . $stock . " AS Stock, TK.Costo, " .
            "TK.Total AS Saldo, TK.Valor_Unitario, TK.Valor_Total, TK.Fecha_Exp, TK.TC, TK.Serie, TK.Factura, TK.Cta_Inv, TK.Contra_Cta, CAST(NULL AS varchar(20)) AS Temperatura, TK.Serie_No, TK.Lote_No, " .
            "TK.Codigo_Tra AS CI_RUC_CC, CM.Marca AS Marca_Tipo_Proceso, TK.Detalle, TK.Centro_Costo AS Beneficiario_Centro_Costo, TK.Orden_No, TK.ID " .
            "FROM Trans_Kardex AS TK, Catalogo_Productos AS CP, Catalogo_Marcas AS CM " .
            "WHERE TK.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND TK.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND TK.T = '" . G_NORMAL . "' ";
    if ($EsKardexIndividual=="true" || (is_bool($EsKardexIndividual) && $EsKardexIndividual)) {
        $sSQL = $sSQL . "AND TK.Codigo_Inv = '" . $Codigo . "' ";
    } else {
        if ($GrupoInv != "*") {
            $sSQL = $sSQL . "AND TK.Codigo_Inv LIKE '" . $GrupoInv . "%' ";
        }
    }
    if ($ModoConsulta === 'BODEGA') {
        $sSQL = $sSQL . "AND TK.CodBodega LIKE '" . $Codigo1 . "%' ";
    } elseif ($ModoConsulta === 'BARRA') {
        $sSQL = $sSQL . "AND TK.Codigo_Barra = '" . $Codigo1 . "' ";
    }
    $sSQL = $sSQL .
            "AND TK.Item = CP.Item " .
            "AND TK.Item = CM.Item " .
            "AND TK.Periodo = CP.Periodo " .
            "AND TK.Periodo = CM.Periodo " .
            "AND TK.Codigo_Inv = CP.Codigo_Inv " .
            "AND TK.CodMarca = CM.CodMar " .
            "ORDER BY TK.Codigo_Inv, TK.Fecha,TK.Entrada DESC,TK.Salida,TK.TP,TK.Numero,TK.ID ";
    $_SESSION['DGKardex']['sSQL'] = $sSQL ;
    return grilla_generica_new($sSQL);
    //$SQLDec = "TK.Costo " . strval($Dec_Costo) . "| TK.Valor_Unitario " . strval($Dec_Costo) . "|,TK.Valor_Total 2|.";
  }

  function Kardex_Total_QR($FechaIni, $FechaFin, $Codigo1, $ModoConsulta){
    $stock = $ModoConsulta === 'BARRA' ? 'TK.Stock_Barra' : ($ModoConsulta === 'BODEGA' ? 'TK.Stock_Bod' : 'TK.Existencia');
    $sSQL = "SELECT TK.Codigo_Inv, CP.Producto, CP.Unidad, TK.CodBodega AS Bodega, TK.Codigo_Barra, TK.Fecha, TK.TP, TK.Numero, TK.Entrada, TK.Salida, " . $stock . " AS Stock, " .
            "TK.Costo, TK.Total AS Saldo, TK.Valor_Unitario, TK.Valor_Total, TK.Fecha_Exp, TK.TC, TK.Serie, TK.Factura, TK.Cta_Inv, TK.Contra_Cta, " .
            "TC.Porc_C AS Temperatura, TK.Serie_No, TK.Lote_No, TK.Codigo_Tra AS CI_RUC_CC, CM.Marca AS Marca_Tipo_Proceso, TK.Detalle, " .
            "TK.Centro_Costo AS Beneficiario_Centro_Costo, TK.Orden_No, TK.ID " .
            "FROM Trans_Kardex AS TK, Catalogo_Productos AS CP, Catalogo_Marcas AS CM, Trans_Correos AS TC " .
            "WHERE TK.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
            "AND TK.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
            "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
            "AND TK.T = '" . G_NORMAL . "' ";
    if ($ModoConsulta === 'BODEGA') {
      $sSQL .= "AND TK.CodBodega LIKE '" . $Codigo1 . "%' ";
    } elseif ($ModoConsulta === 'BARRA') {
      $sSQL .= "AND TK.Codigo_Barra = '" . $Codigo1 . "' ";
    }
    $sSQL .= "AND TK.Item = CP.Item AND TK.Periodo = CP.Periodo AND TK.Codigo_Inv = CP.Codigo_Inv " .
             "AND TK.Item = CM.Item AND TK.Periodo = CM.Periodo AND TK.CodMarca = CM.CodMar " .
             "AND TK.Item = TC.Item AND TK.Periodo = TC.Periodo " .
             "AND TC.Envio_No = SUBSTRING(TK.Codigo_Barra, 1, LEN(TC.Envio_No)) " .
             "ORDER BY TK.CodBodega, TK.Codigo_Inv, TK.Fecha, TK.Entrada DESC, TK.Salida, TK.TP, TK.Numero, TK.ID";
    $_SESSION['DGKardex']['sSQL'] = $sSQL;
    return grilla_generica_new($sSQL);
  }

  function kardex_total($desde,$hasta,$codigo,$CheqBod,$bodega){
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(C.Cliente,1,50) 
            FROM Trans_Kardex As TK, Clientes As C 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' _
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.Codigo_P <> '.'
            AND TK.Fecha BETWEEN '".$desde."' AND '".$hasta."'
            AND TK.Codigo_P = C.Codigo ";
    $this->db->datos($sql);
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(CS.Detalle,1,50) 
            FROM Trans_Kardex As TK, Catalogo_SubCtas As CS 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.Codigo_P <> '.' 
            AND TK.Fecha BETWEEN '".$desde."' AND '".$hasta."' 
            AND TK.Item = CS.Item 
            AND TK.Periodo = CS.Periodo 
            AND TK.Codigo_P = CS.Codigo ";
    $this->db->datos($sql);

    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(CS.Detalle,1,50) 
            FROM Trans_Kardex As TK, Catalogo_SubCtas As CS 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.CodigoL <> '.' 
            AND TK.Fecha BETWEEN '".$desde."' AND #'".$hasta."' 
            AND TK.Item = CS.Item 
            AND TK.Periodo = CS.Periodo 
            AND TK.CodigoL = CS.Codigo ";
    $this->db->datos($sql);
  
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = '.' 
            WHERE Centro_Costo IS NULL ";
    $this->db->datos($sql);

    $sql =  "SELECT TK.Codigo_Inv, CP.Producto, TK.CodBodega As Bodega, TK.Fecha,TK.Entrada, TK.Salida, TK.Existencia, 
            TK.Valor_Unitario, TK.Valor_Total, TK.Costo, TK.Total, TK.TP, TK.Numero As Comp_No, TK.TC, TK.Serie, 
            TK.Factura, C.CI_RUC, C.Cliente As Beneficiario, TK.Detalle, TK.Lote_No, CP.Unidad, TK.Cta_Inv, TK.Contra_Cta, TK.Centro_Costo, TK.Codigo_Barra 
            FROM Trans_Kardex As TK, Catalogo_Productos As CP, Clientes As C 
            WHERE TK.Fecha BETWEEN '".$desde."' AND '".$hasta."'
            AND TK.T = '".G_NORMAL."' 
            AND TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
    if ($codigo != "*") {
      $sql .= "AND TK.Codigo_Inv LIKE '".$codigo."%' ";
    }
    if ($CheqBod) {
      $sql .= "AND TK.CodBodega = '".$bodega."'";
    }
    $sql .=  "AND TK.Item = CP.Item 
            AND TK.Periodo = CP.Periodo 
            AND TK.Codigo_Inv = CP.Codigo_Inv 
            AND TK.Codigo_P = C.Codigo 
            ORDER BY TK.Codigo_Inv, TK.Fecha, TK.Entrada DESC, TK.Salida, TK.TP, TK.Numero, TK.ID ";
    $stmt = $this->db->datos($sql);
    $tabla = grilla_generica_new($sql,'Trans_Kardex','myTable','',false,false,false,1,1,1,100);
    return $tabla;
  }

  function CargaInicial($FechaIni, $FechaFin){
    $sSQL = "UPDATE Trans_Kardex " .
        "SET Codigo_Tra = '.' " .
        "WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' " .
        "AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
        "AND Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'";
    Ejecutar_SQL_SP($sSQL);

    $sSQL = "UPDATE Trans_Kardex " .
        "SET Centro_Costo = SUBSTRING(C.Cliente,1,50), Codigo_Tra = C.CI_RUC " .
        "FROM Trans_Kardex AS TK, Clientes AS C " .
        "WHERE TK.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
        "AND TK.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
        "AND TK.Codigo_P <> '.' " .
        "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
        "AND TK.Codigo_P = C.Codigo";
    Ejecutar_SQL_SP($sSQL);

    $sSQL = "UPDATE Trans_Kardex " .
        "SET Centro_Costo = SUBSTRING(CS.Detalle,1,50), Codigo_Tra = CS.Codigo " .
        "FROM Trans_Kardex AS TK, Catalogo_SubCtas AS CS " .
        "WHERE TK.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
        "AND TK.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
        "AND TK.Codigo_P <> '.' " .
        "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
        "AND TK.Item = CS.Item " .
        "AND TK.Periodo = CS.Periodo " .
        "AND TK.Codigo_P = CS.Codigo";
    Ejecutar_SQL_SP($sSQL);

    $sSQL = "UPDATE Trans_Kardex " .
        "SET Centro_Costo = SUBSTRING(CS.Detalle,1,50), Codigo_Tra = CS.Codigo " .
        "FROM Trans_Kardex AS TK, Catalogo_SubCtas AS CS " .
        "WHERE TK.Item = '" . $_SESSION['INGRESO']['item'] . "' " .
        "AND TK.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "' " .
        "AND TK.CodigoL <> '.' " .
        "AND TK.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "' " .
        "AND TK.Item = CS.Item " .
        "AND TK.Periodo = CS.Periodo " .
        "AND TK.CodigoL = CS.Codigo";
    Ejecutar_SQL_SP($sSQL);

    $sSQL = "UPDATE Trans_Kardex " .
        "SET Centro_Costo = '.' " .
        "WHERE Centro_Costo IS NULL";
    Ejecutar_SQL_SP($sSQL);
  }

  function Eliminar_Kardex_Cero($FechaIni, $FechaFin){
    $sSQL = "DELETE FROM Trans_Kardex
             WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
             AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
             AND Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
             AND Numero = 0";
    return $this->db->String_Sql($sSQL);
  }

  function DatosEtiquetaKardex($ID_Reg){
    $sSQL = "SELECT TK.Codigo_Barra, CP.Producto, CP.PVP
             FROM Trans_Kardex AS TK
             INNER JOIN Catalogo_Productos AS CP
               ON CP.Item = TK.Item
              AND CP.Periodo = TK.Periodo
              AND CP.Codigo_Inv = TK.Codigo_Inv
             WHERE TK.ID = " . intval($ID_Reg) . "
             AND TK.Item = '" . $_SESSION['INGRESO']['item'] . "'
             AND TK.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'";
    return $this->db->datos($sSQL);
  }
} 
?>