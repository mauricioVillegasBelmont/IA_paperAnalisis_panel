<?php
/**
* WebForm PRO PlugIn
*
* Generador de formularios HTML5 para PymEngine
*
* This file is part of Europio GUIGenerator PlugIn.
* Europio GUIGenerator PlugIn is free software: you can redistribute it and/or
* modify it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* Europio GUIGenerator PlugIn is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with Europio GUIGenerator PlugIn. If not,
* see <http://www.gnu.org/licenses/>.
*
*
* @package    Europio GUIGenerator PlugIn
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     LinK <contacto@pymeweb.mx>
* @link       https://pymeweb.mx/
* @version    1.0
* @require    PymEngine 3.4.24 (o superior)
*/


class FormFactory {

  /**
  * Ruta de la plantilla HTML del formulario, por defecto
  */

  const DEFAULT_TEMPLATE = FF_DIR."/formfactory/templates/default_template.html";

  /**
  * Propiedades de acceso público
  * Pueden configurar solo tras instanciar al objeto
  * De ser instanciadas tras llamar a un método, éste podría fallar
  */
  public $template;               # plantilla HTML
  public $id;                     # ID del formulario

  /**
  * Propiedades de acceso público
  * Se puede acceder a ellas en cualquier momento sin riesgo a errores
  */
  public $method;                 # método de envío del formulario
  public $action;                 # valor del atributo action del formulario
  public $fields = array();       # Componentes del formulario
  public $form;                   # Código fuente del formulario completo

  public $title = '';          # Título a mostrar en el encabezado del form
  public $instructions = '';   # Texto a mostrar como inicio del formulario

  /**
  * Construir un objeto de tipo formulario
  *
  * @param  string  $action      valor del atributo action del formulario
  * @param  string  $method      método de envío del formulario
  * @return void
  */
  public function __construct($tmpl='', $action='', $method='') {
    $this->method = ($method) ? $method : 'POST';
    $this->valid_input_types = array(
      'HIDDEN','TEXT','PASSWORD','RESET','BUTTON','DEFAULT_TEMPLATE','IMAGE','COLOR','DATE','DATETIME-LOCAL','MONTH','EMAIL','NUMBER','URL','WEEK','SEARCH','TEL','SUBMIT','RESET','TEXTAREA','CHECKBOX',
    );
    $this->valid_input_group = array('DATALIST','SELECT','CHECKBOX','RADIO');
    $this->action = $action;
    $this->id = "WFP_" . hash('crc32', time());
    $template = (!empty($tmpl))? $tmpl:self::DEFAULT_TEMPLATE;
    $this->template = file_get_contents(APP_DIR . $template);
  }

  /*
  *
  * @single input
  * TEXT, PASSWORD, RESET, BUTTON, DEFAULT_TEMPLATE, IMAGE, COLOR, DATE, DATETIME-LOCAL, MONTH, EMAIL, NUMBER, URL, WEEK, SEARCH, TEL
  *
  */
  public function add_input_type( $element, $type="", $dict=array(), $col=12) {
    $field = $type.' is not valid input type.<br/>';
    $type = strtoupper($type);
    $element = strtoupper($element);
    if (in_array( $type, $this->valid_input_types) && !empty($dict)){
      $field = $this->get_field( $element, $type, $dict);
    }
    $this->add_field($field,$col);
  }
  public function get_input_type($element, $type="", $dict=array(), $col=12) {
    $type = strtoupper($type);
    $element = strtoupper($element);
    if (!in_array($type,$this->valid_input_types) && !empty($dict)) return $type.' is not valid input type.<br/>';
    $field = $this->get_field( $element, $type, $dict);
    return $field;
  }
  /*
  *
  * @multiple options input type
  * select, checkbox group, radio group
  *
  */
  public function add_input_group($type="", $collection=array(), $dict=array(), $col=12) {
    $field = $type.' is not valid input group or empty collection<br/>';
    $type = strtoupper($type);
    if (in_array( $type, $this->valid_input_group) && (!empty($collection) || !empty($dict))){
      //                                 ($key, $collection, $dict, $col=12
      $field = $this->add_iterated_fields($type, $collection, $dict, '');
    }
    $this->add_field($field,$col);
  }
  public function get_input_group($type="", $collection=array(), $dict=array(), $col=12) {
    $field = $type.' is not valid input group or is an empty collection<br/>';
    $type = strtoupper($type);
    if (in_array( $type, $this->valid_input_group) && (!empty($collection) || !empty($dict))){
      $field = $this->add_iterated_fields($type, $collection, $dict, '');
    }
    return $field;
  }







  /**
  * Agregar un captcha al formulario
  *
  * @param  string  $name        nombre del campo valor por defecto: captcha
  * @param  string  $label       etiqueta a mostrar junto al campo valor por defecto: captcha
  * @param  string  $extras      cualquier string a incluir dentro del tag <span> donde se muestra la pregunta
  * @param  string  $dbfile      si desea utilizar su propio archivo de  base de datos, ruta (desde APP_DIR) del  archivo de base de datos
  * @param  int     $col         entero entre 1 y 12 que representa el tamaño de la grilla ocupada por el campo. valor por defecto: 12 (ancho máximo)
  * @return void
  */
  public function add_captcha($name='', $label='', $extras='', $dbfile='', $col=12) {
    $name = ($name) ? $name : 'captcha';
    $value = Captcha($dbfile)->value;
    $this->add_field('CAPTCHA', $name, $label, $value, $extras, $col);
  }


  /**
  * Agregar código JavaScript al formulario, encargado de mostrar mensajes de
  * error junto a cada campo
  *
  * @param  array  $errores      un array asociativo conteniendo el nombre de los campos como clave y el mensaje de error de los campos que han fallado, como valor
  * @return void
  */
  public function add_errorzone($errores=array(), $title="Warning!") {
    $json_errores = json_encode($errores);
    $this->add_field('ERROR-ZONE', $this->id, $title, $json_errores, null, 0);
  }



  /**
  * Agregar un párrafo de texto (con instrucciones) al inicio del formulario
  *
  * @param  string  $content     (requerido) el texto a mostrar
  * @param  string  $extras      cualquier string a incluir en el tag div
  * @return void
  */
  public function add_instructions($content, $extras='') {
    $html = $this->get_code('INSTRUCTIONS');
    $dict = $this->set_dict('','', $content, '', $extras);
    $this->instructions = Template($html)->render($dict);
  }

  /**
  * Agregar un hipervínculo en cualquier parte del formulario
  *
  * @param  string  $anchor      (requerido) texto a mostrar
  * @param  string  $href        (requerido) URI del enlace
  * @param  string  $extras      cualquier string a incluir dentro del tag
  * @param  int     $col         entero entre 1 y 12 que representa el tamaño de la grilla ocupada por el campo. valor por defecto: 12 (ancho máximo)
  * @return void
  */
  public function add_link($anchor, $href, $extras='', $col=12) {
    $this->add_field('LINK', null, $anchor, $href, $extras, $col);
  }

  /**
  * Agregar un conjunto de hipervínculos al comienzo del formulario, debajo
  * del párrafo de instrucciones
  *
  * @param  array   $collection  (requerido) array conteniendo los datos para cada hipervínculo. Cada elemento del array será un array asociativo con dos claves: anchor (texto del enlace) y href (la URI)
  * @return void
  */
  public function add_navbar($collection) {
    $links = array();
    $container = $this->get_code('NAVBARCONTAINER');
    $item = $this->get_code('NAVBAR', $container);
    foreach($collection as $link) $links[] = Template($item)->render($link);
    $result = implode(" | ", $links);
    $this->fields[] = str_replace($item, $result, $container);
  }

  /**
  * Agregar un título en la cabecera del formulario
  *
  * @param  string  $title       Título a mostrar al inicio del formulario
  * @param  string  $extras      cualquier string a incluir en el tag <h3>
  * @return void
  */
  public function add_title($title='Formulario', $extras='') {
    $html = $this->get_code('TITLE');
    $dict = $this->set_dict('','', $title, '', $extras);
    $this->title = Template($html)->render($dict);
  }

  /**
  * Agregar un campo de tipo URL
  *
  * @param  string  $name        (requerido) nombre del campo
  * @param  string  $label       etiqueta a mostrar junto al campo si no se indica, se utiliza $name
  * @param  array   $value       valor por defecto del campo
  * @param  string  $extras      cualquier string a incluir dentro del tag
  * @param  int     $col         entero entre 1 y 12 que representa el tamaño de la grilla ocupada por el campo. valor por defecto: 12 (ancho máximo)
  * @return void
  */
  public function add_url($name, $label='', $value='', $extras='', $col=12) {
    $this->add_field('URL', $name, $label, $value, $extras, $col);
  }

  /**
  * Generar el código fuente del formulario
  *
  * @param  void
  * @return string   El código fuente del formulario. También recuperable por medio de la propiedad $form
  */
  public function get_form($alert = false) {
    $formtag = $this->get_code('FORM');
    $dict = array(
      "formid" => $this->id,
      "method" => $this->method,
      "action" => $this->action
    );
    if(!empty($this->fields)){
      $dict['FORMULARIO'] = implode("\n", $this->fields);
    }
    $dict['ALERT'] = ($alert)?$this->get_code('ALERT'):'';
    $this->form = Template($formtag)->render($dict);
    return $this->form;
  }

  /**
  * Agregar un fieldset (sin cerrarlo)
  *
  * @param  string  $legend        (opcional) Título del fieldset
  * @return void
  */
  public function open_fieldset($legend='') {
    $this->fields[] = $this->get_fieldset('OPEN', $legend);
  }

  /**
  * Agregar la etiqueta de cierre de un fieldset
  *
  * @param  void
  * @return void
  */
  public function close_fieldset() {
    $this->fields[] = $this->get_fieldset('CLOSE');
  }

  protected function _list_dict(&$dict){
    $name=""; $label=""; $value=""; $extras="";
    if(isset($dict['name'])){
      $name= $dict['name'];
      unset($dict['name']);
    }
    if(isset($dict['label'])){
      $label=$dict['label'];
      unset($dict['label']);
    }
    if(isset($dict['value'])){
      $value=$dict['value'];
      unset($dict['value']);
    }
    if($dict['extras']){
      $extras=$dict['extras'];
      unset($dict['extras']);
    }
    return array($name,$label,$value,$extras);
  }


  /**
  * Agregar un campo al array $fields
  *
  * @param  string  $key         (requerido) el nombre del identificado necesario para obtener el código HTML del campo desde la plantilla
  * @param  string  $name        (requerido) el nombre del campo o null
  * @param  string  $label       (requerido) la etiqueta o null
  * @param  string  $value       (requerido) el valor del campo o null
  * @param  string  $extras      (requerido) información adicional o null
  * @return void
  */
  protected function get_field($element,$key, $dict) {
    switch ($key) {
      case 'TEXTAREA':
      case 'HIDDEN':
        if($element != $key){
          $element = $key;
        }
        break;
      default:
        break;
    }
    $base = $this->get_code($element);
    $dict = $this->set_dict($key, $dict);
    return Template($base)->render($dict);
  }
  protected function add_field($render_field, $col=12) {
    list($open, $close) = $this->get_envelope_tags('LAYER', $col);
    $final_render = $open . $render_field . $close;
    $this->fields[] = ($col) ? $final_render : $render_field;
  }

  /**
  * Retorna tags que requieren una apertura y cierre
  *
  * @param  string  $key         (requerido) el nombre del identificado necesario para obtener el código HTML desde la plantilla
  * @param  string  $value       (requerido) un valor requerido a sustituir en los tags de apertura (varía de acuerdo al tag)
  * @return array                un array conteniendo la etiqueta de apertura y la de cierre, en ese orden
  */
  protected function get_envelope_tags($key, $value) {
    $code = $this->get_code($key);
    $tags = explode('{content}', $code);
    $open_tag = str_replace('{value}', $value, $tags[0]);
    $close_tag = $tags[1];
    return array($open_tag, $close_tag);
  }


  /**
  * Agregar un grupo de campos al array $fields
  *
  * @param  string  $key         (requerido) el nombre del identificado necesario para obtener el código HTML del campo desde la plantilla
  * @param  array   $collection  (requerido) array con los datos para generar los campos que conforman el grupo
  * @param  string  $name        (requerido) el nombre del campo o null
  * @param  string  $label       (requerido) la etiqueta o null
  * @param  string  $extras      información adicional
  * @param  int     $col         entero entre 1 y 12 que representa el tamaño de la grilla ocupada por el campo. valor por defecto: 12 (ancho máximo)
  * @return void
  */
  protected function add_iterated_fields($key, $collection, $dict, $col=12) {
    $container = $this->get_code("{$key}CONTAINER");
    $render = Template($container)->render_regex($key, $collection);
    $dict = $this->set_dict($key, $dict);
    return Template($render)->render($dict);
  }

  /**
  * Obtener una porción de código desde la plantilla HTML
  *
  * @param  string  $key         (requerido) el nombre del identificador
  * @param  string  $base        código HTML si difiere del almacenado en la propiedad $template
  * @return string  La porción de código HTML solicitada
  */
  protected function get_code($element, $base=null) {
    $base_str = ($base) ? $base : $this->template;
    return Template($base_str)->get_substr($element, True);
  }

  /**
  * Establecer un diccionario para sustituir el código HTML de un campo
  *
  * @param  string  $name        (requerido) el nombre del campo o null
  * @param  string  $label       (requerido) la etiqueta o null
  * @param  string  $value       (requerido) el valor del campo o null
  * @param  string  $extras      (requerido) información adicional o null
  * @return array   El diccionario necesario para efectuar las sustituciones
  */
  protected function set_dict($key, $dict) {
    if(!empty($this->fields)){
      $id = "{$dict['name']}_".(count($this->fields) + 1);
    }else {
      $id = "{$dict['name']}_".ToolsHelper::randHash(5);
    }
    $dict['label'] = $this->set_label($dict['label'], $id);
    $dict['type'] = strtolower($key);
    $dict['id'] = $id;
    return $dict;
  }

  /**
  * Obtiene las etiquetas para crear un fieldset de apertura y cierre
  *
  * @param  string  $which   (requerido) OPEN|CLOSE
  * @param  string  $legend  (opcional) reuqerido si $which es OPEN
  * @return string  La etiqueta de apertura si $which es OPEN o la de cierre si $which es CLOSE
  */
  protected function get_fieldset($which, $legend='') {
    list($open, $close) = $this->get_envelope_tags('FIELDSET', $legend);
    return ($which == 'OPEN') ? $open : $close;
  }

  /**
  * Establecer la etiqueta para un campo
  *
  * @param  string  $label   (requerido) la etiqueta enviada por el usuario
  * @param  string  $name    el nombre del campo. Si $label es null, utiliza este dato como etiqueta
  * @return string  La etiqueta para el campo
  */
  protected function set_label($_label='', $id='') {
    $label = '';
    if ($_label) {
      $label = $this->get_code("LABEL");
      $label = Template($label)->render(array('label'=>$_label,'id'=>$id));
    }
    return $label;
  }

}

function FormFactory($template="",$config = array()) {
  $config['action'] = ( isset($config['action'])||!empty($config['action']) )?$config['action']:'';
  $config['method'] = ( isset($config['method'])||!empty($config['method']) )?$config['method']:'';
  return new FormFactory($template,$config['action'], $config['method']);
}


?>
