<?php 
    include(dirname(__DIR__, 2) . '/modelo/inscripciones/voluntarios.php');

    $controlador = new InscVoluntariosC();

    if(isset($_GET['CatalogoForm'])){
        echo json_encode($controlador->getCatalogo());
    }
    if(isset($_GET['EnviarInscripcion'])){
        echo json_encode($controlador->enviarInscripcion($_POST['parametros']));
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
            }else{
                $catalogo[] = array(
                    'error' => 'No hay catalogo'
                );
                return $catalogo;
            }
        }

        function enviarInscripcion($parametros){
            $respuesta = $this->modelo->enviarInscripcion($parametros);

            if($respuesta == 1){
                return array(
                    "codigo" => 1,
                    "respuesta" => "Se enviaron los datos correctamente"
                );
            }
            return array(
                "codigo" => 0,
                "respuesta" => "Hubo un error al enviar los datos"
            );
        }
    }
?>