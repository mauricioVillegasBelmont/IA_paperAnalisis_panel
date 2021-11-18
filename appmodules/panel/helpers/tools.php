<?php
/**
* Helper administrativo para validacion y utillerias del sitio y/o panel.
*/

class ToolsHelper {

    static function clean_str( $s ) {
        $s = preg_replace('!\s+!', ' ', trim($s));
        $s = str_replace(array("\t","\r\n","\n","\0","\v"),'', $s);
        $s = htmlentities( strip_tags($s), ENT_QUOTES );
        return $s;
    }

    static function clean_int( $i ){
        return intval(ToolsHelper::clean_str($i));
    }

    static function strlen( $s, $min , $max=0 ) {
        $ok = true;
        $l = mb_strlen($s, "UTF-8");
        $ok = $l >= $min;
        $ok = $max ? ( $ok AND ($l <= $max) ) : $ok;
        return $ok;
    }

    static function alpha_numeric( $input ) {
        return (preg_match("#^[a-zA-ZÀ-ÿ0-9 ]+$#", $input) == 1);
    }

    static function randHash($qtd=5){
        //Under the string $Caracteres you write all the characters you want to be used to randomly generate the code.
        $Caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $QuantidadeCaracteres = strlen($Caracteres);
        $QuantidadeCaracteres--;
        $Hash=NULL;
        for($x=1;$x<=$qtd;$x++){
            $Posicao = rand(0,$QuantidadeCaracteres);
            $Hash .= substr($Caracteres,$Posicao,1);
        }
        return $Hash;
    }

    static function get_ip(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){ //check ip from share internet
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    static function get_browser(){
        return $_SERVER['HTTP_USER_AGENT'];
    }

    // miau's code
    static function get_protocol(){
      return stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';;
    }

    static function array_insert($array, $position, $insert_array) {
      $first_array = array_splice ($array, 0, $position);
      $array = array_merge ($first_array, $insert_array, $array);
      return $array;
    }

    static function get_numeric($val) {
      if (is_numeric($val)) {
        return $val + 0;
      }
      return 0;
    }

}
