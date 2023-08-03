<?php
/**
* Controlador para el ABM de Usuarios y el SessionHandler
*
*/


class UserController extends Controller {

    # ==========================================================================
    #                    Recursos básicos (estándar)
    # ==========================================================================

    public function agregar() {
        @SessionHandler()->check_state(1);
        $this->view->show_form(False, False, $this->model);
    }

    public function editar($id=0) {
        // @SessionHandler()->check_state(1);
        $this->model->user_id = $id;
        $this->model->get();
        $this->view->show_form(False, False, $this->model, 'Editar');
    }

    public function guardar($redirect=true) {
        @SessionHandler()->check_state(1);
        $uri = $_SERVER['REQUEST_URI'];
        $redirect = ($uri == "/panel/user/guardar") ? True : $redirect;

        $id = (isset($_POST['user_id'])) ? $_POST['user_id'] : 0;
        $accion = ($id > 0) ? 'Editar' : 'Agregar';
        $this->model->user_id = $id;

        list($error, $msg) = $this->__validar_data($id);
        $this->__set_level();

        # Si no hay errores
        if(!$error) {
            $this->__guardar($redirect);
        # si hay errores
        } else {
            $this->__return_errors($redirect, get_defined_vars());
        }
    }

    public function listar() {
        @SessionHandler()->check_state(1);
        $collection = CollectorObject::get('User');
        $list = $this->__clean_colection( $collection->collection, array("pwd") );
        $this->view->listar($list);
    }

    public function eliminar($id=0) {
        @SessionHandler()->check_state(1);
        $this->model->user_id = $id;
        $this->model->destroy();
        HTTPHelper::go("/panel/user/listar");
    }

    public function setup(){
        if(SETUP){
            SetupHelper::install_users();
        }else{
            HTTPHelper::exit_by_ee1001();
        }

    }

    # ==========================================================================
    #                    Recursos manejados x SessionHandler
    # ==========================================================================

    # /panel/user/login (formulario para loguearse)
    public function login() {
        if(isset($_SESSION['level']) && $_SESSION['level']>0){
            HTTPHelper::go("/dashboard");
        }
        $this->view->show_login();
    }

    # /panel/user/check (valida los datos ingresados en el form de login)
    public function check() {
        SessionHandler()->check_user();
    }

    # /panel/user/logout (desconexión)
    public function logout() {
        SessionHandler()->destroy_session(True);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function __validar_data($id=0) {
        $user_exists = False;
        $msg = "";
        if($id>0) $this->model->get();
        $this->model->name = ToolsHelper::clean_str( $_POST["name"] );
        $this->model->lastname = ToolsHelper::clean_str( $_POST["lastname"] );
        $this->model->user = ToolsHelper::clean_str( $_POST["user"] );
        $this->model->email = ToolsHelper::clean_str( $_POST["email"] );
        $user_exists = $this->model->exists_user();
        $this->model->change_pwd();
        return array($user_exists, $msg);
    }

    private function __set_level() {
        extract($_POST);
        $level = (isset($otro)) ? $otro : $level;
        $this->model->level = (int)$level;
    }

    private function __guardar($redirect) {
        $this->model->save();
        if($redirect) {
            HTTPHelper::go("/panel/user/listar");
        } else {
            return $this->model->user_id;
        }
    }

    private function __return_errors($redirect, $data) {
        extract($data);
        if($redirect) {
            $this->view->show_form($user_exists, False, $this->model, $accion);
        } else {
            $errores = array(
                'user'=> ($user_exists) ? 'ERROR 1001: user exists' : 0,
                'pass'=> ($badpwd) ? 'ERROR 1002: invalid password' : 0,
            );
            return $errores;
        }
    }

    private function __clean_colection($list=array(), $remove=array()){
        if(empty($list)) return $list;
        foreach($list as $element){
            foreach($remove as $key){
                if(isset($element->$key)) unset($element->$key);
            }
        }
        return $list;
    }

}

?>