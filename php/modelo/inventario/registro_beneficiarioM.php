<?php
include(dirname(__DIR__, 2) . '/funciones/funciones.php');
@session_start();

class registro_beneficiarioM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function LlenarSelect($valor)
    {
        $sql = "SELECT Nivel, TP, Proceso, Cmds
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND Cmds LIKE '" . $valor . "%'
                    ORDER BY Cmds";
        return $this->db->datos($sql);
    }

    function LlenarDatosCliente($query)
    {
        /*Hora_Ent*/
        $sql = "SELECT TOP 100 Cliente, CI_RUC, Codigo, CodigoA, Representante, 
                CI_RUC_R, Telefono_R, Contacto, Profesion, Direccion, 
                Email, Email2, Lugar_Trabajo, Telefono, TelefonoT, Fecha_Cad
                FROM Clientes 
                WHERE Cliente <> '.'  ";
        if (!is_numeric($query)) {
            $sql .= " AND Cliente LIKE '%" . $query . "%'";
        } else {
            $sql .= " AND CI_RUC LIKE '%" . $query . "%'";
        }

        return $this->db->datos($sql);
    }

    function guardarAsignacion($parametros)
    {
        $sql = "UPDATE Clientes SET
                CodigoA = '" . $parametros['CodigoA'] . "', 
                Representante = '" . $parametros['Representante'] . "', 
                CI_RUC_R = '" . $parametros['CI_RUC_R'] . "', 
                Telefono_R = '" . $parametros['Telefono_R'] . "', 
                Contacto = '" . $parametros['Contacto'] . "', 
                Profesion = '" . $parametros['Profesion'] . "', 
                Fecha_Cad = '" . $parametros['Fecha_Cad'] . "', 
                Hora_Ent = '" . $parametros['Hora_Ent'] . "', 
                Direccion = '" . $parametros['Direccion'] . "', 
                Email = '" . $parametros['Email'] . "', 
                Email2 = '" . $parametros['Email2'] . "', 
                Lugar_Trabajo = '" . $parametros['Lugar_Trabajo'] . "', 
                Telefono = '" . $parametros['Telefono'] . "', 
                TelefonoT = '" . $parametros['TelefonoT'] . "' ";
        
        //$sql2 
        return $this->db->datos($sql);

    }

}

?>