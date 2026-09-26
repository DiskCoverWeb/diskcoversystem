<?php date_default_timezone_set('America/Guayaquil'); 
$contratista = '-1'; 
if(isset($_GET['contratistaDetalle']) && $_GET['contratistaDetalle']!=''){$contratista = $_GET['contratistaDetalle'];}?>

<script type="text/javascript">
 var contratista = '<?php echo $contratista; ?>';
 // console.log(contratista);
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script type="text/javascript" src="../../dist/js/inventario/orden_ejecucion.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    if(contratista!='-1')
    {
      buscar_contratistas(contratista);
    }else{
      contratistas();
    }
   // ddl_cuenta_contable();
   // ddl_Proceso();
   // ddl_Grupo();
   ddl_Rubro();

  })

</script>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?php echo $NombreModulo; ?></div>
    <div class="ps-3">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0 p-0"  id="ruta_menu">
          <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
          </li>
        </ol>
      </nav>
    </div>          
</div>
<div class="row mb-2">
  <div class="col-sm-6">
     <div class="btn-group" role="group" aria-label="Basic example">
        <a href="<?php $ruta = explode('&', $_SERVER['REQUEST_URI']);
                print_r($ruta[0] . '#'); ?>" title="Salir de modulo" class="btn btn-outline-secondary">
            <img src="../../img/png/salire.png">
        </a>
        <button type="button" class="btn btn-outline-secondary" title="Informe excel" onclick="imprimir_excel()" >
          <img src="../../img/png/excel2.png">
        </button>
        <button type="button" class="btn btn-outline-secondary" title="Informe pdf" onclick="imprimir_pdf()">
          <img src="../../img/png/pdf.png">
        </button>
      <!--   <button title="Guardar"  class="btn btn-outline-secondary" onclick="grabar_orden_trabajo()">
          <img src="../../img/png/grabar.png" >
        </button> -->
      </div>
  </div>
</div>
<div class="row mb-2">
  <div class="card">
    <div class="card-body">
        <div class="row">
          <div class="col-lg-4">
            <b>Contratista </b><br>
            <select class="form-select form-select-sm" id="ddl_contratista" name="ddl_contratista" onchange="lista_semanas()">
              <option value="">Seleccione</option>
            </select>
          </div>
         <!--  <div class="col-lg-3">
            <b>Rubro </b><br>
            <select class="form-select form-select-sm" id="ddl_Rubro" name="ddl_Rubro">
              <option value="">seleccione</option>
            </select>
          </div> -->
          <div class="col-lg-2">
            <b>Semana </b><br>
            <select class="form-select form-select-sm" id="ddl_semana" name="ddl_semana" onchange="cargar_lista_subrubros()">
              <option value="">Seleccione semana</option>
            </select>
          </div>
          <div class="col-lg-3">
            <b>Contrato</b><br>
            <select class="form-select form-select-sm" id="ddl_Contrato" name="ddl_Contrato" disabled>
              <option value="">Seleccione</option>
            </select>
          </div>
          
        </div>
    </div>
  </div>
</div>
<div class="row mb-2" id="pnl_detalle_proyecto">
  <div class="card">
    <div class="card-body">
       <div class="row">
            <div class="col-sm-4">              
              <b>Proyectos: </b><br>
              <label id="lbl_proyecto">Proyecto de aaa</label>
            </div>   
           <!--  <div class="col-sm-12">
              <b>Contratista: </b>    <br>         
              <label>Proyecto de aaa</label>
            </div> -->
            <div class="col-sm-3">
                <b>Se entrega a cargo material: </b><br>
                <span class=" text-center" id="lbl_material">SI / NO</span>
            </div>
            <div class="col-sm-5">
              <b>El contratista ejecuta el trabajo con mas de una persona:</b><br>
              <span class=" text-center" id="lbl_mas_personas"> SI / NO </span>
            </div>
           
           <div class="col-sm-4">
            <b>Tipo Contrato: </b><br>
            <label id="lbl_categoria">Adicionales</label>
          </div>
          <div class="col-sm-4">
            <b>Fecha inicio: </b>  <br>
            <label id="lbl_fecha">2025-02-01</label>          
          </div>
          <div class="col-sm-2">
            <b>Fecha fin: </b> <br>
            <label id="lbl_fecha_v">2025-02-01</label>
          </div>
        </div>
    </div>
  </div>
</div>
<div class="row mb-2" id="pnl_centro_costos_proyecto">
  <div class="card">
    <div class="card-body">
      <div class="row" id="tbl_subrubros">
       <!--  <div class="col-sm-1">
          <input type="hidden" name="txt_centro_costos" id="txt_centro_costos">
          <input type="hidden" name="txt_id_centro_costos" id="txt_id_centro_costos">
        </div>
        <div class="col-sm-12">
          <h5>Cetro de costos: <span id="lbl_centro_costo"></span></h5>
        </div>
        <div class="col-sm-12">
          <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <th></th>
                  <th>Sub Rubros</th>
                  <th>Unidad</th>
                  <th>Orden</th>
                  <th>Costo total de Orden </th>
                  <th>Ejecutado</th>
                  <th>Costo unitario ejecutado</th>
                  <th>Costo total ejecutado</th>
                  <th>Diferencia</th>
                </thead>
                <tbody id="tbl_body"></tbody>
              </table>            
          </div>          
        </div>    -->
              
      </div>
    </div>    
  </div>
</div>

<div class="modal fade" id="myModal_periodo_trabajo" role="dialog" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-3">
                  <b>Fecha inicio</b> 
                  <b class="form-control" id="lbl_fecha_ini_sub">0000-00-00</b>              
                </div>              
                <div class="col-sm-3">
                    <b>Fecha fin</b>  
                    <b class="form-control" id="lbl_fecha_fin_sub">0000-00-00</b>     
                </div>  
                 <div class="col-sm-3">   
                    <b>Total ejecutado</b>  
                    <input id="txt_total_ejecutado" type="text" class="form-control form-control-sm" value="0" readonly>
                </div>                
                <div class="col-sm-3">
                    <b>Total de multa</b>  
                    <input id="txt_total_multa" type="text" class="form-control form-control-sm" value="0" readonly>        
                </div>
                                  
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-12">
                  Multas
                  <input type="hidden" name="txt_centroCostos" id="txt_centroCostos">
                  <input type="hidden" name="txt_rubro" id="txt_rubro">
                  <div  class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <td><b>Detalle</b></td>
                          <td><b>valor</b></td>
                          <td></td>
                        </tr>
                      </thead>
                      <tbody>
                         <tr>
                          <td style="width:45%">
                              <input id="todo-input" type="text" class="form-control" value="">
                          </td>
                          <td>
                              <input id="todo-input-valor" type="text" onKeyPress="return soloNumerosDecimales(event)" class="form-control" value="">
                          </td>
                          <td>
                             <button type="button" onclick="CreateTodo();" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>Agregar</button>
                          </td>
                        
                        </tr>  
                        <tr>
                          <td colspan="5">
                             <div id="todo-container"></div>                            
                          </td>
                        </tr>                      
                      </tbody>
                    </table>                    
                  </div>                  
                </div>     
                <hr>
                <div class="col-sm-12">
                  <b>Observacion</b>
                  <textarea id="txt_observacion" name="txt_observacion" class="form-control form-control-sm"></textarea>                  
                </div>           
              </div>
            </div>
             <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="btn_guardar_periodo" name="btn_guardar_periodo" onclick="guardar_subrubro_ejecucion()"><i class="bx bx-save"></i>Guardar</button>        
                  <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
              </div>
        </div>
    </div>
</div>

<script></script>
<script>
    // to do list 
     var todos = [];
    var currentTodo = {
      text: "",
      done: false,
      id: 0,
      valor:0,
    }
    document.getElementById("todo-input").oninput = function (e) {
      currentTodo.text = e.target.value;
    };
    /*
      //jQuery Version
      $('#todo-input').on('input',function(e){
        currentTodo.text = e.target.value;
         });
      */
    function DrawTodo(todo) {
      var newTodoHTML = `
      <div class="pb-1 todo-item" todo-id="${todo.id}">
          <div class="row">
              <div class="col-sm-8">
                  <input type="text" readonly class="form-control form-control-sm" value="${todo.text}">
              </div>
              <div class="col-sm-2">
                  <input type="text" readonly class="form-control form-control-sm" value="${todo.valor}">
              </div>
              <div class="col-sm-2">
                <button todo-id="${todo.id}" class="btn btn-outline-secondary bg-danger text-white btn-sm" type="button" onclick="DeleteTodo(this);" id="button-addon2 ">X</button>
              </div>
          </div>  
      </div>
        `;
      var dummy = document.createElement("DIV");
      dummy.innerHTML = newTodoHTML;
      document.getElementById("todo-container").appendChild(dummy.children[0]);
      /*
        //jQuery version
         var newTodo = $.parseHTML(newTodoHTML);
         $("#todo-container").append(newTodo);
        */
    }

    function RenderAllTodos() {
      var container = document.getElementById("todo-container");
      while (container.firstChild) {
        container.removeChild(container.firstChild);
      }
      /*
        //jQuery version
          $("todo-container").empty();
        */
      var total_multa = 0;
      console.log(todos);
      for (var i = 0; i < todos.length; i++) {
        total_multa+=parseFloat(todos[i].valor);
        DrawTodo(todos[i]);
      }

      $('#txt_total_multa').val(total_multa)
    }
    RenderAllTodos();

    function DeleteTodo(button) {
      var deleteID = parseInt(button.getAttribute("todo-id"));
      /*
        //jQuery version
          var deleteID = parseInt($(button).attr("todo-id"));
        */
      for (let i = 0; i < todos.length; i++) {
        if (todos[i].id === deleteID) {
          todos.splice(i, 1);
          RenderAllTodos();
          break;
        }
      }
    }

    function TodoChecked(id) {
      todos[id].done = !todos[id].done;
      RenderAllTodos();
    }

    function CreateTodo() {
      newtodo = {
        text: currentTodo.text,
        done: false,
        id: todos.length,
        valor:$('#todo-input-valor').val()
      }

      $('#todo-input').val('')
      $('#todo-input-valor').val('')
      todos.push(newtodo);
      RenderAllTodos();
    }
  </script>