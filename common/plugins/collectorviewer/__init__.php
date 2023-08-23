<?php
/**
* Pymengine CollectorViewer PlugIn
*/

class CollectorViewer {

    public $options = array(
        "filter"=>"",          # Cadena de búsqueda inicial
        "colno"=>0,            # Nro. de columna por la cual ordenar (comienza en 0)
        "order"=>"desc",       # Tipo de orden [asc|desc]
        "length"=>10,          # Cantidad de registros a mostrar por página
        "addnew"=>"Agregar",   # Texto del enlace para agregar un nuevo elemento
        "head"=>"",            # Título de la tabla
    );

    function __construct($collection=array(), $modulo='', $modelo='',
            $ver=True, $editar=True, $eliminar=True, $options=array()) {
        $this->collection = $collection;
        $this->action = "/$modulo/$modelo";
        $this->table = file_get_contents(
            APP_DIR . "common/plugins/collectorviewer/collectorviewer.html");
        $this->buttons = array('ver'=>$ver, 'editar'=>$editar,
            'eliminar'=>$eliminar);
        if(!empty($options)) $this->options = $options;
        $this->options['lang'] = CVW_LANG;
        $this->set_buttons();
    }

    function set_buttons() {
        $func = self::__set_render_funcname();
        foreach($this->buttons as $tipoboton=>$mostrar) {
            if(!$mostrar) {
                $fuente1 = Template($this->table)->$func($tipoboton, False);
                $fuente2 = Template($this->table)->$func("th{$tipoboton}", False);
                $tbl = str_replace(array($fuente1, $fuente2), "", $this->table);
                $this->table = $tbl;
            }
        }
    }

    function get_thcols() {
        $cadena = "";
        $func = self::__set_render_funcname();
        $fuente = Template($this->table)->$func('tdtitle', False);
        if(isset($this->collection[0])) {
            foreach($this->collection[0] as $k=>$v) {
                $title = str_replace("_", " ", ucwords($k));
                $cadena .= str_replace("{tdtitle}", $title, $fuente);
            }
        }

        return $cadena;
    }

    function render_thcols() {
        $func = self::__set_render_funcname();
        $columnas = str_replace('<!--tdtitle-->', '', $this->get_thcols());
        $fuente = Template($this->table)->$func('tdtitle', False);
        $this->table = str_replace($fuente, $columnas, $this->table);
    }

    function get_tdcols() {
        $cadena = "";
        $func = self::__set_render_funcname();
        $fuente = Template($this->table)->$func('tdvalue', False);
        if(isset($this->collection[0])) {
            foreach($this->collection[0] as $k=>$v) {
                $cadena .= str_replace("{tdvalue}", "{{$k}}", $fuente);
            }
        }

        return $cadena;
    }

    function render_tdcols() {
        $columnas = str_replace('<!--tdvalue-->', '', $this->get_tdcols());
        $func = self::__set_render_funcname();
        $fuente = Template($this->table)->$func('tdvalue', False);
        $this->table = str_replace($fuente, $columnas, $this->table);
    }

    function alter_collection() {
        foreach($this->collection as &$obj) {
            foreach($obj as $property=>$value) {
                if(strpos($property, '_id') > 0) $obj->id = $value;
            }
        }
    }

    function render_rows() {
        $this->render_thcols();
        $this->render_tdcols();
        $this->alter_collection();
        $this->table = Template($this->table)->render_regex('listado',
            $this->collection);
    }

    function get_table() {
        $this->render_rows();
        $dict = array('action'=>$this->action,
            'tableid'=>"EuropioCViewer_" . spl_object_hash($this));
        $dict = array_merge($dict, $this->options);
        $this->table = Template($this->table)->render($dict);
        return $this->table;
    }

    private static function __set_render_funcname() {
        return (USE_PCRE) ? 'get_regex' : 'get_substr';
    }
}

# Alias para compatibilidad pseudo estática
function CollectorViewer($collection=array(), $modulo='', $modelo='',
    $ver=True, $editar=True, $eliminar=True, $options=array()) {
    return new CollectorViewer($collection, $modulo, $modelo, $ver, $editar,
        $eliminar, $options);
}

# extended usage for collector CollectorViewer
class CollectorTable extends CollectorViewer{
  function __construct($collection=array(), $modulo='', $modelo='', $buttons=array(),$options=array(),$template="") {
    parent::__construct();
    $this->collection = $collection;
    $this->action = "/$modulo/$modelo";
    $this->table = empty($template)?file_get_contents(APP_DIR."common/plugins/collectorviewer/collectorviewer.html"):$template;
    $this->options['lang'] = CVW_LANG;
    if(!empty($options)) $this->options = $options;
    if (empty($template) && empty($buttons)) $buttons = array('ver'=>false, 'editar'=>false,'eliminar'=>false);
    $this->buttons = $buttons;
    $this->set_buttons();
  }
  function alter_collection() {
    foreach($this->collection as $k=>&$obj) {
      $obj = (object)$obj;
      $ban = True;
      foreach($obj as $property=>$value) {
        if(strpos($property, '_id') > 0 && $ban){$obj->id = $value; $ban=False;}
        else {
          $obj->id = strval($k); $ban=False;
        }
      }
    }
  }
}

function CollectorTable($collection=array(), $modulo='', $modelo='', $buttons=array(),$options=array(),$template="") {
  return new CollectorTable($collection, $modulo, $modelo, $buttons, $options, $template);
}
?>