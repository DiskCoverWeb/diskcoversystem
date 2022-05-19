<?php  require_once("panel.php"); $_SESSION['INGRESO']['modulo_']='60'; ?>
<script type="text/javascript">
  var f = '';

     function verificar_cuenta()
  {

    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?existe_cuenta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          if(response== -1)
          {
            // Swal.fire('','Genere una cuenta llamada "Cta_Desperdicio" para desperdicios.','info');
           window.location = "../vista/inventario.php?mod=Inventario&cuenta=-1";
          }else if(response == -2)
          {           
            window.location = "../vista/inventario.php?mod=Inventario&cuenta=-2";
          }
          
         
      }
    });
  }


  $(document).ready(function()
  {

    verificar_cuenta();

  $('#imprimir_pdf').click(function(){
  var url = '../controlador/inventario/inventario_onlineC.php?reporte_pdf';                
  window.open(url, '_blank');
}); 
  $('#imprimir_excel').click(function(){
  var url = '../controlador/inventario/inventario_onlineC.php?reporte_excel';                
  window.open(url, '_blank');
}); 


   autocmpletar();
    autocmpletar_rubro();
    autocmpletar_cc();
    cargar_entrega();
    autocmpletar_rubro_bajas();

      $('body').on('DOMNodeInserted', '.select2', function () {
               $("#"+this.id).select2({
                placeholder: 'Seleccione una producto',
                ajax: {
                url: '../controlador/inventario/inventario_onlineC.php?producto=true',
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
     });

     $('body').on('DOMNodeInserted', '.selectr', function () {
        
               $("#"+this.id).select2({
                placeholder: 'Seleccione rubro',
                ajax: {
                url: '../controlador/inventario/inventario_onlineC.php?rubro=true',
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
  }); 
       $('body').on('DOMNodeInserted', '.select_cc', function () {       
          
               $("#"+this.id).select2({
                 placeholder: 'Centro costo',
                 ajax: {
                 url: '../controlador/inventario/inventario_onlineC.php?cc=true',
                 dataType: 'json',
                 delay: 250,
                 processResults: function (data) {
                   return {
                     results: data
                     };
                 },
                 cache: true  
                 }       //      
            });
  }); 
              $('body').on('DOMNodeInserted', '.select_b', function () {       
          
               $("#"+this.id).select2({
                 placeholder: 'Centro costo',
                 ajax: {
                 url: '../controlador/inventario/inventario_onlineC.php?rubro_bajas=true',
                 dataType: 'json',
                 delay: 250,
                 processResults: function (data) {
                   return {
                     results: data
                     };
                 },
                 cache: true  
                 }       //      
            });
  }); 



  });


   function autocmpletar(id=''){
      $('#ddl_productos_'+id).select2({
        placeholder: 'Seleccione una producto',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?producto=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }
  function autocmpletar_rubro(id=''){
   
      $('#ddl_rubro_'+id).select2({
        placeholder: 'Seleccione rubro',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?rubro=true',
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
    function autocmpletar_rubro_bajas(id=''){
   
      $('#ddl_rubro_bajas_'+id).select2({
        placeholder: 'Seleccione Baja por',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?rubro_bajas=true',
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
   function autocmpletar_cc(id=''){
   
       $('#ddl_cc_'+id).select2({
        placeholder: 'Centro costo',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?cc=true',
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

  function cargar_datos(ID='')
  {
    if(ID=='')
    {
     var selec = $('#ddl_productos_').val();
     console.log(selec);
     var cod = selec.split(','); 
     console.log(cod);
     $('#txt_codigo_').val(cod[0]);
     stock_real(cod[0]);
     costo_venta(cod[0]);
     $('#txt_uni_').val(cod[1]);
     $('#TC').val(cod[3]);
    }else
    {
     var selec = $('#ddl_productos_'+ID).val();
     var cod = selec.split(','); 
     $('#txt_codigo_'+ID).val(cod[0]);
     $('#txt_uni_'+ID).val(cod[1]);
     $('#txt_uni_'+ID).val(cod[2]);

    }
  }
  function validar_stock()
  {
    var cant = 0;
    if($('#txt_stock').val()=='')
    {
       Swal.fire(
            '',
            'Seleccione un producto.',
            'info'
          );
       $('#txt_cant_').val(0);

    }else
    {
      var filas = $('#num_filas').val();
      var b = parseFloat($('#txt_bajas_').val());
       cant =parseFloat($('#txt_cant_').val())+parseFloat($('#txt_bajas_').val());
      if(filas != 0)
      {
         for (var i = 0; i < filas ; i++) {
           if($('#txt_codigo_').val() == $('#txt_codigo_'+i).val())
            {              
               cant = cant+parseFloat($('#txt_cant_'+i).val())+parseFloat($('#txt_bajas_'+i).val());
            }        
         }
     }
// console.log(cant);
      if(parseFloat($('#txt_stock').val()) >= cant)
      {

        var val = $('#valor_total').val();
        var can = $('#txt_cant_').val();
       $('#valor_total_linea').val((can*val).toFixed(2));

      }else
      {
        var val = $('#valor_total').val();
         Swal.fire(
            '',
            'La cantidad elegida supera al Stock de '+$('#txt_stock').val()+'.',
            'info'
          );

         if(b == 0)
         {
          $('#txt_cant_').val(0);
         }else
         {
           $('#txt_bajas_').val(0);
         }
       $('#valor_total_linea').val(0*val);

      }
    }
  }
   function validar_stock2(id)
  {
    if($('#txt_stock_'+id).val()=='')
    {
       Swal.fire(
            '',
            'Seleccione un producto.',
            'info'
          );
       $('#txt_cant_'+id).val(0);

    }else
    {
      var filas = $('#num_filas').val();
      var cant = $('#txt_cant_'+id).val();
      if(filas != 0)
      {
         for (var i = 0; i < filas ; i++) {
           if($('#txt_codigo_'+id).val() == $('#txt_codigo_'+i).val())
            {              
               cant = cant + $('#txt_cant_'+i).val();
            }        
         }
     }

      if($('#txt_stock_'+id).val() >= cant)
      {

        var val = $('#valor_total').val();
        var can = $('#txt_cant_').val();
       $('#valor_total_linea').val(can*val);

      }else
      {
         Swal.fire(
            '',
            'La cantidad elegida supera al Stock de '+$('#txt_stock_'+id).val()+'.',
            'info'
          );

       $('#txt_cant_'+id).val(0);       
        var val = $('#valor_total').val();
        var can = $('#txt_cant_').val();
       $('#valor_total_linea').val(can*val);

      }
    }
  }
  function cargar_entrega()
    {
      var lineas = '';
      
      $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/inventario/inventario_onlineC.php?entrega=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#contenido_entrega').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
        // },
        success:  function (response) { 
          if(response)
           {
            $('#num_filas').val(response.length);
            $.each(response,function(i,item){
              console.log(item.Fecha_Fab.date);
                lineas +='<div class="row"><div class="col-sm-12"><div class="col-sm-1"><b>Fecha</b><br><input type="text" readonly value="'+item.Fecha_Fab.date.substr(0,10)+'" class="form-control input-sm" id="fecha_'+i+'"/></div><div class="col-sm-1" style=" padding-left: 0px;  padding-right: 0px;"><b>Codigo</b><input type="hidden" id="txt_id_pro_'+i+'" value="'+item.CODIGO_INV+'"><input type="hidden" id="txt_pos_'+i+'" value="'+item.A_No+'"><input type="text" name="" id="txt_codigo_'+i+'" class="form-control input-sm" value="'+item.CODIGO_INV+'"></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Descripcion</b><select class="form-control select2" id="ddl_productos_'+i+'" name="ddl_productos_'+i+'" onchange="cargar_datos(\''+i+'\')" onfocus="cargar(\''+i+'\')"><option>Seleccione producto</option></select><input type="hidden" id="txt_stock_'+i+'"></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>UNI</b><input type="" value="'+item.UNIDAD+'" name="txt_uni_" id="txt_uni_'+i+'" class="form-control input-sm"></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>Cantidad</b><input value="'+item.CANT_ES+'" type="text" name="txt_cant_" id="txt_cant_'+i+'" placeholder="Cantidad" class="form-control input-sm" onblur="validar_stock2(\''+i+'\');"></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>Centro de costos</b><br><select class="form-control select_cc" id="ddl_cc_'+i+'"  id="ddl_cc_'+i+'"><option>Centro de costos</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>Rubro</b><br><select class="form-control selectr" id="ddl_rubro_'+i+'" name="ddl_rubro_'+i+'"><option>Rubro</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"> <b style="font-size: 13px;">Bajas o desperdicios</b><input type="" name="" placeholder="Bajas o desperdicios" id ="txt_bajas_'+i+'" class="form-control input-sm" value="'+item.Consumos+'" onblur="validar_bajas(\''+i+'\')"></div>';
                  
                    lineas+='<div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>Bajas por</b><br><select class="form-control select_b" id="ddl_rubro_bajas_'+i+'" name="ddl_rubro_bajas_'+i+'"><option>Bajas por</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>Observaciones</b><textarea placeholder="observacion" class="form-control" id="txt_obs_'+i+'">'+item.Procedencia+'</textarea></div><div class="col-sm-1"><br><button onclick="Guardar(\''+i+'\')" class="btn btn-primary" title="Guardar"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button><button onclick="eliminar(\''+i+'\')" class="btn btn-danger" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></div></div></div>';
              
                 
            });            
            $('#contenido_entrega').html(lineas);

            $.each(response,function(i,item){
              stock_pro(item.CODIGO_INV,i);
               $('#ddl_productos_'+i).append($('<option>',{value: item.CODIGO_INV, text: item.PRODUCTO,selected: true }));
                rubro_cod(item.SUBCTA,i);
               cc_cod(item.CONTRA_CTA,i);
               bajas_(item.Codigo_Dr,i);
            });
                
           }
        }
      });
  }

  function cargar(id)
  {
    alert('ddd');

  }

  function Guardar(id='')
  {
    rubro = $('#ddl_rubro_'+id).val().split(',');
    bajas = $('#txt_bajas_'+id).val();
    ddl_bajas = $('#ddl_rubro_bajas_'+id).val().split(',');
    producto = $('#ddl_productos_'+id).val().split(',');
    console.log(producto);
    var parametros = 
    {
        'codigo':$('#txt_codigo_'+id).val(),
        'producto':$('select[name="ddl_productos_'+id+'"] option:selected').text(),
        'cta_pro':producto[5],
        'uni':$('#txt_uni_'+id).val(),
        'cant':$('#txt_cant_'+id).val(),
        'cc':$('#ddl_cc_'+id).val(),
        'rubro':rubro[0],
        'bajas':bajas,
        'observacion':$('#txt_obs_'+id).val(),
        'id':id,
        'ante':$('#txt_id_pro_'+id).val(),
        'fecha':$('#txt_fecha').val(),
        'bajas_por':ddl_bajas[0],
        'TC':$('#TC').val(),
        'valor':$('#valor_total').val(),
        'total':$('#valor_total_linea').val(),
    };

  if(validar_entrada()==true)
  {
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?guardar=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response ==null)
        {
          Swal.fire(
            '',
            'Operaciopn realizada con exito.',
            'success'
          )
         location.reload();
        }else
        {
          Swal.fire(
            '',
            'Algo extraño a pasado.',
            'error'
          )

        }           
      }
    });
  }

  }


function validar_entrada()
{

  if($('#txt_bajas_').val()!=0)
  {
    if($('#ddl_rubro_bajas_').val() == "" || $('#txt_obs_').val()=="")
    {
       Swal.fire(
            '',
            'Procure escoger bajas y agregar un comentaerio.',
            'error'
          );
       return false;
    }
    if($('#ddl_cc_').val() !='' && $('#ddl_rubro_').val() !='')
    {

    return true;
  }else
  {
    Swal.fire(
            '',
            'Rubro o Centro de costo no seleccionado.',
            'error'
          );
    return false;
  }
  }else
  {
    if($('#ddl_rubro_bajas_').val() != "" || $('#txt_obs_').val()!="")
    {
       Swal.fire(
            '',
            'Procure colocar un valor en bajas.',
            'error'
          );
       return false;
    }
    if($('#ddl_cc_').val() !='' && $('#ddl_rubro_').val() !='')
    {

    return true;
  }else
  {
    Swal.fire(
            '',
            'Rubro o Centro de costo no seleccionado.',
            'error'
          );
    return false;
  }
  }

}

  function eliminar(id)
  {
    Swal.fire({
  title: 'Quiere eliminar linea?',
  text: "Esta seguro d eliminar linea!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si'
}).then((result) => {
  if (result.value) {

    var parametros = 
    {
        'id':$('#txt_codigo_'+id).val(),        
        'id_':$('#txt_pos_'+id).val(),
    };
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?eliminar_linea=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response ==1)
        {
          Swal.fire(
            '',
            'Agregado en entregas.',
            'success'
          )         
         cargar_entrega(); 
        }else
        {
          Swal.fire(
            '',
            'Algo extraño a pasado.',
            'error'
          )

        }           
      }
    });
  }
})

  }


   function stock_pro(id,pos)
  {

          // alert(id);
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?producto_id=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response);
          var selec = response[0].id;
     var cod = selec.split('/'); 
          $('#txt_stock_'+pos).val(cod[2]);
         
      }
    });
  }

   function rubro_cod(id,pos)
  {
    $.ajax({
     // data:  {parametros:parametros},
      url:    '../controlador/inventario/inventario_onlineC.php?rubro=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          // console.log(response)

             $('#ddl_rubro_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));
         
      }
    });
  }
function cc_cod(id,pos)
  {

          // console.log(id);
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?cc=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
             $('#ddl_cc_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));         
      }
    });
  }
  function bajas_(id,pos)
  {
    $.ajax({
     // data:  {parametros:parametros},
      url:  '../controlador/inventario/inventario_onlineC.php?rubro_bajas=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          // console.log(response)
             $('#ddl_rubro_bajas_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));         
      }
    });
  }

  function generar_asiento_datos()
  {
     $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?generar_asiento=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          if(response.length > 0)
          {
            generar_asiento(response);
          }
      }
    });

  }

  function datos_asientos()
  {
    var fechaA = f;
    var acabar_debe = 0;
    var acabar_haber = 0; 
      $.ajax({
      data:  {fechaA:fechaA},
      url:  '../controlador/inventario/inventario_onlineC.php?datos_asiento=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          $.each(response.debe,function(i,item){
            console.log(response.debe);
             var parametros = 
                {
                  "va" :item.valor,//valor que se trae del otal sumado
                  "dconcepto1" :item.dconcepto1,
                  "codigo" : item.codigo, // cuenta de codigo de 
                  "cuenta" : item.dconcepto1, // detalle de cuenta;
                  "efectivo_as" :item.fecha, // observacion si TC de catalogo de cuenta
                  "chq_as" : 0,
                  "moneda" : 1,
                  "tipo_cue" : item.tipo_cue,
                  "cotizacion" : 0,
                  "con" : 0,// depende de moneda
                  "t_no" : '60',
                  "ajax_page": 'ing1',
                  "cl": 'as_i',
                };
                generar_asiento(parametros);
          });
          acabar_debe = 1;
          $.each(response.haber,function(i,item){
            var parametros = 
                {
                  "va" :item.valor,//valor que se trae del otal sumado
                  "dconcepto1" :item.dconcepto1,
                  "codigo" : item.codigo, // cuenta de codigo de 
                  "cuenta" : item.dconcepto1, // detalle de cuenta;
                  "efectivo_as" :item.fecha, // observacion si TC de catalogo de cuenta
                  "chq_as" : 0,
                  "moneda" : 1,
                  "tipo_cue" : item.tipo_cue,
                  "cotizacion" : 0,
                  "con" : 0,// depende de moneda
                  "t_no" : '60',
                  "ajax_page": 'ing1',
                  "cl": 'as_i',
                };
                generar_asiento(parametros);
            
          });          
          acabar_haber = 1; 
          if(acabar_haber ==1 && acabar_debe == 1)
          {           
            numero_com();
          }else
          {
            alert('no entro');
          }           
      }
    });

  }

  function generar_asiento(parametros)
  {
     $.ajax({
      data:  parametros,
      url:   'ajax/vista_ajax.php',
      type:  'post',
      success:  function (response) {
        console.log(response);
      }
    });
  }

function generar_comprobante(parametros)
{
  var fech = f;
    var parame = 
    {
       "ajax_page": 'ing1',
      cl: 'ing_com',
      ru: parametros.ru, //codigo del cliente que sale co el ruc del beneficiario codigo
      tip:parametros.tip,//tipo de cuenta contable cd, etc
      fecha1: fech,// fecha actual 2020-09-21
      concepto: parametros.concepto, //detalle de la transaccion realida
      totalh: parametros.totalh, //total del haber
      num_com: parametros.num_com // codigo de comprobante de esta forma 2019-9000002
    };
    $.ajax({
       data:  parame,
      url:   'ajax/vista_ajax.php',
      type:  'post',
      // beforeSend: function () {
      //     $("#compro").html("");
      // },
       success:  function (response) {

        var com = parametros.num_com.split('-');       
         Swal.fire({
             //position: 'top-end',
            type: 'success',
            title: 'Comprobante '+com[1]+' ingresado con exito!',
            showConfirmButton: true
            //timer: 2500
          });                                   
      generar_uno_uno();
      }

    });
}


  function numero_com(){
   // var f = fec;
    var parametros = 
      {
        "ajax_page": 'bus',
        cl: 'num_com',
        tip: 'CD',
        fecha: f, //fecha                    
      };
      $.ajax({
        data:  parametros,
        url:   'ajax/vista_ajax.php',
        type:  'post',
        // beforeSend: function () {
        //     $("#num_com").html("");
        // },
        success:  function (response) {
          //console.log(response);
          datos_comprobante(response);
        }
      });
  }
function datos_comprobante(codigo)
{
  // if(readCookie('intentos')==null)
  // {
  //  document.cookie = "intentos=1";
  // }else
  // {
  //   var int = intval(readCookie('intentos'));
  //   int = int+1;
  //   document.cookie = "intentos="+int;
  // }
  // if(readCookie('intentos')<3)
  // {
  var fechaC = f;
   $.ajax({
      data:  {codigo:codigo,fechaC:fechaC},
      url:   '../controlador/inventario/inventario_onlineC.php?datos_comprobante=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          if(response==-1)
          {
            datos_asiento_SC();            
             // Swal.fire({
             //    type: 'error',
             //    title: 'Las transacciones no cuadran correntamente vuelva a intentar para corregir!',
             //    text: ''
             //  });
          }else if(response == -2)
          {
            datos_asiento_SC();
            // Swal.fire({
            //     type: 'error',
            //     title: 'Las transacciones no cuadran correntamente vuelva a intentar para corregir!',
            //     text: ''
            //   });
          }
          else
          {
            document.cookie = "intentos=; max-age=0";
            ingresar_trans_kardex(response.num_com,f);
            generar_comprobante(response);

          }
      }
    });
 // }else
 // {
 //  Swal.fire({
 //                type: 'error',
 //                title: 'Las transacciones no cuadran correntamente vuelva a intentar mas tarde!',
 //                text: ''
 //              });
 // }
} 
function ingresar_trans_kardex(comprobante,fechaC)
{
  //var f = fechaC;
   $.ajax({
      data:  {comprobante:comprobante,f:f},
      url:   '../controlador/inventario/inventario_onlineC.php?Trans_kardex=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        
      }
    });

}
function datos_asiento_SC()
{

  var fc = new Array();
  var num = $('#num_filas').val();
  for (var i = 0; i < num; i++) {
    fc[i] = $('#fecha_'+i).val();
  }
  let fec=fc.filter (
      (value,pos,self) => {
        return pos === self.indexOf(value);
      }
    );
  var fecha_c ='';
  $.each(fec,function(i,item){
      fecha_c+= item+'/'; 
  })
  $('#fechas_compro').val(fecha_c);
  generar_uno_uno();
}

function generar_uno_uno()
{
  if($('#fechas_compro').val() !="")
  {
    var fe = $('#fechas_compro').val().slice(0,-1).split('/');
    var res = $('#fechas_compro').val().split(fe[0]+'/').join('');
    $('#fechas_compro').val(res);
    f = fe[0];
     datos_asiento_SC_(fe[0]);
  }else
  {
    eliminar_asientosk();
  }
}

function eliminar_asientosk()
{
    $.ajax({
      //data:  {},
      url:  '../controlador/inventario/inventario_onlineC.php?eliminar_asientos_k=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {  
           cargar_entrega();
      }
    });



}

function datos_asiento_SC_(fecha)
{ 
 // var f = fecha;
  $.ajax({
      data:  {fecha:fecha},
      url:  '../controlador/inventario/inventario_onlineC.php?datos_asiento_SC=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 

          console.log(response);
         $.each(response,function(i,item){
           var parametros = 
                  {
                    "ajax_page": 'ing1',
                    cl: 'ing_sub1',
                    be:item.benericiario,
                    ru: item.ruc,
                    co: item.Codigo,// codigo de cuenta cc
                    tip: item.tipo,//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    tic: item.tic, //debito o credito (1 o 2);
                    sub: item.sub, //Codigo se trae catalogo subcuenta
                    sub2:item.benericiario,//nombre del beneficiario
                    fecha_sc: item.fecha, //fecha 
                    fac2: item.fac2,
                    mes: 0,
                    valorn: item.valorn,//valor de sub cuenta 
                    moneda: item.moneda, /// moneda 1
                    Trans: item.Trans,//detalle que se trae del asiento
                    T_N: item.T_N,
                    t: item.tipo,                        
                  };
                  // console.log(parametros);
                 asiendo_sc(parametros);
         }); 

        datos_asientos();     
      }
    });



}
  function asiendo_sc(parametros)
  {     
    console.log(parametros);
    $.ajax({
      data:  parametros,
      url:   'ajax/vista_ajax.php',
      type:  'post',
      success:  function (response) {
        // console.log(response);
      }
    });
  }


  function validar_bajas(id='')
  {  

      var bajas = $('#txt_bajas_').val();
      var cant = $('#txt_cant_').val();
      sum_stock = parseFloat(bajas)+parseFloat(cant);
      stock = parseFloat($('#txt_stock').val());
      if(sum_stock <= stock){
      if(bajas =='')
      {
        $('#txt_bajas_').val(0);
      }
        var bajas = $('#txt_bajas_').val();
      if(bajas !=0)
      {
         $('#ba').css('display','block');
        $('#ob').css('display','block');
      }else
      {
         $('#ba').hide();
         $('#ob').hide();

      }
    }else
    {
       Swal.fire(
            '',
            'La cantidad elegida supera al Stock de '+$('#txt_stock').val()+'.',
            'info'
          );

       $('#txt_bajas_').val(0);

    }
   }

   function stock_real(id)
   {
     $.ajax({
      data:  {id:id},
      url:   '../controlador/inventario/inventario_onlineC.php?stock_kardex=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          if(response[0].stock != null)
          {
          $('#txt_stock').val(response[0].stock);
          }else
          {
            $('#txt_stock').val(0);
             Swal.fire(
            '',
            'Este Producto no contiene  Stock',
            'info'
          );
             $('#ddl_productos_').val('').trigger('change');
          }
      }
    });
   }
     function costo_venta(id)
   {
     $.ajax({
      data:  {id:id},
      url:   '../controlador/inventario/inventario_onlineC.php?costo_venta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          //console.log(response);
          if(response[0].Costo!= null)
          {
            $('#valor_total').val(response[0].Costo);
          }else
          {
            $('#valor_total').val(0);            
          }
      }
    });
   }

 function comprueba_negativos(id){
  //var nu = parseFloat($('#txt_'+id+'_').val());
  var nu = $('#txt_'+id+'_').val();
  var patt = new RegExp("^[+]?[0-9]{1,9}(?:.[0-9]{1,3})?$");
  var patt1 = new RegExp("^[+]?[0-9]{1,9}(?:.)?$");
  if(isNaN(nu) || nu=="" )
  {
    nu = '';
  }else
  { 
    if(patt.test(nu))
    {
     // console.log('cumple');
    }else
    {
       if(patt1.test(nu))
        {
           //console.log('cumple');
        }else
        {
          nu=0
        }
    }
  }
//   if( /^-?(?:\d+(?:,\d*)?)$/.test(nu)){
//   if(nu < 0){
//      nu = 1;
//   }
// } 
$('#txt_'+id+'_').val(nu);

}
</script>

   <div style="padding-left: 20px;padding-right: 20px">
    <div class="row">
      <div class="col-lg-3 col-sm-4 col-md-8 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="./inventario.php?mod=Inventario#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button title="Consultar Catalogo de cuentas"  class="btn btn-default" onclick="datos_asiento_SC();/*datos_asientos();*//*numero_com();*/">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>
      </div>      
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h1><b>ENTREGA DE MATERIAL</b></h1>
      </div>
    </div>
    
   
   
   <div class="row">
    <div class="panel panel-info">
       <div class="panel-heading">
        <input type="hidden" name="" id="TC">
        <input type="hidden" name="" id="valor_total">
        <input type="hidden" name="" id="valor_total_linea">
        <input type="hidden" name="" id="fechas_compro">
         <div class="row">  
        <div class="col-sm-12">
               <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;width: 130px;">
                  <b>Fecha</b><br>
                   <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" value="<?php echo date('Y-m-d');?>">
                </div>
               <div class="col-sm-1" style=" padding-left: 0px;  padding-right: 0px;width: 6%;">
                  <b>Codigo</b>
                  <input type="text" name="txt_codigo_" id="txt_codigo_" disabled="" class="form-control input-sm">
                </div>
                <div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;">
                   <b>Descripcion</b><br>
                  <select class="form-control" id="ddl_productos_" name="ddl_productos_" onchange="cargar_datos()">
                    <option>Seleccione producto</option>
                  </select>
                </div>
                <div class="col-sm-1"  style="padding-left: 2px;  padding-right: 0px;">
                 <div class="row">
                    <div class="col-sm-6" style="padding-right:  2px;  padding-right: 0px;">
                       <b>UNI</b>                  
                       <input type="" name="txt_uni_" id="txt_uni_" disabled="" class="form-control input-sm">
                     </div>
                    <div class="col-sm-6" style="padding-left: 2px;  padding-right: 2px;">
                      <b>Stock </b>                        
                      <input type="" readonly="" class="form-control input-sm" name="txt_stock" id="txt_stock">
                    </div>
                  </div>
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Cantidad</b>
                  <input name="txt_cant_" id="txt_cant_" placeholder="Cantidad" class="form-control input-sm" onblur="validar_stock()" value="0" type="text" onkeyup="comprueba_negativos('cant')">
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Centro de costos</b><br>
                 <select class="form-control" id="ddl_cc_">
                   <option value="">Centro de costos</option>
                 </select>
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Rubro</b><br>
                    <select class="form-control" id="ddl_rubro_" name="ddl_rubro_">
                      <option value="">Rubro</option>
                    </select>
                </div>                
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b style="font-size: 13px;">Bajas o desperdicios</b>
                  <input name="" id="txt_bajas_" placeholder="Bajas o desperdicios" class="form-control input-sm" value="0" onblur="validar_stock();validar_bajas()" type="text" onkeyup="comprueba_negativos('bajas')">
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display: none" id="ba" >
                  <b>Baja por</b><br>
                    <select class="form-control" id="ddl_rubro_bajas_" name="ddl_rubro_bajas_">
                      <option value="">Baja por</option>
                    </select>
                </div>  
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px; display: none"  id ="ob">
                  <b>Observaciones</b>
                  <textarea placeholder="observacion" class="form-control" id="txt_obs_"></textarea>
                </div>
                <div class="col-sm-1" id="campos">
                  <br>
                  <button class="btn btn-primary btn-sm" title="Agregar" onclick="Guardar();"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                  <input type="hidden" name="" id="num_filas">
                </div>  
           </div>
    </div>

       </div>
       <div class="panel-body">
          <div class="text-center" id="contenido_entrega"></div>

       </div>
     </div>
   </div>