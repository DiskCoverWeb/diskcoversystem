var tbl_DGKardex;
var tbl_DGKardexRes;
$(document).ready(function()
{
  let param_datatable = {
    // responsive: true,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    },
    scrollX: true,
    paging:false,
    searching:false,
    pageLength: 100,
    info:false,
    // scrollY: 330,
    // scrollCollapse: true,
  };

  tbl_DGKardex = $('#tbl_DGKardex').DataTable(param_datatable);
  tbl_DGKardexRes = $('#tbl_DGKardexRes').DataTable(param_datatable);

  asignarHeightPantalla($("#LabelBodega"), $("#heightDisponible"))
  document.title = "Diskcover | EXISTENCIA DE INVENTARIO";

  autocomplete_dctinv();
  actualizarModoConsulta();

  $('#DCTInv').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
    cambiarProducto();
  });
  $('#DCBodega').on('select2:select', function (e) {
    var data = e.params.data;
    console.log(data);
    //cambiarProducto();
  });
});

function autocomplete_dctinv(){
  let codigoProducto = '';
  $('#DCTInv').select2({
    placeholder: 'Seleccionar',
    dropdownParent: $('#FormKardex'),
    ajax: {
        url: '../controlador/inventario/kardexC.php?cambiarProducto=true&tipo=I&codigoProducto='+codigoProducto,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    }
  });
}

function autocomplete_bodega(tipo){
  $('#DCBodega').select2({
    placeholder: tipo === 'BARRA' ? '** Seleccionar Código de barra**' : '** Seleccionar Bodega**',
    dropdownParent: $('#FormKardex'),
    width: '80%',
    ajax: {
        url: '../controlador/inventario/kardexC.php?bodegas=true&tipo='+encodeURIComponent(tipo),
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    }
  });
}

function actualizarModoConsulta(){
  var modo = $('input[name="ModoConsulta"]:checked').val() || 'TODOS';
  var $filtro = $('#DCBodega');

  if ($filtro.hasClass('select2-hidden-accessible')) {
    $filtro.select2('destroy');
  }
  $filtro.empty().append(new Option('** Seleccionar **', ''));
  $filtro.val('').prop('disabled', modo === 'TODOS');

  if (modo !== 'TODOS') {
    autocomplete_bodega(modo);
  }
}

function cambiarProducto(){
  let codigoProducto = $("#DCTInv").val();
  //producto = codigoProducto.split("/");
  $.ajax({
    type: "GET",                 
    url: '../controlador/inventario/kardexC.php?cambiarProducto=true&tipo=P&codigoProducto='+codigoProducto,
    success: function(data)             
    {
      if (data.length > 0) {
        datos = JSON.parse(data);
        console.log(datos);
        llenarComboList(datos,'DCInv')
      }else{
        console.log("No tiene datos");
      }        
    }
  });
}

function productoFinal(){
  let seleccion = $("#DCInv").val();
  if (!seleccion || seleccion.length === 0) {
    $("#LabelCodigo, #LabelUnidad, #LabelMinimo, #LabelMaximo, #NombreProducto").val('');
    $("#LabelExitencia").val('0.00');
    return;
  }
  let codigoProducto = seleccion[0];
  let producto = codigoProducto.split("/");
  let LabelCodigo = producto[0];
  let LabelMinimo = producto[1];
  let LabelMaximo = producto[2];
  let LabelUnidad = producto[3];
  let NombreProducto = producto[4];
  $("#LabelCodigo").val(LabelCodigo);
  $("#LabelUnidad").val(LabelUnidad);
  $("#LabelMinimo").val(formatearNumero(LabelMinimo));
  $("#LabelMaximo").val(formatearNumero(LabelMaximo));
  $("#LabelExitencia").val('0.00');
  $("#NombreProducto").val(NombreProducto);
}

function Consultar_Tipo_Kardex(EsKardexIndividual, porQR=false){//revisada
  $('#myModal_espera').modal('show');

  $('#DGKardex').show();
  $('#DGKardexRes').hide();
  tbl_DGKardex.destroy();
  tbl_DGKardex = $('#tbl_DGKardex').DataTable({
    
    pageLength: 100,
    // responsive: true,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    },
    /*columnDefs: [
        { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
    ],*/
    ajax: {
        url: porQR
          ? '../controlador/inventario/kardexC.php?Kardex_Total_QR=true'
          : '../controlador/inventario/kardexC.php?Consultar_Tipo_Kardex=true&EsKardexIndividual='+EsKardexIndividual,
        type: 'POST',  // Cambia el método a POST    
        data: function(d) {
            return $("#FormKardex").serialize();
        }, dataSrc: function(json) {
          $('#myModal_espera').modal('hide');
          if (json.error) {
            Swal.fire({ icon: 'warning', title: 'Consulta no disponible', text: json.mensaje });
            return [];
          }
          $('#LabelExitencia').val(json.LabelExistencia); 
          return json.data;
        }                     
    },
    scrollX: true,  // Habilitar desplazamiento horizontal
    //     paging:false,
    //     searching:false,
    //     info:false,
    fixedHeader: true,
    //responsive: true,
    // scrollY: '330px',
    // scrollCollapse: true,
    columns: [
        { data: null,
          render: function(data, type, item) {
            botons = `<button type="button" class="btn btn-sm btn-warning pe-0" onclick="Cambiar_Articulo('${data.Producto}', '${data.ID}', '${data.TC}', '${data.Serie}', '${data.Factura}', '${data.Codigo_Inv}');"><i class="bx bx-refresh"></i></button>
                      <button type="button" class="btn btn-sm btn-info pe-0" onclick="Cambia_Codigo_de_Barra('${data.Producto}', '${data.ID}', '${data.TC}', '${data.Serie}', '${data.Factura}', '${data.Codigo_Inv}');"><i class="bx bx-barcode"></i></button>
                      <button type="button" class="btn btn-sm btn-success pe-0" onclick="Cambia_la_Serie('${data.Producto}', '${data.ID}', '${data.TC}', '${data.Serie}', '${data.Factura}', '${data.Codigo_Inv}');"><i class="bx bx-retweet"></i></button>
                      <button type="button" class="btn btn-sm btn-danger pe-0" onclick="Imprime_Codigos_de_Barra(this);"><i class="bx bx-printer"></i></button>`;

            return botons;                    
          },
        width:'160px'},
        { data: 'Codigo_Inv' , width:'100px'},
        { data: 'Producto' , width:'400px'},
        { data: 'Unidad' , width:'100px'},
        { data: 'Bodega' , width:'150px'},
        { data: 'Fecha.date',  
          render: function(data, type, item) {
              return data ? new Date(data).toLocaleDateString() : '';
          }
        , width:'100px'},
        { data: 'TP' , width:'50px'},
        { data: 'Numero' , width:'60px', className: 'text-end'},
        { data: 'Entrada' , width:'60px', className: 'text-end'},
        { data: 'Salida' , width:'60px', className: 'text-end'},
        { data: 'Stock' , width:'60px', className: 'text-end'},
        { data: 'Costo' , width:'60px', className: 'text-end'},
        { data: 'Saldo' , width:'60px', className: 'text-end',  
          render: function(data, type, item) {
              return data ? parseFloat(data).toFixed(2) : '';
          }
        },
        { data: 'Valor_Unitario' , width:'100px', className: 'text-end'},
        { data: 'Valor_Total' , width:'100px', className: 'text-end',  
          render: function(data, type, item) {
              return data ? parseFloat(data).toFixed(2) : '';
          }
        },
        { data: 'Fecha_Exp', width:'100px',
          render: function(data) {
            return data && data.date ? new Date(data.date).toLocaleDateString() : '';
          }
        },
        { data: 'TC' , width:'60px'},
        { data: 'Serie' , width:'80px'},
        { data: 'Factura' , width:'70px', className: 'text-end'},
        { data: 'Cta_Inv' , width:'100px'},
        { data: 'Contra_Cta' , width:'100px'},
        { data: 'Temperatura' , width:'100px'},
        { data: 'Serie_No' , width:'70px'},
        { data: 'Codigo_Barra' , width:'100px'},
        { data: 'Lote_No' , width:'100px'},
        { data: 'CI_RUC_CC' , width:'100px'},
        { data: 'Marca_Tipo_Proceso' , width:'150px'},
        { data: 'Detalle' , width:'100px'},
        { data: 'Beneficiario_Centro_Costo' , width:'250px'},
        { data: 'Orden_No' , width:'150px'},
        { data: 'ID' , width:'80px'},
    ]
  });

  
  /*$.ajax({
    type: "POST",                 
    url: '../controlador/inventario/kardexC.php?Consultar_Tipo_Kardex=true&EsKardexIndividual='+EsKardexIndividual,
    dataType: 'json',
    data: $("#FormKardex").serialize(),
    success: function(data)             
    {
      if (data.length<=0) {
        Swal.fire({
          icon: 'warning',
          title: 'No hay informacion',
          text: ''
        });
      }else{
        document.title = "Diskcover | " + (EsKardexIndividual ? "EXISTENCIA DE INVENTARIO" : "EXISTENCIA DE TODOS LOS INVENTARIOS");

        $('#DGKardex').html(data.DGKardex);   
        $('#LabelExitencia').val(data.LabelExitencia); 
      }  
      $('#myModal_espera').modal('hide');      
    }
  });*/
}

function consulta_kardex(){ //revisado
  $('#DGKardex').hide();
  $('#DGKardexRes').show();
  tbl_DGKardexRes.destroy();
  tbl_DGKardexRes = $('#tbl_DGKardexRes').DataTable({
    // responsive: true,
    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
    },
    /*columnDefs: [
        { targets: [8,9,10,11,12,13], className: 'text-end' } // Alinea las columnas 0, 2 y 4 a la derecha
    ],*/
    ajax: {
        url: '../controlador/inventario/kardexC.php?consulta_kardex=true',
        type: 'POST',  // Cambia el método a POST    
        data: function(d) {
            return $("#FormKardex").serialize();
        }, 
        dataSrc: function(json) {
          $('#LabelExitencia').val(json.LabelExistencia); 
          return json.data;
        }                     
    },
    scrollX: true,  // Habilitar desplazamiento horizontal
    //     paging:false,
    //     searching:false,
    //     info:false,
    fixedHeader: true,
    //responsive: true,
    scrollY: '330px',
    scrollCollapse: true,
    columns: [
        { data: 'Codigo_Inv' , width:'100px'},
        { data: 'Codigo_Barra' , width:'200px'},
        { data: 'Entradas' , width:'100px', className: 'text-end'},
        { data: 'Salidas' , width:'100px', className: 'text-end'},
        { data: 'Stock_Kardex' , width:'100px', className: 'text-end'}
    ]
  });

  // $('#myModal_espera').modal('show');
  // $.ajax({
  //   type: "POST",                 
  //   url: '../controlador/inventario/kardexC.php?consulta_kardex=true',
  //   dataType: 'json',
  //   data: $("#FormKardex").serialize(), 
  //   success: function(data)             
  //   {
  //     if (data.error) {
  //       Swal.fire({
  //         type: 'warning',
  //         title: '',
  //         text: data.mensaje
  //       });
  //     }else{
  //       $('#DGKardex').html(data.DGKardex);   
  //       $('#LabelExitencia').val(data.LabelExitencia); 
  //     }   
  //     $('#myModal_espera').modal('hide');     
  //   }
  // });
}

function generarPDF(){
  url = '../controlador/inventario/kardexC.php?generarPDF=true&'+$("#FormKardex").serialize();
  window.open(url, '_blank');
}

function generarExcelKardex(){ //revisada
  url = '../controlador/inventario/kardexC.php?generarExcelKardex=true&'+$("#FormKardex").serialize();
  window.open(url, '_blank');
}

function Eliminar_Kardex_Cero(){
  Swal.fire({
    icon: 'warning',
    title: '¿Eliminar comprobantes en cero?',
    text: 'Se eliminarán los registros con número 0 entre las fechas seleccionadas.',
    showCancelButton: true,
    confirmButtonText: 'Eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (!result.value) {
      return;
    }
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: '../controlador/inventario/kardexC.php?Eliminar_Kardex_Cero=true',
      data: $('#FormKardex').serialize(),
      success: function(response){
        Swal.fire({
          icon: response.rps ? 'success' : 'error',
          title: response.rps ? 'Proceso terminado' : 'No se pudo completar',
          text: response.mensaje
        });
      },
      error: function(){
        Swal.fire('Error', 'Ocurrió un error al eliminar los registros.', 'error');
      }
    });
  });
}

function Imprime_Codigos_de_Barra(button){
  var fila = tbl_DGKardex.row($(button).closest('tr')).data();
  if (!fila) {
    Swal.fire('Error', 'No fue posible leer el producto seleccionado.', 'error');
    return;
  }
  var ventanaImpresion = window.open('', '_blank');
  if (!ventanaImpresion) {
    Swal.fire('Aviso', 'Permita las ventanas emergentes para generar el PDF.', 'warning');
    return;
  }
  var cantidadSugerida = Math.max(1, Math.floor(Number(fila.Stock) || 1));
  Swal.fire({
    title: 'Cantidad de códigos a imprimir',
    input: 'number',
    inputValue: cantidadSugerida,
    inputAttributes: { min: 1, max: 1000, step: 1 },
    showCancelButton: true,
    confirmButtonText: 'Generar PDF',
    cancelButtonText: 'Cancelar',
    preConfirm: function(cantidad){
      var valor = Number(cantidad);
      if (!Number.isInteger(valor) || valor < 1 || valor > 1000) {
        Swal.showValidationMessage('Ingrese una cantidad entre 1 y 1000.');
        return false;
      }
      return valor;
    }
  }).then(function(result){
    if (result.value) {
      var url = '../controlador/inventario/kardexC.php?imprimirCodigoBarras=true'
        + '&ID_Reg=' + encodeURIComponent(fila.ID)
        + '&Cantidad=' + encodeURIComponent(result.value);
      ventanaImpresion.location = url;
    } else {
      ventanaImpresion.close();
    }
  });
}

function Cambia_la_Serie(Producto, ID_Reg, TC, Serie, Factura, CodigoInv) {//revisada
  Swal.fire({
    title: 'INGRESE LA SERIE DE ESTE PRODUCTO: '+Producto,
    showCancelButton: true,
    cancelButtonText: 'Cerrar',
    confirmButtonText: 'Actualizar',
    html:
      '<label for="CodigoP">INGRESO DE SERIE:</label>' +
      '<input type="tel" id="CodigoP" class="swal2-input" required>' +
      '<span id="error1" style="color: red;"></span><br>',
    focusConfirm: false,
    preConfirm: () => {
      const CodigoP = document.getElementById('CodigoP').value;
      if(CodigoP!="" && CodigoP!="."){
        return [CodigoP];
      }else{
        Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar una serie para actualizar';
        return false
      }
    }
  }).then((result) => {
    if (result.value) {
      const [CodigoP] = result.value;
      if(CodigoP!="" && CodigoP!="."){
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '../controlador/inventario/kardexC.php?ActualizarSerie=true',
          data: {'CodigoP' : CodigoP, 'ID_Reg':ID_Reg, 'TC':TC, 'Serie':Serie, 'Factura':Factura, 'CodigoInv':CodigoInv },
          beforeSend: function () {   
            $('#myModal_espera').modal('show');
          },    
          success: function(response)
          { 
            setTimeout(()=>{
              $('#myModal_espera').modal('hide');        
              if(response.rps){
                Swal.fire('¡Bien!', response.mensaje, 'success')
              }else{
                Swal.fire('¡Oops!', response.mensaje, 'warning')
              }

            }, 500);
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });
      }
    }
  });
}

function Cambia_Codigo_de_Barra(Producto, ID_Reg, TC, Serie, Factura, CodigoInv) {
  Swal.fire({
    title: 'INGRESE EL CODIGO DE BARRAS DE ESTE PRODUCTO: '+Producto,
    showCancelButton: true,
    cancelButtonText: 'Cerrar',
    confirmButtonText: 'Actualizar',
    html:
      '<label for="CodigoB">INGRESO DE CODIGO DE BARRAS:</label>' +
      '<input type="tel" id="CodigoB" class="swal2-input" required>' +
      '<span id="error1" style="color: red;"></span><br>',
    focusConfirm: false,
    preConfirm: () => {
      const CodigoB = document.getElementById('CodigoB').value;
      if(CodigoB!="" && CodigoB!="."){
        return [CodigoB];
      }else{
        Swal.getPopup().querySelector('#error1').textContent = 'Debe ingresar una serie para actualizar';
        return false
      }
    }
  }).then((result) => {
    if (result.value) {
      const [CodigoB] = result.value;
      if(CodigoB!="" && CodigoB!="."){
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url: '../controlador/inventario/kardexC.php?CambiaCodigodeBarra=true',
          data: {'CodigoB' : CodigoB, 'ID_Reg':ID_Reg, 'TC':TC, 'Serie':Serie, 'Factura':Factura, 'CodigoInv':CodigoInv },
          beforeSend: function () {   
            $('#myModal_espera').modal('show');
          },    
          success: function(response)
          { 
            setTimeout(()=>{
              $('#myModal_espera').modal('hide');        
              if(response.rps){
                Swal.fire('¡Bien!', response.mensaje, 'success')
              }else{
                Swal.fire('¡Oops!', response.mensaje, 'warning')
              }
            }, 500);
          },
          error: function () {
            $('#myModal_espera').modal('hide');
            alert("Ocurrio un error inesperado, por favor contacte a soporte.");
          }
        });
      }
    }
  });
}

function Cambiar_Articulo(Producto, ID_Reg, TC, Serie, Factura, CodigoInv) {
  $('#FrmProductos').modal('show');

  $("#LblProducto").val(Producto) 
  $("#ID_Reg").val(ID_Reg) 
  $("#TC").val(TC) 
  $("#Serie").val(Serie) 
  $("#Factura").val(Factura) 
  $("#CodigoInv").val(CodigoInv) 

  $('#DCArt').select2({
    placeholder: '** Seleccionar Nuevo**',
    dropdownParent: $('#FrmProductos'),
    width:'100%',
    ajax: {
        url: '../controlador/inventario/kardexC.php?ListarArticulos=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    }
  });
  
  /*$.ajax({
    type: 'POST',
    dataType: 'json',
    url: '../controlador/inventario/kardexC.php?ListarArticulos=true',
    beforeSend: function () {   
      $('#myModal_espera').modal('show');
    },    
    success: function(response)
    { 
      if(response.rps){
        $('#myModal_espera').modal('hide');
        $("#LblProducto").val(Producto) 
        $("#ID_Reg").val(ID_Reg) 
        $("#TC").val(TC) 
        $("#Serie").val(Serie) 
        $("#Factura").val(Factura) 
        $("#CodigoInv").val(CodigoInv) 
        $('#FrmProductos').modal('show');
        llenarComboList(response.DCArt,'DCArt')
        $("#DCArt").focus();
      }else{
        $('#myModal_espera').modal('hide');
        Swal.fire('¡Oops!', response.mensaje, 'warning')
      }
    },
    error: function () {
      $('#myModal_espera').modal('hide');
      alert("Ocurrio un error inesperado, por favor contacte a soporte.");
    }
  });*/
}
function AceptarCambio() {
  Swal.fire({
    title: 'PREGUNTA DE ACTUALIZACION',
    showCancelButton: true,
    cancelButtonText: 'Cerrar',
    confirmButtonText: 'Actualizar',
    html:
      '<label for="">Esta seguro de cambiar: '+$("#LblProducto").val()+'</label>' +
      '<label for="">por el Producto:'+$('#DCArt option:selected').text()+'</label>',
    focusConfirm: false,
  }).then((result) => {
    if (result.value) {
      $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '../controlador/inventario/kardexC.php?ConfirmarCambiar_Articulo=true',
        data: $("#FormCambiarProducto").serialize(),
        beforeSend: function () {   
          $('#myModal_espera').modal('show');
        },    
        success: function(response)
        { 
          setTimeout(()=>{
            $('#myModal_espera').modal('hide'); 
            if(response.rps){
              $('#FrmProductos').modal('hide');
              Swal.fire('¡Bien!', response.mensaje, 'success')
            }else{
              Swal.fire('¡Oops!', response.mensaje, 'warning')
            } 
          }, 500);
                
        },
        error: function () {
          $('#myModal_espera').modal('hide');
          alert("Ocurrio un error inesperado, por favor contacte a soporte.");
        }
      });
    }
  });
}