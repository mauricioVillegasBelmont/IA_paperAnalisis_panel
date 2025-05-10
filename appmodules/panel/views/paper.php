<?php

class PaperView extends View
{
  public function render_page(){
    $this->set_lib();
    parent::render();
  }

  public function set_lib(){
    // $GLOBALS['DICT']['FOOTER_IMPORTS'] .= '<script src="{PUBLIC_ASSETS}/js/registro.bundle.js" charset="utf-8"></script>';
  }
}