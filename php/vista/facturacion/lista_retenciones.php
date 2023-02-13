<?php  @session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript">

  $(document).ready(function()
  {

  }
  </script>

  <div class="row">
    <div class="col-lg-4 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="generar_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 </div>
</div>
		<div class="row">
      <form id="filtros">    			
          <div class="col-sm-4">
            <b>CI / RUC</b>
            <select class="form-control input-xs" id="ddl_cliente" name="ddl_cliente" onchange="periodos(this.value);rangos();">
              <option value="">Seleccione Cliente</option>
            </select>
          </div>
          <div class="col-sm-1" style="padding: 0px;">
            <b>Serie</b>
              <select class="form-control input-xs" name="DCLinea" id="DCLinea" tabindex="1" style="padding-left:8px">
                <option value=""></option>
              </select>
          </div>
          <div class="col-sm-2">
            <b>Desde</b>
              <input type="date" name="txt_desde" id="txt_desde" class="form-control input-xs">           
          </div>
          <div class="col-sm-2">
            <b>Hasta</b>
              <input type="date" name="txt_hasta" id="txt_hasta" class="form-control input-xs">            
          </div>    			
    			<div class="col-sm-2"><br>
    				<button class="btn btn-primary btn-xs" type="button" onclick="validar()"><i class="fa fa-search"></i> Buscar</button>
    			</div>
      </form>

		</div>
    <div class="panel" id="panel_datos" style="display:none;margin-bottom: 1px;">
      <div class="row">
        <div class="col-sm-4">
          <b>Cliente: </b><i id="lbl_cliente"></i>
        </div>
         <div class="col-sm-3">
          <b>CI / RUC: </b><i id="lbl_ci_ruc"></i>
        </div>
         <div class="col-sm-3">
          <b>Telefono: </b><i id="lbl_tel"></i>
        </div>
         <div class="col-sm-4">
          <b>Email: </b><i id="lbl_ema"></i>
        </div>
         <div class="col-sm-8">
          <b>Direccion: </b><i id="lbl_dir"></i>
        </div>
      </div>      
    </div>
	<div class="row">
    <div class="col-sm-6">
      <h2 style="margin-top: 0px;">Listado de facturas</h2>
    </div>
    <div class="col-sm-6 text-right" id="panel_pag">
      
    </div>
		<div  class="col-sm-12" style="overflow-x: scroll;height: 500px;">    
      <table class="table text-sm" style=" white-space: nowrap;">
        <thead>
          <th></th>
          <th>T</th>          
          <th>Razon_Social</th>
          <th>TC</th>
          <th>Serie</th>
          <th>Autorizacion</th>
          <th>Factura</th>
          <th>Fecha</th>
          <th>SubTotal</th>
          <th>Con_IVA</th>
          <th>IVA</th>
          <th>Descuento</th>
          <th>Total</th>
          <th>Saldo</th>
          <th>RUC_CI</th>
          <th>TB</th>
        </thead>
        <tbody  id="tbl_tabla">
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
        </tbody>
      </table>
	
		</div>		
	</div>
  
</div>

<div id="modal_email" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Recuperar Clave</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
          <p>Su nueva clave se enviara al correo:</p>
          <h5 id="lbl_email">El usuario no tien un Email registrado contacte con la institucion</h5>
          <input type="hidden" name="txt_email" id="txt_email">
          <!-- <form enctype="multipart/form-data" id="form_img" method="post"> -->
           
          <!-- </form>   -->
          <br> 
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm btn-block"  id="btn_email" onclick="enviar_mail()"> Enviar Email</button>
        <button type="button" class="btn btn-default btn-sm btn-block"   data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enviar email</h5>
            </div>
            <div class="modal-body">
                <div class="row"> 
                    <div class="col-sm-12">
                        <div id="emails-input" name="emails-input" placeholder="añadir email"></div>
                        <input type="hidden" name="txt_fac" id="txt_fac">
                        <input type="hidden" name="txt_serie" id="txt_serie">
                        <input type="hidden" name="txt_codigoc" id="txt_codigoc">
                        <input type="hidden" name="txt_to" id="txt_to">
                    </div>
                    <div class="col-sm-12">
                      <input type="" id="txt_titulo" name="txt_titulo" class="form-control form-control-sm" placeholder="titulo de correo" value="comprobantes">
                    </div>
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="3" style="resize:none" placeholder="Texto" id="txt_texto" name="txt_texto"></textarea>
                    </div>                                                  
                    <div class="col-sm-3">
                        <label><input type="checkbox" name="cbx_factura" id="cbx_factura" checked>Enviar Factura</label>
                    </div>  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="enviar_email()" >Enviar</button>
            </div>
        </div>
  </div>
</div>





  <script src="../../dist/js/utils.js"></script>
  <script src="../../dist/js/emails-input.js"></script>
  <script src="../../dist/js/multiple_email.js"></script>