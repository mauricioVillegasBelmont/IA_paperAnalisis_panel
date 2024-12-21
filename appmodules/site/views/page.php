<?php
/**
* Vistas del ABM de Page
*/


class PageView {

  public function show_page($dict = array('TEMPLATE' => 'home')) {

    $str = TemplateHeplpers::get_template( $dict['TEMPLATE'] . '.html' );
    unset($dict['TEMPLATE']);

    $this->set_imports_page();
    if (isset($dict['LIBS'])) {
      self::set_page_libraries($dict['LIBS']);
      unset($dict['LIBS']);
    }

    if (isset($dict['list'])) {
      foreach ($dict['list'] as $key => $list) {
        $str = Template($str)->render_regex($key, $list);
      }
      unset($dict['list']);
    }
    $html = Template('')->show($str);
    $html = TemplateHeplpers::renderer_helper($html, $dict);
    print $html;
  }

  public function show_json($resJSON = array("status"=>"fail", "msg"=>"Respuesta por default.")){
    header('Content-Type: application/json');
    print json_encode($resJSON);
  }

  # ==========================================================================
  #                       PRIVATE FUNCTIONS: Helpers
  # ==========================================================================

  private function set_imports_page(){
    TemplateHeplpers::common_globals();
    unset($_SESSION["error_msg"]);
  }

  private function set_page_libraries($lib = array()){
    if (!isset($GLOBALS['DICT']['HEAD_IMPORTS'])) $GLOBALS['DICT']['HEAD_IMPORTS'] = '';
    if (!isset($GLOBALS['DICT']['FOOTER_IMPORTS'])) $GLOBALS['DICT']['FOOTER_IMPORTS'] = '';
    $lib = array_unique($lib);
    TemplateHeplpers::set_libraries($lib);
  }
}

?>