            

            <section id="contenido">
                <div class="contenedor flow-text">
                  <!-- ESTO ES EL POLE POSITION-->
                  <div id="polePosition">
                      <p style="text-align: center;">DA CLICK SOBRE EL PILOTO QUE CREES QUE OCUPARÁ LA POSICIÓN DE PRIVILEGIO.</p>
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
                        <li><a href="#" class="siguiente boton Disable">SIGUIENTE</a></li>
                      </ul>
                    </div>
                  </div>
                  <div id="vueltaRapida">
                      <p style="text-align: center;">DA CLICK SOBRE EL PILOTO QUE CREES QUE SE QUEDARÁ CON LA VUELTA RÁPIDA.</p>
                    <ul class="listaPilotos listaPilotosVueltaRapida">
                      <?php foreach($pilotos as $piloto): $i = 1;?>
                        <li data-idpiloto="<?php echo enc_encrypt($piloto->idPiloto, KEY);?>"><img src="<?php echo IMGPILOTOS_URL; echo $piloto->fotografia; ?>" alt=""><span class="info">
                        <h3><?php echo $piloto->nombre . " " .$piloto->apellidoP; ?></h3>
                        <p><?php echo $piloto->escuderia; ?></p></span></li>
                        <?php endforeach;?>
                    </ul>
                    <div class="Navegador">
                      <ul>
                        <li><a href="#" class="anterior boton boton--secundario">ANTERIOR</a></li>
                        <li><a href="#" class="siguiente boton Disable">SIGUIENTE</a></li>
                      </ul>
                    </div>
                  </div>
                  <div id="TopTen">
                    <p style="text-align: center;">ARRASTRA EN ORDEN A LOS 10 PILOTOS QUE LLEGARÁN PRIMERO A LA META.</p>
                    <section>
                      <ul id="sortable" class="listaPilotos listaPilotosTopTen conect">
                        <?php foreach($pilotos as $piloto): $i = 1;?>
                        <li data-idpiloto="<?php echo enc_encrypt($piloto->idPiloto, KEY);?>">

                          <span class="pilotoInfoCondensada">
                            <img src="<?php echo IMGPILOTOS_URL.$piloto->fotografia ?>" alt=""><span class="info">
                            <h3><?php echo $piloto->nombre . " " .$piloto->apellidoP; ?></h3>
                            <p><?php echo $piloto->escuderia; ?></p></span>
                          </span>
                        </li>
                        <?php endforeach;?>
                      </ul>
                        <ul class="listaPilotos listaPilotosTopTenResultado conect" id="sortable2" >
                           <!--  <li id="prueba"></li> -->
                      </ul>
                    </section>
                    <div class="Navegador">
                      <ul id="ocultar">
                        <li><a href="#" class="anterior boton boton--secundario">ANTERIOR</a></li>
                        <li id="topTenGuardar"><a id="avanzar" href="#" class="siguiente boton Disable">SIGUIENTE</a></li>
                      </ul>
                    </div>
                  </div>
                  <div id="VistaRapida">
                    <!--<p style="text-align: center;">REVISA BIEN TUS PREDICCIONES PORQUE UNA VEZ QUE GUARDES TU PRONÓSTICO NO PODRÁS HACER MÁS CAMBIOS.</p>-->
                    <div id="poleResultado">
                      <h3>POSICIÓN DE PRIVILEGIO</h3>
                      <ul class="listaPilotos">
                        <li><img src="http://placehold.it/150x150" alt=""><span class="info"> 
                            <h3>Nombre del Piloto demasiado largo para recordar y arto coqueto</h3>
                            <p>Nombre de la Escuderia</p></span></li>
                      </ul>
                    </div>
                    <div id="vueltaResultado">
                      <h3>VUELTA RÁPIDA</h3>
                      <ul class="listaPilotos">
                        <li><img src="http://placehold.it/150x150" alt=""><span class="info"> 
                            <h3>Nombre del Piloto demasiado largo para recordar y arto coqueto</h3>
                            <p>Nombre de la Escuderia</p></span></li>
                      </ul>
                    </div>
                    <div id="toptenResultado">
                      <h3>10 PRIMEROS</h3>
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
                      <label style="text-align: justify;">REVISA BIEN TUS PREDICCIONES Y LA JORNADA AL GAURDAR O ACTUALIZAR.</label>
                      <div class="input-field col s6">
                          <input type="hidden" value="<?php echo enc_encrypt($jornada[0]->idJornada, KEY);?>" id="idJornada" >
                          <h1><?php echo $jornada[0]->nombre; ?></h1>
                      </div>
                    </div>
                    <div class="Navegador">
                      <ul>  
                        <li><a href="#" class="anterior boton boton--secundario">ANTERIOR</a></li>
                        <?php if($resultado): ?>
                        <li><a href="#" id="guardar" class="GuardarPrediccion boton boton--general">GUARDAR</a></li>
                        <?php else: ?>
                        <li><a href="#" id="guardar" class="ActualizarPrediccion boton boton--general">ACTUALIZAR</a></li>
                        <?php endif; ?>
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
                  if($("#sortable2 li").length == 10){
                    $("#TopTen .Navegador .siguiente").removeClass("Disable").addClass("boton--secundario");
                  }
                  if($("#sortable2 li").length < 10){
                    $("#TopTen .Navegador .siguiente").removeClass("boton--secundario").addClass("Disable");
                  }
                });
                $("#sortable2").on("sortremove", function(event,ui){
                  var longitud = $("#sortable2 li").length;
                  if(longitud < 10){
                    $("#TopTen .Navegador .siguiente").removeClass("boton--secundario").addClass("Disable");
                    console.log("me ejecute");
                  }
                }); 
              });
            </script>