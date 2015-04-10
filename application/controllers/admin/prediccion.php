<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/Sanitize.php');
class Prediccion extends CI_Controller {
        
        public function __construct()
        {
            parent::__construct();
            // Your own constructor code
            $this->load->library(array('session', 'Sanitize'));
            $this->load->helper(array('url', 'function'));
            $this->load->model(array( 'admin/M_consultas', 'admin/M_insert', 'admin/M_update'));
            error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        }
       
        public function cargaPrediccion(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("2", KEY)):
                redirect(site_url('index') );
            endif;
            $prediccion = $_POST;
            //echo "<pre>";            print_r($prediccion); die;
            $data['pilotos'] = $this->M_consultas->get_pilotos(FALSE);
            #Se obtiene si el usuario quiere ver su predicción o si quiere realizar una predicción
            switch ($prediccion['prediccion']):
                case "realizarP":
                    #Se buscan a los pilotos titulares
                    $data['idJornada'] = $this->sanitize->clean_string($prediccion['date']);
                    //$data['idJornada'] = enc_decrypt($data['idJornada'], KEY);
                    //Se sacan los id's de los pilotos aleatoriamente
                    $data['preview'] = FALSE;
                    //echo "<pre>"; print_r($_POST); die;
                break;
                case "modificarP":
                    $usuario = enc_decrypt($this->session->userdata['id_usuario'], KEY);
                    $data['idJornada'] = $prediccion['date'];
                    $data['pole'] = $this->M_consultas->get_ResultadoPole($usuario, $data['idJornada']);
                    $data['vuelta'] = $this->M_consultas->get_ResultadoVuelta($usuario, $data['idJornada']);
                    $data['top'] = $this->M_consultas->get_ResultadoTopTen($usuario, $data['idJornada']);
                    $data['preview'] = TRUE;
                    
                    $data['lugarTop'] = array('First', 'Second', 'Third', 'Four', 'Five', 'Six', 'Seven', 'Eigth', 'Nine', 'Ten');
                    //echo "<pre>"; print_r($data); die;
                break;
            endswitch;
            $data['expertos'] = $this->getApuestasExpertos($data['idJornada']);
            $data['modificar'] = 1;
            $data['title'] = "<h1>PRONÓSTICO / <em>POLE POSITION</em></h1>";
            $data['body'] = "app/finalUser/prediccion";
            $this->load->view('includes/finalUser/cargaPagina', $data);
        }
        
        public function saveApuesta(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("2", KEY)):
                redirect(site_url('index') );
            endif;
            //echo "<pre>"; print_r($_POST); die;
            $pole = enc_decrypt($this->sanitize->clean_string($_POST['PolePosition']), KEY);
            $vuelta = enc_decrypt($this->sanitize->clean_string($_POST['VueltaRapida']), KEY);
            $top = $this->sanitize->white_array_list($_POST['TopTen']);
            $idJornada = enc_decrypt($this->sanitize->clean_string($_POST['jornada']), KEY);
            if(empty($pole) || empty($vuelta) || empty($top) || count($top) < 9):
               echo "vacio"; die;
            endif;
            
            $i = 0;
            foreach($top as $key):
                $top[$i] = enc_decrypt($key, KEY);
                $i++;
            endforeach;
            
            $arrayPole['idPiloto'] = $pole;
            $arrayPole['idJornada'] = $idJornada;
            
            $arrayVuelta['idPiloto'] = $vuelta;
            $arrayVuelta['idJornada'] = $idJornada;
            
            $top = takeTopTen($top);
            $top['idJornada'] = $idJornada;
            
            $this->db->trans_start();
            $this->saveResultadopole($arrayPole, TRUE);
            $this->saveVueltaRapida($arrayVuelta, TRUE);
            $this->saveTopTen($top, TRUE);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE):
                echo FALSE; die;
                // genera un error... o usa la función log_message() para guardar un registro del error
            endif;
            
            if ($this->db->trans_status() === TRUE):
                echo TRUE; die;
                // genera un error... o usa la función log_message() para guardar un registro del error
            endif;
        }
        
        /*
         * El argumento "bandera" indica si es una inserción de una predicción o una actualización
         * bandera = TRUE, indica que el usuario está seguro de su predicción y desea términarla
         * bandera = FALSE, indica que el usuario aún quiere hacer cambios en su predicción
        */
        function saveResultadopole($datos, $bandera){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("2", KEY)):
                redirect(site_url('index') );
            endif;
            //echo "<pre>"; print_r($datos); die;
            $insert['idPiloto'] = $datos['idPiloto'];
            //$insert['idUsuario'] = enc_decrypt($this->session->userdata['id_usuario'], KEY);
            $insert['idJornada'] = $datos['idJornada'];

            //Se comprueba que el usuario ya realizó una apuesta
            $repetido = $this->M_consultas->get_resultadoPole($insert['idJornada']);
            if(empty($repetido)):
                $this->M_insert->saveResultadoPole($insert, $bandera);
                /*if($result):
                    $this->load->view("includes/finalUser/cargaPagina", $data);
                else:
                    echo "Error al insertar la apuesta"; die;
                endif;*/
            else:
                $this->M_update->updateResultadoPole($insert, $bandera);
                /*if( $result ):
                    $this->load->view("includes/finalUser/cargaPagina", $data);
                else:
                    echo "Error al actualizar tu predicción"; die;
                endif;*/
                
            endif;
        }
        
        /*
         * El argumento "bandera" indica si es una inserción de una predicción o una actualización
         * bandera = TRUE, indica que el usuario está seguro de su predicción y desea términarla
         * bandera = FALSE, indica que el usuario aún quiere hacer cambios en su predicción
        */
        function saveVueltaRapida($datos, $bandera){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("2", KEY)):
                redirect(site_url('index') );
            endif;
            //echo "<pre>"; print_r($datos); die;
            $insert['idPiloto'] = $datos['idPiloto'];
            //$insert['idUsuario'] = enc_decrypt($this->session->userdata['id_usuario'], KEY);
            $insert['idJornada'] = $datos['idJornada'];
            //$insert['tiempo'] = $datos['tiempo'];
            //$data['idJornada'] = $insert['idJornada'];
            $repetido = $this->M_consultas->get_ResultadoVuelta($insert['idJornada']);
            
            if(empty($repetido)):
                $this->M_insert->saveResultadoVuelta($insert, $bandera);
                /*if($result):
                    $data['body'] = "app/finalUser/topTen";
                    $this->load->view("includes/finalUser/cargaPagina", $data);
                 else:
                     echo "Error al insertar la apuesta"; die;
                endif;*/
            else:
                $this->M_update->updateResultadoVuelta($insert, $bandera);
                /*if($result):
                    $data['body'] = "app/finalUser/topTen";
                    $this->load->view("includes/finalUser/cargaPagina", $data);
                 else:
                     echo "<p>" .$this->db->last_query() . "</p>";
                     echo "Error al actualizar la apuesta"; die;
                endif;*/
            endif;
            
        }
        
        /*
         * El argumento "bandera" indica si es una inserción de una predicción o una actualización
         * bandera = TRUE, indica que el usuario está seguro de su predicción y desea términarla
         * bandera = FALSE, indica que el usuario aún quiere hacer cambios en su predicción
        */
        function saveTopTen($datos, $bandera){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("2", KEY)):
                redirect(site_url('index') );
            endif;
            
            $apuesta = $datos;
            //$apuesta['idUsuario'] = enc_decrypt($this->session->userdata['id_usuario'], KEY);
            $apuesta['idJornada'] = $datos['idJornada'];
            //unset($apuesta['date']);
            $repetido = $this->M_consultas->get_ResultadoTopTen($apuesta['idJornada']);
            if(empty($repetido)):
                $this->M_insert->saveResultadoTopTen($apuesta, $bandera);
                /*if($result):
                    $data['body'] = "app/finalUser/index";
                    redirect(base_url() ."/finalUser/bienvenido");
                    $this->load->view("includes/finalUser/cargaPagina", $data);
                else:
                    echo "<p>" .$this->db->last_query() . "</p>";
                     echo "Error al actualizar la apuesta"; die;
                endif;*/
            else:
                $this->M_update->updateResultadoTopTen($apuesta, $bandera);
                /*if($update):
                    $data['body'] = "app/finalUser/index";
                    redirect(base_url() ."finalUser/bienvenido");
                    $this->load->view("includes/finalUser/cargaPagina", $data);
                else:
                    echo "Error al actualizar tu apuesta"; die;
                endif;*/
            endif;
        }

        function posicionesAleatorias($repeticiones, $valorMaximo){
             $x = 0;
             //$num = 18;
             $valores = array();
             while ($x < $repeticiones) :
                 $num_aleatorio = rand(1,$valorMaximo);
                 if (!in_array($num_aleatorio,$valores)):
                     array_push($valores,$num_aleatorio);
                     $x++;
                 endif;
             endwhile;
             return $valores;
        }
        
        public function modifyPrediccion(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("2", KEY)):
                redirect(site_url('index') );
            endif;
            
            $idUsuario = enc_decrypt($this->session->userdata('id_usuario'), KEY);
            $idJornada = $this->sanitize->clean_string( enc_decrypt($_POST['date'], KEY) );
            
            $data['pole'] = $this->M_consultas->get_ResultadoPole($idUsuario, $idJornada);
            $data['vuelta'] = $this->M_consultas->get_ResultadoVuelta($idUsuario, $idJornada);
            $data['top'] = $this->M_consultas->get_ResultadoTopTen($idUsuario, $idJornada);
            $data['top'] = separateTopTen($data['top']);
            $data['pilotos'] = $this->M_consultas->get_pilotos(FALSE);
            $data['pilotosRestantes'] = $this->M_consultas->get_pilotosRestantes($data['top']);
            $data['modificar'] = 2;
            
            $data['body'] = 'app/finalUser/prediccion';
            $data['title'] = 'MODIFICA TU PRONÓSTICO';
            
            //echo "<pre>"; print_r($data); die;
            $this->load->view('includes/finalUser/cargaPagina', $data);
        }
        
        public function verPrediccion(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("2", KEY)):
                redirect(site_url('index') );
            endif;
            
            $idUsuario = enc_decrypt($this->session->userdata('id_usuario'), KEY);
            $idJornada = $this->sanitize->clean_string( enc_decrypt($_POST['date'], KEY) );
            
            $data['pole'] = $this->M_consultas->get_ResultadoPole($idUsuario, $idJornada);
            $data['vuelta'] = $this->M_consultas->get_ResultadoVuelta($idUsuario, $idJornada);
            $data['top'] = $this->M_consultas->get_ResultadoTopTen($idUsuario, $idJornada);
            $data['top'] = separateTopTen($data['top']);
            foreach($data['top'] as $piloto):
                //echo "<pre>"; print_r($piloto); die;
                $piloto->escuderia = $this->M_consultas->get_escuderia($piloto->idPiloto);
            endforeach;
            //$data['pilotos'] = $this->M_consultas->get_pilotos(FALSE);
            //$data['pilotosRestantes'] = $this->M_consultas->get_pilotosRestantes($data['top']);
            $data['modificar'] = 3;
            
            $data['body'] = 'app/finalUser/prediccion';
            $data['title'] = '<h1>TU PRONÓSTICO </h1>';
            
            //echo "<pre>"; print_r($data); die;
            $this->load->view('includes/finalUser/cargaPagina', $data);
            
        }
        
        function getApuestasExpertos($idJornada){
            $idJornada = enc_decrypt($idJornada, KEY);
            $expertos = $this->M_consultas->get_datosUsuarios(3, 0, 0, 0, 1, 0, 3);
            $apuesta = array();
            $apuesta = $expertos;
            
            //echo "<pre>"; print_r($apuesta); die;
            $i = 0;
            foreach($expertos as $experto):
                $apuesta[$i]->pole =  $this->M_consultas->get_ResultadoPole($experto->idUsuario, $idJornada);
                $i++;
            endforeach;
            
            $i = 0;
            foreach($expertos as $experto):
                $apuesta[$i]->vuelta = $this->M_consultas->get_ResultadoVuelta($experto->idUsuario, $idJornada);
                $i++;
            endforeach;
            
            foreach($expertos as $experto):
                $topten[] = $this->M_consultas->get_ResultadoTopTen($experto->idUsuario, $idJornada);
            endforeach;
            
            $i = 0;
            foreach($topten as $top):
                $apuesta[$i]->top = separateTopTen($top);
                $apuesta[$i]->top = $this->getEscuderia($apuesta[$i]->top);
                $i++;
            endforeach;
            
                return $apuesta;
            
            
            
        }
        
         function getEscuderia($datos){
            foreach($datos as $dato):
                $escuderia = $this->M_consultas->get_nameEscuderia($dato->escuderia);
                $dato->escuderia = $escuderia[0]->nombre;
            endforeach;

            return $datos;
        }
        
        
}