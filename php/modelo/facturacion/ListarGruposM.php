<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

class ListarGruposM
{
    private $db;

    public function __construct()
    {

        $this->db = new db();

    }

    public function DCGrupos()
    {
        $sql = "SELECT Grupo
                FROM Clientes
                WHERE FA <> 0";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        $sql .= "GROUP BY Grupo
                ORDER BY Grupo";
        return $this->db->datos($sql);
    }

    public function DCTipoPago()
    {
        $sql = "SELECT (Codigo + ' ' + Descripcion) As CTipoPago
                FROM Tabla_Referenciales_SRI
                WHERE Tipo_Referencia = 'FORMA DE PAGO'
                AND Codigo IN ('01','16','17','18','19','20','21')
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function DCProductos()
    {
        $sql = "SELECT *
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P'
                AND LEN(Cta_Inventario) = 1 
                AND INV <> 0
                ORDER BY Producto";
        return $this->db->datos($sql);
    }

    public function DCLinea($parametros)
    {
        $sql = "SELECT *
                FROM Catalogo_Lineas
                WHERE TL <> 0 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact = '" . $parametros['TipoFactura'] . "'
                AND Fecha <= '" . BuscarFecha($parametros['MBFechaI']) . "'
                AND Vencimiento >= '" . BuscarFecha($parametros['MBFechaI']) . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function MBFecha_LostFocus($parametros)
    {
        $sql = "SELECT *
                FROM Catalogo_Lineas
                WHERE TL <> 0 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact = '" . $parametros['TipoFactura'] . "'
                AND Fecha <= '" . BuscarFecha($parametros['MBFecha']) . "'
                AND Vencimiento >= '" . BuscarFecha($parametros['MBFecha']) . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function Listar_Grupo($parametros)
    {
        if ($parametros['PorDireccion']) {
            $sql = "SELECT Direccion
                    FROM Clientes
                    WHERE FA <> 0";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
            }
            $sql .= "GROUP BY Direccion
                    ORDER BY Direccion";
            return $this->db->datos($sql);
        } else {
            $sql = "SELECT Grupo
                    FROM Clientes
                    WHERE FA <> 0";
            if ($_SESSION['INGRESO']['Mas_Grupos']) {
                $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
            }
            $sql .= "GROUP BY Grupo
                    ORDER BY Grupo";
            return $this->db->datos($sql);
        }
    }

    public function Listar_Clientes_Grupo($parametros)
    {
        $titulo = "";
        $sql = "SELECT T,Cliente,Grupo,Direccion,Codigo,CI_RUC,Email,Email2,Fecha_N,Representante,TD_R, CI_RUC_R,DireccionT,Telefono_R,TelefonoT,EmailR,Saldo_Pendiente
                FROM Clientes
                WHERE Cliente <> '.' ";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        } else {
            if ($parametros['PorGrupo']) {
                $titulo = "LISTADO DE CLIENTES (Grupo No. " . $parametros['DCCliente'] . ")";
                $sql .= " AND Grupo = '" . $parametros['DCCliente'] . "'";
            } else if ($parametros['PorDireccion']) {
                $titulo = "LISTADO DE CLIENTES (Direccion: " . $parametros['DCCliente'] . ")";
                $sql .= " AND Direccion = '" . $parametros['DCCliente'] . "'";
            } else {
                $titulo = "LISTADO DE CLIENTES";
            }
        }
        $sql .= "AND FA <> 0
                ORDER BY Grupo,Cliente";
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes', '', $titulo, false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Listar_Deuda_Por_Api($parametros, $FechaTope)
    {
        $sql = "UPDATE Clientes
                SET Saldo_Pendiente = 0, Credito = 0
                WHERE Codigo <> '.'";
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Saldo_Pendiente = (SELECT ROUND(SUM(CF.Valor-CF.Descuento-CF.Descuento2),2,0)
                                       FROM Clientes_Facturacion As CF
                                       WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                                       AND CF.Fecha <= '" . $FechaTope . "'
                                       AND CF.Codigo = Clientes.Codigo)
                WHERE Codigo <> '.'";
        if ($parametros['CheqRangos']) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Fecha_Cad = (SELECT MIN(CF.Fecha)
                                 FROM Clientes_Facturacion As CF
                                 WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                                 AND CF.Fecha <= '" . $FechaTope . "'
                                 AND CF.Codigo = Clientes.Codigo)
                WHERE Codigo <> '.'";
        if ($parametros['CheqRangos']) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Saldo_Pendiente = 0
                WHERE Saldo_Pendiente IS NULL";
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Fecha_Cad = '" . $FechaTope . "'
                WHERE Fecha_Cad IS NULL";
        Ejecutar_SQL_SP($sql);

        $sql = "UPDATE Clientes
                SET Credito = DATEDIFF(day,Fecha_Cad,'" . $FechaTope . "')
                WHERE Codigo <> '.'";
        Ejecutar_SQL_SP($sql);

        $sql = "SELECT Grupo, Cliente As Estudiante, CI_RUC As Cedula, Saldo_Pendiente, Credito As Dias_Mora, EmailR, Codigo
                FROM Clientes
                WHERE FA <> 0";
        if ($parametros['CheqRangos']) {
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        $sql .= "ORDER BY Grupo, Cliente";
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes', '', 'LISTADO DE CLIENTES', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Pensiones_Mensuales_Anio($ListaCampos)
    {
        $sql = "SELECT " . $ListaCampos . " 
                FROM Reporte_CxC_Cuotas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY GrupoNo,Cliente";
        //print_r($sql);die();
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Reporte_CxC_Cuotas', '', 'PENSIONES MENSUALES DEL AÑO', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Listado_Becados($parametros, $FechaIni, $FechaFin)
    {
        $sql = "SELECT C.Cliente As Estudiantes,C.Grupo,CF.Mes,CF.Valor,CF.Descuento,CF.Descuento2,(CF.Valor-(CF.Descuento+CF.Descuento2)) As Total_Pagar,(((CF.Descuento+CF.Descuento2)/CF.Valor)*100) As Porc
                FROM Clientes As C, Clientes_Facturacion As CF
                WHERE CF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'
                AND (CF.Descuento+CF.Descuento2) <> 0";
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND C.Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        } else {
            if ($parametros['PorGrupo']) {
                $sql .= "AND C.Grupo = '" . $parametros['DCCliente'] . "'";
            } else if ($parametros['PorDireccion']) {
                $sql .= "AND C.Direccion = '" . $parametros['DCCliente'] . "'";
            }
        }
        $sql .= "AND CF.Codigo = C.Codigo
                 ORDER BY C.Grupo,C.Cliente,CF.Num_Mes";
        //print_r($sql);die();
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes_Facturacion', '', 'LISTADO DE BECADOS', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Nomina_Alumnos($parametros)
    {
        $sql = "SELECT C.Cliente As Estudiantes,' ' As T_1,' ' As T_2,' ' As T_3,' ' As T_4,' ' As T_5,C.Grupo,C.Direccion,C.Email,Count(DF.Codigo) As No_Facturas
                FROM Clientes AS C,Detalle_Factura As DF
                WHERE C.Cliente <> '.'";
        if ($parametros['PorGrupo']) {
            $sql .= "AND C.Grupo = '" . $parametros['DCCliente'] . "'";
        } elseif ($parametros['PorDireccion']) {
            $sql .= "AND C.Direccion = '" . $parametros['DCCliente'] . "'";
        }
        if ($parametros['CheqRangos'] <> 0) {
            $sql .= "AND C.Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }
        /*TODO: Ver donde se define Codigo3
        if($parametros['DCProductosVisible']){
            $sql .= "AND DF.Codigo ="
        }*/
        if ($parametros['OpcActivos']) {
            $sql .= "AND C.T = 'N'";
        } else {
            $sql .= "AND C.T <> 'N'";
        }
        $sql .= "AND C.FA <> 0
                 AND DF.T <> '" . G_ANULADO . "'
                 AND DF.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                 AND DF.Item = '" . $_SESSION['INGRESO']['item'] . "'
                 AND C.Codigo = DF.CodigoC
                 GROUP BY C.Grupo,C.Cliente,C.Direccion,C.Email
                 ORDER BY C.Grupo,C.Cliente";
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes', '', 'NOMINA DE ALUMNOS', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Resumen_Pensiones_Mes($parametros, $FechaIni, $FechaFin)
    {
        $sql = "SELECT CF.Periodo,COUNT(CP.Producto) AS Cant,CF.GrupoNo,CP.Producto,SUM(CF.Valor-(CF.Descuento+CF.Descuento2)) As Total
                FROM Clientes_Facturacion As CF,Catalogo_Productos As CP
                WHERE CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND CP.Item = '" . $_SESSION['INGRESO']['item'] . "'";
        if (date('m', strtotime($parametros['MBFechaI'])) == date('m', strtotime($parametros['MBFechaF']))) {
            $sql .= "AND CF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'";
        } else {
            $sql .= "AND CF.Fecha <= '" . $FechaFin . "'";
        }
        $sql .= "AND CF.GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'
                 AND CF.Codigo_Inv = CP.Codigo_Inv
                 AND CF.Item = CP.Item
                 GROUP BY CF.Periodo,CF.GrupoNo,CP.Producto
                 UNION
                 SELECT 'x' As Periodo,COUNT(CP.Producto) AS Cant,' ==> ' As GrupoNo,'Total por Cobrar' As Producto,SUM(CF.Valor-CF.Descuento) As Total
                 FROM Clientes_Facturacion As CF,Catalogo_Productos As CP
                 WHERE CP.Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                 AND CP.Item = '" . $_SESSION['INGRESO']['item'] . "'";
        if (date('m', strtotime($parametros['MBFechaI'])) == date('m', strtotime($parametros['MBFechaF']))) {
            $sql .= "AND CF.Fecha BETWEEN '" . $FechaIni . "' AND '" . $FechaFin . "'";
        } else {
            $sql .= "AND CF.Fecha <= '" . $FechaFin . "'";
        }
        $sql .= "AND CF.GrupoNo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'
                AND CF.Codigo_Inv = CP.Codigo_Inv
                AND CF.Item = CP.Item
                ORDER BY CF.Periodo,CF.GrupoNo,CP.Producto";
        //print_r($sql);die();
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes_Facturacion', '', 'RESUMEN DE PENSIONES DEL MES', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);
    }

    public function Command5_Click($parametros, $ListaDeCampos){
        $sql = "UPDATE Reporte_CxC_Cuotas
                SET E = 0
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        Ejecutar_SQL_SP($sql);
        
        for($i = 0; $i < count($parametros['LstClientes']); $i++){
            $NombreCliente = $parametros['LstClientes'][$i]['Cliente'];
            $sql = "UPDATE Reporte_CxC_Cuotas
                    SET E = 1
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                    AND Cliente = '" . $NombreCliente . "'";
            Ejecutar_SQL_SP($sql);
        }

        $sql = "SELECT " . $ListaDeCampos . ", C.Representante, C.CI_RUC, C.Email, C.EmailR, C.Cliente 
                FROM Reporte_CxC_Cuotas As RCC, Clientes As C 
                WHERE RCC.Item = '" . $_SESSION['INGRESO']['item'] . "' 
                AND RCC.CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "' 
                AND RCC.E <> 0 
                AND RCC.Codigo = C.Codigo 
                ORDER BY RCC.GrupoNo, RCC.Cliente";
        $AdoAux = $this->db->datos($sql);
        return $AdoAux;
    }
}