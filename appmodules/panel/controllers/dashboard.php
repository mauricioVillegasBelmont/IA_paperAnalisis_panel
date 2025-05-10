<?php
/**
* Controlador para el ABM de Dashboard y el SessionHandler
*
*/


class DashboardController extends Controller {

    # ==========================================================================
    #                    Recursos básicos (estándar)
    # ==========================================================================

    public function default() {
        // var_dump($GLOBALS['DICT']);die();
			SessionHandler()->check_state(2);
			$dict['TEMPLATE'] = 'd-home';


			

			$chat = 'hello testing <br>';
			$dict['CHAT'] = $chat ? $chat : "No hay respuesta";
			$dict['PHP_VERSION'] = phpversion();
			$dict['WEB_DIR'] = WEB_DIR;
			$dict['SERVER_URI'] = SERVER_URI;
			$dict['POST_MAX_SIZE'] = ini_get('post_max_size');
			$dict['UPLOAD_MAX_FILESIZE'] = ini_get('upload_max_filesize');
			$this->view->show_page($dict);
    }

    public function pull(){
        SessionHandler()->check_state(1);
        $console = $this->exec_console("cd ".APP_DIR." && git pull 2>&1");
        $this->view->show_consola($console);
    }

    public function git_status(){
        SessionHandler()->check_state(1);
        //"cd ".APP_DIR." && git checkout . && git status 2>&1"
        $console = $this->exec_console("git status");
        $console.= "<br /><a href=\"/panel/dashboard/pull\"><button>GIT PULL</button></a>";
        $this->view->show_consola($console);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function exec_console($cmd=NULL){
        $str = "";
        if(function_exists("Shell_exec") && $cmd!=NULL){
            try {
                $str = Shell_exec($cmd);
            } catch (Exception $e) {
                $str = 'Excepción capturada: '.$e->getMessage()."\n";
            }
        }
        return nl2br($str);
    }
}

?>
