<?php

abstract class View
{
 public $basetemplate;
 public $globals;
 public $pnl_title;
 public $str;
 public $dict;
 public $remove;
 public $list;

  public function __construct(){
    $this->pnl_title = '';
    $this->basetemplate = $GLOBALS['BASE_TEMPLATE_PANEL'];
    if (!isset($GLOBALS['DICT']['HEAD_IMPORTS'])) $GLOBALS['DICT']['HEAD_IMPORTS'] = '';
    if (!isset($GLOBALS['DICT']['FOOTER_IMPORTS'])) $GLOBALS['DICT']['FOOTER_IMPORTS'] = '';
    $this->clear();
  }

  public function render()
  {
    $this->set_globals();
    $html = Template($this->pnl_title, $this->basetemplate)->show($this->str);
    $html = $this->remove_unuseds($html);
    $html = $this->render_lists($html);
    $html = $this->render_dicts($html);
    print $html;
    $this->clear();
  }

  public function set_globals()
  {
    foreach ($this->globals as $key => $g_dict) {
      $GLOBALS['DICT'][$key] = $g_dict;
    }
  }
  public function remove_unuseds($str = null)
  {
    if (is_null($str)) {
      $str = $this->str;
    }
    if (empty($this->remove)) {
      return $str;
    }
    foreach ($this->remove as $element) {
      $str = Template($str)->delete($element);
    }
    return Template($str)->render();
  }

  public function render_lists($str=null)
  {
    if (is_null($str)) {
      $str = $this->str;
    }
    if (empty($this->list)) {
      return $str;
    }
    foreach ($this->list as $key => $list) {
      $str = Template($str)->render_regex($key, $list);
    }
    return $str;
  }
  public function render_dicts($str=null)
  {
    if (is_null($str)) {
      $str = $this->str;
    }
    return Template($str)->render($this->dict);
  }

  public function clear()
  {
    $this->str = '';
    $this->dict = array();
    $this->remove = array();
    $this->list = array();
  }

  public function show_json($resJSON = array("status" => "fail", "msg" => "Respuesta por default."))
  {
    header('Content-Type: application/json');
    print json_encode($resJSON);
  }
}