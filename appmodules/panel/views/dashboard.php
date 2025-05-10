<?php
/**
* Vistas del ABM de Dashboard y SessionHandler
*/


class DashboardView { 

    public function show_page($dict) {
        $basetemplate =$GLOBALS['BASE_TEMPLATE_PANEL'];
        $str = TemplateHeplpers::get_template($dict['TEMPLATE'] . '.html', 'panel');
        //is admin
        if($_SESSION['level']>1){
            $str = Template($str)->delete("admin");
        }
        //end admin
        $GLOBALS['DICT']['PNL_TITLE'] = "Dashboard Site"; //set title page

        $html = Template(NULL, $basetemplate)->show($str);
        $html = TemplateHeplpers::renderer_helper($html, $dict);
        print $html;
    }


    public function show_json($resJSON = array("status" => "fail", "msg" => "Respuesta por default."))
    {
        header('Content-Type: application/json');
        print json_encode($resJSON);
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function set_imports_page()
    {
        TemplateHeplpers::common_globals();
        unset($_SESSION["error_msg"]);
    }

    private function set_page_libraries($lib = array())
    {
        if (!isset($GLOBALS['DICT']['HEAD_IMPORTS'])) $GLOBALS['DICT']['HEAD_IMPORTS'] = '';
        if (!isset($GLOBALS['DICT']['FOOTER_IMPORTS'])) $GLOBALS['DICT']['FOOTER_IMPORTS'] = '';
        $lib = array_unique($lib);
        TemplateHeplpers::set_libraries($lib);
    }

}

?>