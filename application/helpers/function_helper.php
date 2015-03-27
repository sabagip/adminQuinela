<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function dateConvert($date)
    {
        $date = explode("-", $date);
        $mes = $date[1];
        $mes = dateToLetter($mes);
        $date[1] = $mes;
        $date = implode(" de ", $date);
        return $date;
    }   
    
    function dateToLetter($fecha){
        switch ($fecha) {

          case "01":
              $mes="Enero";
            break;
          case "02":
              $mes="Febrero";
            break;
          case "03":
              $mes="Marzo";
            break;
          case "04":
              $mes="Abril";
            break;
          case "05":
              $mes="Mayo";
            break;
          case "06":
              $mes="Junio";
            break;
          case "07":
              $mes="Julio";
            break;
          case "08":
              $mes="Agosto";
            break;
          case "09":
              $mes="Septiembre";
            break;
          case "10":
              $mes="Octubre";
            break;
          case "11":
              $mes="Noviembre";
            break;
          case "12":
              $mes="Diciembre";
            break;

          default:
            break;
        }

        return $mes;
    }

    function subsDate($date , $days){
        $date = strtotime($days .' days',strtotime($date));
        $date = date("d-m-Y", $date);
        $date = dateConvert($date);
        return $date;
    }
    
    function get_FechaHoyZonaHoraria($fechaHoy, $utc){
        #Se convierte la fecha del servidor a UTC +0
        $fechaHoy = strtotime("+6 Hours", $fechaHoy);
        #Se saca el UTC de donde será la carrera
        $signo = substr($utc, 0, 1);
        
        if($signo == "+"):
            $fechaHoy = strtotime($utc ." Hours", $fechaHoy);
        elseif($signo == "-"):
            $fechaHoy = strtotime($utc ." Hours", $fechaHoy);
        endif;
        
        return $fechaHoy;
        
    }
    
    function RandomString($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
    {
        $source = 'abcdefghijklmnopqrstuvwxyz';
        if($uc==1) $source .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if($n==1) $source .= '1234567890';
        if($sc==1) $source .= '|@#~$%()=^*+[]{}-_';
        if($length>0){
            $rstr = "";
            $source = str_split($source,1);
            for($i=1; $i<=$length; $i++){
                mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,count($source));
                $rstr .= $source[$num-1];
            }

        }
        return $rstr;
    }
    
    function enc_encrypt($string, $key) {
        $result = '';
        for($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char) + ord($keychar));
            $result .= $char;
        }

        return base64_encode($result);
    }

    function enc_decrypt($string, $key) {
        $result = '';
        $string = base64_decode($string);

        for($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char) - ord($keychar));
            $result .= $char;
        }

        return $result;
    }
    
    function separateTopTen($topTen){
        //echo "<pre>"; print_r($topTen); die;
        $posiciones = array(
                                'First', 'Second', 'Third', 'Four', 'Five',
                                'Six', 'Seven', 'Eigth', 'Nine', 'Ten'
                                );
        $result = array();
        for( $i=0; $i<10; $i++):
            $posicion = "idPilot" . $posiciones[$i];
            $name = "pilotName" . ($i + 1);
            $last = "pilotLast" . ($i + 1);
            $arreglo = array(
                                'idPiloto' => $topTen[0]->$posicion,
                                'nombre' => $topTen[0]->$name,
                                'apellidoP' => $topTen[0]->$last
                                );
            $result[] = (object)$arreglo;
        endfor;
        
        //echo "<pre>"; print_r($result); die;
        return $result;
    }
    
    function divideFecha($date){
        $fecha = date( "j-n-Y-h-i-s", $date );
        $fecha = explode("-", $fecha);
        return $fecha;
    }
    
    function takeTopTen($topTen){
        $posiciones = array(
                                'First', 'Second', 'Third', 'Four', 'Five',
                                'Six', 'Seven', 'Eigth', 'Nine', 'Ten'
                                );
    
        $result = array();
        for( $i=0; $i<10; $i++):
            $posicion = "idPilot" . $posiciones[$i];
            $arreglo = array( $posicion => $topTen[$i] );
            $result[] = (object)$arreglo;
        endfor;
        
        //echo "<pre>"; print_r($result); die;
        return $result;
    }
?>