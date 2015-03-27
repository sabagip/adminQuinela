<?php date_default_timezone_set('America/Mexico_City');  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/Sanitize.php');

class Index extends CI_Controller {
    private $appId;
    private $secret;
         public function __construct()
       {
            parent::__construct();
            $this->load->library(array('session','encrypt', 'Sanitize'));
            $this->load->model(array('admin/M_consultas','admin/M_insert', 'admin/M_update'));
            error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
       }

	public function index()
	{
            //echo "<pre>"; print_r($this->session->all_userdata());
            switch ($this->session->userdata('perfil')):
                case "":
                    $data['token'] = $this->token();
                    //echo "<pre>"; print_r($data); die;
                    $this->load->view('index', $data);
                break;
                case enc_encrypt("1", KEY):
                    $data['token'] = $this->token();
                    if($this->session->userdata('usuario_incorrecto') == TRUE):
                        $this->session->unset_userdata('usuario_incorrecto');
                    endif;
                    $this->logout_ci();
                    redirect(BASE_URL2.'index', $data );
                break;
                case enc_encrypt("2", KEY):
                    $data['token'] = $this->token();
                    if($this->session->userdata('usuario_incorrecto') == TRUE):
                        $this->session->unset_userdata('usuario_incorrecto');
                    endif;
                    redirect(BASE_URL2.'admin/bienvenido', $data );
                default:      
                    /*$data['token'] = $this->token();
                    $data['titulo'] = 'Login con roles de usuario en codeigniter';
                    $this->load->view('login_view',$data);*/
                break;
            endswitch;
                    
		
	}
        
        public function login(){
            if( $this->input->post('token') && $this->input->post('token') == $this->session->userdata('token')):
                $user = $this->sanitize->clean_string($_POST['userName']);
                $pass = $this->sanitize->clean_string($_POST['password']);
                $pass = enc_encrypt($pass , KEY);
                $result = $this->M_consultas->m_login($user, $pass);
                if( !empty( $result ) ):
                    $data = array(
                                    'is_logued'     =>  TRUE,
                                    'id_usuario'    => enc_encrypt($result[0]->idUsuario, KEY),
                                    'username'      => $result[0]->nombre ,
                                    'perfil'        => enc_encrypt($result[0]->idPermiso, KEY)
                                );
                    $this->session->set_userdata($data);
                    if($this->session->userdata('usuario_incorrecto') ):
                        $this->session->unset_userdata('usuario_incorrecto');
                    endif;
                    //redirect(base_url("index"));
                    $this->index();
                else: 
                    $this->index();
                    //redirect(BASE_URL2.('login/index'));
                endif;
            else:
                 $this->index();
            endif;
        }
        
        public function cargaRegistroFacebook(){
            $this->load->library('facebook'); // Automatically picks appId and secret from config
        
            $login_url = $this->facebook->getLoginUrl(array(
                'redirect_uri' => BASE_URL2.('index/guardaFacebook'), 
                'scope' => array("email", "user_birthday", "user_hometown", "user_location") // permissions here
            ));
            
            return $login_url;
        }
        
        public function cargaLoginFacebook(){
            //$this->load->library('facebook'); // Automatically picks appId and secret from config
        
            $login_url = $this->facebook->getLoginUrl(array(
                'redirect_uri' => BASE_URL2.('index/ingresaFacebook'), 
                'scope' => array("email", "user_birthday", "user_hometown", "user_location") // permissions here
            ));
            
            return $login_url;
        }
        
        public function cargaRegistroTwitter(){
            $connection = $this->twitteroauth->create(TWCONSUMERKEY, TWCONSUMERSECRET, TWACCESSTOKEN, TWACCESSTOKENSECRET);
            $content = $connection->get('account/verify_credentials');
            
            $data = array(
                            'status' => $message,
                            'in_reply_to_status_id' => $in_reply_to );
            $result = $connection->post('statuses/update', $data);
            echo "<pre>"; print_r($result); die;
        }
        
        public function guardaFacebook(){
            #Se toma el id del usuario en facebook
            $user = $this->facebook->getUser();
            #Si se pudo tomar el id
            if ($user) :
                #Se valida que el usuario no esté repetido
                $repetido = $this->M_consultas->get_usuarioRepetido($user, TRUE);
                #Si hay un registro repetido
                if(!empty($repetido)):
                    redirect($this->index());
                else:
                    try {
                        #Se toman los datos del usuario en facebook
                        $user_profile = $this->facebook->api('/me');
                        #Se toma el id del usuario como contraseña y se encripta
                        $user_profile['contrasena'] = enc_encrypt($user_profile['id'], KEY);
                        #Se pasa la fecha de nacimiento del usuario en formato MYSQL
                        $user_profile['birthday'] = date("Y-m-d", strtotime($user_profile['birthday']));
                        #Se traduce el sexo a español
                        switch ($user_profile['gender']):
                            case "male":
                                $user_profile['gender'] = "Masculino";
                            break;
                            case "female":
                                $user_profile['gender'] = "Femenino";
                            break;
                        endswitch;
                        #Se toman la información necesarios para insertarlos en la BD
                        $data = array(
                                        'nombre' => $user_profile['first_name'],
                                        'apellidoP' => $user_profile['last_name'],
                                        'apellidoM' => '',
                                        'usuario' => $user_profile['id'],
                                        'contrasena' => $user_profile['contrasena'],
                                        'email' => $user_profile['email'],
                                        'fechaNacimiento' => $user_profile['birthday'],
                                        'sexo' => $user_profile['gender']
                                        );
                        #Se inserta la información del usuario en la BD
                        $result = $this->M_insert->insertFinalUser($data);
                        
                        #############################################################################
                        #   El siguiente codigo comentareado es para cuando se habiliten los        #
                        #   Correos electronicos de confirmación                                    #
                        #############################################################################
                        
                        //$data['codigo'] = RandomString(10,TRUE,TRUE,FALSE); 
                        //$result = $this->M_insert->insertRegisterUser($data);
                        #Si la inserción fue correcta
                        if($result):
                            /*$headers = "From: Quiniela Racing FASTmag 2015";
                            $headers .= "Content-type: text/html; charset=utf-8\n";
                            $mensaje = "Usted solicito un registro en Quiniela Racing FASTmag 2015, \n
                            Para confirmarlo debe hacer click en el siguiente enlace: \n
                            http://localhost/index.php/inicio/confirmar/codigo=".$data['codigo']; 
                            $bool = mail($email,"Confirmacion de registro en Quiniela Racing FASTmag 2015", $mensaje, $headers);
                            if($bool):
                                echo "Correo enviado";die;
                            else:
                                $this->M_insert->deleteRegisterUser($data['codigo']);
                                echo "No se pudo mandar el mensaje";die;
                            endif;*/

                            $this->index();
                        else:
                            echo false;
                        endif;
                    
                    } catch (FacebookApiException $e) {
                        $user = null;
                    }
                endif;
            else:
                $this->facebook->destroySession();
            endif;
            
        }
        
        public function ingresaFacebook(){
            #Se toma el id del usuario en facebook
            $user = $this->facebook->getUser();
            
            if ($user) {
                try {
                    $data['user_profile'] = $this->facebook->api('/me');
                } catch (FacebookApiException $e) {
                    $user = null;
                }
            }else {
                $this->facebook->destroySession();
            }
            
            $usuario = $data['user_profile']['id'];
            
            $validaUsuario = $this->M_consultas->get_datosUsuarios(1, 0, 0, 0, 1, $usuario);
            //echo "<pre>"; print_r($validaUsuario); die;
            
            if ( !empty($validaUsuario) && $user) {
                $data = array(
                                    'is_logued'     =>  TRUE,
                                    'id_usuario'    => enc_encrypt($validaUsuario[0]->idUsuario, KEY),
                                    'username'      => $validaUsuario[0]->nombre . $validaUsuario[0]->apellidoP,
                                    'perfil'        => enc_encrypt($validaUsuario[0]->idPermiso, KEY)
                                );
                $this->session->set_userdata($data);
                if($this->session->userdata('usuario_incorrecto') ):
                    $this->session->unset_userdata('usuario_incorrecto');
                endif;
                $data['logout_url'] = BASE_URL2.('index/logoutFacebook'); // Logs off application
                
                if($validaUsuario[0]->primerLogin == 0):
                    $this->M_update->updatePrimerLogin($usuario);
                    $data['body'] = 'app/finalUser/bienvenida';
                    $data['title'] = '<h1>BIENVENIDA</h1>';
                    $this->load->view('includes/finalUser/cargaPagina',$data);
                else:
                    redirect(base_url("finalUser/informacion"));
                endif;

            } else {
                $data['login_url'] = $this->facebook->getLoginUrl(array(
                    'redirect_uri' => BASE_URL2.('welcome/login'), 
                    'scope' => array("email") // permissions here
                ));
                echo "Aún no estás registrado"; die;
            }
            //echo "<pre>";            print_r($data); die;
        }
    
        public function registro(){
            $data['body'] = "registro";
            $data['paises'] = $this->M_consultas->get_paises("");
            $this->load->view('includes/finalUser/cargaPagina', $data);
        }
        
        public function registraUsuario(){
            $var= $_POST;
            $usuarioRep = $this->M_consultas->get_usuarioRepetido($var['userName'], true);
            $emailRep = $this->M_consultas->get_usuarioRepetido($var['email'], false);
            if( !empty($usuarioRep) || !empty($emailRep)):
                if( !empty($usuarioRep)):
                    $data['usuarioRep'] = TRUE;
                else:
                    $data['usuarioRep'] = FALSE;
                endif;
                
                if( !empty($emailRep)):
                    $data['emailRep'] = TRUE;
                else:
                    $data['emailRep'] = FALSE;
                endif;
            else:
                return false;
                $var['codigo'] = RandomString(10,TRUE,TRUE,FALSE);
                $email = $var['email'];
                $insert = $this->M_insert->insertRegisterUser($var);
                if($insert):
                    $headers = "From: Quiniela Racing FASTmag 2015";
                    $headers .= "Content-type: text/html; charset=utf-8\n";
                    $mensaje = "Usted solicito un registro en Quiniela Racing FASTmag 2015, \n
                    Para confirmarlo debe hacer click en el siguiente enlace: \n
                    http://localhost/index.php/inicio/confirmar/codigo=".$var['codigo']; 
                    $bool = mail($email,"Confirmacion de registro en Quiniela Racing FASTmag 2015", $mensaje, $headers);
                    if($bool):
                        echo "Correo enviado";die;
                    else:
                        $this->M_insert->deleteRegisterUser($var['codigo']);
                        echo "No se pudo mandar el mensaje";die;
                    endif;
                    
                    $this->index();
                else:
                    $data['body'] = "registro";
                    $data['falloInsercion'] = true;
                    $this->load->view('includes/finalUser/cargaPagina', $data);
                endif;
            endif;
        }

        public function nuevoUsuario(){
            $nombre = $this->sanitize->clean_string($_POST['nombre']);
            $apellidoP = $this->sanitize->clean_string($_POST['apellido']);
            $usuario =  $this->sanitize->clean_string($_POST['userName']);
            $contrasena = $this->sanitize->clean_string($_POST['password']);
            $email = $this->sanitize->clean_email($_POST['email']);
            $fechaNacimiento = $this->sanitize->clean_email($_POST['date']);
            $idPais = enc_decrypt( $this->sanitize->clean_string($_POST['userCountry']), KEY);
            //$_POST;
            $var = array(
                            'nombre'        =>  $nombre,
                            'apellidoP'     =>  $apellidoP,
                            'usuario'       =>  $usuario,
                            'contrasena'    =>  $contrasena,
                            'email'         =>  $email,
                            'idPais'        =>  $idPais
                
            );
            //echo "<pre>"; print_r($var); die;
            $usuarioRep = $this->M_consultas->get_usuarioRepetido($var['usuario'], true);
            $emailRep = $this->M_consultas->get_usuarioRepetido($var['email'], false);
            //echo "<pre>"; print_r($usuarioRep); echo "</pre>";
            //echo "<pre>"; print_r($emailRep); echo "</pre>"; die;
            if( !empty($usuarioRep) || !empty($emailRep)):   
                if( !empty($usuarioRep)):
                    $data['usuarioRep'] = TRUE;
                    return "Usuario Repetido";
                else:
                    $data['usuarioRep'] = FALSE;
                endif;
                
                if( !empty($emailRep)):
                    $data['emailRep'] = TRUE;
                    return "email Repetido";
                else:
                    $data['emailRep'] = FALSE;
                endif;
                echo FALSE;
                //$data['body'] = "registro";
                //$this->load->view('includes/finalUser/cargaPagina', $data);
            else:
                $var['contrasena'] = enc_encrypt($var['contrasena'], KEY);
                $insert = $this->M_insert->insertFinalUser($var);
                if($insert):
                    echo TRUE;
                endif;
            endif;
        }

        public function token(){
            $token = md5(uniqid(rand(),true));
            $this->session->set_userdata('token',$token);
            return $token;
        }

        public function logout_ci(){
            $this->session->sess_destroy();
            redirect(BASE_URL2."index");
            //$this->index();
        }
        
        public function logoutFacebook(){

        $this->load->library('facebook');

        // Logs off session from website
        $this->facebook->destroySession();
        // Make sure you destory website session as well.

        redirect('index');
    }
    
}

/*
 [array]Sesiones
(
    'session_id'    => encriptación aleatoria,
    'ip_address'    => 'cadena - dirección IP del usuario',
    'user_agent'    => 'cadena - datos del agente del usuario',
    'last_activity' => marca de tiempo
 * 
 * Variables propias de la sesión
    'is_logued'     =>  TRUE,
    'id_usuario'    =>  $result[0]->idUsuario,
    'username'      =>  $result[0]->usuario,
    'perfil'        =>  $result[0]->idPermiso
)
 */



?>