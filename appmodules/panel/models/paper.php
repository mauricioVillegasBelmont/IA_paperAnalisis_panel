<?php
/**
 * Pymengine IA Services PlugIn v1.0
 * 
 * @package IAServices
 * @author  Pymeweb
 * @license MIT
 * @link
 */
/**
 * 
  `paper_id` int(11) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `labels` text NOT NULL,
  `abstract` text NOT NULL,
  `metodology` text NOT NULL,
  `conclusions` text NOT NULL,
  `bibliography` text NOT NULL,
  `created` datetime NOT NULL,
  `path` varchar(256) NOT NULL
 * 
 */
class Paper  extends StandardObject {
  public $paper_id;
  public $title;
  public $authors;
  public $labels;
  public $abstract;
  public $metodology;
  public $conclusions;
  public $bibliography;
  public $document;
  public $created;
  public $path;

  public function __construct() {
    $this->paper_id = 0;
    $this->title = '';
    $this->authors = '';
    $this->labels = '';
    $this->abstract = '';
    $this->metodology = '';
    $this->conclusions = '';
    $this->bibliography = '';
    $this->document = '';
    $this->created = '';
    $this->path = '';
  }

  public function save() {
    if($this->paper_id != 0) return;
    $this->created = date("Y-m-d H:i:s", time());
    parent::save();
    // unset($_SESSION['testing']);
  }

  public function update(){
    if($this->paper_id == 0) return;
    parent::save();
  }

  public function set_values($values){
    $this->title = ToolsHelper::clean_str($values["title"]);
    $this->authors = serialize(  
      ToolsHelper::sanitize_strings_arr($values["authors"])
    );
    $this->labels = serialize(  
      ToolsHelper::sanitize_strings_arr($values["labels"])
    );
    $this->abstract = ToolsHelper::clean_str($values["abstract"]);
    $this->metodology = ToolsHelper::clean_str($values["metodology"]);
    $this->conclusions = ToolsHelper::clean_str($values["conclusions"]);
    $this->bibliography = serialize(
      ToolsHelper::sanitize_strings_arr($values["references"])
    );
    $this->document = ToolsHelper::clean_str($values["document"]);
    
    $this->path = ToolsHelper::clean_str($values["path"]??"");
  }
  public function get_authors() {
    return unserialize($this->authors);
  }
  public function get_label() {
    return unserialize($this->labels);
  }
  public function get_bibliography() {
    return unserialize($this->bibliography);
  }
  public function get(){
    parent::get();
    $this->labels = unserialize($this->labels);
    $this->bibliography = unserialize($this->bibliography);
    $this->created = date("Y-m-d H:i:s", strtotime($this->created));
    return $this;
  }


  public function get_labels(){
    $query = "SELECT `paper_id`, `labels` FROM `paper` WHERE `labels` != ''";
    $resp = DBLayer::execute($query, array());
    $response = array('labels'=>array());
    foreach($resp as $key => $value){
      $response[$value['paper_id']] = unserialize($value['labels']);
      $response['labels'] = array_merge($response['labels'],unserialize($value['labels']));
    }
    $labels = array_unique($response['labels']);

    
    // return $resp;
    return $response;
  }

}