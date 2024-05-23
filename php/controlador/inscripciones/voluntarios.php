<?php 
    include(dirname(__DIR__, 2) . '/modelo/inscripciones/voluntarios.php');

    $controlador = new InscVoluntariosC();

    if(isset($_GET['CatalogoForm'])){
        echo json_encode($controlador->getCatalogo());
    }

    class InscVoluntariosC{
        private $modelo;

        function __construct(){
            $this->modelo = new InscVoluntariosM();
        }

        function getCatalogo(){
            $datos = $this->modelo->getCatalogoForm();
            $catalogo = array();
            if(count($datos) > 0){
                foreach($datos as $key => $value){
                    $catalogo[] = array(
                        'DG' => $value['DG'],
                        'Tipo' => $value['Tipo'],
                        'Codigo' => $value['Codigo'],
                        'Cuenta' => $value['Cuenta'],
                        'Comentario' => $value['Comentario'],
                        'Imagen' => $value['Imagen']
                    );
                }
                return $catalogo;
            }
            return $catalogo;
        }
    }
?>