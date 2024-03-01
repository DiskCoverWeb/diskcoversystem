<?php

require_once(dirname(__DIR__, 2) . "/modelo/facturacion/FPensionesM.php");

$controlador = new FPensionesC();

if (isset($_GET['DCInv'])) {
    echo json_encode($controlador->DCInv());
}

if (isset($_GET['ExistenRubros'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Existen_Rubros($parametros));
}

if (isset($_GET['InsertarPensiones'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Insertar_Pensiones($parametros));
}

if (isset($_GET['Tipo_Cambio_Valor'])) {
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->Tipo_Cambio_Valor($parametros));
}

//TODO: Agregar le isset para eliminar pensiones, no agregarlo hasta tener la clave de supervisor.

if (isset($_GET['Copiar_Mes'])) {
    echo json_encode($controlador->Copiar_Mes());
}

class FPensionesC
{

    private $modelo;

    public function __construct()
    {
        $this->modelo = new FPensionesM();
    }

    public function Copiar_Mes(){
        try{
            $AdoAux = $this->modelo->Copiar_Mes();
            if(count($AdoAux) > 0 ){
                return array("res" => "1", "datos" => $AdoAux);
            }else{
                throw new Exception("No se encontraron datos");
            }
        }catch(Exception $e){
            return array("res" => "0", "msj" => "No se encontraron datos", 'error' => $e->getMessage());
        }
    }

    public function Tipo_Cambio_Valor($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            $this->modelo->Tipo_Cambio_Valor($parametros, $Mifecha);
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al cambiar el valor", 'error' => $e->getMessage());
        }
    }

    public function DCInv()
    {
        try {
            $datos = $this->modelo->DCInv();
            if (count($datos) == 0) {
                throw new Exception("No se encontraron productos");
            }
            return array("res" => "1", "datos" => $datos);
        } catch (Exception $e) {
            return array("res" => "0", "msj" => $e->getMessage());
        }
    }

    public function Eliminar_Pensiones($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            $this->modelo->DeleteClientesFacturacion($parametros, $Mifecha);
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al eliminar pensiones", 'error' => $e->getMessage());
        }
    }

    public function Existen_Rubros($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $Titulo = "";
            $Mensaje = "";
            if ($parametros['Codigo1'] === "") {
                $parametros['Codigo1'] = G_NINGUNO;
            }
            if ($parametros['Codigo2'] === "") {
                $parametros['Codigo2'] = G_NINGUNO;
            }

            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            $AdoAux = $this->modelo->AdoAuxInsertar($parametros, $Mifecha);
            if (count($AdoAux) > 0) {
                $Titulo = "GENERACION DE RUBROS A FACTURAR POR LOTE ";
                $Mensaje = "Actualmente ya existe rubros a facturar en este rango de Grupo y de fechas. \n
                                Realmente desea borrar estos datos he ingresar los nuevos ";
                return array("res" => "1", "titulo" => $Titulo, "msj" => $Mensaje);
            } else {
                throw new Exception("No existen rubros a facturar en este rango de Grupo y de fechas");
            }
        } catch (Exception $e) {
            return array("res" => "0", "msj" => $e->getMessage());
        }
    }

    public function Insertar_Pensiones($parametros)
    {
        try {
            $Mifecha = $parametros['FechaTexto'];
            $NoDiaT = date("d", strtotime($Mifecha));
            if ($parametros['Codigo1'] === "") {
                $parametros['Codigo1'] = G_NINGUNO;
            }
            if ($parametros['Codigo2'] === "") {
                $parametros['Codigo2'] = G_NINGUNO;
            }
            $Mifecha = $this->Rango_Fechas_Proceso($parametros['Contador'], $Mifecha);
            //No se vuelve a comprobar si existen rubros.
            $this->modelo->DeleteClientesFacturacion($parametros, $Mifecha);
            $Mifecha = $parametros['FechaTexto'];

            for ($i = 1; $i <= $parametros['Contador']; $i++) {
                $NoDias = intval(date("d", strtotime($Mifecha)));
                $NoMes = intval(date("m", strtotime($Mifecha)));
                $NoAnio = intval(date("Y", strtotime($Mifecha)));
                $Mesl = MesesLetras($NoMes);
                $this->modelo->InsertClientesFacturacion($parametros, $NoAnio, $NoMes, $Mifecha, $Mesl);
                $Mifecha = new DateTime($Mifecha);
                $Mifecha->add(new DateInterval("P1M"));
                $Mifecha = $Mifecha->format("d/m/Y");
            }
            Eliminar_Nulos_SP("Clientes_Facturacion");
            return array("res" => "1", "msj" => "Proceso Terminado");
        } catch (Exception $e) {
            return array("res" => "0", "msj" => "Error al insertar los datos", 'error' => $e->getMessage());
        }
    }

    /**
     * Método que se encarga de calcular el último dia de una fecha sumado un número de meses determinado
     * @param $numMeses
     * @param $fecha
     * @return DateTime
     */
    private function Rango_Fechas_Proceso($numMeses, $fecha): DateTime
    {
        $fecha = new DateTime($fecha);
        $fecha->add(new DateInterval("P" . $numMeses . "M"));
        $fecha->format("Y-m-t");
        return $fecha;
    }


}