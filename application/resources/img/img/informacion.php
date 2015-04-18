<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/Sanitize.php');
include_once APPPATH . "libraries/phpmailer/PHPMailerAutoload.php";

class Informacion extends CI_Controller {
        
        public function __construct()
        {
            parent::__construct();
            // Your own constructor code
            $this->load->library(array('session', 'Sanitize'));
            $this->load->helper(array('url', 'function'));
            $this->load->database('default');
            $this->load->model(array('finalUser/M_consultas', 'finalUser/M_update'));
            error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        }
       
        public function index(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $idUsuario = enc_decrypt($this->session->userdata['id_usuario'], KEY) ;
            $datoUsuario = $this->M_consultas->get_datosUsuarios(1, 0, 0, $idUsuario, 1, 0);
            //Se obtiene el GP más proximo
            $data['near'] = $this->M_consultas->get_nearRaces();
            $data['last'] = $this->M_consultas->get_lastRaces($idUsuario);
            #Se obtienen el total de puntaje que sacó el usuario
            $data['puntajePorCarrera'] = $this->sumaPuntaje($data['last']);
            #Se suman todos los puntajes por carrera
            $data['puntajeTotal'] = $datoUsuario[0]->puntosTotales;
            
            #Si existen próximas carreras
            if(!empty($data['near'])):
                #Se convierten las fechas a formato humano
                #Si la carrera es en cualquier otro lugar, la quiniela se cierra en el primer minuto del Jueves
                $fechaLimite =  strtotime( "-2 day 01 minutes", strtotime($data['near'][0]->fechaJornada));
                //$fechaLimite =  strtotime( "-3 day 01 minutes", strtotime($data['near'][0]->fechaJornada));
                #Si la carrera es en Mónaco el cierre de la quiniela se hace en el primer minuto del Miercoles
                if($data['near'][0]->pista == "GP de Mónaco"):
                    $fechaLimite =  strtotime( "-4 day 01 minutes", strtotime($data['near'][0]->fechaJornada));
                endif;
                #Se saca la hora del servidor y se suma o resta según donde será la carrera
                $fechaHoy = time();
                //$fechaHoy = get_FechaHoyZonaHoraria($fechaHoy, $data['near'][0]->zonaHoraria);
                //echo $fechaHoy . "    " .$fechaLimite; die;
                #Se obtiene si el usuario ya hizo una predicción
                $apuestaActiva = $this->M_consultas->get_ApuestaActiva($idUsuario, $data['near'][0]->idJornada);
                
                #Si el usuario ya realizó una predicción y solo salvó 
                if( !empty($apuestaActiva) && $apuestaActiva[0]->prediccionActiva == 0):
                    #Se activa un botón para la modificación de una predicción
                    $data['near'][0]->boton = "TRUE";
                    $data['ruta'] = "finalUser/prediccion/modifyPrediccion";
                    #Se habilita o desabilita el botón de predicción según sea el caso
                    if($fechaHoy > $fechaLimite):
                        $data['near'][0]->prediccion = "verPrediccion";
                    else:
                        $data['near'][0]->prediccion = "enabled";
                    endif;
                #Si el usuario ya realizó una predicción y la guardó
                elseif( !empty($apuestaActiva) && $apuestaActiva[0]->prediccionActiva == 1):
                    $data['near'][0]->boton = "TRUE";
                    $data['near'][0]->prediccion = "verPrediccion";
                    $data['ruta'] = "finalUser/prediccion/verPrediccion";
                
                #De lo contrario, si el usuario no ha realizado predicciones
                else:
                    #Se habilita o desabilita el botón de predicción según sea el caso
                    if($fechaHoy > $fechaLimite):
                        $data['near'][0]->prediccion = "sinPrediccion";
                    else:
                        $data['near'][0]->prediccion = "enabled";
                    endif;
                    $data['ruta'] = "finalUser/prediccion/cargaPrediccion";
                endif;
                
                
                #Se convierten las fechas a formato humano
                $data['fechaLimite1'] = divideFecha($fechaLimite);
                $data['fechaServidor'] = divideFecha($fechaHoy);
                $fechaLimite = date( "d-m-Y", $fechaLimite );
                $data['near'][0]->fechaLimite = dateConvert($fechaLimite);
                $data['near'][0]->fechaJornada = dateConvert($data['near'][0]->fechaJornada);
                foreach($data['last'] as $key):
                    $key->fechaJornada = dateConvert($key->fechaJornada);
                endforeach;
                $title =  "<h1> MIS PUNTOS / " .$data['puntajeTotal']. "  </h1>";
                $data['title'] = $title;   
                $data['body'] = 'app/finalUser/mispuntos';
                //echo "<pre>"; print_r($data); die;
                $this->load->view('includes/finalUser/cargaPagina', $data);
            #Si no existen próximas carreras
            else:
                $data['body'] = 'app/finalUser/SinCarreras'; 
                $this->load->view('includes/finalUser/cargaPagina', $data);
           endif;
       }
       
       
        public function resultadoMay18(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            $data['idUsuario'] = $this->session->userdata['id_usuario'];
            #Se obtienen los usuarios normales con edad igual o mayor de 18
            $data['datos'] = $this->M_consultas->get_datosUsuarios(1, 1);
            //echo "<pre>"; print_r($data['edadUsuario']); die;
            $data['body'] = "app/finalUser/resultados";
            $this->load->view("includes/finalUser/cargaPagina", $data);
        }
        
        public function resultadoMen18(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            $data['idUsuario'] = $this->session->userdata['id_usuario'];
            #Se obtienen los usuarios normales con edad igual o mayor de 18
            $data['datos'] = $this->M_consultas->get_datosUsuarios(1, 2);
            //echo "<pre>"; print_r($data['edadUsuario']); die;
            $data['body'] = "app/finalUser/resultados";
            $this->load->view("includes/finalUser/cargaPagina", $data);
        }
        
        public function resultadoPais(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            $data['idUsuario'] = $this->session->userdata['id_usuario'];
            #Se obtiene la edad del usuario
            $datosUsuario = $this->M_consultas->get_datosUsuarios(0, 0, 0, $data['idUsuario']);
            $idPais = $datosUsuario[0]->idPais;
            
            if($datosUsuario[0]->edad >= 18):
                $data['datos'] = $this->M_consultas->get_datosUsuarios(1, 1, $idPais, 0);
            else:
                $data['datos'] = $this->M_consultas->get_datosUsuarios(1, 2, $idPais, 0);
            endif;
            
            $data['body'] = "app/finalUser/resultados";
            $this->load->view("includes/finalUser/cargaPagina", $data);
        }
        
        public function resultadoGeneral(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            $data['idUsuario'] = $this->session->userdata['id_usuario'];
            $data['datos'] = $this->M_consultas->get_datosUsuarios(1, 0, 0, 0);
            $data['body'] = "app/finalUser/resultados";
            $this->load->view("includes/finalUser/cargaPagina", $data);
            
        }

        function sumaPuntaje($arreglo){
            $total = array();
            foreach ($arreglo as $arr):
                $total[] = $arr->puntajePodio + $arr->puntajeVuelta + $arr->puntajePole;
            endforeach;
            return $total;
        }

        function sumaTotal($arreglo){
            $total = array();
            foreach ($arreglo as $arr):
                $total =+ $arr;
            endforeach;
            return $total;
        }
        
        public function perfilUsuario(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $idUsuario = enc_decrypt($this->session->userdata('id_usuario'), KEY);
            
            $data['usuario'] = $this->M_consultas->get_datosUsuarios(1, 0, 0, $idUsuario, 0, 0);
            //echo "<pre>"; print_r($datoUsuario); die;
            $data['body'] = 'app/finalUser/micuenta';
            $data['title'] = '<h1>MI PERFIL</h1>';
            $data['preview'] =  TRUE;
            $this->load->view('includes/finalUser/cargaPagina', $data);
            
        }
        
        public function editaUsuario(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $idUsuario = enc_decrypt($this->session->userdata('id_usuario'), KEY);
            
            $data['usuario'] = $this->M_consultas->get_datosUsuarios(1, 0, 0, $idUsuario, 0, 0);
            $data['usuario'][0]->fechaNacimiento = convierteFecha($data['usuario'][0]->fechaNacimiento);
            $data['paises'] = $this->M_consultas->get_paises();
            //echo "<pre>"; print_r($data); die;
            $data['body'] = 'app/finalUser/editarUsuario';
            $data['title'] = '<h1>EDITAR PERFIL</h1>';
            $data['preview'] =  FALSE;
            $this->load->view('includes/finalUser/cargaPagina', $data);            
        }
        
        public function guardaCambiosUsuario(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            //echo "<pre>"; print_R($_POST);  die;
            $idUsuario = enc_decrypt($this->session->userdata('id_usuario'), KEY);
            $nombre = $this->sanitize->clean_string($_POST['nombre']);
            $apellidP = $this->sanitize->clean_string($_POST['apellido']);
            $usuario = $this->sanitize->clean_string($_POST['userName']);
            $contrasena = enc_encrypt($this->sanitize->clean_string($_POST['pass']), KEY);
            $email = $this->sanitize->clean_email($_POST['email']);
            //$edad = $this->sanitize->clean_string($_POST['userAge']);
            $edad = convierteFecha($_POST['userAge']);
            $idPais = enc_decrypt($this->sanitize->clean_string($_POST['userCountry']), KEY);
            
            $datos = array(
                            'nombre' => $nombre,
                            'apellidoP' => $apellidP,
                            'usuario' => $usuario,
                            'contrasena' => $contrasena,
                            //'email' => $email,
                            'fechaNacimiento' => $edad,
                            'idPais' => $idPais
            );
            //echo "<pre>"; print_R($datos); die; 
            
            $result = $this->M_update->updateUsuario($datos, $idUsuario);
            if($result):
                //$this->perfilUsuario();
                echo true;
            else:
                echo false; die;
            endif;
        }
        
        public function campeones(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $data['campeones'] = $this->M_consultas->get_campeones();
            $data['title'] = "<h1> Campeones </h1>";
            $data['body'] = "app/finalUser/campeones";
            $this->load->view("includes/finalUser/cargaPagina" , $data);
        }
        
        
        public function termyCond(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $data['title'] = "<h1> TÉRMINOS Y CONDICIONES </h1>";
            $data['body'] = "app/finalUser/condiciones";
            $this->load->view("includes/finalUser/cargaPagina" , $data);
        }
        
        public function comoJugar(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $data['title'] = "<h1> REGLAS </h1>";
            $data['body'] = "app/finalUser/reglas";
            $this->load->view("includes/finalUser/cargaPagina" , $data);
        }
        
        public function contacto(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $idUsuario = enc_decrypt($this->session->userdata('id_usuario'), KEY);
            $data['usuario']= $this->M_consultas->get_datosUsuarios(1, 0, 0, $idUsuario, 1, 0, 0, 0);
            $data['title'] = "<h1> Contacto </h1>";
            $data['body'] = "app/finalUser/contacto";
            $this->load->view("includes/finalUser/cargaPagina" , $data);
        }
        
        public function enviaContacto(){
            if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt("1", KEY)):
                redirect(site_url('index') );
            endif;
            
            $idUsuario = enc_decrypt($this->session->userdata('id_usuario'), KEY);
            $usuario = $this->M_consultas->get_datosUsuarios(1, 0, 0, $idUsuario, 1, 0, 0, 0);
            
            $asunto = $this->sanitize->clean_string($_POST['asunto']);
            $comentarios = $this->sanitize->clean_string($_POST['comentarios']);
            $email = $this->sanitize->clean_email($_POST['contacto']);
            
            //Create a new PHPMailer instance
            $mail = new PHPMailer;

            $mail->isSendmail();
            //Set who the message is to be sent from
            $mail->setFrom($email, $usuario[0]->nombre . " " . $usuario[0]->apellidoP);
            //Set an alternative reply-to address
            //$mail->addReplyTo('replyto@fast-mag.com', 'FASTmag F1');
            //Set who the message is to be sent to
            $mail->addAddress('contacto@fast-mag.com' , 'Contacto Pronostica F1');
            //Set the subject line
            $mail->Subject = $asunto;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alterfile_get_contents(LIB_URL ."phpmailer/contents.html"));
            //Replace the pnative body
            $mail->msgHTML($comentarios);
            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment(base_url() .'resources/img/fastmag.png');
            //echo "<pre>";                        print_r($mail); die;
            //send the message, check for errors
            if (!$mail->send()) {
                echo "mailerError"; die;
                //echo "--->No se pudo mandar el mensaje";die;
            } else {
                echo TRUE; die;
            }
        }
        
        
    }