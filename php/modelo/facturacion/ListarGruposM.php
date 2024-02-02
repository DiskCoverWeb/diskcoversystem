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
        if ($parametros['PorDireccion'] === 'true') {
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
        if($_SESSION['INGRESO']['Mas_Grupos']){
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        if($parametros['CheqRangos'] <> 'false'){
            $sql .= " AND Grupo BETWEEN '" . $parametros['Codigo1'] . "' AND '" . $parametros['Codigo2'] . "'";
        }else{
            if($parametros['PorGrupo'] === 'true'){
                $titulo = "LISTADO DE CLIENTES (Grupo No. " . $parametros['DCCliente'] . ")";
                $sql .= " AND Grupo = '" . $parametros['DCCliente'] . "'";
            }else if($parametros['PorDireccion'] === 'true'){
                $titulo = "LISTADO DE CLIENTES (Direccion: " . $parametros['DCCliente'] . ")";
                $sql .= " AND Direccion = '" . $parametros['DCCliente'] . "'";
            }else{
                $titulo = "LISTADO DE CLIENTES";
            }
        }
        $sql .= "AND FA <> 0
                ORDER BY Grupo,Cliente";
        $AdoQuery = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Clientes', '', $titulo, false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoQuery' => $AdoQuery);

    }
}