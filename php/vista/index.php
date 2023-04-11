<!DOCTYPE html>
<html>
  <head>
    <title>Error 404 - Página no encontrada</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  <!-- Bootstrap 3.3.7 -->
	  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
	  <!-- Font Awesome -->
	  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <style>
      /* Estilos para el botón de volver */
      .boton-volver {
        position: fixed;
        bottom: 50px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 20px;
        padding: 15px 32px;
        text-align: center;
        font-size: 16px;
        cursor: pointer;
      }
      /* Estilos para la imagen de fondo */
      body {
        background-image: url("../../img/404.jpg");
        background-size: cover;
        background-position: center top;
        background-repeat: no-repeat;
      }

      @media (max-width: 768px) {
      	body {
      		background-image: url("../../img/404_movil.jpg");
      	}
      	.boton-volver {
	        position: fixed;
	        bottom: 120px;
	    }
      }
    </style>
  </head>
  <body>
    <!-- Botón de volver -->
    <a href="javascript:history.back()" class="btn btn-success boton-volver"><i class="fa fa-chevron-left"></i> Volver </a>
  </body>
</html>
