<?php
$jsAsignarRol = dirname(__DIR__, 3).'/dist/js/rol_pagos/asignar_rol.js';
include(__DIR__.'/modal_asignar_rol.php');
?>
<script src="../../dist/js/rol_pagos/asignar_rol.js?v=<?php echo file_exists($jsAsignarRol) ? filemtime($jsAsignarRol) : time(); ?>"></script>
<script>
    $(document).ready(function () {
        addCliente();
    })
</script>
