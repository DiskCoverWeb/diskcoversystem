<?php 
require_once(dirname(__Dir__,2).'/headers/header.php');
require_once(dirname(__DIR__,1)."/modelo/loginM.php");

$loginM = new loginM();
$loginM->sincronizar_foto_usuario_mysql();
?>
<script>
    $(document).ready(function() {
        abrir_modal_perfil();
    });
</script>
<?php require_once(dirname(__DIR__, 2).'/headers/footer.php');?>
