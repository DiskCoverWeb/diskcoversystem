<?php

function val_estado($intervalo)
{

	$rps = "'rps'";
	$mensaje = "'mensaje'";
	$titulo = "'titulo'";

	echo '
	<script>

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

	function loadData()
	{
		console.log("CONTADOR TEST");
		var response = ' . validar_estado_all() . '
		var rps = response[' . $rps . '];
		var mensaje = response[' . $mensaje .'];
		var titulo = response['. $titulo .'];

		if(rps != "ok")
		{
			if(rps == "BLOQ" || rps == "MAS360" || rps == "VEN360" || rps == noAuto || rps == noActivo)
			{
				Swal.fire(
					titulo,
					mensaje,
					"error",
					
				).then(function()
				{
					Swal.fire(
						"Mensaje Enviado Correctamente",
						"Se ha enviado un mensaje a su corro",
						"success"
					).then(function()
					{						
						logout();
					});
				});
			}else
			{
				Swal.fire(
					titulo,
					mensaje,
					"icon"
			   );
			}
		}
		
	}

	loadData();
	setInterval(() => loadData(), '. $intervalo .' * 60 * 1000);


	</script>';

}


?>