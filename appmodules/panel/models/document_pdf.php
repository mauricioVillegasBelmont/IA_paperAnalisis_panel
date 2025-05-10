<?php
/**
* Modelo para el ABM de Usuarios
*/


class Document_pdf extends StandardObject {
  public $document_pdf_id;
  public $name;
  public $content;
  public $created;
  public $processed;


  public function __construct() {
    $this->document_pdf_id = 0;
    $this->name = '';
    $this->content = '';
    $this->created = '';
    $this->processed = 0;
  }

  public function sanitize_values($values){
    $name = str_split($values["name"]?ToolsHelper::clean_str($values["name"]):"", 500);
    $values["name"] = $name[0];
    $values["content"] = $values["content"]?ToolsHelper::clean_str($values["content"]):"";;
    return $values;
  }

  public function set_values($values){
    $values = $this->sanitize_values($values);
    $this->name = $values["name"];
    $this->content = $values["content"];
  }

  public function save() {
    if($this->document_pdf_id == 0) $this->created = date("Y-m-d H:i:s", time());
    parent::save();
  }

  public function get_ia_unprocessed(){
    $sql = "SELECT `document_pdf_id`,`name`, `created` FROM document_pdf WHERE processed = 0";
    $result = DBLayer::execute($sql);
    return $result;
  }


}

?>