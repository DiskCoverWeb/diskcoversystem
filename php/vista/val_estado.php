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
		var response = ' . validar_estado_all() . '
		var rps = response[' . $rps . '];
		var mensaje = response[' . $mensaje .'];
		var titulo = response['. $titulo .'];

		if(rps != "ok")
		{
			if(rps == "BLOQ" || rps == "MAS360" || rps == "VEN360" || rps == noAuto || rps == noActivo)
			{
				Swal.fire({
					title:titulo,
					html:mensaje,
					type:"error",
				}).then(function()
				{
					Swal.fire({
						title:"Mensaje Enviado Correctamente",
						html:"Se ha enviado un mensaje a su correo",
						timer:4000,
						timerProgressBar:true,
						type:"success"
					}).then(function()
					{						
						logout();
					});
				});
			}else
			{
				Swal.fire({
					title:titulo,
					html:mensaje,
					type:"info"
			   });
			}
		}
		
	}

	loadData();
	setInterval(() => loadData(), '. $intervalo .' * 60 * 1000);


	</script>';

}


?>