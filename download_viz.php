<!DOCTYPE html>
<html>
  <meta http-equiv="content-type" content="text/html; charset=UTF8">

  <div id ="sandbox" style="visibility:hidden;">
    <?php
      echo $_POST["viz_svg"];
    ?>
  </div>

  <script src="http://code.jquery.com/jquery-latest.js"></script>
  <script src="js/saveSvgAsPng.js"></script>
  <script>
    var canvas = $('#sandbox').find('svg')[0];
    saveSvgAsPng(canvas, 'diagram.png');
  </script>
</html>
