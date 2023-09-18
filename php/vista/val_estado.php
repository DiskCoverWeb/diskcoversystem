<?php

function val_estado()
{
    $response = validar_estado_all();

    if ($response['rps'] != 'ok') {
        $rps = $response['rps'];
        $mensaje = $response['mensaje'];
        $mensaje_js = str_replace("\n", "\\n", $mensaje);
        $titulo = $response['titulo'];
        $icon = "'info'";
        $toLogin =
            '
				function logout()
				{ 
				   $.ajax({
					url:   "../controlador/login_controller.php?logout=true",
					type:  "post",
					dataType: "json",
					  success:  function (response) { 
						console.log(response);
					  if(response == 1)
					  {
						location.href = "login.php";          
					  }     
					}
				  });
				}
				Swal.fire(
					 "' . $titulo . '",
					 "' . $mensaje_js . '",
					 ' . $icon . '
				).then(function() {
					logout();
				});';
        $continue =
            'Swal.fire(
					 "' . $titulo . '",
					 "' . $mensaje_js . '",
					 ' . $icon . '
					 
				);';
        if ($rps == 'BLOQ' || $rps == 'MAS360' || $rps == 'VEN360' || $rps == 'noAuto' || $rps == 'noActivo') {
            echo
                '
					<script>
					' . $toLogin . '
					</script>';
        } else {
            echo
                '
					<script>
					' . $continue . '
					</script>';
        }
    }
}

function test(){
    echo '<script>';
    echo 'console.log("TEST CONTADOR");';
    echo '</script>';
}

?>