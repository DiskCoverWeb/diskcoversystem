<?php
include(dirname(__DIR__,2)."/db/db1.php");
@session_start();

class CatalogoCuentasRol {

   private $conn;
   private $campos = [
      'Cta_Sueldo', 'Cta_Horas_Ext', 'Cta_Antiguedad', 'Cta_Diferencia', 'Cta_Vacacion', 'Cta_Quincena',
      'Cta_Aporte_Patronal_G', 'Cta_IESS_Personal', 'Cta_IESS_Patronal',
      'Cta_Decimo_Tercer_G', 'Cta_Decimo_Tercer_P', 'Cta_Decimo_Cuarto_G', 'Cta_Decimo_Cuarto_P',
      'Cta_Fondo_Reserva_G', 'Cta_Fondo_Reserva_P', 'Cta_Vacaciones_G', 'Cta_Vacaciones_P',
      'Cta_Per_Efermedad', 'Cta_Ext_Conyugue_P', 'Cta_Per_Maternidad',
   ];

   function __construct()
   {
      $this->conn = new db();
   }

   function getCampos(){
      return $this->campos;
   }

   function listarGrupos(){
      $sSQL = "SELECT Grupo_Rol as id, Grupo_Rol as text
         FROM Catalogo_Rol_Cuentas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         ORDER BY Grupo_Rol";
      return $this->conn->datos($sSQL);
   }

   function obtenerGrupo($grupoRol){
      $sSQL = "SELECT * FROM Catalogo_Rol_Cuentas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Grupo_Rol = '".$grupoRol."'";
      $datos = $this->conn->datos($sSQL);
      return count($datos) > 0 ? $datos[0] : null;
   }

   function existeGrupo($grupoRol){
      $sSQL = "SELECT Grupo_Rol FROM Catalogo_Rol_Cuentas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Grupo_Rol = '".$grupoRol."'";
      $datos = $this->conn->datos($sSQL);
      return count($datos) > 0;
   }

   function guardarGrupo($grupoRol, $cuentas){
      $existe = $this->existeGrupo($grupoRol);

      $sets = [];
      foreach($this->campos as $campo){
         $valor = isset($cuentas[$campo]) ? $cuentas[$campo] : '0';
         $sets[] = $campo." = '".$valor."'";
      }

      if($existe){
         $sSQL = "UPDATE Catalogo_Rol_Cuentas SET ".implode(', ', $sets)."
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND Grupo_Rol = '".$grupoRol."'";
      } else {
         $columnas = array_merge(['Item', 'Periodo', 'Grupo_Rol', 'X'], $this->campos);
         $valores = array_merge(
            ["'".$_SESSION['INGRESO']['item']."'", "'".$_SESSION['INGRESO']['periodo']."'", "'".$grupoRol."'", "'.'"],
            array_map(function($campo) use ($cuentas){
               return "'".(isset($cuentas[$campo]) ? $cuentas[$campo] : '0')."'";
            }, $this->campos)
         );
         $sSQL = "INSERT INTO Catalogo_Rol_Cuentas (".implode(', ', $columnas).")
            VALUES (".implode(', ', $valores).")";
      }
      return $this->conn->String_Sql($sSQL);
   }

   function tieneEmpleadosVinculados($grupoRol){
      $sSQL = "SELECT Codigo FROM Catalogo_Rol_Pagos
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Grupo_Rol = '".$grupoRol."'";
      $datos = $this->conn->datos($sSQL);
      return count($datos) > 0;
   }

   function eliminarGrupo($grupoRol){
      if($this->tieneEmpleadosVinculados($grupoRol)){
         return -2;
      }
      $sSQL = "DELETE FROM Catalogo_Rol_Cuentas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Grupo_Rol = '".$grupoRol."'";
      return $this->conn->String_Sql($sSQL);
   }
}
