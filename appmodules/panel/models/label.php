<?php
// 
/**
 * Pymengine IA Services PlugIn v1.0
 * 
 * @package IAServices
 * @author  Pymeweb
 * @license MIT
 * @link
 */
/**
  `label_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `created` datetime NOT NULL
 */

class Label  extends StandardObject{
  public $label_id;
  public $name;
  public $created;

  public function __construct(){
    $this->label_id = 0;
    $this->name = '';
    $this->created = '';
  }
  public function save(){
    if ($this->label_id != 0) return
      $this->created = date("Y-m-d H:i:s", time());
    parent::save();
  }

  public function update(){
    if ($this->label_id == 0) return
      parent::save();
  }
  public function edit_values($values) {
    $this->name = ToolsHelper::clean_str($values["name"]);;
  }

  public function sanitize_values($values){
    $this->name = ToolsHelper::clean_str($values["name"]);
    return $values;
  }
  public function set_values($values){
    $values = $this->sanitize_values($values);
    $this->name = $values["name"];
  }
  public function batch_labels($labels){
    foreach ($labels as $label) {
      $sql = "INSERT INTO label (`name`, `created`)
        SELECT * FROM (SELECT '{$label}' AS name, NOW() AS created) AS tmp
        WHERE NOT EXISTS (
          SELECT 1 FROM label WHERE name = '{$label}'
        ) LIMIT 1;";
      DBLayer::execute($sql, array());
    }

    return;
  }
}