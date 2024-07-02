<link rel="stylesheet" href="../../dist/css/estilo_inscripciones.css">
<script type="text/javascript"></script>
<script src="../../dist/js/inscripciones.js"></script>
<div class="mensaje-form-enviado" id="mensaje-form-enviado">
    <div class="res-form" id="res-form">
        <!--<div class="icon-rform rf-icheck">
            <i class="fa fa-check-circle" aria-hidden="true"></i>
        </div>
        <p class="msg-rform">Se pudo enviar el formulario con exito</p>-->
        <!--<div class="icon-rform rf-ierr">
            <i class="fa fa-times-circle" aria-hidden="true"></i>
        </div>
        <p class="msg-rform">No se pudo enviar el formulario</p>-->
        <div class="icon-rform rf-iload">
            <i class="fa fa-circle-o-notch" aria-hidden="true"></i>
        </div>
        <p class="msg-rform">Enviando Formulario...</p>
    </div>
</div>
<div class="contenedor-cf" id="contenedor-cf">
    <div class="cargar-formulario">
        <div>
            <div class="icono-cargar"><i class="fa fa-circle-o-notch" aria-hidden="true"></i></div>
        </div>
        <div class="p-cargar">Cargando Formulario...</div>
    </div>
</div>
<div class="form-contenedor" id="form-contenedor">

</div>
<div id="modalVistaArchivo" class="modal fade">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="modVATitulo"></h4>
			</div>
			<div class="modal-body" style="height:70vh;margin:5px">
				<iframe id="modVAFrame" src="" frameborder="0" style="height:100%; width:100%;"></iframe>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>