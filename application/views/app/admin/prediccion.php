            
            <section id="contenido">
                <div class="contenedor flow-text">
                  <!-- ESTO ES EL POLE POSITION-->
                  <div id="polePosition">
                    <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam, perspiciatis, voluptate temporibus quibusdam blanditiis asperiores cupiditate architecto, maxime quam adipisci incidunt.</p>
                    <ul class="listaPilotos listaPilotosPole">
                        <?php foreach($pilotos as $piloto): $i = 1;?>
                        <li data-idpiloto="<?php echo enc_encrypt($piloto->idPiloto, KEY);?>"><img src="<?php echo IMGPILOTOS_URL.$piloto->fotografia ?>" alt=""><span class="info">
                        <h3><?php echo $piloto->nombre . " " .$piloto->apellidoP; ?></h3>
                        <p><?php echo $piloto->escuderia; ?></p></span></li>
                        <?php endforeach;?>
                    </ul>
                    <div class="Navegador">
                      <ul>
                        <li><span class="Disable boton">ANTERIOR</span></li>
                        <li><a href="#" class="siguiente boton boton--secundario">SIGUIENTE</a></li>
                      </ul>
                    </div>
                  </div>
                  <div id="vueltaRapida">
                    <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam, perspiciatis, voluptate temporibus quibusdam blanditiis asperiores cupiditate architecto, maxime quam adipisci incidunt.</p>
                    <ul class="listaPilotos listaPilotosVueltaRapida">
                      <?php foreach($pilotos as $piloto): $i = 1;?>
                        <li data-idpiloto="<?php echo enc_encrypt($piloto->idPiloto, KEY);?>"><img src="<?php echo IMGPILOTOS_URL.$piloto->fotografia ?>" alt=""><span class="info">
                        <h3><?php echo $piloto->nombre . " " .$piloto->apellidoP; ?></h3>
                        <p><?php echo $piloto->escuderia; ?></p></span></li>
                        <?php endforeach;?>
                    </ul>
                    <div class="Navegador">
                      <ul>
                        <li><a href="#" class="anterior boton boton--secundario">ANTERIOR</a></li>
                        <li><a href="#" class="siguiente boton boton--secundario">SIGUIENTE</a></li>
                      </ul>
                    </div>
                  </div>
                  <div id="TopTen">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Totam, perspiciatis, voluptate temporibus quibusdam blanditiis asperiores cupiditate architecto, maxime quam adipisci incidunt.</p>
                    <section>
                      <ul id="sortable" class="listaPilotos listaPilotosTopTen conect">
                        <?php foreach($pilotos as $piloto): $i = 1;?>
                        <li data-idpiloto="<?php echo enc_encrypt($piloto->idPiloto, KEY);?>"><img src="<?php echo IMGPILOTOS_URL.$piloto->fotografia ?>" alt=""><span class="info">
                        <h3><?php echo $piloto->nombre . " " .$piloto->apellidoP; ?></h3>
                        <p><?php echo $piloto->escuderia; ?></p></span></li>
                        <?php endforeach;?>
                      </ul>
                        <ul class="listaPilotos listaPilotosTopTenResultado conect" id="sortable2" >
                           <!--  <li id="prueba"></li> -->
                      </ul>
                    </section>
                    <div class="Navegador">
                      <ul id="ocultar">
                        <li><a href="#" class="anterior boton boton--secundario">ANTERIOR</a></li>
                        <li id="topTenGuardar"><a id="avanzar" href="#" class="siguiente boton boton--secundario">SIGUIENTE</a></li>
                      </ul>
                    </div>
                  </div>
                  <div id="VistaRapida">
                    <div id="poleResultado">
                      <h3>El Resultado</h3>
                      <ul class="listaPilotos">
                        <li><img src="http://placehold.it/150x150" alt=""><span class="info"> 
                            <h3>Nombre del Piloto demasiado largo para recordar y arto coqueto</h3>
                            <p>Nombre de la Escuderia</p></span></li>
                      </ul>
                    </div>
                    <div id="vueltaResultado">
                      <h3>El Resultado</h3>
                      <ul class="listaPilotos">
                        <li><img src="http://placehold.it/150x150" alt=""><span class="info"> 
                            <h3>Nombre del Piloto demasiado largo para recordar y arto coqueto</h3>
                            <p>Nombre de la Escuderia</p></span></li>
                      </ul>
                    </div>
                    <div id="toptenResultado">
                      <h3>El Resultado</h3>
                      <ul class="listaPilotos">
                        <li><img src="http://placehold.it/150x150" alt=""><span class="info"> 
                            <h3>Nombre del Piloto demasiado largo para recordar y arto coqueto</h3>
                            <p>Nombre de la Escuderia</p></span></li>
                        <li><img src="http://placehold.it/150x150" alt=""><span class="info"> 
                            <h3>Nombre del Piloto demasiado largo para recordar y arto coqueto</h3>
                            <p>Nombre de la Escuderia</p></span></li>
                        <li><img src="http://placehold.it/150x150" alt=""><span class="info"> 
                            <h3>Nombre del Piloto demasiado largo para recordar y arto coqueto</h3>
                            <p>Nombre de la Escuderia</p></span></li>
                      </ul>
                    </div>
                    <div class="Navegador">
                      <ul>
                        <li><a href="#" class="anterior boton boton--secundario">ANTERIOR</a></li>
                        <li><a href="#" id="guardar" class="Guardar boton boton--general">GUARDAR</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </section>
            <script>    
                $(document).ready(function(){
                    $('#sortable2, #sortable').sortable({
                      connectWith: ".conect",
                      tolerance: "pointer"
                    }).disableSelection();
                    $("#sortable2").on("sortreceive", function(event,ui){
                      
                      if($("#sortable2 li").length > 10){
                            $(ui.sender).sortable('cancel');
                            alert("Solo puedes seleccionar 10 pilotos");
                        }
                      if($("#sortable2 li").length < 10){
                        }
                    }); 
                   })
            </script>