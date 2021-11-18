<?php
/**
* Modelo para el ABM de Usuarios
*/


class User extends StandardObject {

    public function __construct() {
        $this->user_id = 0;
        $this->name = '';
        $this->lastname = '';
        $this->user = '';
        $this->email = '';
        $this->pwd = '';
        $this->level = 0;
        $this->last_login = '';
    }
    
    function save() {
        if($this->user_id == 0){
            $fecha = date("Y-m-d H:i:s", time());
            $this->created = $fecha;
            $this->last_login = $fecha;
            $this->active = True;
        }
        parent::save();    
    }

    function exists_user(){
        $sql = "SELECT user_id FROM user WHERE user LIKE ?";
        $results = DBLayer::execute($sql, array($this->user));
        if(count($results)>0 && $results[0]["user_id"]!==$this->user_id)
            return true;
        else
            return false;
    }

    function change_pwd(){
        $pwd = $_POST["pwd"];
        if( $pwd != "d41d8cd98f00b204e9800998ecf8427e" ){
            if( function_exists("mcrypt_create_iv") ) {
                $salt = mcrypt_create_iv(64, MCRYPT_DEV_URANDOM);
            } else {
                $salt = random_bytes(64);
            }
            $strange = $pwd . $salt;
            $pwd = hash('sha256', $strange);
            $this->pwd = $pwd;
            $this->salt = $salt;
        }
    }

}

?>
