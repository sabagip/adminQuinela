<?php date_default_timezone_set('America/Mexico_City');
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Bienvenido extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Your own constructor code
        $this->load->library(array('session', 'grocery_CRUD'));
                
        $this->load->model(array("admin/M_consultas", "admin/M_insert", "admin/M_update"));
    }
    public function index() {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt('2', KEY)):
            redirect(site_url('index'));
        endif;
        $crud = new grocery_CRUD();
        $crud->set_table('f1_usuario');
        $crud->set_subject("Usuario");
        $crud->columns('usuario', 'nombre', '{nombre}  {apellidoP}', 'apellidoM', 'contrasena', 'email', 'fechaNacimiento', 'sexo', 'idPais', 'puntosTotales', 'activo');
        $crud->display_as(array(
            'nombre' => 'Nombre(s)',
            '{nombre}  {apellidoP}' => 'Apellido Paterno',
            'apellidoM' => 'Apellido Materno',
            'contrasena' => 'Contraseña',
            'email' => 'Correo Electronico',
            'fechaNacimiento' => 'Fecha de Nacimiento',
            'puntosTotales' => 'Puntos Totales',
            'activo' => 'Usuario Activo'));
        $crud->required_fields(
                '(nombre)', '{nombre}  {apellidoP}', 'contrasena', 'email', 'fechaNacimiento', 'idPais', 'fechaRegistro', 'idPermiso');
        
        $crud->field_type('sexo','dropdown',
                                array( "Masculino"  => "Masculino", "Femenino" => "Femenino"));
        
        $crud->field_type('primerLogin','dropdown',
                                array( "1"  => "Si", "0" => "No"));
        
        $crud->field_type('idPermiso','dropdown',
                                array( "1"  => "Usuario Normal"));
        $crud->where('idPermiso', 1);
        $crud->unset_fields('fotografia', 'puntosTotales', 'facebook', 'twitter', 'google');
        $crud->set_relation("idPais", "f1_pais", "nombre");
        
        $crud->callback_before_update(array($this, 'encriptaPassword'));
        $crud->callback_before_insert(array($this, 'encriptaPassword'));
        
        $output = $crud->render();
        $output->body = "app/admin/index";
        
        $this->load->view('includes/admin/cargaPagina', $output);
        //$this->load->view('app/admin/index', $output);
    }
    public function admin() {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        try {
            $crud = new grocery_CRUD();
            $crud->set_table('f1_usuario');
            $crud->set_subject("Usuario");
            $crud->columns('nombre', '{nombre}  {apellidoP}', 'apellidoM', 'usuario', 'contrasena', 'email', 'TelefonoCas', 'telefonoCel', 'fechaNacimiento', 'sexo', 'idPais');
            $crud->display_as(array(
                'nombre' => 'Nombre(s)',
                '{nombre}  {apellidoP}' => 'Apellido Paterno',
                'apellidoM' => 'Apellido Materno',
                'contrasena' => 'Contraseña',
                'email' => 'Correo Electronico',
                'TelefonoCas' => 'Télefono de Casa',
                'telefonoCel' => 'Télefono Celular',
                'fechaNacimiento' => 'Fecha de Nacimiento',
                'idPais' => 'Pais'));
            $crud->where("idPermiso", 2);
            $crud->unset_fields('fotografia');
            
            $crud->field_type('sexo','dropdown',
                                array( "Masculino"  => "Masculino", "Femenino" => "Femenino"));
            
            $crud->field_type('idPermiso','dropdown',
                                array( "2"  => "Administrador"));
            
            $crud->set_relation("idPais", "f1_pais", "nombre");
            $crud->required_fields(
                    '(nombre)', '{nombre}  {apellidoP}', 'contrasena', 'email', 'telefonoCel', 'fechaNacimiento', 'pais', 'idPermiso');
            
            
            
            $crud->callback_before_update(array($this, 'encriptaPassword'));
            $crud->callback_before_insert(array($this, 'encriptaPassword'));
            
            $output = $crud->render();
            //echo "<pre>"; print_r($output); die;
            $output->body = "app/admin/index";
            $this->load->view('includes/admin/cargaPagina', $output);
        } catch (Exception $e) {
            show_error($e->getMessage());
        }
    }
    
    function encriptaPassword($datos){
        $datos['contrasena'] = enc_encrypt($datos['contrasena'], KEY);
        return $datos;
    }
    
    
    
    public function jornadas() {
        if ($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        $crud = new Grocery_CRUD();
        $crud->set_table('f1_jornada');
        $crud->display_as(array(
            'fechaJornada' => 'Día del Grand Prix',
            'idPista' => 'Nombre del evento',
        ));
        $crud->required_fields('fechaJornada', 'idPista');
        $crud->set_relation('idPista', "f1_pistas", 'nombre');
        
        $crud->callback_before_update(array($this,'actualizaImagenGP'));
        $crud->callback_before_insert(array($this,'insertaImagenGP'));
        $crud->callback_before_delete(array($this,'borraImagenGP'));
        $output = $crud->render();
        $output->body = "app/admin/index";
        $this->load->view('includes/admin/cargaPagina', $output);
    }
    public function pista() {
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        
        try{
            $crud = new Grocery_CRUD();
            $crud->set_table("f1_pistas");
            
            $crud->display_as(array(
                                    'nombre'    =>  'Nombre del evento',
                                    'idPais'    =>  'Pais',
                                    'longitud'  =>  'Longitud (m)'));
            $crud->required_fields('nombre', 'idPais', 'longitud');
            $crud->set_relation('idPais', 'f1_pais', 'nombre');
            $crud->set_field_upload('fotografia', IMGPISTAS_URL);
            $output = $crud->render();
            $output->body = "app/admin/index";
            $this->load->view('includes/admin/cargaPagina', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage());
        }
    }
    
    public function pilotos() {
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        
        try{
            $crud = new Grocery_CRUD();
            $crud->set_table("f1_piloto");
            
            $crud->display_as(array(
                                    'nombre'        =>  'Nombre',
                                    '{nombre}  {apellidoP}'     =>  'Apellido Paterno',
                                    'apellidoM'     =>  'Apellido Materno',
                                    'idPais'        =>  'Pais',
                                    'idEscuderia'   =>  'Escuderia',
                                    'fotografia'    =>  'Fotografía'));
            $crud->required_fields('nombre', 'idPais', '{nombre}  {apellidoP}', 'idEscuderia', 'fotografia');
            
            $crud->set_relation('idPais', 'f1_pais', 'nombre');
            $crud->set_relation('idEscuderia', 'f1_escuderia', 'nombre');
            $crud->set_field_upload('fotografia', IMGPILOTOS_URL);
            
            $crud->callback_before_update(array($this,'actualizaImagenPiloto'));
            $crud->callback_before_insert(array($this,'insertaImagenPiloto'));
            $crud->callback_before_delete(array($this,'borraImagenPiloto'));
            
            $output = $crud->render();
            $output->body = "app/admin/index";
            $this->load->view('includes/admin/cargaPagina', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage());
        }
    }
    
    public function escuderias() {
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        
        try{
            $crud = new Grocery_CRUD();
            $crud->set_table("f1_escuderia");
            
            $crud->display_as(array(
                                    'nombre'        =>  'Nombre',
                                    'logo'          =>  'Logotipo'));
            $crud->required_fields('nombre', 'logo');
            
            $crud->set_field_upload('logo', IMGESCUDERIA_URL);
            $output = $crud->render();
            $output->body = "app/admin/index";
            $this->load->view('includes/admin/cargaPagina', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage());
        }
    }
    
    public function podio() {
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        
        try{
            $crud = new Grocery_CRUD();
            $crud->set_table("f1_resultado_top_ten");
            
            $crud->columns();
            $crud->display_as(array(
                                    'idPilotFirst'                  =>  'Primero',
                                    'idPilotSecond'                 =>  'Segundo',
                                    'idPilotThird'                  =>  'Tercero',
                                    'idPilotFour'                   =>  'Cuarto',
                                    'idPilotFive'                   =>  'Quinto',
                                    'idPilotSix'                    =>  'Sexto',
                                    'idPilotSeven'                  =>  'Septimo',
                                    'idPilotEigth'                  =>  'Octavo',
                                    'idPilotNine'                   =>  'Noveno',
                                    'idPilotTen'                    =>  'Decimo',
                                    'idPilotEleven'                 =>  'Decimo primero',
                                    'idPilotTwelve'                 =>  'Decimo segundo',
                                    'idPilotThirteen'               =>  'Decimo tercero',
                                    'idPilotFourteen'               =>  'Decimo cuarto',
                                    'idPilotFiveteen'               =>  'Decimo quinto',
                                    'idPilotSixteen'                =>  'Decimo sexto',
                                    'idPilotSeventeen'              =>  'Decimo septimo',
                                    'idPilotEighteen'               =>  'Decimo octavo',
                                    'idPilotNineteen'               =>  'Decimo noveno',
                                    'idPilotTwenty'                 =>  'Veinteavo',
                                    'idJornada'                     =>  'Fecha del Evento'    
                ));
            $crud->required_fields('idPilotFirst', 'idPilotSecond', 'idPilotThird', 'idPilotFour', 
                                    'idPilotFive', 'idPilotSix', 'idPilotSeven', 'idPilotEigth', 'idPilotNine', 
                                    'idPilotTen', 'idPilotEleven', 'idPilotTwelve', 'idPilotThirteen', 
                                    'idPilotFourteen', 'idPilotFiveteen', 'idPilotSixteen', 'idPilotSeventeen', 'idPilotEighteen');
            
            $crud->set_relation('idPilotFirst',         'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotSecond',        'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation('idPilotThird',         'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation( 'idPilotFour',         'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation('idPilotFive',          'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation('idPilotSix',           'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotSeven',         'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotEigth',         'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotNine',          'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotTen',           'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation('idJornada',            'f1_jornada', 'fechaJornada');
            $this->grocery_crud->callback_column('idJornada', array($this,'_formatea_acceso'));
            $output = $crud->render();
            $output->body = "app/admin/index";
            $this->load->view('includes/admin/cargaPagina', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage());
        }
    }
    
    public function anadeResultado(){
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        //$data['jornadas'] = $this->M_consultas->get_jornadas();
        $data['jornada'] = $this->M_consultas->get_lastRace();
        $data['pilotos'] = $this->M_consultas->get_pilotos(FALSE);
        
        $resultado = $this->M_consultas->get_resultadoPole($data['jornada'][0]->idJornada);
        if(empty($resultado)):
            $data['resultado'] = TRUE;
        else:
            $data['resultado'] = FALSE;
        endif;
        //echo "<pre>"; print_r($data); die;
        
        $data['body'] = "app/admin/prediccion";
        $this->load->view("includes/admin/cargaPagina", $data);
    }
    
    
    function _formatea_acceso($value) {
        $acceso_formateado = null;
        if (!is_null($value)) {
            $fecha_unix    = human_to_unix($value);
            $acceso_formateado   = mdate('%d/%m/%Y', $fecha_unix);
        }
        return $acceso_formateado;
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
    
    public function expertos(){
        //echo "<pre>"; print_r($_SERVER['DOCUMENT_ROOT']. IMGEXPERTO_URL) ; die;
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        
        try{
            $crud = new Grocery_CRUD();
            $crud->set_table("f1_usuario");
            $crud->set_subject("Experto");
            
            $crud->columns();
            $crud->display_as(array(
                                    'nombre'            =>  'Nombre',
                                    'apellidoP'         =>  'Apellido Paterno',
                                    'usuario'           =>  'Usuario',
                                    'contrasena'        =>  'Contraseña',
                                    'email'             =>  'Correo Electronico',
                                    'fechaNacimiento'   =>  'Fecha de Nacimiento',
                                    'sexo'              =>  'Sexo',
                                    'idPais'            =>  'Pais',
                                    'fotografia'        =>  'Fotografia',
                                    'activo'            =>  'Activo',
                                    
                ));
            $crud->required_fields('nombre', 'apellidoP', 'usuario', 'contrasena', 
                                    'email', 'fechaNacimiento', 'sexo', 'idPais', 
                                    'fotografia', 'activo', 'idPermiso');
            
            //$crud->set_field_upload('fotografia', 'application/resources/img/expertos');
            $crud->set_field_upload('fotografia', IMGEXPERTO_URL);
            $crud->set_relation('idPais',         'f1_pais', 'nombre'); 
            $crud->where("idPermiso", 3);
            
            $crud->unset_fields("primerLogin", "puntosTotales", "facebook", "twitter", "google");
            
            $crud->field_type('sexo','dropdown',
                                array( "Masculino"  => "Masculino", "Femenino" => "Femenino"));
            $crud->field_type('idPermiso','dropdown',
                                array( "3"  => "Experto"));
            
            $crud->callback_before_update(array($this,'encriptaPassword'));
            $crud->callback_before_update(array($this,'actualizaImagenExperto'));
            
            $crud->callback_before_insert(array($this,'encriptaPassword'));
            $crud->callback_before_insert(array($this,'insertaImagenExperto'));
            
            $crud->callback_before_delete(array($this,'borraImagenExperto'));
            
            $output = $crud->render();
            $output->body = "app/admin/index";
            $this->load->view('includes/admin/cargaPagina', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage());
        }
        
    }
    
    public function tramposos(){
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        $fechas = $this->fechasJornadaAnterior();
        $this->db = $this->load->database('default2',true);
        $tramposos = $this->M_consultas->get_tramposos($fechas);
        
        if(!empty($tramposos)):
            try{
                $crud = new Grocery_CRUD();
                $crud->set_table("registro");
                $crud->set_subject("tramposos");

                $crud->display_as(array(
                                        'idUsuario'     =>  'Nombre de Usuario',
                                        'movimiento'    =>  'Movimiento',
                                        'tabla'         =>  'Tabla',
                                        'activo'        =>  'Activo',
                                        'fecha'         =>  'Fecha del movimiento'
                                    ));
                $crud->set_relation('idUsuario', BDPRINCIPAL .'f1_usuario', 'usuario');
                $crud->where("activo" , 1);
                $crud->where("fecha >= '" .date("Y-m-d", $fechas['fechaInicio']) ."'");
                $crud->where("fecha <= '" .date("Y-m-d", $fechas['fechaFinal']) ."' GROUP BY Usuario");
                
                $crud->unset_add();
                $crud->unset_edit();
                $crud->unset_delete();

                $crud->add_action('Desactivar Usuario', IMG_URL. "prohibir.jpg", 'admin/bienvenido/agregaTrampa');

                $output = $crud->render();
                $output->body = "app/admin/index";
                $this->load->view('includes/admin/cargaPagina', $output);
            }
            catch(Exception $e){
                show_error($e->getMessage());
            }
        else:
            redirect( 'admin/bienvenido/verTramposos');
        endif;
    }
    
    
    
    
    
    
    /*
     * INICIAN CALLBACKS DE EXPERTOS
     */
    function insertaImagenExperto($datos){
        $archivo = copy(IMGORIGTEXPERTO_URL . $datos['fotografia'], IMGDESTEXPERTO_URL . $datos['fotografia']);
        if($archivo):
            echo "hecho"; 
            return true;
        else:
            return false;
        endif;
    }
    
    function borraImagenExperto($datos){
        $usuario = $this->M_consultas->get_datosUsuarios(0, 0, 0, $datos);
        //echo "<pre>"; print_r($usuario); die;
        
        if(!empty($usuario)):
            $archivo = unlink(IMGORIGTEXPERTO_URL . $usuario[0]->fotografia);
            $archivo2 = unlink(IMGDESTEXPERTO_URL . $usuario[0]->fotografia);
            
            if($archivo):
                echo "hecho"; 
                if($archivo2):
                    return true;
                else:
                    return false;
                endif;
                
            else:
                return false;
            endif;
        endif;
    }
    
    function actualizaImagenExperto($datos){
        $archivo = copy(IMGORIGTEXPERTO_URL . $datos['fotografia'], IMGDESTEXPERTO_URL . $datos['fotografia']);
        if($archivo):
            echo "hecho"; 
            return true;
        else:
            echo "falla"; 
            die;
        endif;
    }
    
    /*
     * INICIAN CALLBACKS DE PILOTOS
     */
    
    function insertaImagenPiloto($datos){
        $archivo = copy(IMGORIGTPILOTO_URL . $datos['fotografia'], IMGDESTPILOTO_URL . $datos['fotografia']);
        if($archivo):
            echo "hecho"; 
            return true;
        else:
            return false;
        endif;
    }
    
    function borraImagenPiloto($datos){
        $usuario = $this->M_consultas->get_datosUsuarios(0, 0, 0, $datos);
        //echo "<pre>"; print_r($usuario); die;
        
        if(!empty($usuario)):
            $archivo = unlink(IMGDESTPILOTO_URL . $usuario[0]->fotografia);
            $archivo2 = unlink(IMGORIGTPILOTO_URL . $usuario[0]->fotografia);
            
            if($archivo):
                echo "hecho"; 
                if($archivo2):
                    return true;
                else:
                    return false;
                endif;
                
            else:
                return false;
            endif;
        endif;
    }
    
    function actualizaImagenPiloto($datos){
        $archivo = copy(IMGORIGTPILOTO_URL . $datos['fotografia'], IMGDESTPILOTO_URL . $datos['fotografia']);
        if($archivo):
            echo "hecho"; 
            return true;
        else:
            echo "falla"; 
            die;
        endif;
    }
    
    /*
     * INICIAN CALLBACKS DE JORNADAS
     */
    
    function insertaImagenGP($datos){
        $archivo = copy(IMGORIGTJORNADA_URL . $datos['fotografia'], IMGDESTJORNADA_URL . $datos['fotografia']);
        if($archivo):
            echo "hecho"; 
            return true;
        else:
            return false;
        endif;
    }
    
    function borraImagenGP($datos){
        $usuario = $this->M_consultas->get_datosUsuarios(0, 0, 0, $datos);
        //echo "<pre>"; print_r($usuario); die;
        
        if(!empty($usuario)):
            $archivo = unlink(IMGDESTJORNADA_URL . $usuario[0]->fotografia);
            $archivo2 = unlink(IMGORIGJORNADA_URL . $usuario[0]->fotografia);
            
            if($archivo):
                echo "hecho"; 
                if($archivo2):
                    return true;
                else:
                    return false;
                endif;
                
            else:
                return false;
            endif;
        endif;
    }
    
    function actualizaImagenGP($datos){
        $archivo = copy(IMGORIGJORNADA_URL . $datos['fotografia'], IMGDESTJORNADA_URL . $datos['fotografia']);
        if($archivo):
            echo "hecho"; 
            return true;
        else:
            echo "falla"; 
            die;
        endif;
    }
    
    function permisoUsuario($datos){
        $datos['idPermiso'] = 1;
        return $datos;
    }
    
    function permisoAdmin($datos){
        $datos['idPermiso'] = 2;
    }
    
    function permisoExperto($datos){
        $datos['idPermiso'] = 3;
    }
    
    function fechasJornadaAnterior(){
        $lastRace = $this->M_consultas->get_lastRace();
        if(!empty($lastRace)):
            $lastRace = $lastRace[0];
            $fechaInicio =  strtotime( "-3 day 01 minutes", strtotime($lastRace->fechaJornada));
            if($lastRace->nombre == "GP de Mónaco"):
                        $fechaInicio =  strtotime( "-4 day 01 minutes", strtotime($lastRace->fechaJornada));
            endif;
            
            $fechaFinal = $lastRace->fechaJornada;
            $data['fechaInicio'] = $fechaInicio;
            $data['fechaFinal'] = strtotime($fechaFinal);
            return $data;
        else:
            echo "<h1> No hay carreras por evaluar <h1>";
            die;
        endif;
    }
    
    
    function agregaTrampa(){
        $fechas = $this->fechasJornadaAnterior();
        $lastRace = $this->M_consultas->get_lastRace();
        
        $this->db = $this->load->database('default2',true);
        $tramposos = $this->M_consultas->get_tramposos($fechas);
        
        //echo "<pre>"; print_r($lastRace); die;
        //echo "<pre>"; print_r($tramposos); die;
        $this->db = $this->load->database('default',true);
        $usuario = $this->M_update->updateAgregaTrampa($lastRace[0]->idJornada, $tramposos);
        if($usuario):
            redirect( 'admin/bienvenido/verTramposos');
        else:
            return false;
        endif;
        
        //echo "<pre>"; print_r($usuario); die;
    }
    function evaluaPredicciones(){
        $this->db = $this->load->database('default',true);
        $jornada = $this->M_consultas->get_lastRace();
        $jornada = $jornada[0]->idJornada;
        $resultadoVuelta = $this->M_consultas->get_resultadoVuelta($jornada);
        if(!empty($resultadoVuelta)):
            $resultadoTop = $this->M_consultas->get_resultadoTopTen($jornada);
            $resultadoPole = $this->M_consultas->get_resultadoPole($jornada);
            
            $apuestaPole = $this->M_consultas->get_userApuestaActivaPole($jornada);
            $apuestaVuelta = $this->M_consultas->get_userApuestaActivaVuelta($jornada);
            $apuestaTop = $this->M_consultas->get_userApuestaActivaTopTen($jornada);
            
            $this->db->trans_start();
                echo "Iniciando Evaluacion en apuestas de posición de privilegio <br>";
                $this->puntuaPole($apuestaPole, $resultadoPole);
                echo "Iniciando Evaluacion en apuestas de vuelta rápida <br>";
                $this->puntuaVuelta($apuestaVuelta, $resultadoVuelta);
                echo "Iniciando Evaluacion en apuestas de posición de privilegio <br>";
                $this->puntuaTopTen($apuestaTop, $resultadoTop);
            $this->db->trans_complete();
            
            if($this->db->trans_status() === TRUE):
                echo "Puntajes actualizados de la jornada <br>";
                echo "Comenzando a Actualizar el total de puntos por usuario.... <br>";
                sleep(3);
                               
                $result = $this->actualizaPuntajesTotales($jornada);
                if($result):
                    echo "Puntajes Actualizados satisfactoriamente";
                    sleep(5);
                    redirect('admin/bienvenido/tramposos');
                else:
                    echo "Error al actualizar puntajes, reiniciando....";
                    sleep(5);
                    redirect('admin/bienvenido/actualizaPuntajesTotales');
                endif;
            else:
                echo "Error, Intentando de nuevo....";
                sleep(3);
                redirect('admin/bienvenido/evaluaPredicciones');
            endif;
        else:
            echo "<pre>"; print_r("vacio") ; die;
        endif;
        //echo "<pre>"; print_r("vacio") ; die;
    }
    
    function puntuaPole($apuestas, $resultados){
        $puntajes = $this->M_consultas->get_puntaje("pole");
        foreach ($apuestas as $apuesta):
            if($apuesta->idPiloto == $resultados[0]->idPiloto):
                $this->M_update->updatePuntajePole($apuesta->idUsuario, $puntajes[0]->valor);
            endif;
        endforeach;    
    }
    
    function puntuaVuelta($apuestas, $resultados){
        $puntajes = $this->M_consultas->get_puntaje("vueltaRapida");
        
        foreach ($apuestas as $apuesta):
            if($apuesta->idPiloto == $resultados[0]->idPiloto):
                $this->M_update->updatePuntajeVuelta($apuesta->idUsuario, $puntajes[0]->valor);
            endif;
        endforeach;
        
    }
    
    function puntuaTopTen($apuestas, $resultados){
        $puntaje['5oMas'] = $this->M_consultas->get_puntaje("5oMas");
        $puntaje['perfecto'] = $this->M_consultas->get_puntaje("perfecta");
        
        $posiciones = array(
                                'First', 'Second', 'Third', 'Four', 'Five',
                                'Six', 'Seven', 'Eigth', 'Nine', 'Ten'
                                );
        for($x = 1; $x<=10; $x++):
            $puntaje[$x-1] = $this->M_consultas->get_puntaje($x . "lugar");
        endfor;
        /*echo "<pre>"; print_r($puntaje); echo "</pre>";
        echo "<pre>"; print_r($posiciones); echo "</pre>";
        echo "<pre>"; print_r($resultados); echo "</pre>";
        echo "<pre>"; print_r($apuestas); die;*/
        foreach ($apuestas as $apuesta):
            $aciertos = 0;
            $valor = 0;
            for($x = 0; $x < 10; $x++):
                $clave = "idPilot" . $posiciones[$x];
                if($apuesta->$clave == $resultados[0]->$clave):
                    $aciertos += 1;
                    $valor += $puntaje[$x][0]->valor;
                endif;
            endfor;
            
            if($aciertos >= 5 && $aciertos < 10):
                $valor += $puntaje['5oMas'][0]->valor;
            endif;
            
            if($aciertos == 10):
                $valor = $puntaje['perfecto'][0]->valor;
            endif;
            
            $this->M_update->updatePuntajeTop($apuesta->idUsuario, $valor);
            
        endforeach;
        
    }
    
    public function verTramposos(){
        $crud = new Grocery_CRUD();
        $crud->set_table("f1_apuesta_pole");
        $crud->set_subject("tramposos");
        
        $crud->columns('idUsuario', 'total');
        
        $crud->display_as(array(
                                    'idUsuario' => 'Usuario',
                                    'total'     =>  'Total de trampas'
        ));
        
        $crud->set_relation('idUsuario', 'f1_usuario', 'usuario');
        //$crud->set_relation('idJornada', 'f1_pistas', 'nombre');
        $crud->callback_column('total', array($this, 'cuentaTrampas'));
        $crud->where("activo", 1);
        $crud->where("trampaApuesta >= 1 GROUP BY usuario");
        
        $crud->unset_fields('idPiloto', 'idJornada' );
        //echo "<pre>"; print_r($crud); die;
        $crud->unset_add();
        $crud->unset_edit();
        $crud->unset_delete();

        $crud->add_action('Desactivar Usuario', IMG_URL. "prohibir.jpg", 'admin/bienvenido/desactivaUsuarios');

        $output = $crud->render();
        
        
        $output->body = "app/admin/index";
        $this->load->view('includes/admin/cargaPagina', $output);
    }
    
    function cuentaTrampas($datos, $renglon){
        //echo "ren<pre>"; print_r($renglon); echo "<pre>"; die;
        
        $trampas = $this->M_consultas->get_cuentaTrampas($renglon->idUsuario);
        return $trampas[0]->trampas;
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
    
    function desactivaUsuarios($idApuesta){
        //echo "d<pre>"; print_r($idApuesta); echo "<pre>"; die;
        $usuario = $this->M_consultas->get_userApuestaActivaPole(0, $idApuesta);
        $idUsuario = $usuario[0]->idUsuario;
        
        $this->M_update->updateDesactivaUsuario($idUsuario);
        
        redirect('admin/bienvenido/verTramposos');
    }
    
    function actualizaPuntajesTotales($jornada){
        $usuarios = $this->M_consultas->get_userApuestaActivaPole($jornada);
        
        $this->db->trans_start();
            foreach($usuarios as $usuario):
                $puntajeTotalPole = $this->M_consultas->get_sumaPorApuestas($usuario->idUsuario, 'f1_apuesta_pole');
                $puntajeTotalVuelta = $this->M_consultas->get_sumaPorApuestas($usuario->idUsuario, 'f1_apuesta_vuelta');
                $puntajeTotalTopTen = $this->M_consultas->get_sumaPorApuestas($usuario->idUsuario, 'f1_apuesta_top_ten');
                
                $total = $puntajeTotalPole[0]->total + $puntajeTotalTopTen[0]->total + $puntajeTotalVuelta[0]->total;
                $this->M_update->updatePuntajeTotal($total, $usuario->idUsuario);
            endforeach;
        $this->db->trans_complete();
        
        if($this->db->trans_status() === TRUE):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
}
