<?php
include(dirname(__DIR__,2).'/modelo/rol_pagos/registro_horas_laboradasM.php');
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

if(isset($_GET['generarDias']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->generarDias($parametros));
}

if(isset($_GET['valor_hora'])){
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->getValorHora($parametros));
}

if(isset($_GET['beneficiarios']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->generarBeneficiarios($parametros));
}

if(isset($_GET['datos_beneficiario']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->datosBeneficiario($parametros));
}

if(isset($_GET['insertarRegistroManual']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->insertarRegistroManual($parametros));
}

if(isset($_GET['eliminarRegistroHoras']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->eliminarRegistroHoras($parametros));
}

if(isset($_GET['eliminarDiasFecha']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->eliminarDiasFecha($parametros));
}

if(isset($_GET['permisoEnfermedad']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->permisoEnfermedad($parametros));
}

if(isset($_GET['agregarNovedad']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->agregarNovedad($parametros));
}

if(isset($_GET['eliminarNovedad']))
{
    $controlador = new registroHorasLaboradasC();
    $parametros = $_POST;
    echo json_encode($controlador->eliminarNovedad($parametros));
}

class registroHorasLaboradasC
{
    private $modelo;
    
    function __construct()
    {
        $this->modelo=new RegistroHorasLaboradas();
    }

    function generarDias($parametros){
        $parametros['PrimerDiaMes'] = PrimerDiaMes($parametros['Fecha'], "Y/m/d");
        $parametros['UltimoDiaMes'] = UltimoDiaMes($parametros['Fecha'], "Y/m/d");
        $parametros['FechaIni'] = BuscarFecha($parametros['PrimerDiaMes']);
        $parametros['FechaFin'] = BuscarFecha($parametros['UltimoDiaMes']);
        $parametros['UltimoDia'] = DiaMes($parametros['UltimoDiaMes']);
        $respuesta = $this->modelo->generarDias($parametros);
        return $respuesta;
    }

    function generarBeneficiarios($parametros){
        $parametros['FechaIni'] = BuscarFecha(PrimerDiaMes($parametros['Fecha'],  "Y/m/d"));
        $parametros['FechaFin'] = BuscarFecha(UltimoDiaMes($parametros['Fecha'],  "Y/m/d"));
        $datos = $this->modelo->obtenerBeneficiarios($parametros);
        return $datos;
    }

    function datosBeneficiario($parametros){
        $fechaInicial = '';
        $OpcMov = $parametros['OpcMov'];
        $fecha = DateTime::createFromFormat('Y-m-d', $parametros['Fecha']);
        $mes = $fecha->format('m');
        $año = $fecha->format('Y');
        if($OpcMov == 'anio_a'){
            $fechaInicial = '01/01/'.$año;
        } else{
            if ($OpcMov == 'mes_a'){ $NoMeses=(int)$mes; }
            if ($OpcMov == 'dos_m'){ $NoMeses=(int)$mes - 1; }
            if ($OpcMov == 'tres_m'){ $NoMeses=(int)$mes - 2; }
            if ($OpcMov == 'cuatro_m'){ $NoMeses=(int)$mes - 3; }
            if ($NoMeses <= 0){$NoMeses= 1;}
            $NoMeses = sprintf('%02d', $NoMeses);
            $fechaInicial = "01/".$NoMeses."/".$año;
        }

        $parametros['FechaInicial'] = $fechaInicial;
        $parametros['FechaIni'] = BuscarFecha(FormatearFecha($parametros['FechaInicial'], 'd/m/Y')); 
        $parametros['FechaFin'] = BuscarFecha($parametros['Fecha']);

        $dato_valor_hora = $this->modelo->getValorHora($parametros);

        $datos_horas_trabajadas = $this->modelo->ListarHorasTrabajadas($parametros['FechaIni'], $parametros['FechaFin'], $parametros['Codigo']);
        $totales = 0;
        $saldo = 0; 
        foreach ($datos_horas_trabajadas as $value){
            $totales = $totales + $value['Horas'];
            $saldo = $saldo + $value['Ing_Liquido'];
        }

        $novedades = $this->modelo->ListarNovedades($parametros);

        $result = ['HorasTrabajadas' => $datos_horas_trabajadas, 'Total'=> $totales,'Saldo'=> $saldo, 'Dato_Valor_Hora' => $dato_valor_hora, 'Novedades'=>$novedades];
        return $result;
    }

    function getValorHora($parametros){
        $parametros['Fecha'] = BuscarFecha($parametros['Fecha']);
        $datos = $this->modelo->getValorHora($parametros);
        return $datos;
    }

    function insertarRegistroManual($parametros){
        $horas = floatval($parametros['Horas']);
        $valorHora = floatval($parametros['ValorHora']);
        $horasExtras = floatval($parametros['HorasExtras']);
        $modo = $parametros['ModoHoraExtra'];
        $valorPorHora = floatval($parametros['ValorPorHora']);

        if($modo == '%'){
            $cuota = 1 + ($valorPorHora/100);
            $totalExtra = $horasExtras * ($valorHora * $cuota);
        } else {
            $cuota = $valorPorHora;
            $totalExtra = $horasExtras * $cuota;
        }
        $ingLiquido = isset($parametros['IngLiquido']) && $parametros['IngLiquido'] !== ''
            ? floatval($parametros['IngLiquido'])
            : (($horas * $valorHora) + $totalExtra);

        $datos = [
            'Codigo' => $parametros['Codigo'],
            'Fecha' => BuscarFecha($parametros['Fecha']),
            'Dias' => intval($parametros['Dias']),
            'Horas' => $horas,
            'HorasExtras' => $horasExtras,
            'PorcHrExt' => $modo == '%' ? $cuota : 0,
            'ValorHora' => $valorHora,
            'IngLiquido' => round($ingLiquido, 2),
            'IngHorasExt' => round($totalExtra, 2),
            'Orden' => $parametros['Orden'],
        ];
        return $this->modelo->insertarRegistroManual($datos);
    }

    function eliminarRegistroHoras($parametros){
        $parametros['Fecha'] = BuscarFecha($parametros['Fecha']);
        return $this->modelo->eliminarRegistroHoras($parametros);
    }

    function eliminarDiasFecha($parametros){
        $parametros['Fecha'] = BuscarFecha($parametros['Fecha']);
        return $this->modelo->eliminarDiasFecha($parametros['Fecha']);
    }

    function permisoEnfermedad($parametros){
        if(intval($parametros['Dias']) <= 3){
            return -1;
        }
        $parametros['Fecha'] = BuscarFecha($parametros['Fecha']);
        return $this->modelo->permisoEnfermedad($parametros);
    }

    function agregarNovedad($parametros){
        $parametros['Fecha'] = BuscarFecha($parametros['Fecha']);
        $parametros['Hora'] = date('H:i:s');
        $parametros['Tarea'] = substr(trim($parametros['Tarea']), 0, 50);
        return $this->modelo->agregarNovedad($parametros);
    }

    function eliminarNovedad($parametros){
        $parametros['Fecha'] = BuscarFecha($parametros['Fecha']);
        return $this->modelo->eliminarNovedad($parametros);
    }

}
?>