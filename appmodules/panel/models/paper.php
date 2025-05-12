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
    $values = $this->sanitize_values($values);
    $this->title = $values["title"];
    $this->authors = $values["authors"];
    $this->labels = $values["labels"];
    $this->abstract = $values["abstract"];
    $this->metodology = $values["metodology"];
    $this->conclusions = $values["conclusions"];
    $this->bibliography = $values["bibliography"];
    $this->document = $values["document"];
    $this->path = $values["path"];
  }
  public function sanitize_values($values){
    $values["title"] = $values["title"]?ToolsHelper::clean_str($values["title"]):"";
    $values["authors"] = $values["authors"]?serialize(  
      ToolsHelper::sanitize_strings_arr($values["authors"])
    ):"";
    $values["labels"] = $values["labels"]?serialize(  
      ToolsHelper::sanitize_strings_arr($values["labels"])
    ):"";
    $values["abstract"] = $values["abstract"]?ToolsHelper::clean_str($values["abstract"]):"";
    $values["metodology"] = $values["metodology"]?ToolsHelper::clean_str($values["metodology"]):"";
    $values["conclusions"] = $values["conclusions"]?ToolsHelper::clean_str($values["conclusions"]):"";
    $values["bibliography"] = $values["bibliography"]?serialize(
      ToolsHelper::sanitize_strings_arr($values["bibliography"])
    ):"";
    $values["document"] = $values["document"]?ToolsHelper::clean_str($values["document"]):"";
    $values["path"] = $values["path"]?ToolsHelper::clean_str($values["path"]):"";
    return $values;
  }

  public function push_row($values) {
    $values = $this->sanitize_values($values);
    $sql = "INSERT INTO `paper`( `title`, `authors`, `labels`, `abstract`, `metodology`, `conclusions`, `bibliography`, `document`, `created`, `path`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
    list(
      $title,
      $authors,
      $labels,
      $abstract,
      $metodology,
      $conclusions,
      $bibliography,
      $document,
      $path,
    ) = $values;
    $response = DBLayer::execute($sql, array($title,$authors,$labels,$abstract,$metodology,$conclusions,$bibliography,$document,$path));
    // return $response;
  }
  public function get_authors() {
    return unserialize($this->authors);
  }
  public function get_documeny_labels() {
    return unserialize($this->labels);
  }
  public function get_document_bibliography() {
    return unserialize($this->bibliography);
  }
  public function get(){
    parent::get();
    $this->labels = unserialize($this->labels);
    $this->bibliography = unserialize($this->bibliography);
    $this->created = date("Y-m-d H:i:s", strtotime($this->created));
    return $this;
  }


  public function get_all_labels(){
    $query = "SELECT `paper_id`, `labels` FROM `paper` WHERE `labels` != ''";
    $resp = DBLayer::execute($query, array());
    $response = array('labels'=>array());
    foreach($resp as $key => $value){
      $response[$value['paper_id']] = unserialize($value['labels']);
      $response['labels'] = array_merge($response['labels'],unserialize($value['labels']));
    }
    $labels = array_unique($response['labels']);
    return $labels = array_unique($response['labels']);;
  }
  public function get_all_bibliography(){
    $query = "SELECT `paper_id`, `bibliography` FROM `paper` WHERE `bibliography` != ''";
    $resp = DBLayer::execute($query, array());
    $response = array('bibliography'=>array());
    foreach($resp as $key => $value){
      // $response[$value['paper_id']] = unserialize($value['labels']);
      $response['bibliography'] = array_merge($response['bibliography'],unserialize($value['bibliography']));
    } 
    // $bibliography = array_unique($response['bibliography']);
    return array_unique($response['bibliography']);
  }

}