<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8" lang="es">
        <title>Pruebas Quiniela</title>
        <link rel="stylesheet"  href="<?php echo STYLE_URL; ?>estilo.css" type="text/css" media="all"/>
        <!--<link rel="stylesheet"  href="<?php echo STYLE_URL; ?>estilos.css" type="text/css" media="all"/>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="<?php echo JS_URL?>jquery-ui.min.js"></script>
        <script src="<?php echo JS_URL?>countdown.js"></script>
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
    <body>
        
        
        
        
    
