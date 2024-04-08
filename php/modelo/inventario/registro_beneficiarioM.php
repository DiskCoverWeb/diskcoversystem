<?php

/** 
 * AUTOR DE RUTINA : Dallyana Vanegas
 * FECHA CREACION : 16/02/2024
 * FECHA MODIFICACION : 08/04/2024
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

    function LlenarTblPoblacion()
    {
        $sql = "SELECT CP.Proceso AS Poblacion,
                SUM(CASE WHEN C.Sexo = 'M' THEN 1 ELSE 0 END) AS Hombres,
                SUM(CASE WHEN C.Sexo = 'F' THEN 1 ELSE 0 END) AS Mujeres,
                COUNT(*) AS Total
                FROM Clientes AS C
                JOIN Clientes_Datos_Extras AS CD ON C.Codigo = CD.Codigo
                JOIN Catalogo_Proceso AS CP ON CD.Area = CP.Cmds
                WHERE CP.Cmds LIKE '91.%'
                GROUP BY CP.Proceso
                ORDER BY CP.Proceso";
        return $this->db->datos($sql);
    }

    function LlenarCalendario($valor)
    {
        $sql = "SELECT TOP 100 C.Actividad, C.Cliente, F.Envio_No,  F.Dia_Ent, F.Hora_Ent       
                    FROM Clientes AS C
                    LEFT JOIN Clientes_Datos_Extras AS F ON C.Codigo = F.Codigo
                    WHERE C.Cliente <> '.'
                    AND Actividad = '" . $valor . "'
                    ORDER BY Hora_Ent";
        return $this->db->datos($sql);
    }

    function ObtenerColor($valor)
    {
        if ($valor) {
            $sql = "SELECT " . Full_Fields('Catalogo_Proceso') . "
                FROM Catalogo_Proceso
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Cmds = '" . $valor . "'";
            $resultado = $this->db->datos($sql);
            if (!empty($resultado)) {
                return $resultado[0];
            } else {
                return 0;
            }
        }
    }

    function LlenarSelectSexo()
    {
        $sql = "SELECT Tipo_Referencia, Codigo, Descripcion
                FROM Tabla_Referenciales_SRI
                WHERE (Tipo_Referencia = 'SEXO')
                ORDER BY Tipo_Referencia, Descripcion";
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


    function LlenarSelectRucCliente($query)
    {
        $sql = "SELECT TOP 100 Cliente, CI_RUC, Codigo
                FROM Clientes
                WHERE Cliente <> '.'";

        if (!is_numeric($query)) {
            $sql .= " AND Cliente LIKE '%" . $query . "%'";
        } else {
            $sql .= " AND CI_RUC LIKE '%" . $query . "%'";
        }
        return $this->db->datos($sql);
    }

    function llenarCamposInfo($valor)
    {
        $sql = "SELECT Codigo, CodigoA, Representante, CI_RUC_R, Telefono_R, Contacto, Sexo,
                    Profesion, Email, Email2, Telefono, TelefonoT, Dia_Ent, 
                    Hora_Ent, Prov, Ciudad, Canton, Parroquia, Barrio,
                    Direccion, DireccionT,  Referencia, Calificacion, Actividad
                FROM  Clientes 
                WHERE Cliente <> '.'
                AND Codigo = '" . $valor . "'";
        $resultado = $this->db->datos($sql);
        if (!empty($resultado)) {
            return $resultado[0];
        } else {
            return 0;
        }
    }

    function llenarCamposInfoAdd($valor)
    {
        $sql = "SELECT CodigoA AS CodigoA2, Dia_Ent AS Dia_Ent2, Hora_Ent AS Hora_Ent2,
                    Envio_No, No_Soc, Area, Acreditacion, Tipo_Dato,
                    Cod_Fam, Evidencias, Observaciones, Item, Etapa_Procesal
                FROM  Clientes_Datos_Extras
                WHERE Codigo = '" . $valor . "'";
        $resultado = $this->db->datos($sql);
        if (!empty($resultado)) {
            return $resultado[0];
        } else {
            return 0;
        }
    }

    function sqlComunDonacion()
    {
        return "SELECT Codigo, Concepto, Picture
                FROM Catalogo_Lineas
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
                AND LEN(Fact) = 3";
    }

    function LlenarTipoDonacion($valor)
    {
        $sql = $this->sqlComunDonacion();

        if (!is_numeric($valor)) {
            $sql .= " AND Concepto LIKE '%" . $valor . "%'";
        }

        $sql .= " ORDER BY Fact";
        return $this->db->datos($sql);
    }

    function actualizarSelectDonacion($valor)
    {
        if ($valor) {
            $sql = $this->sqlComunDonacion();
            $sql .= " AND Codigo LIKE '%" . $valor . "%'";

            return $this->db->datos($sql);
        }
    }

    function LlenarSelects_Val($query, $valor, $valor2)
    {
        $sql = "SELECT " . Full_Fields('Catalogo_Proceso') . "
                    FROM Catalogo_Proceso
                    WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'";

        if (strlen($valor) > 2) {
            $sql .= " AND Cmds LIKE '" . $valor . "'";
        } else {
            $sql .= " AND Cmds LIKE '" . $valor . ".%'";
        }
        $sql .= (!is_numeric($query)) ? " AND Proceso LIKE '%" . $query . "%'" : " AND Cmds LIKE '%" . $query . "%'";

        $sql .= " ORDER BY Cmds";

        if ($valor2) {
            return $this->actualizarSelectDonacion($valor);
        }
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
                Sexo = '" . $parametros['Sexo'] . "', 
                Email = '" . $parametros['Email'] . "', 
                Email2 = '" . $parametros['Email2'] . "',                 
                Telefono = '" . $parametros['Telefono'] . "', 
                TelefonoT = '" . $parametros['TelefonoT'] . "', 
                Prov = '" . $parametros['Provincia'] . "', 
                Ciudad = '" . $parametros['Ciudad'] . "', 
                Canton = '" . $parametros['Canton'] . "', 
                Parroquia = '" . $parametros['Parroquia'] . "', 
                Barrio = '" . $parametros['Barrio'] . "', 
                Direccion = '" . $parametros['CalleP'] . "', 
                DireccionT = '" . $parametros['CalleS'] . "', 
                Referencia = '" . $parametros['Referencia'] . "'
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
                Etapa_Procesal = '" . $parametros['Comentario'] . "',
                No_Soc = '" . $parametros['No_Soc'] . "', 
                Area = '" . $parametros['Area'] . "',
                Acreditacion = '" . $parametros['Acreditacion'] . "', 
                Tipo_Dato = '" . $parametros['Tipo_Dato'] . "', 
                Cod_Fam = '" . $parametros['Cod_Fam'] . "', 
                Evidencias = '" . $parametros['NombreArchivo'] . "', 
                Observaciones = '" . $parametros['Observaciones'] . "'
                WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
                AND Codigo = '" . $parametros['Codigo'] . "'";

                //print_r($sql);
        return $this->db->datos($sql);
    }

    function CrearClienteDatosExtra($parametros)
    {
        $sql2 = "INSERT INTO Clientes_Datos_Extras (Codigo, CodigoA, Dia_Ent, Hora_Ent, 
        Envio_No, Etapa_Procesal, No_Soc, Area, 
        Acreditacion, Tipo_Dato, Cod_Fam, Evidencias, Observaciones, Item) 
        VALUES ('" . $parametros['Codigo'] . "', 
                '" . $parametros['CodigoA2'] . "', 
                '" . $parametros['Dia_Ent2'] . "', 
                '" . $parametros['Hora_Registro'] . "', 
                '" . $parametros['Envio_No'] . "', 
                '" . $parametros['Comentario'] . "', 
                '" . $parametros['No_Soc'] . "', 
                '" . $parametros['Area'] . "', 
                '" . $parametros['Acreditacion'] . "', 
                '" . $parametros['Tipo_Dato'] . "', 
                '" . $parametros['Cod_Fam'] . "', 
                '" . $parametros['NombreArchivo'] . "', 
                '" . $parametros['Observaciones'] . "',
                '" . $_SESSION['INGRESO']['item'] . "')";
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

        Eliminar_Nulos_SP("Clientes");
        Eliminar_Nulos_SP("Clientes_Datos_Extras");
        return ['dato1' => $sql1, 'dato2' => $sql2];
    }
}

?>