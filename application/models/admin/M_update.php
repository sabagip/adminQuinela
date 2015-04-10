<?php

class M_update extends CI_Model{
     public function __construct()
    {
        parent::__construct();
        //  %Y/%M/%d'
    }
    
    function updateResultadoPole($update){
        try{
            $this->db->where("idJornada", $update['idJornada']);
            $this->db->update("f1_resultado_pole", $update);
            RETURN TRUE;
        }
        catch(Exception $e){
            return FALSE;
        }
    }
    
    function updateResultadoVuelta($update){
        try{

            $this->db->where("idJornada", $update['idJornada']);
            $this->db->update("f1_resultado_vuelta", $update);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
    function updateResultadoTopTen($apuesta){
        
        try{
            $datos = array(
                                'idPilotFirst'      =>    $apuesta[0]->idPilotFirst,
                                'idPilotSecond'     =>    $apuesta[1]->idPilotSecond,
                                'idPilotThird'      =>    $apuesta[2]->idPilotThird,
                                'idPilotFour'       =>    $apuesta[3]->idPilotFour,
                                'idPilotFive'       =>    $apuesta[4]->idPilotFive,
                                'idPilotSix'        =>    $apuesta[5]->idPilotSix,
                                'idPilotSeven'      =>    $apuesta[6]->idPilotSeven,
                                'idPilotEigth'      =>    $apuesta[7]->idPilotEigth,
                                'idPilotNine'       =>    $apuesta[8]->idPilotNine,
                                'idPilotTen'        =>    $apuesta[9]->idPilotTen,
                                'idJornada'         =>    $apuesta['idJornada']);

            $this->db->where("idJornada", $apuesta['idJornada']);
            $this->db->update('f1_resultado_top_ten', $datos);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
    function updateUserApuestaPole($idUser, $idJornada, $bandera){
        if($bandera):
            $datos = array( "puntaje" => 5);
        else:
            $datos = array( "puntaje" => 0);    
        endif;
        
        try{
            $this->db
                        ->where("idJornada", $idJornada)
                        ->where("prediccionActiva", 1)
                        ->where("idUsuario", $idUser);

            $this->db->update("f1_apuesta_pole", $datos);
            return TRUE;
        }
        catch(Exception $e){
            return FALSE;
        }
    }
    
    function updateUserApuestaVuelta($idUser, $idJornada, $bandera){
        if($bandera):
            $datos = array( "puntaje" => 5);
        else:
            $datos = array( "puntaje" => 0);    
        endif;
        
        try{
            $this->db
                        ->where("idJornada", $idJornada)
                        ->where("prediccionActiva", 1)
                        ->where("idUsuario", $idUser);

            $this->db->update("f1_apuesta_vuelta", $datos);
            return TRUE;
        }
        catch (Exception $e){
            return FALSE;
        }
    }
    
    #puntaje indica el puntaje total del usuario
    function updateUserApuestaTopTen( $idUser, $idJornada, $puntaje){
        
        $datos = array("puntaje" => $puntaje);
        
        try{
            $this->db
                        ->where("idJornada", $idJornada)
                        ->where("prediccionActiva", 1)
                        ->where("idUsuario", $idUser);

            $this->db->update("f1_apuesta_top_ten", $datos);
        }
        catch (Exception $e){
            return FALSE;
        }
    }
    
}