<?php 
include('../headers/header.php');

?>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  <link rel="stylesheet" href="../../dist/css/arbol_bodega.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>
<script>
  $(document).ready(function(){
   

  })

 
  </script>
  <!-- Full Width Column -->
  <div class="content-wrapper">
    <div class="container">

      <!-- partial:index.partial.html -->
<div id="fontSizeWrapper">
  <label for="fontSize">Font size</label>
  <input type="range" value="1" id="fontSize" step="0.5" min="0.5" max="5" />
</div>
<ul class="tree">
  <li>
    <input type="checkbox" checked="checked" id="c1" />
    <label class="tree_label" for="c1">Level 0</label>
    <ul>
      <li>
        <input type="checkbox" checked="checked" id="c2" />
        <label for="c2" class="tree_label">Level 1</label>
        <ul>
          <li><span class="tree_label">Level 2</span></li>
          <li><span class="tree_label">Level 2</span></li>
        </ul>
      </li>
      <li>
        <input type="checkbox" id="c3" />
        <label for="c3" class="tree_label">Looong level 1 <br/>label text <br/>with line-breaks</label>
        <ul>
          <li><span class="tree_label">Level 2</span></li>
          <li>
            <input type="checkbox" id="c4" />
            <label for="c4" class="tree_label"><span class="tree_custom">Specified tree item view</span></label>
            <ul>
              <li><span class="tree_label">Level 3</span></li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
  
  
  <li>
    <input type="checkbox" id="c5" />
    <label class="tree_label" for="c5">Level 0</label>
    <ul>
      <li>
        <input type="checkbox" id="c6" />
        <label for="c6" class="tree_label">Level 1</label>
        <ul>
          <li><span class="tree_label">Level 2</span></li>
        </ul>
      </li>
      <li>
        <input type="checkbox" id="c7" />
        <label for="c7" class="tree_label">Level 1</label>
        <ul>
          <li><span class="tree_label">Level 2</span></li>
          <li>
            <input type="checkbox" id="c8" />
            <label for="c8" class="tree_label">Level 2</label>
            <ul>
              <li><span class="tree_label">Level 3</span></li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>

 <script src="../../dist/js/arbol_bodega.js"></script>

     
    </div>
  </div>
  <?php include('../headers/footer.php');?>
 