<script type="text/javascript">
  $(document).ready(function() {
    FInfoErrorShowView()
  });

  function FInfoErrorShowView(){
    $.ajax({
      type: "POST",                 
      url: '../controlador/modalesC.php?FInfoErrorShow=true',
      dataType:'json', 
      success: function(datos)             
      {
        var tbody = $("#DGInfoError tbody");
        for (var i = 0; i < datos.length; i++) {
            var fila = $("<tr>");
            fila.append($("<td>").text(datos[i]['Texto']));
            tbody.append(fila);
        }
      },
      error: function (e) {
        alert("error inesperado en Fechas de Cierres")
      }
    });
  }

</script>		

<style type="text/css">
.col{
  display: inline-block;
}
</style>	

<div class="box box-info">
  <div class="row">
    <div class="col-xs-12" style="margin: 5px;">
      <div class="col">
        <a  href="javascript:void(0)" title="Ok" class="btn btn-default">
          <img src="../../img/png/check_ok_accept.png" width="25" height="30">
          <!-- <br>OK -->
        </a>
      </div>
      <div class="col">
        <a  href="javascript:void(0)" title="Excel" class="btn btn-default" onclick="Diario_Caja()">
          <img src="../../img/png/excel.png" width="25" height="30">
          <!-- <br>Excel -->
        </a>
      </div>
      <div class="col">
        <a  href="javascript:void(0)" title="Imprimir" class="btn btn-default" onclick="Diario_Caja()">
          <img src="../../img/png/impresora.png" width="25" height="30">
          <!-- <br>Imprimir -->
        </a>
      </div>
    </div><br>
    <div class="col-xs-12">
      <div class="table-responsive" style="overflow-y: scroll; min-height: 50px;max-height:300px; width: auto;">
        <div class="sombra" style>
          <table id="DGInfoError" class="table-sm" style="width: -webkit-fill-available;">
            <thead>
              <tr>
                <th>Descripcion</th>
              </tr>
            </thead>
            <tbody id="DGInfoErrorBody">
            </tbody>
          </table>          
        </div>
      </div>
    </div>
  </div>
</div>          
