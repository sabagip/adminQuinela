<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Pronostica F1</title>
    <meta charset="utf-8">
    <link rel="icon" href="<?php echo IMG_URL ."fastmag.png"; ?>" type="image/png" />
    <link href="http://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="<?php echo STYLE_URL; ?>estilos.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
        
  </head>
  <body class="home">
    <div class="wrapperHome fadeIn animated">
      <figure><img src="<?php echo IMG_URL; ?>logoQuiniela.png" alt=""></figure>
      <p>ADMINISTRA Y TEN ACTUALIZADA LA INFORMACIÓN DE LOS GP'S DE LA FORMULA 1.</p>
      <div class="navigationHome fadeInUp animated"><a id="callIniciarSesion" href="#" class="boton boton--general waves-effect waves-light">INICIAR SESIÓN</a></div>
    </div>
    <div id="registro" class="lightbox">
      <div class="lightbox__fondo fadeIn animated"></div>
      <div class="lightbox__contenedor slideInUp animated">
        <h3 class="lightbox__titulo z-depth3">REGÍSTRATE</h3>
        <div class="lightbox__contenido z-depth3"><br>
          <div class="row"><a href="<?php echo $facebook?> " class="col s4 waves-effect waves-light"><img src="<?php echo IMG_URL; ?>facebook-signin.png" alt=""></a><a href="#" class="col s4 waves-effect waves-light"><img src="<?php echo IMG_URL; ?>twitter-signin.png" alt=""></a><a href="#" class="col s4 waves-effect waves-light"><img src="<?php echo IMG_URL; ?>googleplus-signin.png" alt=""></a></div>
          <div class="separador"> 
            <h5>ó</h5>
          </div>
        </div>
      </div>
    </div>
    <div id="iniciarSesion" class="lightbox">
      <div class="lightbox__fondo fadeIn animated"></div>
      <div class="lightbox__contenedor slideInUp animated">
        <h3 class="lightbox__titulo z-depth3">INICIAR SESIÓN</h3>
        <div class="lightbox__contenido z-depth3"><br>
            <form class="row" method="post" id="frmLogin" action="<?php echo BASE_URL2 ."index/login" ?>">
            <!-- Datos de Cuenta-->
            <div class="row">
              <div class="input-field col s12">
                <input id="userName" name="userName" type="text" class="validate">
                <label for="userName">Nombre de Usuario</label>
              </div>
            </div>
            <div class="row">
              <div class="input-field col s12">
                <input id="password" name="password" type="password" class="validate">
                <label for="password">Contraseña</label>
              </div>
            </div>
            <input type="hidden" name="token" value="<?php echo $token ;?>">
            <div class="row"><a id="submitIniciarSesion" class="boton boton--secundario waves-effect waves-light" >INICIAR SESIÓN</a></div>
          </form>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>materialize/dist/js/materialize.min.js"></script>
    <script src="<?php echo JS_URL; ?>Quiniela.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
    <script>
        $(function(){
         $("#submitIniciarSesion").click(function(){
            $('#frmLogin').submit();
         });
        });                
        
        
        
    </script>
  </body>
</html>