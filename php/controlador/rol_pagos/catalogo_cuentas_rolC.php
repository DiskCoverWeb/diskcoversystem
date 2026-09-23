<?php
include(dirname(__DIR__,2).'/modelo/rol_pagos/catalogo_cuentas_rolM.php');
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

if(isset($_GET['grupos']))
{
    $controlador = new catalogoCuentasRolC();
    echo json_encode($controlador->listarGrupos());
}

if(isset($_GET['datosGrupo']))
{
    $controlador = new catalogoCuentasRolC();
    $parametros = $_POST;
    echo json_encode($controlador->datosGrupo($parametros));
}

if(isset($_GET['guardarGrupo']))
{
    $controlador = new catalogoCuentasRolC();
    $parametros = $_POST;
    echo json_encode($controlador->guardarGrupo($parametros));
}

if(isset($_GET['eliminarGrupo']))
{
    $controlador = new catalogoCuentasRolC();
    $parametros = $_POST;
    echo json_encode($controlador->eliminarGrupo($parametros));
}

class catalogoCuentasRolC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new CatalogoCuentasRol();
    }

    function normalizarGrupoRol($grupoRol){
        $g = strtoupper(trim($grupoRol));
        $g = str_replace(' ', '_', $g);
        $g = str_replace('.', '_', $g);
        $g = substr($g, 0, 30);
        return $g;
    }

    // Si el grupo ya existe tal cual fue escrito (ej. "Gerente general") se respeta ese nombre;
    // solo un grupo nuevo se normaliza (mayúsculas, espacios/puntos a "_").
    function resolverGrupoRol($grupoRol){
        $exacto = trim($grupoRol);
        if(strlen($exacto) > 1 && $this->modelo->existeGrupo($exacto)){
            return $exacto;
        }
        return $this->normalizarGrupoRol($grupoRol);
    }

    function listarGrupos(){
        return $this->modelo->listarGrupos();
    }

    function datosGrupo($parametros){
        $grupoRol = $this->resolverGrupoRol($parametros['GrupoRol'] ?? '');
        if(strlen($grupoRol) <= 1){
            return null;
        }
        return $this->modelo->obtenerGrupo($grupoRol);
    }

    function guardarGrupo($parametros){
        $grupoRol = $this->resolverGrupoRol($parametros['GrupoRol'] ?? '');
        if(strlen($grupoRol) <= 1){
            return -1;
        }

        $cuentas = [];
        foreach($this->modelo->getCampos() as $campo){
            $valor = trim($parametros[$campo] ?? '0');
            $cuentas[$campo] = $valor === '' ? '0' : $valor;
        }

        $respuesta = $this->modelo->guardarGrupo($grupoRol, $cuentas);
        return ['Respuesta' => $respuesta, 'GrupoRol' => $grupoRol];
    }

    function eliminarGrupo($parametros){
        $grupoRol = $this->resolverGrupoRol($parametros['GrupoRol'] ?? '');
        if(strlen($grupoRol) <= 1){
            return -1;
        }
        return $this->modelo->eliminarGrupo($grupoRol);
    }
}
