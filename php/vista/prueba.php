<?php 
include('../headers/header_panel.php');

?>
<script>
  $(document).ready(function(){
   

  })

  function funcion1()
  {

  	 $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/pruebaC.php?proceso=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
      
      }
    });
  }
  function funcion2()
  {

  	 $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/pruebaC.php?proceso2=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
      
      }
    });
  }
  function funcion3()
  {

  	 $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/pruebaC.php?proceso3=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
      
      }
    });
  }
  </script>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <button onclick="funcion1()">proceso</button>
        <button onclick="funcion2()">proceso 2</button>
        <button onclick="funcion3()">proceso 3</button>
      </section>

      <!-- Main content -->
      <section class="content" id="contenido">
        <div class="box">
          <div class="row">
            <div class=" col-xs-2 col-sm-3 col-lg-3">
              congresacion de las hermanas de la congregacion 
            </div>
            <div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              <div class="row">
                <table class="table">
                  <tr>
                    <td>ACCesos<input type="radio" name=""></td>
                    <td>ddd<input type="radio" name=""></td>
                    <td>asda<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasda<input type="radio" name=""></td>
                    <td>asdasd<input type="radio" name=""></td>
                    <td>das<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>asdas<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasdasas<input type="radio" name=""></td>

                  </tr>
                </table>                
              </div>
            </div>
          </div>
          <div class="row">
            <div class=" col-xs-2 col-sm-3 col-lg-3">
              congresacion de las hermanas de la congregacion 
            </div>
            <div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              <div class="row">
                <table class="table">
                  <tr>
                    <td>ACCesos<input type="radio" name=""></td>
                    <td>ddd<input type="radio" name=""></td>
                    <td>asda<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasda<input type="radio" name=""></td>
                    <td>asdasd<input type="radio" name=""></td>
                    <td>das<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>asdas<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasdasas<input type="radio" name=""></td>

                  </tr>
                </table>                
              </div>
            </div>
          </div>
          <div class="row">
            <div class=" col-xs-2 col-sm-3 col-lg-3">
              congresacion de las hermanas de la congregacion 
            </div>
            <div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              <div class="row">
                <table class="table">
                  <tr>
                    <td>ACCesos<input type="radio" name=""></td>
                    <td>ddd<input type="radio" name=""></td>
                    <td>asda<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasda<input type="radio" name=""></td>
                    <td>asdasd<input type="radio" name=""></td>
                    <td>das<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>asdas<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasdasas<input type="radio" name=""></td>

                  </tr>
                </table>                
              </div>
            </div>
          </div>
          <div class="row">
            <div class=" col-xs-2 col-sm-3 col-lg-3">
              congresacion de las hermanas de la congregacion 
            </div>
            <div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              <div class="row">
                <table class="table">
                  <tr>
                    <td>ACCesos<input type="radio" name=""></td>
                    <td>ddd<input type="radio" name=""></td>
                    <td>asda<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasda<input type="radio" name=""></td>
                    <td>asdasd<input type="radio" name=""></td>
                    <td>das<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>asdas<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasdasas<input type="radio" name=""></td>

                  </tr>
                </table>                
              </div>
            </div>
          </div>
          <div class="row">
            <div class=" col-xs-2 col-sm-3 col-lg-3">
              congresacion de las hermanas de la congregacion 
            </div>
            <div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              <div class="row">
                <table class="table">
                  <tr>
                    <td>ACCesos<input type="radio" name=""></td>
                    <td>ddd<input type="radio" name=""></td>
                    <td>asda<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasda<input type="radio" name=""></td>
                    <td>asdasd<input type="radio" name=""></td>
                    <td>das<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>asdas<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasdasas<input type="radio" name=""></td>

                  </tr>
                </table>                
              </div>
            </div>
          </div>
          <div class="row">
            <div class=" col-xs-2 col-sm-3 col-lg-3">
              congresacion de las hermanas de la congregacion 
            </div>
            <div class="col-xs-10 col-lg-9" style="overflow-x:scroll;">
              <div class="row">
                <table class="table">
                  <tr>
                    <td>ACCesos<input type="radio" name=""></td>
                    <td>ddd<input type="radio" name=""></td>
                    <td>asda<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasda<input type="radio" name=""></td>
                    <td>asdasd<input type="radio" name=""></td>
                    <td>das<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>asdas<input type="radio" name=""></td>
                    <td>dasd<input type="radio" name=""></td>
                    <td>dasdasas<input type="radio" name=""></td>

                  </tr>
                </table>                
              </div>
            </div>
          </div>
          
        </div>   	  
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>
  <?php include('../headers/footer_panel.php');?>
 