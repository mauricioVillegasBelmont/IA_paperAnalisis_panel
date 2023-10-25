<?php
/**
* Controlador para el ABM de Dashboard y el SessionHandler
*
*/


class DashboardController extends Controller {

    # ==========================================================================
    #                    Recursos básicos (estándar)
    # ==========================================================================

    public function home() {
        // var_dump($GLOBALS['DICT']);die();
        SessionHandler()->check_state(2);
        $this->view->show_home();
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
