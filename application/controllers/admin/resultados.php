<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resultados extends CI_Controller {
        
        public function __construct()
        {
            parent::__construct();
            // Your own constructor code
            $this->load->library('session');
            
            $this->load->helper(array('url', 'function'));
        
            $this->load->database('default');
            
            $this->load->model("admin/M_consultas");
            $this->load->model("admin/M_insert");
            $this->load->model("admin/M_update");
            
        }
        
        public function saveResultado(){
            $pole = array("idPiloto" => $_POST['poleP'], "idJornada" => $_POST['jornada']);
            $vuelta = array("idPiloto" => $_POST['vueltaR'], "idJornada" => $_POST['jornada'], "tiempo" => $_POST['tiempo']);
            $top = $_POST;
            $repeatPole = $this->M_consultas->get_resultadoPole($vuelta['idJornada']);
            
            #Si no se han guardado resultados de la jornada indicada
            if( empty($repeatPole)  ):
                #Se inicia una transacción para guardar los resultados
                $this->db->trans_start();
                    $this->M_insert->saveApuestapole($pole);
                    $this->M_insert->saveApuestaVuelta($vuelta);
                    $this->M_insert->saveApuestaTopTen($top);
                $this->db->trans_complete();
                
                if ($this->db->trans_status() === FALSE):
                        echo "-----Error: " .log_message(); die;
                endif;
                
                #Se inicia una transacción para actualizar los puntajes de los usuarios
                $this->db->trans_start();
                    $this->setPuntajes($vuelta['idJornada']);
                $this->db->trans_complete();
                
                if ($this->db->trans_status() === FALSE):
                        echo "-----Error: " .log_message(); die;
                endif;
            
            #Si se han guardado resultados de la jornada indicada
            else:
                #Se inicia una transacción para actualizar los resultados
                $this->db->trans_start();
                    $this->M_update->updateApuestapole($pole);
                    $this->M_update->updateApuestaVuelta($vuelta);
                    $this->M_update->updateApuestaTopTen($top);
                $this->db->trans_complete();
                
                if ($this->db->trans_status() === FALSE):
                    echo "-----Error: " .log_message(); die;
                endif;
                
                #Se inicia una transacción para actualizar los puntajes de los usuarios
                $this->db->trans_start();
                    $this->setPuntajes($vuelta['idJornada']);
                $this->db->trans_complete();
                
                if ($this->db->trans_status() === FALSE):
                        echo "-----Error: " .log_message(); die;
                endif;
            endif;
            
            $data['body'] = "app/admin/index";
            $this->load->view("includes/admin/cargaPagina", $data);
        }
        
        function setPuntajes($idJornada){
            $usersPole = $this->M_consultas->get_userApuestaActivaPole($idJornada);
            $usersVuelta = $this->M_consultas->get_userApuestaActivaVuelta($idJornada);
            $usersTop = $this->M_consultas->get_userApuestaActivaTopTen($idJornada);

            $resultadoPole = $this->M_consultas->get_resultadoPole($idJornada);
            $resultadoVuelta = $this->M_consultas->get_resultadoVuelta($idJornada);
            $resultadoTopTen = $this->M_consultas->get_resultadoTopTen($idJornada);
            
            //$puntajes = $this->M_consultas->get_puntajes();
            
            #Se actualizan los puntajes de los usuarios en la tabla "apuesta_pole"
            foreach($usersPole as $user):
                if($user->idPiloto == $resultadoPole[0]->idPiloto):
                    $this->M_update->updateUserApuestaPole( $user->idUsuario, $idJornada, TRUE);
                else:
                    $this->M_update->updateUserApuestaPole( $user->idUsuario, $idJornada,FALSE);
                endif;
            endforeach;
            
            #Se actualizan los puntajes de los usuarios en la tabla "apuesta_vuelta"
            foreach($usersVuelta as $user):
                if($user->idPiloto == $resultadoVuelta[0]->idPiloto):
                    $this->M_update->updateUserApuestaVuelta($user->idUsuario, $idJornada, TRUE);
                else:
                    $this->M_update->updateUserApuestaVuelta( $user->idUsuario, $idJornada, FALSE);
                endif;
            endforeach;
            
            #Se actualizan los puntajes de los usuarios en la tabla "apuesta_top_ten"
            foreach($usersTop as $user):
                #La variable $puntaje tendrá la sumatoria de los aciertos del top ten
                #la variable $aciertos tendrá el control de los puntos adicionales
                $puntaje = 0;
                $aciertos= 0;
                if( $user->idPilotFirst == $resultadoTopTen[0]->idPilotFirst):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("1");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotSecond == $resultadoTopTen[0]->idPilotSecond):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("2");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotThird == $resultadoTopTen[0]->idPilotThird):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("3");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotFour == $resultadoTopTen[0]->idPilotFour):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("4");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotFive == $resultadoTopTen[0]->idPilotFive):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("5");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotSix == $resultadoTopTen[0]->idPilotSix):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("6");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotSeven == $resultadoTopTen[0]->idPilotSeven):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("7");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotEigth == $resultadoTopTen[0]->idPilotEigth):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("8");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotNine == $resultadoTopTen[0]->idPilotNine):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("9");
                    $aciertos += 1;
                endif;
                
                if( $user->idPilotTen == $resultadoTopTen[0]->idPilotTen):
                    $puntaje += $this->M_consultas->get_puntosPorAcierto("10");
                    $aciertos += 1;
                endif;
                #Se actualizan los puntajes según el número de aciertos
                if($aciertos == 10):
                    $comodin = $this->M_consultas->get_puntaje("perfecta");
                    $comodin = (int) $comodin[0]->valor;
                    $total = $comodin + $puntaje;
                    $this->M_update->updateUserApuestaTopTen($user->idUsuario, $idJornada, $total);
                endif;
                
                if($aciertos >= 5 && $aciertos <= 9):
                    $comodin = $this->M_consultas->get_puntaje("5oMas");
                    $comodin = (int) $comodin[0]->valor;
                    $total = $comodin + $puntaje;
                    $this->M_update->updateUserApuestaTopTen($user->idUsuario, $idJornada, $total);
                endif;
                
                if($aciertos < 5):
                    $total = $puntaje;
                    $this->M_update->updateUserApuestaTopTen($user->idUsuario, $idJornada, $total);
                endif;
            endforeach;
        }
        
}