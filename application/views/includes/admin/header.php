    <body>
        <script>
         (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
         (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
         m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
         })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

         ga('create', 'UA-61658547-1', 'auto');
         ga('send', 'pageview');

        </script>
        <div id="principal">
          <div class="contenedor"><a href="http://fast-mag.com" target="_blank" class="fast-mag"><img src="<?php echo IMG_URL;?>FMW.png"></a><a class="menuActivador"><img src="<?php echo IMG_URL; ?>menuActivador.png"></a>
            <nav class="navegacionGeneral">
                <div id="menuItems">
                    <a title="Usuarios" href="<?php echo base_url() ."index.php/admin/bienvenido"; ?>" class="big">Usuarios</a>
                    <a title="Administradores" href="<?php echo base_url() ."index.php/admin/bienvenido/admin"; ?>" class="big">Administradores</a>
                    <a title="Jornadas" href="<?php echo base_url() ."index.php/admin/bienvenido/jornadas"; ?>" class="big">Jornadas</a>
                    <a title="Pistas" href="<?php echo base_url() ."index.php/admin/bienvenido/pista"; ?>" class="big">Pistas</a>
                    <a title="Pilotos" href="<?php echo base_url() ."index.php/admin/bienvenido/pilotos"; ?>" class="big">Pilotos</a>
                    <a title="Escuderias" href="<?php echo base_url() ."index.php/admin/bienvenido/escuderias"; ?>" class="big">Escuderias</a>
                    <a title="Podio" href="<?php echo base_url() ."index.php/admin/bienvenido/podio"; ?>" class="big">Podio</a>
                    <a title="Añadir Resultado" href="<?php echo base_url() ."index.php/admin/bienvenido/anadeResultado"; ?>" class="big">Añadir Resultado</a>
                    <a title="Añadir Resultado" href="<?php echo base_url() ."index.php/admin/bienvenido/expertos"; ?>" class="big">Expertos</a>
                    <a href="<?php echo base_url('index.php/index/logout_ci')?>" class="big">Cerrar Sesión</a>
                </div>
            </nav>
          </div>
        </div>
        <p><br><br></p>
