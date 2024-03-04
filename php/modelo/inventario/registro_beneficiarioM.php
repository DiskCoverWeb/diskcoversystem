<?php

/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * FECHA CREACION : 16/02/2024
 * FECHA MODIFICACION : 29/02/2024
 * DESCIPCION : Clase modelo para llenar campos y guardar registros de Agencia
 */

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
        $sql = "SELECT TOP 100 Cliente, CI_RUC, Codigo, CodigoA, Representante, 
                CI_RUC_R, Telefono_R, Contacto, Profesion, Direccion, 
                Email, Email2, Lugar_Trabajo, Telefono, TelefonoT, Fecha_Cad --, Hora_Ent
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
                 Actividad = '" . $parametros['Actividad'] . "',
                 CodigoA = '" . $parametros['CodigoA'] . "', 
                 Representante = '" . $parametros['Representante'] . "', 
                 CI_RUC_R = '" . $parametros['CI_RUC_R'] . "', 
                 Telefono_R = '" . $parametros['Telefono_R'] . "', 
                 Contacto = '" . $parametros['Contacto'] . "',   
                 Profesion = '" . $parametros['Profesion'] . "', 
                 -- Hora_Ent = '" . $parametros['Hora_Ent'] . "', 
                 -- Fecha_Cad = '" . $parametros['Fecha_Cad'] . "', 
                 Direccion = '" . $parametros['Direccion'] . "', 
                 Email = '" . $parametros['Email'] . "', 
                 Email2 = '" . $parametros['Email2'] . "', 
                 Lugar_Trabajo = '" . $parametros['Lugar_Trabajo'] . "', 
                 Telefono = '" . $parametros['Telefono'] . "', 
                 TelefonoT = '" . $parametros['TelefonoT'] . "' 
                 WHERE CI_RUC = '" . $parametros['CI_RUC'] . "'";

        $envioNo = isset($parametros['Envio_No']) ? $parametros['Envio_No'] : '';
        $noSoc = isset($parametros['No_Soc']) ? $parametros['No_Soc'] : '';

        $sql2 = "INSERT INTO Clientes_Datos_Extras (Codigo, CodigoA, Fecha_Registro, --Hora_Ent, 
                     Envio_No, No_Soc, Area, Acreditacion, Tipo_Dato, Cod_Fam, Evidencias, Observaciones, Item) 
                     VALUES ('" . $parametros['Codigo'] . "', 
                             '" . $parametros['CodigoA2'] . "', 
                             '" . $parametros['Fecha_Registro'] . "', 
                             -- '" . $parametros['Hora_Ent'] . "', 
                             '" . $envioNo . "', '" . $noSoc . "', 
                             '" . $parametros['Area'] . "', 
                             '" . $parametros['Acreditacion'] . "', 
                             '" . $parametros['Tipo_Dato'] . "', 
                             '" . $parametros['Cod_Fam'] . "', 
                             '" . $parametros['NombreArchivo'] . "', 
                             '" . $parametros['Observaciones'] . "',
                             '" . $_SESSION['INGRESO']['item'] . "')";
        
        $dato1 = $this->db->datos($sql);
        $dato2 = $this->db->datos($sql2);
        Eliminar_Nulos_SP("Clientes_Datos_Extras");
        return array('dato1' => $dato1, 'dato2' => $dato2);
    }
}

?>