<?php
include(dirname(__DIR__,2)."/db/db1.php");
@session_start();

class AsignarRol {

   private $conn;

   function __construct()
   {
      $this->conn = new db();
   }

   private function itemPeriodo(){
      return "Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
   }

   function grupos(){
      $sSQL = "SELECT * FROM Catalogo_Rol_Cuentas WHERE ".$this->itemPeriodo()." ORDER BY Grupo_Rol";
      return $this->conn->datos($sSQL);
   }

   function referencias($tipo, $sinPunto = false){
      $sSQL = "SELECT Codigo, Descripcion FROM Tabla_Referenciales_SRI
         WHERE Tipo_Referencia = '".$tipo."' ".($sinPunto ? "AND Descripcion <> '.' " : "")."
         ORDER BY ".($sinPunto ? "Descripcion" : "Codigo");
      return $this->conn->datos($sSQL);
   }

   function subCuentas(){
      $sSQL = "SELECT TC, Codigo, Detalle FROM Catalogo_SubCtas
         WHERE ".$this->itemPeriodo()."
         AND Agrupacion = 0
         AND TC IN ('CC','G','GC')
         ORDER BY Detalle, TC";
      return $this->conn->datos($sSQL);
   }

   function formasPago($tipo){
      $sSQL = "SELECT Codigo+' - '+Cuenta AS Cuentas, TC, Codigo, Cuenta FROM Catalogo_Cuentas
         WHERE ".$this->itemPeriodo()."
         AND Codigo < '3' AND DG = 'D' ";
      if($tipo == 'E'){
         $sSQL .= "AND TC = 'CJ' ";
      } else if($tipo == 'C' || $tipo == 'T'){
         $sSQL .= "AND TC = 'BA' ";
      } else {
         $sSQL .= "AND TC NOT IN ('CJ','BA','RI','RF') ";
      }
      $sSQL .= "ORDER BY Codigo";
      return $this->conn->datos($sSQL);
   }

   function iess($fechaYmd){
      $sSQL = "SELECT Codigo, Porc FROM Tabla_Por_ICE_IVA
         WHERE Codigo IN ('IESS_Per','IESS_Pat','IESS_ExtC','Sueldo_Bas','Canasta_Ba')
         AND '".$fechaYmd."' BETWEEN Fecha_Inicio AND Fecha_Final
         ORDER BY Codigo";
      return $this->conn->datos($sSQL);
   }

   function cliente($codigo){
      $sSQL = "SELECT Cliente, Codigo, Pais, Cod_Ejec FROM Clientes WHERE Codigo = '".$codigo."'";
      $datos = $this->conn->datos($sSQL);
      return count($datos) > 0 ? $datos[0] : null;
   }

   function empleado($codigo){
      $sSQL = "SELECT * FROM Catalogo_Rol_Pagos WHERE ".$this->itemPeriodo()." AND Codigo = '".$codigo."'";
      $datos = $this->conn->datos($sSQL);
      return count($datos) > 0 ? $datos[0] : null;
   }

   function acceso($codigo){
      $sSQL = "SELECT * FROM Accesos WHERE Codigo = '".$codigo."'";
      $datos = $this->conn->datos($sSQL);
      return count($datos) > 0 ? $datos[0] : null;
   }

   function duplicado($campo, $valor, $codigo){
      $sSQL = "SELECT Codigo FROM Catalogo_Rol_Pagos
         WHERE ".$this->itemPeriodo()."
         AND ".$campo." = '".$valor."'
         AND Codigo <> '".$codigo."'";
      return count($this->conn->datos($sSQL)) > 0;
   }

   function actualizarAcceso($codigo, $usuario, $clave, $nombre, $codEjec){
      $sSQL = "UPDATE Accesos SET Clave = '".$clave."', Usuario = '".$usuario."',
         Nombre_Completo = '".$nombre."', Cod_Ejec = '".$codEjec."'
         WHERE Codigo = '".$codigo."'";
      return $this->conn->String_Sql($sSQL);
   }
}
