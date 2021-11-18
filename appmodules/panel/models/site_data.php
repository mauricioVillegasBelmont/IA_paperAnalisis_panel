<?php
/**
* Modelo para el ABM de Site Data
*/

class Site_Data extends StandardObject {
	public function __construct() {
	    $this->site_data_id = 0;
	    $this->name = '';
	    $this->value = '';
	    $this->status = 0;
	    $this->fecha = '';
	}

	function save() {
	    parent::save();
	}

	function get_data($name){
		$sql = "SELECT site_data_id FROM site_data WHERE name LIKE ? LIMIT 0,1";
		$results = DBLayer::execute($sql, array($name));
		if(count($results)>0){
			$this->site_data_id = $results[0]["site_data_id"];
			$this::get();
			return unserialize($this->value);
		}else{
			return NULL;
		}
	}

	function set_data($name, $value){
		$this->get_data($name);
		$this->name = $name;
		$this->value = serialize($value);
		$this->fecha = date("Y-m-d H:i:s", time());
		$this->save();
	}

	function exist_data($name){
		if( $this->get_data($name) === NULL ){
			return false;
		}
		return true;
	}
}

?>
