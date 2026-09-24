<?php
include(dirname(__DIR__,2).'/modelo/rol_pagos/asignar_rolM.php');
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

if(isset($_GET['catalogos']))
{
    $controlador = new asignarRolC();
    echo json_encode($controlador->catalogos());
}

if(isset($_GET['formasPago']))
{
    $controlador = new asignarRolC();
    echo json_encode($controlador->formasPago($_POST));
}

if(isset($_GET['empleado']))
{
    $controlador = new asignarRolC();
    echo json_encode($controlador->empleado($_POST));
}

if(isset($_GET['tipoIdentificacion']))
{
    $controlador = new asignarRolC();
    echo json_encode($controlador->tipoIdentificacion($_POST));
}

if(isset($_GET['guardar']))
{
    $controlador = new asignarRolC();
    echo json_encode($controlador->guardar($_POST));
}

class asignarRolC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new AsignarRol();
    }

    // Convierte los DateTime de sqlsrv a texto Y-m-d para poder llenar <input type="date">.
    private function fechasATexto($fila){
        if(!is_array($fila)) return $fila;
        foreach($fila as $k => $v){
            if($v instanceof DateTime){
                $fila[$k] = $v->format('Y-m-d');
            }
        }
        return $fila;
    }

    private function num($valor){
        return floatval(str_replace(',', '', $valor ?? 0));
    }

    private function txt($valor, $defecto = '.'){
        $valor = trim($valor ?? '');
        return $valor === '' ? $defecto : $valor;
    }

    private function bool($valor){
        return ($valor === true || $valor === 'true' || $valor === '1' || $valor === 1);
    }

    function catalogos(){
        $iess = ['IESS_Per' => 0, 'IESS_Pat' => 0, 'IESS_ExtC' => 0, 'Sueldo_Bas' => 0, 'Canasta_Ba' => 0];
        foreach($this->modelo->iess(BuscarFecha(date('Y-m-d'))) as $fila){
            $iess[$fila['Codigo']] = floatval($fila['Porc']);
        }
        return [
            'grupos' => $this->modelo->grupos(),
            'convenio' => $this->modelo->referencias('APLICA CONVENIO'),
            'condiciones' => $this->modelo->referencias('TRABAJADOR'),
            'bancos' => $this->modelo->referencias('BANCOS Y COOP', true),
            'subcuentas' => $this->modelo->subCuentas(),
            'iess' => $iess,
            'fecha' => date('Y-m-d'),
        ];
    }

    function formasPago($p){
        return $this->modelo->formasPago($p['Tipo'] ?? 'E');
    }

    function empleado($p){
        $codigo = trim($p['Codigo'] ?? '');
        return [
            'cliente' => $this->modelo->cliente($codigo),
            'empleado' => $this->fechasATexto($this->modelo->empleado($codigo)),
            'acceso' => $this->modelo->acceso($codigo),
        ];
    }

    function tipoIdentificacion($p){
        $datos = Digito_verificador(trim($p['CI'] ?? ''));
        return ['Tipo' => $datos['Tipo_Beneficiario'] ?? 'N'];
    }

    function guardar($p){
        $codigo = trim($p['Codigo'] ?? '');
        $cli = $this->modelo->cliente($codigo);
        if(!$cli){
            return ['ok' => 0, 'msg' => 'No se encontró el empleado'];
        }
        $nombre = $cli['Cliente'];
        $usuario = $this->txt($p['Usuario']);
        $clave = $this->txt($p['Clave']);

        if($usuario != '.' && $this->modelo->duplicado('Usuario', $usuario, $codigo)){
            return ['ok' => 0, 'msg' => 'Usuario ya asignado'];
        }
        if($clave != '.' && $this->modelo->duplicado('Clave', $clave, $codigo)){
            return ['ok' => 0, 'msg' => 'Clave ya asignado'];
        }

        $grupoRol = $this->txt($p['GrupoRol']);
        $ctaHorasExt = $this->txt($p['CtaHorasExt'], '0');
        $horasExt = intval(substr($ctaHorasExt, 0, 1)) > 0;

        $pais593 = intval($cli['Pais'] ?? 0) == 593;
        $identificacion = $pais593 ? '999' : $this->txt($p['Identificacion']);
        $tIdentificacion = $pais593 ? 'N' : $this->txt($p['TIdentificacion']);

        $salida = $this->bool($p['Salida'] ?? false);
        $maternidad = $this->bool($p['Maternidad'] ?? false);
        $fechaVI = $this->txt($p['FechaVI'], date('Y-m-d'));
        $mes = intval(date('n', strtotime($fechaVI)));
        $fp = $p['FP'] ?? 'E';
        $codEjec = $cli['Cod_Ejec'] ?? '.';
        if($codEjec == '' || $codEjec == '.'){
            $codEjec = '';
            foreach(preg_split('/\s+/', trim($nombre)) as $palabra){ $codEjec .= strtoupper(substr($palabra, 0, 1)); }
        }

        SetAdoAddNew("Catalogo_Rol_Pagos");
        SetAdoFields("Item", $_SESSION['INGRESO']['item']);
        SetAdoFields("Periodo", $_SESSION['INGRESO']['periodo']);
        SetAdoFields("Codigo", $codigo);
        SetAdoFields("SubModulo", $this->txt($p['SubModulo']));
        SetAdoFields("Horas_Ext", $horasExt);
        SetAdoFields("Fecha", $this->txt($p['Fecha'], date('Y-m-d')));
        SetAdoFields("Valor_Hora", $this->num($p['ValorHora']));
        SetAdoFields("Horas_Sem", $this->num($p['HorasSem']));
        SetAdoFields("Salario", $this->num($p['Salario']));
        SetAdoFields("Valor_Dec_3ro", $this->num($p['ValorDec3ro']));
        SetAdoFields("Valor_Dec_4to", $this->num($p['ValorDec4to']));
        SetAdoFields("Dias_Dec_3ro", intval($p['DiasDec3ro'] ?? 0));
        SetAdoFields("Dias_Dec_4to", intval($p['DiasDec4to'] ?? 0));
        SetAdoFields("Porc_Com", $this->num($p['PorcCom']) / 100);
        SetAdoFields("Usuario", $usuario);
        SetAdoFields("Clave", $clave);
        SetAdoFields("Grupo_Rol", $grupoRol);
        SetAdoFields("Tarjeta", Sin_Signos_Especiales($this->txt($p['Tarjeta'])));
        SetAdoFields("SN", ($p['SalarioNeto'] ?? '1') == '2' ? '2' : '1');
        SetAdoFields("No_CSSP", strtoupper($this->txt($p['NoCSSP'])));
        SetAdoFields("No_CUSSP", strtoupper($this->txt($p['NoCUSSP'])));
        SetAdoFields("AFP_ONP", strtoupper($this->txt($p['AFPONP'])));
        SetAdoFields("No_Personal", $this->txt($p['NoSeguro']));
        SetAdoFields("CodProfesion", str_pad(strval(intval($p['CodProfesion'] ?? 0)), 10, '0', STR_PAD_LEFT));
        SetAdoFields("FormaPago10to", strtoupper($this->txt($p['FPDec'], 'A')));
        SetAdoFields("Ejecutivo", $nombre);
        SetAdoFields("FechaVI", $fechaVI);
        SetAdoFields("FechaVF", $this->txt($p['FechaVF'], date('Y-m-d')));
        SetAdoFields("Mes", $mes);
        SetAdoFields("Cta_Transferencia", '.');
        SetAdoFields("Acreditar_Cta", '.');
        SetAdoFields("TC", $this->txt($p['TC']));
        SetAdoFields("Cta_Forma_Pago", $this->txt($p['CtaFormaPago']));
        SetAdoFields("Pagar_Fondo_Reserva", $this->bool($p['PagarFondoReserva'] ?? false));
        SetAdoFields("Pagar_Decimos", $this->bool($p['PagarDecimos'] ?? false));
        SetAdoFields("TiempoParcial", $this->bool($p['TiempoParcial'] ?? false));
        SetAdoFields("Reingreso_FR", $this->bool($p['ReingresoFR'] ?? false));
        SetAdoFields("ExtC", $this->bool($p['ExtC'] ?? false));
        SetAdoFields("Opc_Guardian", $this->bool($p['Guardian'] ?? false));
        SetAdoFields("Identificacion", $identificacion);
        SetAdoFields("TIdentificacion", $tIdentificacion);
        SetAdoFields("Aplica", $this->txt($p['Aplica'], 'NA'));
        SetAdoFields("Condicion", $this->txt($p['Condicion'], '01'));
        SetAdoFields("Porcentaje", $this->num($p['PorcDiscap']));
        SetAdoFields("Porc_IESS_Per", $this->num($p['PorcIESSPer']) / 100);
        SetAdoFields("Porc_IESS_Pat", $this->num($p['PorcIESSPat']) / 100);
        SetAdoFields("Porc_IESS_ExtC", $this->num($p['PorcIESSExtC']) / 100);
        SetAdoFields("Vivienda", $this->num($p['Vivienda']));
        SetAdoFields("Salud", $this->num($p['Salud']));
        SetAdoFields("Educacion", $this->num($p['Educacion']));
        SetAdoFields("Alimentacion", $this->num($p['Alimentacion']));
        SetAdoFields("Vestimenta", $this->num($p['Vestimenta']));
        SetAdoFields("Turismo", $this->num($p['Turismo']));
        SetAdoFields("Discapacidad", $this->num($p['Discapacidad']));
        SetAdoFields("Tercera_Edad", $this->num($p['TerceraEdad']));
        SetAdoFields("Cod_Ejec", $codEjec);
        SetAdoFields("Carga_Familiar", intval($p['Cargas'] ?? 0));
        SetAdoFields("T", $salida ? 'R' : 'N');
        SetAdoFields("FechaC", $salida ? $this->txt($p['FechaC'], date('Y-m-d')) : date('Y-m-d'));
        SetAdoFields("FechaMat", $maternidad ? $this->txt($p['FechaMat'], date('Y-m-d')) : date('Y-m-d'));
        SetAdoFields("Maternidad", $maternidad);
        SetAdoFields("Codigo_Banco", intval($p['CodigoBanco'] ?? 0));

        if($fp == 'E'){
            SetAdoFields("FP", "E");
        } else if($fp == 'C'){
            SetAdoFields("FP", "C");
        } else if($fp == 'T'){
            SetAdoFields("FP", "T");
            SetAdoFields("Cta_Transferencia", $this->txt($p['CtaAbono']));
            SetAdoFields("Acreditar_Cta", $this->txt($p['AcreditarCI']));
        } else {
            SetAdoFields("FP", "O");
        }

        if($this->modelo->empleado($codigo)){
            SetAdoFieldsWhere("Item", $_SESSION['INGRESO']['item']);
            SetAdoFieldsWhere("Periodo", $_SESSION['INGRESO']['periodo']);
            SetAdoFieldsWhere("Codigo", $codigo);
            $re = SetAdoUpdateGeneric();
        } else {
            $re = SetAdoUpdate();
        }

        if($re != 1){
            return ['ok' => 0, 'msg' => 'No se pudo grabar la asignación al rol'];
        }

        // Clave de ingreso al sistema (tabla Accesos)
        if($this->modelo->acceso($codigo)){
            $this->modelo->actualizarAcceso($codigo, $usuario, $clave, ULCase($nombre), $codEjec);
        } else {
            SetAdoAddNew("Accesos", true);
            SetAdoFields("TODOS", true);
            SetAdoFields("Clave", $clave);
            SetAdoFields("Codigo", $codigo);
            SetAdoFields("Usuario", $usuario);
            SetAdoFields("Nombre_Completo", ULCase($nombre));
            SetAdoFields("Cod_Ejec", $codEjec);
            SetAdoFields("Primaria", $this->bool($p['Primaria'] ?? false));
            SetAdoFields("Secundaria", $this->bool($p['Secundaria'] ?? false));
            SetAdoFields("Bachillerato", $this->bool($p['Bachillerato'] ?? false));
            SetAdoUpdate();
        }

        return ['ok' => 1];
    }
}
