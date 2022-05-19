<?php  require_once("panel.php");?>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button title="Guardar"  class="btn btn-default" onclick="">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>     
 </div>
</div>
<div class="container">
  <div class="row"><br>
     <div class="panel panel-primary">
      <div class="panel-heading text-center"><b>INSERTAR FACTURA</b></div>
      <div class="panel-body" style="border: 1px solid #337ab7;">
        <div class="row">
          <div class="col-sm-6"> 
            <b>Codigo Cliente:</b>
            <input type="text" name="txt_codcli" id="txt_codcli" class="form-control input-sm">            
            <b>Nombre:</b>
            <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm">
           <b>Fecha:</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm">            
          </div>
          <div class="col-sm-6">
             <b>COD FACTURA:</b>
            <input type="text" name="txt_departamento" id="txt_departamento" class="form-control input-sm">
            <b>RUC:</b>
            <input type="text" name="txt_departamento" id="txt_departamento" class="form-control input-sm">            
            <b>IVA:</b>
            <input type="text" name="txt_habitacion" id="txt_habitacion" class="form-control input-sm">
          </div>          
        </div>
      </div>
       <div class="panel-body">
        <div class="row">
          <div class="col-sm-6"> 
            <b>Referencia:</b>
            <input type="text" name="txt_codcli" id="txt_codcli" class="form-control input-sm">            
          </div>
          <div class="col-sm-6">           
          </div>          
        </div>
        <div class="row">
              <div class="col-sm-4"> 
                <b>Descripcion:</b>
                <input type="text" name="txt_codcli" id="txt_codcli" class="form-control input-sm">            
              </div>   
              <div class="col-sm-2"> 
                <b>Precio:</b>
                <input type="text" name="txt_codcli" id="txt_codcli" class="form-control input-sm">            
              </div>   
              <div class="col-sm-1"> 
                <b>Cantidad:</b>
                <input type="text" name="txt_codcli" id="txt_codcli" class="form-control input-sm">            
              </div>   
              <div class="col-sm-2"> 
                <b>Dscto:</b>
                <input type="text" name="txt_codcli" id="txt_codcli" class="form-control input-sm">            
              </div>   
              <div class="col-sm-1"> 
                <b>Importe:</b>
                <input type="text" name="txt_codcli" id="txt_codcli" class="form-control input-sm">            
              </div> 
              <div class="col-sm-1"><br>
                <button class="btn btn-primary"><i class="fa fa-arrow-down"></i> Agregar</button>
              </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="table-responsive" style="height:400px">
      <table class="table table-hover">
        <thead>
          <th>ITEM</th>
          <th>REFERENCIA</th>
          <th class="text-center">DESCRIPCION</th>
          <th>CANTIDAD</th>
          <th>PRECIO</th>
          <th>DCTO %</th>
          <th>IMPORTE</th>
        </thead>
        <tbody style="height:400px">
          
        </tbody>
      </table>
    </div>
    
  </div>
  <div class="row">
    <div class="col-sm-9">
      <button type="button" class="btn btn-primary"><i class="fa fa-check"></i> Aceptar</button>
      <button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelar</button>      
    </div>
    <div class="col-sm-3">
      <div class="row">
          <div class="col-sm-6">
            Sub Total:
          </div>
          <div class="col-sm-6">
            <input type="text" name="" class="form-control input-sm">
          </div>        
      </div> 
      <div class="row">
          <div class="col-sm-6">
            IVA:
          </div>
          <div class="col-sm-6">
            <input type="text" name="" class="form-control input-sm">
          </div>        
      </div> 
      <div class="row">
          <div class="col-sm-6">
            Precio Total:
          </div>
          <div class="col-sm-6">
            <input type="text" name="" class="form-control input-sm">
          </div>        
      </div>      
    </div>   
  </div>   
</div>
