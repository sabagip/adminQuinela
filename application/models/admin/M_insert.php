<?php
class M_insert extends CI_Model{
     public function __construct()
    {
        parent::__construct();
        //  %Y/%M/%d'
    }
    
    function saveResultadoPole($apuesta){
        try{
            $this->db->insert('f1_resultado_pole', $apuesta);
            return TRUE;
            
        }
        catch(Exception $e){
            return FALSE;
        }
    }
    
    function saveResultadoVuelta($apuesta){
        try{
            $this->db->insert('f1_resultado_vuelta', $apuesta);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
    function saveResultadoTopTen($apuesta){
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
            
            //echo "<pre>"; print_r($datos); die;
            $this->db->insert('f1_resultado_top_ten', $datos);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
    function saveGanadorJornada($ganador){
        try{
            $this->db->insert('f1_ganador_jornada', $ganador);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
    
}

?>