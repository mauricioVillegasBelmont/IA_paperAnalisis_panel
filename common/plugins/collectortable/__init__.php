<?php
/**
* Pymengine CollectorTable PlugIn v2.0
*/

/*
    $table = array(
        "collection"=>array(),
        "modulo"=>'string',
        "modelo"=>'string',
        "buttons"=>array(),
        "options"=>array(),
        "template"=>'',
    );
*/
class CollectorTable {

    public $options = array(
        "lang"=>   CVW_LANG,
        "filter"=> CV_DEFAULT_FILTER,          # Cadena de búsqueda inicial
        "colno"=>  CV_DEFAULT_ORDERBYCOLUMN,   # Nro. de columna por la cual ordenar (comienza en 0)
        "order"=>  CV_DEFAULT_ORDERDIRECTION,  # Tipo de orden [asc|desc]
        "length"=> CV_DEFAULT_LENGTH,          # Cantidad de registros a mostrar por página
        "addnew"=> CV_DEFAULT_LINK_ADDNEW,     # Texto del enlace para agregar un nuevo elemento
        "head"=>   CV_DEFAULT_TABLE_TITLE,     # Título de la tabla
    );
    public $buttons = array(
        'ver' => true,
        'editar' => false,
        'eliminar' => false
    );

    function __construct(
        $table = array(
            "collection" => array(),
            "modulo" => '',
            "modelo" => '',
            "buttons" => array(),
            "options" => array(),
            "template" => '')
        ) {

        $collection = self::__colection_sanitizer($table['collection']);

        $this->collection = $collection;//$table['collection'];
        $this->modulo = "{$table['modulo']}";
        $this->modelo = "{$table['modelo']}";
        $this->action = "/{$table['modulo']}/{$table['modelo']}";

        $this->table = empty($table['template']) ? file_get_contents(APP_DIR . "common/plugins/collectortable/templates/default.html") : file_get_contents( APP_DIR . $table['template'] );


        if (!empty($table['buttons'])) $this->buttons = array_merge($this->buttons, $table['buttons']);
        if (!empty($table['options'])) $this->options = array_merge($this->options,$table['options']);
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
        foreach ($this->collection as &$obj) {
            foreach ($obj as $property => $value) {
                if (strpos($property, '_id') > 0) $obj->id = $value;
            }
        }
    }

    function render_rows() {
        $this->render_thcols();
        $this->render_tdcols();
        $this->alter_collection();
        $this->table = Template($this->table)->render_regex('listado', $this->collection);
    }

    function get_table() {
        $this->render_rows();
        $dict = array(
            'action'  => $this->action,
            'tableid' => "PymengineCollectorTable_" . spl_object_hash($this),
            'modulo'  => $this->modulo,
            'modelo'  => $this->modelo,
        );
        $dict = array_merge($dict, $this->options);
        $this->table = Template($this->table)->render($dict);
        return $this->table;
    }

    private static function __colection_sanitizer( $collection ) {
        foreach($collection as $k => &$item){
            if(!is_object($item)) $collection[$k] = (object) $item;
        }
        return $collection;
    }
    private static function __set_render_funcname() {
        return (USE_PCRE) ? 'get_regex' : 'get_substr';
    }
}

# Alias para compatibilidad pseudo estática
function CollectorTable($table = array("collection" => array(), "modulo" => '', "modelo" => '', "buttons" => array(), "options" => array(),"template"=>'')){
    return new CollectorTable ($table);
}











?>