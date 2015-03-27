                Posicion de Privilegio
                <form id="formPolePosition" method="post" action="<?php echo base_url(). "index.php/admin/resultados/saveResultado";?> ">
                    Resultados de: 
                    <select name="jornada">
                    <?php
                    foreach($jornada as $key):?>
                        <option value="<?php echo $key->idJornada; ?>"><?php echo $key->nombre; ?></option>
                    <?php
                    endforeach;
                    ?>
                    </select>
                    <br><br><br>
                    
                    <table name="tblPole">
                        <tr>
                            <td><label>1°</label><input name="poleP" type="text" value="<?php echo $posiciones[0] ?>"> </td>
                        </tr>
                    </table>

                    <br/> <br/> <br/>

                    Vuelta Rapida:
                    <table name="tblVueltaRapida">
                        <tr>
                            <td><label>Piloto</label><input name="vueltaR" type="text" value="<?php echo $posiciones1[0] ?>"> </td>
                        </tr>
                        <tr>
                            <td><label>Tiempo</label><input name="tiempo" type="time"> </td>
                        </tr>
                    </table>

                    <br/> <br/> <br/>

                    Top Ten:
                    <table name="tblTopTen">
                        <?php for($i = 0; $i < 10; $i++):?>
                        <tr>
                            <td><label><?php echo $i + 1;?>°</label><input name="<?php echo $i + 1; ?>" type="text" value="<?php echo $posiciones2[$i] ?>"> </td>
                        </tr>
                        <?php endfor;?>
                    </table>

                    <input type="submit" name="salvar" value="Salvar Resultados"/>
                </form>