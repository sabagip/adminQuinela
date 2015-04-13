<?php
class M_consultas extends CI_Model{
     public function __construct()
    {
        parent::__construct();
        //  %Y/%M/%d'
    }

    function get_jornadas(){
        $this->db
                    ->select("jornada.idJornada, pista.nombre")
                    ->from("f1_jornada AS jornada")
                    ->join("f1_pistas AS pista", "jornada.idPista = pista.idPista")
                    ->order_by("fechaJornada", "ASC");
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_resultadoPole($idJornada){
        $this->db
                    ->select("idResultado, idPiloto, idJornada")
                    ->from("f1_resultado_pole")
                    ->where("idJornada", $idJornada);
        
        $query = $this->db->get();
        
        return $query->result();
    }
    
    function get_resultadoVuelta($idJornada){
        $this->db
                    ->select("idResultado, idPiloto, idJornada, tiempo")
                    ->from("f1_resultado_vuelta")
                    ->where("idJornada", $idJornada);
        
        $query = $this->db->get();
        
        return $query->result();
    }
    
    function get_resultadoTopTen($idJornada){
        $this->db
                    ->select("idResultado, idPilotFirst, idPilotSecond, idPilotThird")
                    ->select("idPilotFour, idPilotFive, idPilotSix, idPilotSeven")
                    ->select("idPilotEigth, idPilotNine, idPilotTen, idJornada")
                    ->from("f1_resultado_top_ten")
                    ->where("idJornada", $idJornada);
        
        $query = $this->db->get();
        
        return $query->result();
    }
    
    function get_userApuestaActivaPole($idJornada){
        $this->db
                    ->select("idJornada, idUsuario,  idPiloto, puntaje ")
                    ->from("f1_apuesta_pole")
                    ->where("prediccionActiva" , 1)
                    ->where("idJornada", $idJornada);
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_userApuestaActivaVuelta($idJornada){
        $this->db
                    ->select("idJornada, idUsuario,  idPiloto, tiempo, puntaje ")
                    ->from("f1_apuesta_vuelta")
                    ->where("prediccionActiva" , 1)
                    ->where("idJornada", $idJornada);
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_userApuestaActivaTopTen($idJornada){
        $this->db
                    ->select("idJornada, idUsuario, idPilotFirst, idPilotSecond")
                    ->select("idPilotThird, idPilotFour, idPilotFive, idPilotSix")
                    ->select("idPilotSeven, idPilotEigth, idPilotNine, idPilotTen, puntaje")
                    ->from("f1_apuesta_top_ten")
                    ->where("prediccionActiva" , 1)
                    ->where("idJornada", $idJornada);
        
        $query = $this->db->get();
        return $query->result();
    }
    
    
    
    function get_puntaje($nombre = 0){
        $this->db
                    ->select("nombre, valor")
                    ->from("f1_puntaje");
        
        if($nombre !== 0):
            $this->db->where("nombre", $nombre);
        endif;
        $query = $this->db->get();
        
        return $query->result();
    }
    
    /*function get_puntosPorAcierto($lugar){
        $nombreLugar = $lugar ."lugar";
        $this->db
                ->select("valor")
                ->from("f1_puntaje")
                ->where("nombre", $nombreLugar);
        
        $query = $this->db->get();
        $query = (int) $query->result()[0]->valor;
        return $query;
    }*/
    
    
    #Obtiene los usuarios normales o administradores mayores o menores de 18 años
    #$AdminOUser = 1 indica que se devuelvan a los usuarios normales
    #$AdminOUser = 2 indica que se devuelvan a los administradores
    #$MayorOMenor =  1 indica que se devuelvan a los mayores de 18 años
    #$MayorOMenor =  2 indica que se devuelvan a los menores de 18 años
    #$pais = [idPais] indica que traiga a los usuarios de un pais en especifico
    #$pais = 0 indica que no se deben de traer a los usuarios sin discriminar el país
    #$idUsuario = [idUsuario] indica que se traigan los datos del usuario con tal Id
    #$idUsuario = 0 indica que se debe de traer a todos los usuarios
    #activo = 1 indica que de debe de traer a los usuarios activos 
    #activo = 2 indica que se debe de traer a los usuarios inactivos
    function get_datosUsuarios($adminOUser = 0, $mayorOMenor = 0, $pais = 0, $idUsuario = 0, $activo = 0, $userName = 0){
        $this->db
                    ->select("user.idUsuario, user.usuario, user.idPais, pais.nombre AS pais, user.puntosTotales")
                    ->select("user.nombre, user.apellidoP, user.apellidoM, user.idPermiso, user.primerLogin")
                    ->select('user.email, user.contrasena, user.fotografia')
                    ->select("TIMESTAMPDIFF(YEAR, fechaNacimiento, CURDATE()) AS edad", FALSE)
                    ->from("f1_usuario AS user")
                    ->join("f1_pais AS pais", "user.idPais = pais.idPais")
                    //->where("user.puntosTotales >= 0")
                    ->order_by("user.puntosTotales", "DESC");
        
        if($adminOUser == 1):
            $this->db->where("idPermiso", 1);
        elseif($adminOUser == 2):
            $this->db->where("idPermiso", 2);
        endif;
        
        if($mayorOMenor == 1):
            $this->db->where("TIMESTAMPDIFF(YEAR, fechaNacimiento, CURDATE()) >= 18");
        elseif($mayorOMenor == 2):
            $this->db->where("TIMESTAMPDIFF(YEAR, fechaNacimiento, CURDATE()) < 18");
        endif;
        
        if($pais !== 0):
            $this->db->where("user.idPais", $pais);
        endif;
        
        if($idUsuario !== 0):
            $this->db->where("user.idUsuario", $idUsuario);
        endif;
        
        if($userName !== 0):
            $this->db->where("user.usuario", $userName);
        endif;
        
        if($activo == 1):
            $this->db->where("activo", 1);
        elseif($activo == 2):
            $this->db->where("activo", 0);
        endif;
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_paises($nombrePais = ""){
        $this->db
                    ->select('idPais, nombre')
                    ->from('f1_pais');
        
        if($nombrePais != ""):
            $this->db->where('nombre', $nombrePais);
        endif;
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function m_login($user, $pass){
        $this->db
                ->select('idUsuario, usuario, contrasena, idPermiso, nombre, apellidoP')
                ->from('f1_usuario')
                ->where('usuario', $user)
                ->where('contrasena', $pass);
        
        $query = $this->db->get();
        
        if($query->num_rows() == 1):
            return $query->result();
        else:
            $this->session->set_userdata('usuario_incorrecto', 'Los datos introducidos son incorrectos');
            //redirect(base_url().'index','refresh');
        endif;   
    }
    
    function get_pilotos($suplente){
        $this->db   
                ->select('piloto.idPiloto, piloto.nombre, piloto.apellidoP, piloto.apellidoM, piloto.fotografia')
                ->select('escuderia.nombre as escuderia')
                ->from('f1_piloto as piloto')
                ->join('f1_escuderia as escuderia', 'escuderia.idEscuderia = piloto.idEscuderia');
        
        if($suplente):
            $this->db->where('suplente != 0');
        else:
            $this->db->where('suplente', 0);    
        endif;
        
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_lastRace(){
        $this->db
                ->select('jornada.idJornada, jornada.fechaJornada, pista.nombre')
                ->distinct()
                ->from('f1_jornada as jornada')
                ->join('f1_pistas AS pista', 'pista.idPista = jornada.idPista')
                ->where('jornada.fechaJornada < now()')
                ->where('date_format(jornada.fechaJornada, "%Y") = YEAR( NOW() )')
                ->order_by('fechaJornada DESC')
                ->limit(1);
        
        $query = $this->db->get();
        //echo "<pre>"; print_r($this->db->last_query()); die;
        return $query->result();
    }
    
    function get_tramposos($fechas){
        $this->db
                    ->select('idUsuario')
                    ->from('registro')
                    ->where("fecha >= '" .date("Y-m-d", $fechas['fechaInicio']) ."'")
                    ->where("fecha <= '" .date("Y-m-d", $fechas['fechaFinal']) ."'")
                    ->group_by('idUsuario');
        
        $query = $this->db->get();
        return $query->result();
    }
}