<?php 
    require_once(dirname(__DIR__, 2) . "/db/db1.php");
    @session_start();

    class InscVoluntariosM{
        private $db;

        function __construct(){
            $this->db = new db();
        }

        function getCatalogoForm(){
            $sql = "SELECT * 
            FROM Catalogo_Auditor
            WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
            AND Periodo = '" . $_SESSION['INGRESO']['periodo'] . "'
            ORDER BY Codigo";
            return $this->db->datos($sql);
        }

        function enviarInscripcion($param){
            $sql = "INSERT INTO Clientes
            (T,FA,Codigo,Cliente,Telefono,CI_RUC,Sexo,Fecha_N,Plan_Afiliado,Est_Civil,Calificacion,Gestacion,Especial,Referencia,Dosis,Asignar_Dr,DireccionT,Representante,CodigoA,Contacto,Telefono_R,Tipo_Cta,Canton,Parroquia,Barrio,Direccion,DirNumero,Credito,No_Dep,Matricula,Cod_Banco,Descuento,Profesion,Porc_C,FAX,FactM,Casilla,Cod_Ejec,Cta_CxP,Lugar_Trabajo,Tipo_Cliente,Bono_Desarrollo,IESS,Actividad,Tipo_Vivienda,Servicios_Basicos,Archivo_CI_RUC_PAS,Archivo_Record_Policial,Archivo_Planilla,Archivo_Carta_Recom,Archivo_Certificado_Medico,Archivo_VIH,Archivo_Reglamento,TB,TD)
            VALUES(
            '.',
            0,
            '".$param['cedula']."',
            '".$param['cliente']."',
            '".$param['telefono']."',
            '".$param['cedula']."',
            '".$param['genero']."',
            '".$param['fecha_nacimiento']."',
            '".$param['ciudadania']."',
            '".$param['estado_civil']."',
            '".$param['estado_lactancia']."',
            '".$param['estado_gestacion']."',
            ".$param['toma_medicina'].",
            '".$param['medicamento']."',
            '".$param['dosis']."',
            ".$param['alergia'].",
            '".$param['prod_alergia']."',
            '".$param['nombre_conyugue']."',
            '".$param['cedula_conyugue']."',
            '".$param['nombre_emergencia']."',
            '".$param['telefono_emergencia']."',
            '".$param['parentesco_emergencia']."',
            '".$param['canton']."',
            '".$param['parroquia']."',
            '".$param['barrio']."',
            '".$param['direccion']."',
            '".$param['num_casa']."',
            ".$param['personas_domicilio'].",
            ".$param['num_hijos'].",
            ".$param['hijos_mayores'].",
            ".$param['hijos_menores'].",
            ".$param['discapacidad'].",
            '".$param['tipo_discapacidad']."',
            ".$param['porc_discapacidad'].",
            '".$param['conadis']."',
            ".$param['familiar_discapacidad'].",
            '".$param['parentesco_fdiscapacidad']."',
            '".$param['conadis_familiar']."',
            '".$param['enfermedad']."',
            '".$param['nivel_estudio']."',
            '".$param['ocupacion']."',
            ".$param['bono'].",
            ".$param['jubilacion'].",
            '".$param['actividad_economica']."',
            '".$param['vivienda']."',
            '".$param['servicios_basicos']."',
            '".$param['cedula_pdf']."',
            '".$param['record_policial']."',
            '".$param['planilla_sbasico']."',
            '".$param['carta_recomendacion']."',
            '".$param['certificado_medico']."',
            '".$param['prueba_vih']."',
            '".$param['reglamento_baq']."',
            'INSC',
            'C');";

            return $this->db->String_Sql($sql);
        }
    }
?>