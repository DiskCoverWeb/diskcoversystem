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
        $sql = "SELECT TOP 100 Cliente, CI_RUC FROM Clientes WHERE Cliente <> '.'"; 

        if (!empty($query)) {
            if (!is_numeric($query)) {
                $sql .= " AND Cliente LIKE '%" . $query . "%'"; 
            } else {
                $sql .= " AND CI_RUC LIKE '" . $query . "%'";
            }
        }
        return $this->db->datos($sql);
    }

    function seleccionarClienteConRUCVisc($RUC, $Cliente) {
        //print_r($Cliente);
        if ($RUC) {
            $sql = "SELECT Cliente, CodigoA, Representante, CI_RUC_R, Telefono_R, Contacto, Profesion, Direccion, Email, Email2, Lugar_Trabajo, Telefono, TelefonoT FROM Clientes WHERE CI_RUC = '" . $RUC . "'";
        } elseif ($Cliente) {
            $sql = "SELECT CI_RUC, CodigoA, Representante, CI_RUC_R, Telefono_R, Contacto, Profesion, Direccion, Email, Email2, Lugar_Trabajo, Telefono, TelefonoT FROM Clientes WHERE Cliente = '" . $Cliente . "'";
        } else {
            $sql = "";
        }
        //print_r($sql);
        return $this->db->datos($sql);
    }
    
}

?>