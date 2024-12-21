<?php
class TemplateHeplpers{
  public static function get_template( $template_file ){
    $file = APP_DIR . "appmodules/site/views/templates/" . $template_file;
    if (!file_exists($file)) return '';
    return file_get_contents($file);
  }

  public static function show_json($resJSON = array("status"=>"fail", "msg"=>"Respuesta por default.")){
    header('Content-Type: application/json');
    print json_encode($resJSON);
  }

  public static function renderer_helper($str, $dict){
    if (isset($dict['remove'])) {
      foreach ($dict['remove'] as $element) {
        $str = Template($str)->delete($element);
      }
      unset($dict['remove']);
    }
    if (isset($dict['list'])) {
      foreach ($dict['list'] as $key => $list) {
        $str = Template($str)->render_regex($key, $list);
      }
      unset($dict['list']);
    }
    if (isset($dict['globals'])) {
      foreach ($dict['globals'] as $key => $g_dict) {
        $GLOBALS['DICT'][$key] = $g_dict;
      }
      unset($dict['globals']);
    }
    $GLOBALS['DICT'] = array_merge($GLOBALS['DICT'], $dict);

    return Template($str)->render();
  }

  public static function common_globals(){
    $GLOBALS['DICT']['ROBOTS'] = "";
    if(!PRODUCTION){
      $GLOBALS['DICT']['ROBOTS'] ="<meta name=\"robots\" content=\"noindex\" />";
    }

    $GLOBALS['DICT']['GOOGLE_ANALYTICS'] = '';
    if(GOOGLE_ANALYTICS){
      $GLOBALS['DICT']['GOOGLE_ANALYTICS'] = self::get_template("scripts/google_analytics.html");
    }

    $GLOBALS['DICT']['GOOGLE_TM_HEAD'] = "";
    $GLOBALS['DICT']['GOOGLE_TM_BODY'] = "";
    if(GOOGLE_TM){
      $GLOBALS['DICT']['GOOGLE_TM_HEAD'] = self::get_template("scripts/google_tm_head.html");
      $GLOBALS['DICT']['GOOGLE_TM_BODY'] = self::get_template("scripts/google_tm_body.html");
    }

    $GLOBALS['DICT']['REGISTRATION_NAME'] = '';
    if( isset($_SESSION['PREREGISTRATION']['name']) ){
      $GLOBALS['DICT']['REGISTRATION_NAME'] = $_SESSION['PREREGISTRATION']['name'];
    }

    $GLOBALS['DICT']['REGISTRATION_MAIL'] = '';
    if( isset($_SESSION['PREREGISTRATION']['mail']) ){
      $GLOBALS['DICT']['REGISTRATION_MAIL'] = $_SESSION['PREREGISTRATION']['mail'];
    }

    $GLOBALS['DICT']['ERROR_MSG'] = '';
    if( isset($_SESSION['error_msg']) && $_SESSION["error_msg"] != NULL ){
      $GLOBALS['DICT']['REGISTRATION_MAIL'] = '<p class="text--center my--5">'.$_SESSION["error_msg"].'</p>';
    }


    $GLOBALS['DICT']['MQUERY_DOWN_SM'] = 'max-width: 576px';
    $GLOBALS['DICT']['MQUERY_DOWN_MD'] = 'max-width: 768px';
    $GLOBALS['DICT']['MQUERY_DOWN_LG'] = 'max-width: 992px';
    $GLOBALS['DICT']['MQUERY_UP_SM'] = 'min-width: 576px';
    $GLOBALS['DICT']['MQUERY_UP_MD'] = 'min-width: 768px';
    $GLOBALS['DICT']['MQUERY_UP_LG'] = 'min-width: 992px';
  }

  public static function set_libraries($lib = array()){
    foreach ($lib as $code) {
      switch ($code) {
        case 'home':
          $GLOBALS['DICT']['FOOTER_IMPORTS'] .= '<script src="{SITE_ASSETS}/js/home.bundle.js" charset="utf-8"></script>';
          break;
        case 'registro':
          $GLOBALS['DICT']['FOOTER_IMPORTS'] .= '<script src="{SITE_ASSETS}/js/registro.bundle.js" charset="utf-8"></script>';
          break;
        case 'animate_css':
          $GLOBALS['DICT']['HEAD_IMPORTS'] = '<link rel="stylesheet" type="text/css" href="{SITE_ASSETS}/css/animate.min.css" />';
          break;
        case 'trivia':
          $GLOBALS['DICT']['HEAD_IMPORTS'] .= '<link rel="prefetch" href="/get_trivia" as="fetch">';
          $GLOBALS['DICT']['FOOTER_IMPORTS'] .= "<script src='{SITE_ASSETS}/js/trivia.bundle.js' charset='utf-8'></script>";
          break;
        case 'disableDevtool':
          $GLOBALS['DICT']['FOOTER_IMPORTS'] .= '<script src="{SITE_ASSETS}/js/disableDevtool.bundle.js" charset="utf-8"></script>';
          break;
        default:
          break;
      }
    }
  }

}