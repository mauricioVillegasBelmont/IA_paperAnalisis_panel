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
  `reference_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `autors` VARCHAR(255),
  `type` ENUM('libro', 'articulo_revista', 'articulo_periodico', 'fuente_web', 'tesis', 'informe', 'capitulo_libro', 'ley', 'norma', 'otros') NOT NULL,
  `created` datetime NOT NULL
 * 
 */

class reference extends StandardObject{
  public $reference_id;
  public $name;
  public $autors;
  public $type;
  public $created ;

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
    $this->reference_id = 0;
    $this->name = '';
    $this->autors = '';
    $this->type = '';
    $this->created = '';
  }
  public function save(){
    if ($this->reference_id != 0) return
      $this->created = date("Y-m-d H:i:s", time());
    parent::save();
  }
  public function update(){
    if ($this->reference_id == 0) return
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

  public function get(){
    parent::get();
    $this->autors = unserialize($this->autors);
    $this->created = date("Y-m-d H:i:s", strtotime($this->created));
    return $this;
  }
}