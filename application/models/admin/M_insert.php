<?php
class M_insert extends CI_Model{
     public function __construct()
    {
        parent::__construct();
        //  %Y/%M/%d'
    }
    
    function saveApuestapole($apuesta){
        try{
            $this->db->insert('f1_resultado_pole', $apuesta);
            return TRUE;
            
        }
        catch(Exception $e){
            return FALSE;
        }
    }
    
    function saveApuestaVuelta($apuesta){
        try{
            $this->db->insert('f1_resultado_vuelta', $apuesta);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
    function saveApuestaTopTen($apuesta){
        try{
            $datos = array(
                                'idPilotFirst'      =>    $apuesta[1],
                                'idPilotSecond'     =>    $apuesta[2],
                                'idPilotThird'      =>    $apuesta[3],
                                'idPilotFour'       =>    $apuesta[4],
                                'idPilotFive'       =>    $apuesta[5],
                                'idPilotSix'        =>    $apuesta[6],
                                'idPilotSeven'      =>    $apuesta[7],
                                'idPilotEigth'      =>    $apuesta[8],
                                'idPilotNine'       =>    $apuesta[9],
                                'idPilotTen'        =>    $apuesta[10],
                                'idJornada'         =>    $apuesta['jornada']);

            $this->db->insert('f1_resultado_top_ten', $datos);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
}

?>