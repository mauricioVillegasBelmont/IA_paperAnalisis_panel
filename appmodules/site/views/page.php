<?php
/**
* Vistas del ABM de Page
*/


class PageView {

    public function show_home(){
        $this->set_imports_page();
        $file = APP_DIR . "appmodules/site/views/templates/home.html";
        $str = file_get_contents($file);
        $html = Template('')->show($str);
        print $html;
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function set_imports_page(){
      $GLOBALS['DICT']['ROBOTS'] = "";
      if(!PRODUCTION){
        $GLOBALS['DICT']['ROBOTS'] ="<meta name=\"robots\" content=\"noindex\" />";
      }
      if(GOOGLE_ANALYTICS){
        $file = APP_DIR . "appmodules/site/views/templates/google_analytics.html";
        $str = file_get_contents($file);
        $GLOBALS['DICT']['GOOGLE_ANALYTICS'] = $str;
      }
    }

}

?>
