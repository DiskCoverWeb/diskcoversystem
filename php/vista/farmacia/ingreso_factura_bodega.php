<script type="text/javascript">
$( document ).ready(function() {
   provincias();
});


function provincias()
  {
   var option ="<option value=''>Seleccione provincia</option>"; 
     $.ajax({
      url: '../controlador/educativo/detalle_estudianteC.php?provincias=true',
      type:'post',
      dataType:'json',
     // data:{usu:usu,pass:pass},
      beforeSend: function () {
                   $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#prov').html(option);
      console.log(response);
    }
    });

  }

	
</script>
 <div class="row"><br>
    <div class="col-lg-7 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
       <!--  <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
          </a>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
          </a>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div>      -->
 </div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="row box-body">
				<div class="col-sm-2">
					<b>Fecha</b>
					<input type="date" name="" id="" class="form-control input-sm" value="<?php echo date('Y-m-d');?>">
				</div>
				<div class="col-sm-5">
					<b>Proveedor</b>
					<select class="form-control input-sm" id="prov">
						<option value="">Seleccione provincia</option>
					</select>
				</div>
				<div class="col-sm-2">
					<b>Cod Factura</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>		
				<div class="col-sm-2">
					<b>RUC / CI</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>
				<div class="col-sm-1">
					<b>IVA</b>
					<select class="form-control input-sm" id="prov">
						<option value="">Iva</option>
					</select>
				</div>
				<div class="col-sm-12">
					<hr>
				</div>		
				<div class="col-sm-2">
					<b>Referencia</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>
						
				<div class="col-sm-4">
					<b>Producto</b>
					<select class="form-control input-sm" id="prov">
						<option value="">Seleccione provincia</option>
					</select>
				</div>
				<div class="col-sm-1">
					<b>Cantidad</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>
				<div class="col-sm-1">
					<b>Precio</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>
				<div class="col-sm-1">
					<b>Dcto %</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>		
				<div class="col-sm-1">
					<b>Total</b>
					<input type="" name="" id="" class="form-control input-sm">
				</div>		
				<div class="col-sm-2 text-right">
					<br>
					<button class="btn btn-primary btn-sm">Guardar</button>
				</div>		
			</div>			
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<table class="table table-hover">
				<thead>
					<th>Referencia</th>
					<th>Descripcion</th>
					<th>Cantidad</th>
					<th>Precio</th>
					<th>DCTO %</th>
					<th>Importe</th>
				</thead>
				<tbody>
					<tr><td colspan="6">Sin registros</td></tr>
				</tbody>
			</table>
			
		</div>
	</div>
</div>
<div class="row">
	<div class="box">
		<div class="row box-body">
			<div class="col-sm-9 text-center">
				<br>
				<button class="btn btn-primary btn-sm">Generar comprbante</button>
			</div>
			<div class="col-sm-3">
				<div class="row text-right">
					<div class="col-sm-6">
						<b>Sub total</b>						
					</div>
					<div class="col-sm-6">
						<input type="" name="" class="form-control input-sm">
					</div>
					<div class="col-sm-6">
						<b>Total Iva</b>
					</div>
					<div class="col-sm-6">
						<input type="" name="" class="form-control input-sm">
					</div>
					<div class="col-sm-6">
						<b>Total</b>
					</div>
					<div class="col-sm-6">
						<input type="" name="" class="form-control input-sm">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>