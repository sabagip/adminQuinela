<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Pronostica F1</title>
    <meta charset="utf-8">
    <link href="http://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet" type="text/css"/>
    <link rel="icon" href="<?php echo IMG_URL ."fastmag.png"; ?>" type="image/png" /> 
    <link href="http://fonts.googleapis.com/css?family=Oswald:300,400,700" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo STYLE_URL;?>estilos.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
    <script src="<?php echo JS_URL?>jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo STYLE_URL;?>css/alertify/alertify.css">
    <link rel="stylesheet" type="text/css" href="<?php echo STYLE_URL;?>css/alertify/alertify.rtl.css">
    <link rel="stylesheet" type="text/css" href="<?php echo STYLE_URL;?>css/alertify/themes/default.css">
    <link rel="stylesheet" type="text/css" href="<?php echo STYLE_URL;?>css/alertify/themes/default.rtl.css">
    <script src="<?php echo JS_URL?>alertify/alertify.js"></script>
    <?php 
            if(isset($css_files)):
                foreach($css_files as $file): ?>
                    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
         <?php  endforeach;
            endif;?>
        
        <?php 
            if(isset($js_files)):
                foreach($js_files as $file): ?>
                    <script src="<?php echo $file; ?>"></script>
         <?php  endforeach; 
    endif;?>
  </head>
