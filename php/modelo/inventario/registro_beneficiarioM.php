<?php

/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * FECHA CREACION : 16/02/2024
 * FECHA MODIFICACION : 11/03/2024
 * DESCIPCION : Clase modelo para llenar campos y guardar registros de Agencia
 */

include (dirname(__DIR__, 2) . '/funciones/funciones.php');
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
        $sql = "SELECT Nivel, TP, Proceso, Cmds, Picture
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                    AND Cmds LIKE '" . $valor . ".%'
                    ORDER BY Cmds";
        return $this->db->datos($sql);
    }

    function LlenarSelectDiaEntrega()
    {
        $sql = "SELECT Dia_Mes, Dia_Mes_C, Zip, ID
                FROM Tabla_Dias_Meses
                WHERE (Tipo = 'D')
                ORDER BY No_D_M ";

        return $this->db->datos($sql);
    }

    function LlenarDatosCliente($query)
    {
        $sql = "SELECT TOP 100
                C.Cliente, C.CI_RUC, C.Codigo, C.CodigoA, C.Representante,
                C.CI_RUC_R, C.Telefono_R, C.Contacto, C.Profesion, C.Direccion,
                C.Email, C.Email2, C.Lugar_Trabajo, C.Telefono, C.TelefonoT,
                C.Dia_Ent, C.Hora_Ent, C.Calificacion, C.Actividad,
                F.CodigoA AS CodigoA2, F.Dia_Ent AS Dia_Ent2, F.Hora_Ent AS Hora_Ent2,
                F.Envio_No, F.No_Soc, F.Area, F.Acreditacion, F.Tipo_Dato,
                F.Cod_Fam, F.Evidencias, F.Observaciones, F.Item
            FROM
                Clientes AS C
            LEFT JOIN
                Clientes_Datos_Extras AS F ON C.Codigo = F.Codigo
            WHERE
                C.Cliente <> '.'";

        if (!is_numeric($query)) {
            $sql .= " AND Cliente LIKE '%" . $query . "%'";
        } else {
            $sql .= " AND CI_RUC LIKE '%" . $query . "%'";
        }
        return $this->db->datos($sql);
    }

    function LlenarTipoDonacion($query)
    {
        $sql = "SELECT Codigo, Concepto
                FROM Catalogo_Lineas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND LEN(Fact) = 3";
        if (!is_numeric($query)) {
            $sql .= " AND Concepto LIKE '%" . $query . "%'";
        } else {
            $sql .= " AND Codigo LIKE '%" . $query . "%'";
        }
        $sql .= "ORDER BY Fact";

        return $this->db->datos($sql);
    }

    function ActualizarClientes($parametros)
    {
        $sql = "UPDATE Clientes SET
                Actividad = '" . $parametros['Actividad'] . "',
                Calificacion = '" . $parametros['Calificacion'] . "',
                CodigoA = '" . $parametros['CodigoA'] . "', 
                Representante = '" . $parametros['Representante'] . "', 
                CI_RUC_R = '" . $parametros['CI_RUC_R'] . "', 
                Telefono_R = '" . $parametros['Telefono_R'] . "', 
                Contacto = '" . $parametros['Contacto'] . "',   
                Profesion = '" . $parametros['Profesion'] . "', 
                Hora_Ent = '" . $parametros['Hora_Ent'] . "', 
                Dia_Ent = '" . $parametros['Dia_Ent'] . "', 
                Direccion = '" . $parametros['Direccion'] . "', 
                Email = '" . $parametros['Email'] . "', 
                Email2 = '" . $parametros['Email2'] . "', 
                Lugar_Trabajo = '" . $parametros['Lugar_Trabajo'] . "', 
                Telefono = '" . $parametros['Telefono'] . "', 
                TelefonoT = '" . $parametros['TelefonoT'] . "' 
                WHERE CI_RUC = '" . $parametros['CI_RUC'] . "'";

        return $this->db->datos($sql);
    }

    function ActualizarClientesDatosExtra($parametros)
    {
        $sql = "UPDATE Clientes_Datos_Extras SET
                CodigoA = '" . $parametros['CodigoA2'] . "', 
                Dia_Ent = '" . $parametros['Dia_Ent2'] . "', 
                Hora_Ent = '" . $parametros['Hora_Registro'] . "', 
                Envio_No = '" . $parametros['Envio_No'] . "', 
                No_Soc = '" . $parametros['No_Soc'] . "', 
                Area = '" . $parametros['Area'] . "', 
                Acreditacion = '" . $parametros['Acreditacion'] . "', 
                Tipo_Dato = '" . $parametros['Tipo_Dato'] . "', 
                Cod_Fam = '" . $parametros['Cod_Fam'] . "', 
                Evidencias = '" . $parametros['NombreArchivo'] . "', 
                Observaciones = '" . $parametros['Observaciones'] . "'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $parametros['Codigo'] . "'";

        return $this->db->datos($sql);
    }

    function CrearClienteDatosExtra($parametros)
    {
        $sql2 = "INSERT INTO Clientes_Datos_Extras (Codigo, CodigoA, Dia_Ent, Hora_Ent, 
        Envio_No, No_Soc, Area, Acreditacion, Tipo_Dato, Cod_Fam, Evidencias, Observaciones, Item) 
        VALUES ('" . $parametros['Codigo'] . "', 
                '" . $parametros['CodigoA2'] . "', 
                '" . $parametros['Dia_Ent2'] . "', 
                '" . $parametros['Hora_Registro'] . "', 
                '" . $parametros['Envio_No'] . "', 
                '" . $parametros['No_Soc'] . "', 
                '" . $parametros['Area'] . "', 
                '" . $parametros['Acreditacion'] . "', 
                '" . $parametros['Tipo_Dato'] . "', 
                '" . $parametros['Cod_Fam'] . "', 
                '" . $parametros['NombreArchivo'] . "', 
                '" . $parametros['Observaciones'] . "',
                '" . $_SESSION['INGRESO']['item'] . "')";

        Eliminar_Nulos_SP("Clientes_Datos_Extras");
        return $this->db->datos($sql2);
    }


    function guardarAsignacion($parametros)
    {
        $sql = "SELECT COUNT(*) AS count FROM Clientes_Datos_Extras WHERE Codigo = '" . $parametros['Codigo'] . "'";
        $result = $this->db->datos($sql);
        
        if ($result[0]['count'] > 0) {
            $sql1 = $this->ActualizarClientes($parametros);
            $sql2 = $this->ActualizarClientesDatosExtra($parametros);
        } else {
            $sql1 = $this->ActualizarClientes($parametros);
            $sql2 = $this->CrearClienteDatosExtra($parametros);
        }

        return array('dato1' => $sql1, 'dato2' => $sql2);
    }
}

?>