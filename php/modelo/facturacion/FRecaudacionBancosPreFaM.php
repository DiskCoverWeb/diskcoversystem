<?php
require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

/*
    AUTOR DE RUTINA	: Leonardo Súñiga
    FECHA CREACION	: 03/01/2024
    FECHA MODIFICACION: 17/01/2024
    DESCIPCION : Clase que se encarga de manejar la conexión con la base de datos de la pantalla de recaudacion de bancos
*/


class FRecaudacionBancosPreFaM
{
    private $db;


    public function __construct()
    {

        $this->db = new db();

    }

    public function DCLinea()
    {
        $sql = "SELECT *
                FROM Catalogo_Lineas
                WHERE TL <> 0
                AND Fact NOT IN ('CP','NC','LC')
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function AdoProducto()
    {
        $sql = "SELECT * 
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND TC = 'P'
                ORDER BY Codigo_Inv";
        return $this->db->datos($sql);
    }

    public function DCBanco()
    {
        $sql = "SELECT *
                FROM Catalogo_Cuentas
                WHERE TC = 'BA'
                AND DG = 'D' 
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                ORDER BY Codigo";
        return $this->db->datos($sql);
    }

    public function DCGrupos()
    {
        $sql = "SELECT Grupo, Count(Grupo) As Cantidad
                FROM Clientes
                WHERE FA <> 0";
        if ($_SESSION['INGRESO']['Mas_Grupos']) {
            $sql .= " AND DirNumero = '" . $_SESSION['INGRESO']['item'] . "'";
        }
        $sql .= "GROUP BY Grupo
                ORDER BY Grupo";
        return $this->db->datos($sql);
    }

    public function DCEntidadBancaria()
    {
        $sql = "SELECT Descripcion, Abreviado, ID
                FROM Tabla_Referenciales_SRI
                WHERE Tipo_Referencia = 'BANCOS Y COOP'
                AND Abreviado <> '.'
                AND TPFA <> '0'
                ORDER BY Descripcion";
        return $this->db->datos($sql);
    }

    public function MBFechaI_LostFocus($parametros)
    {
        $sql = "SELECT * 
                FROM Catalogo_Lineas
                WHERE TL <> '0'
                AND '" . BuscarFecha($parametros['MBFechaI']) . "' <= Vencimiento
                AND Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Fact IN ('NV','FA')
                ORDER BY Codigo";
        return $this->db->datos($sql);

    }

    public function Command1_Click_Delete_AsientoF()
    {
        $sql = "DELETE * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function Command1_Click_Delete_TablaTemporal()
    {
        $sql = "DELETE * 
                FROM Tabla_Temporal
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Modulo = '" . $_SESSION['INGRESO']['modulo_'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function Command1_Click_Update_ClientesFacturacion()
    {
        $sql = "UPDATE Clientes_Facturacion
                SET X = '.'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'";
        Ejecutar_SQL_SP($sql);
    }

    public function AdoCliDB($CodigoCli)
    {
        $sql = "SELECT Codigo,Cliente,Grupo, CI_RUC
                FROM Clientes
                WHERE CI_RUC = '" . $CodigoCli . "'";
        return $this->db->datos($sql);
    }

    public function AdoAuxProducto($CodigoInv)
    {
        $sql = "SELECT Producto
                FROM Catalogo_Productos
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND Codigo_Inv = '" . $CodigoInv . "'
                AND TC = 'P'";
        return $this->db->datos($sql);
    }

    public function AdoAuxClientes_Facturacion($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope)
    {
        $sql = "SELECT * 
                FROM Clientes_Facturacion
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $CodigoCli . "'";
        if ($NoMeses > 0) {
            $sql .= " AND Num_Mes = '" . $NoMeses . "'";
        }
        if ($NoAnio > 0) {
            $sql .= " AND Periodo = '" . $NoAnio . "'";
        }
        if ($CodigoInv <> G_NINGUNO) {
            $sql .= " AND Codigo_Inv = '" . $CodigoInv . "'";
        }
        $sql .= "AND Fecha <= '" . BuscarFecha($FechaTope) . "'
                ORDER BY Periodo, Num_Mes";
        return $this->db->datos($sql);

    }

    public function AdoAuxClientes_FacturacionUpdate($CodigoCli, $NoMeses, $NoAnio, $CodigoInv, $FechaTope)
    {
        $sql = "UPDATE Clientes_Facturacion
                SET X = 'X'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $CodigoCli . "'";
        if ($NoMeses > 0) {
            $sql .= " AND Num_Mes = '" . $NoMeses . "'";
        }
        if ($NoAnio > 0) {
            $sql .= " AND Periodo = '" . $NoAnio . "'";
        }
        if ($CodigoInv <> G_NINGUNO) {
            $sql .= " AND Codigo_Inv = '" . $CodigoInv . "'";
        }
        $sql .= "AND Fecha <= '" . BuscarFecha($FechaTope) . "'";
        $this->db->String_Sql($sql);

    }

    public function AdoFactura()
    {
        $sql = "SELECT * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY FECHA,Codigo_Cliente,Numero";
        return $this->db->datos($sql);
    }

    public function AdoFacturaUpdate()
    {
        $sql = "UPDATE Asiento_F
                SET CodBod = 'X'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        $this->db->String_Sql($sql);
    }

    public function AdoAuxClientesFacturacion2($Codigo_Cliente, $CODIGO, $A_No, $HABIT)
    {
        $sql = "SELECT Codigo,Num_Mes,Periodo,SUM(Valor) AS TValor 
                FROM Clientes_Facturacion
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $Codigo_Cliente . "'
                AND Codigo_Inv = '" . $CODIGO . "'
                AND Num_Mes = '" . $A_No . "'
                AND Periodo <= '" . $HABIT . "'
                GROUP BY Codigo,Num_Mes,Periodo";
        return $this->db->datos($sql);
    }

    public function DeleteAsientoF()
    {
        $sql = "DELETE * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodBod = 'X'";
        Ejecutar_SQL_SP($sql);
    }

    public function AdoFactura2()
    {
        $sql = "SELECT * 
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY RUTA,Codigo_Cliente,FECHA,Numero";
        return $this->db->datos($sql);
    }

    public function AdoFacturaUpdate2($Factura_No)
    {
        $sql = "UPDATE Asiento_F
                SET Numero = '" . $Factura_No . "'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'";
        $this->db->String_Sql($sql);
    }

    public function DGFactura()
    {
        $sql = "SELECT FECHA,Numero As FACTURA,RUTA As BENEFICIARIO,CANT,CODIGO,PRODUCTO,TOTAL,COSTO As COMISION,
                HABIT As PERIODO,Mes,A_No As NO_MES,Codigo_Cliente,Serie,Autorizacion,Cta As Trans_No,
                CODIGO_L As Forma_P,TICKET As No_Cta,Cod_Ejec As Cod_Banco,CodigoU,Item
                FROM Asiento_F
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND CodigoU = '" . $_SESSION['INGRESO']['CodigoU'] . "'
                ORDER BY Numero";
        $AdoAsientoF = $this->db->datos($sql);
        $datos = grilla_generica_new($sql, 'Asiento_F', '', 'PREFACTURACION DEL DIA', false, false, false, 1, 1, 1, 100);
        return array('datos' => $datos, 'AdoAsientoF' => $AdoAsientoF);

    }


}
