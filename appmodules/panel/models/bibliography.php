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
  `bibliography_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `autors` VARCHAR(255),
  `type` ENUM('libro', 'articulo_revista', 'articulo_periodico', 'fuente_web', 'tesis', 'informe', 'capitulo_libro', 'ley', 'norma', 'otros') NOT NULL,
  `created` datetime NOT NULL
 * 
 */

class Bibliography extends StandardObject{
  public $bibliography_id;
  public $name;
  public $autors;
  public $type;
  public $created;

  public $valid_types = array(
    'libro',
    'articulo_revista',
    'articulo_periodico',
    'fuente_web',
    'tesis',
    'informe',
    'capitulo_libro',
    'ley',
    'norma',
    'otros'
  );

  public function __construct(){
    $this->bibliography_id = 0;
    $this->name = '';
    $this->autors = '';
    $this->type = '';
    $this->created = '';
  }
  public function save(){
    if ($this->bibliography_id != 0) return
      $this->created = date("Y-m-d H:i:s", time());
    parent::save();
  }
  public function update(){
    if ($this->bibliography_id == 0) return
      parent::save();
  }
  public function set_values($values) {
    $this->name = ToolsHelper::clean_str($values["name"]);
    $this->autors = serialize(
      ToolsHelper::sanitize_strings_arr($values["autors"])
    );
    if (in_array($values["type"], $this->valid_types)) {
      $this->type = ToolsHelper::clean_str($values["type"]);
    }
  }

  public function batch_bibliography($bibliography){
    foreach ($bibliography as $biblio) {
      $sql = "INSERT INTO bibliography (`name`, `created`)
        SELECT * FROM (SELECT '{$biblio}' AS name, NOW() AS created) AS tmp
        WHERE NOT EXISTS (
          SELECT 1 FROM bibliography WHERE name = '{$biblio}'
        ) LIMIT 1;";
      DBLayer::execute($sql, array());
    }
    return;
    
  }
}