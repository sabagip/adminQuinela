        <header>
            <nav>
                <ul>
                    <li><a title="Usuarios" href="<?php echo base_url() ."index.php/admin/bienvenido"; ?>">Usuarios</a></li>
                    <li><a title="Administradores" href="<?php echo base_url() ."index.php/admin/bienvenido/admin"; ?>">Administradores</a></li>
                    <li><a title="Jornadas" href="<?php echo base_url() ."index.php/admin/bienvenido/jornadas"; ?>">Jornadas</a></li>
                    <li><a title="Pistas" href="<?php echo base_url() ."index.php/admin/bienvenido/pista"; ?>">Pistas</a></li>
                    <li><a title="Pilotos" href="<?php echo base_url() ."index.php/admin/bienvenido/pilotos"; ?>">Pilotos</a></li>
                    <li><a title="Escuderias" href="<?php echo base_url() ."index.php/admin/bienvenido/escuderias"; ?>">Escuderias</a></li>
                    <li><a title="Podio" href="<?php echo base_url() ."index.php/admin/bienvenido/podio"; ?>">Podio</a></li>
                    <li><a title="Añadir Resultado" href="<?php echo base_url() ."index.php/admin/bienvenido/anadeResultado"; ?>">Añadir Resultado</a></li>
                    <li>Bienvenido <?php echo $this->session->userdata('username');?></li>
                    <li><a href="<?php echo base_url('index.php/index/logout_ci')?>">Cerrar Sesión</a></li>
                </ul>
                </nav>
        </header>
