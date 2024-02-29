<?php

require_once(dirname(__DIR__, 2) . "/db/db1.php");
require_once(dirname(__DIR__, 2) . "/funciones/funciones.php");

class asignacion_osM
{

    private $db;

    public function __construct()
    {

        $this->db = new db();

    }

    public function tipoBeneficiario($query = false): array
    {
        $sql = "SELECT TOP 100 C.Codigo, C.CodigoA, C.Cliente, C.CI_RUC, CD.Fecha_Registro, CD.Envio_No, 
                CD.CodigoA as CodigoACD, CD.Beneficiario,
                CD.No_Soc, CD.Area, CD.Acreditacion, CD.Tipo, CD.Cod_Fam, CD.Salario, CD.Descuento, 
                CD.Evidencias, CD.Item 
                FROM Clientes as C
                INNER JOIN Clientes_Datos_Extras as CD ON C.Codigo = CD.Codigo
                WHERE CD.Item = '001'";
        if (!is_numeric($query)) {
            $sql .= " AND C.Cliente LIKE '%" . $query . "%'";
        } else {
            $sql .= " AND C.CI_RUC LIKE '%" . $query . "%'";
        }
        $sql .= " ORDER BY C.Cliente";
        try {
            return $this->db->datos($sql);
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }



}