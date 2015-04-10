<?php

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
        $crud->columns('nombre', '{nombre}  {apellidoP}', 'apellidoM', 'usuario', 'contrasena', 'email', 'fechaNacimiento', 'sexo', 'idPais', 'puntosTotales');

        $crud->display_as(array(
            'nombre' => 'Nombre(s)',
            '{nombre}  {apellidoP}' => 'Apellido Paterno',
            'apellidoM' => 'Apellido Materno',
            'contrasena' => 'Contraseña',
            'email' => 'Correo Electronico',
            'fechaNacimiento' => 'Fecha de Nacimiento',
            'puntosTotales' => 'Puntos Totales'));

        $crud->required_fields(
                '(nombre)', '{nombre}  {apellidoP}', 'contrasena', 'email', 'fechaNacimiento', 'idPais');

        $crud->where('idPermiso', 1);
        $crud->unset_fields('idPermiso');
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
            $crud->unset_fields('idPermiso');
            
            $crud->set_rules("sexo", "sexo1", "Masculino, Femenino");
            
            $crud->set_relation("idPais", "f1_pais", "nombre");

            $crud->required_fields(
                    'nombre)', '{nombre}  {apellidoP}', 'contrasena', 'email', 'telefonoCel', 'fechaNacimiento', 'pais');
            
            
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
            $crud->set_relation('idPilotEleven',        'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotTwelve',        'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation('idPilotThirteen',      'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotFourteen',      'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotFiveteen',      'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotSixteen',       'f1_piloto', '{nombre}  {apellidoP}'); 
            $crud->set_relation('idPilotSeventeen',     'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation('idPilotEighteen',      'f1_piloto', '{nombre}  {apellidoP}');
            $crud->set_relation('idJornada',            'f1_jornada', 'fechaJornada');
            $crud->unset_fields(array ('idPilotNineteen', 'idPilotTwenty'));
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
        
        if($this->session->userdata['perfil'] == FALSE && $this->session->userdata['perfil'] != enc_encrypt('2', KEY)):
            redirect(site_url('inicio'));
        endif;
        
        try{
            $crud = new Grocery_CRUD();
            $crud->set_table("f1_usuario");
            
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
                                    'fotografia', 'activo');
            
            //$crud->set_field_upload('fotografia', 'application/resources/img/expertos');
            $crud->set_field_upload('fotografia', IMGEXPERTO_URL);
            
            $crud->set_relation('idPais',         'f1_pais', 'nombre'); 
            $crud->where("idPermiso = 3");
            
            $this->grocery_crud->callback_before_update('contrasena', array($this,'encriptaPassword'));
            $this->grocery_crud->callback_before_insert('contrasena', array($this,'encriptaPassword'));
            $output = $crud->render();
            $output->body = "app/admin/index";
            $this->load->view('includes/admin/cargaPagina', $output);
        }
        catch(Exception $e){
            show_error($e->getMessage());
        }
        
    }
}
