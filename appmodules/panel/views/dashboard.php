<?php
/**
* Vistas del ABM de Dashboard y SessionHandler
*/


class DashboardView { 

    public function show_home() {
        $basetemplate =$GLOBALS['BASE_TEMPLATE_PANEL'];
        $file = APP_DIR . "appmodules/panel/views/templates/d-home.html";
        $str = file_get_contents($file);
        //is admin
        if($_SESSION['level']>1){
            $str = Template($str)->delete("admin");
        }
        //end admin
        $GLOBALS['DICT']['PNL_TITLE'] = "Dashboard Site"; //set title page
        $html = Template(NULL, $basetemplate)->show($str);
        print $html;
    }

    public function show_consola($console=""){
        $_dict = array("CONSOLA"=>$console);
        $dict = array_merge($_dict, $GLOBALS['DICT']);

        $basetemplate = $GLOBALS['BASE_TEMPLATE_PANEL'];
        $file = APP_DIR . "appmodules/panel/views/templates/d-consola.html";
        $str = file_get_contents($file);
        $GLOBALS['DICT']['PNL_TITLE'] = "Consola"; //set title page
        $html = Template($str)->render($dict);
        $html = Template(NULL, $basetemplate)->show($html);
        print $html;
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

}

?>