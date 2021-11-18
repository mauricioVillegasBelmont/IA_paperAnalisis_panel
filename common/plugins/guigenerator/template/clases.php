<?php

abstract class BSCoreTemplate {

    public $base;
    
    function __construct() {
        $this->base = file_get_contents(STATIC_DIR . "sb-admin-v2/elements.html");
    }

    protected function get_code($key) {
        $this->base = Template($this->base)->get_substr($key, True);
    }
}


class BSTemplate {

    public $base;
    public $elements = array();
    public $page;

    public function __construct($title='') {
        $base = file_get_contents(STATIC_DIR . "sb-admin-v2/blank.html");
        $this->base = str_replace('{title}', $title, $base);
    }

    public function make() {
        $elements = implode("\n", $this->elements);
        $this->page = str_replace('{more}', $elements, $this->base);
    }

    public function add($element) {
        $this->elements[] = $element->code;
    }

}


class BSNavBar extends BSCoreTemplate {

    public function __construct($brand='') {
        parent::__construct();
        $this->get_code('BSNavBar');
        $this->code = str_replace('{brand}', $brand, $this->base);
    }

}

?>
