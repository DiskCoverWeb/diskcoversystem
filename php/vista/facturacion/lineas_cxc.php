<script type="text/javascript">
  $(document).ready(function () {
  	$('#panel').css('height','300px');
  })
	
</script>
<div class="row">
	<div class="col-sm-7">
		<div class="panel panel-primary">
			<div class="panel-heading" style="padding: 0px 10px 0px 10px;">
				NOMBRE DE LA CUENTA POR COBRAR
			</div>
			<div class="panel-body" id="panel">
				ddd
			</div>
		</div>
	</div>
	<div class="col-sm-5">
		<button type="button" class="btn btn-default" title="Grabar factura" onclick="boton1()">
			<img src="../../img/png/grabar.png"><br>
			Grabar
			<br>
		</button>
		<br>
		<button type="button" class="btn btn-default" title="Grabar factura" onclick="boton1()">
			<img src="../../img/png/grabar.png"><br>
			Vencimiento <br> de Facturas
		</button>
		<br>
		
	</div>
</div>
<div class="row">
	<div class="col-sm-5">
		<div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">CODIGO</label>
          <div class="col-sm-10">
            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
          </div>
        </div>	
	</div>
	<div class="col-sm-7">
		<div class="form-group">
          <label for="inputEmail3" class="col-sm-2 control-label">DESCRIPCION</label>
          <div class="col-sm-10">
            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
          </div>
        </div>	
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-body">
				<ul class="nav nav-tabs">
				  <li class="active"><a data-toggle="tab" href="#home">DATOS DE PROCESO</a></li>
				  <li><a data-toggle="tab" href="#menu1">DATOS DEL S.R.I</a></li>
				</ul>

				<div class="tab-content">
				  <div id="home" class="tab-pane fade in active">
				     <div class="row"><br>
				     	<div class="col-sm-6">
							<div class="form-group">
					          <label for="inputEmail3" class="col-sm-5 control-label">CxC Clientes</label>
					          <div class="col-sm-7">
					            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
					          </div>
					        </div>	
						</div>
						<div class="col-sm-6">
							<div class="form-group">
					          <label for="inputEmail3" class="col-sm-5 control-label">CxC Año Anterior</label>
					          <div class="col-sm-7">
					            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
					          </div>
					        </div>	
						</div>
						<div class="col-sm-12">
				     	     <label><input type="checkbox" name=""> Cuenta de Venta si manejamos por Sector</label>
				     	</div>
				     	<div class="col-sm-6">
				     	     <label><input type="checkbox" name=""> Facturacion por Meses</label>
				     	</div>
				     	<div class="col-sm-6">
				     	    <div class="form-group">
					          <label for="inputEmail3" class="col-sm-5 control-label">TIPO DE DOCUMENTO</label>
					          <div class="col-sm-7">
					            <select class="form-control input-xs">
					            	<option value="">.</option>
					            </select>
					          </div>
					        </div>	
				     	</div>
				     </div>
				     <div class="row">
				     	<div class="col-sm-6">
				     	    <div class="form-group">
					         <label for="inputEmail3" class="col-sm-7 control-label">NUMERP DE FACTURAS POR PAGINAS</label>
					          <div class="col-sm-5">
					          		<input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
					          </div>
					        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">ITEMS POR FACTURA</label>
						          <div class="col-sm-7">
						            	<input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-12">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">FORMATO GRAFICO DEL DOCUMENTO (EXTENSION:GIF)</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>				     	
				     </div>
				     <div class="row">
				     	<div class="col-sm-12">
				     		ESPACIO Y POSICION DE LA COPIA DE LA FACTURA / NOTA DE VENTA
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">POSICION X DE LA FACTURA</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	    <div class="form-group">
					          <label for="inputEmail3" class="col-sm-5 control-label">POSICION Y DE LA FACTURA</label>
					          <div class="col-sm-7">
					            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
					          </div>
					        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">ESPACIO ENTRE LA FACTURA</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-2 control-label">LARGO</label>
						          <div class="col-sm-3">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						          <label for="inputEmail3" class="col-sm-2 control-label">X</label>
						          <label for="inputEmail3" class="col-sm-2 control-label">ANCHO</label>
						          <div class="col-sm-3">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>

						      </div>	
				     	</div>
				     	
				     </div>
				     
				  </div>
				  <div id="menu1" class="tab-pane fade">
					   <div class="row">
					   	<div class="col-sm-12">
					   		DATOS DEL S.R.I. DE LA FACTURA / NOTA DE VENTA
					   	</div>
					   	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">FECHA DE INICIO</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">SECUENCIAL DE INICIO</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">FECHA DE VENCIMIENTO</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-6">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-5 control-label">AUTORIZACION</label>
						          <div class="col-sm-7">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
				     	<div class="col-sm-12">
				     	     <div class="form-group">
						          <label for="inputEmail3" class="col-sm-8 control-label">SERIE DE FACTURA / NOTA DE VENTA (ESTAB. Y PUNTO DE VENTA)</label>
						          <div class="col-sm-2">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						          <div class="col-sm-2">
						            <input type="text" class="form-control input-xs" id="inputEmail3" placeholder="Email">
						          </div>
						        </div>	
				     	</div>
					   </div>
				  </div>				  
				</div>
			</div>
		</div>		
	</div>
</div>