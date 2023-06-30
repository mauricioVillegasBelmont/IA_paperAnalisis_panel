<?php
/**
* Controlador para el ABM de reporting y el SessionHandler
*
*/
class Reporting extends Controller {

	public function reporte($args){
		SessionHandler()->check_state(2);
		$tipo_reporte = (is_string($args) && $args == 'registro') ? 'registro' : $args;
		$tipo_reporte = (is_array($args)) ? $args[0] : $tipo_reporte;
		$data = (object) array();
		switch ($tipo_reporte) {
			case 'game':
				$game = new Game();
				$collection = $game->get_colection_by_game($tipo_reporte);
				$table = 'game';
				$clear_table = array();
				break;
			default:
				$table = 'registro';
				$clear_table = array("newsletter", "status", "ip", "browser");
				break;
		}
		$collection = (!isset($collection)) ? CollectorObject::get($table) : $collection;
		$data->list = $this->__clean_colection($collection->collection, $clear_table);
		$this->view->listar($data, $tipo_reporte);
	}

	public function descargar($args){
		SessionHandler()->check_state(2);
		$tipo_reporte = (is_string($args) && $args == 'registro') ? 'registro' : $args;
		$tipo_reporte = (is_array($args)) ? $args[0] : $tipo_reporte;
		$data = (object) array();
		switch ($tipo_reporte) {
			case 'game':
				$game = new Game();
				$collection = $game->get_colection_by_game($tipo_reporte);
				$table = 'game';
				$clear_table = array();
				break;
			default:
				$table = 'Registro';
				$clear_table = array("newsletter", "status", "ip", "browser");
				break;
		}
		$collection = (!isset($collection)) ? CollectorObject::get($table) : $collection;
		$data->list = $this->__clean_colection($collection->collection, $clear_table);
		$this->view->descarga($data, $tipo_reporte);
	}

	# ==========================================================================
	#                       PRIVATE FUNCTIONS: Helpers
	# ==========================================================================
	private function get_list_count($opc = "registro"){
		$cond = "";
		$sql = "";
		switch ($opc) {
			case 'registro':
				$sql = "SELECT COUNT(*) AS 'n' FROM registro";
				break;
			case 'socios':
				$sql = "SELECT COUNT(*) AS 'n' FROM registro WHERE clave != ''";
				break;
			default:
				$sql = "SELECT COUNT(*) AS 'n' FROM game";
				break;
		}
		$results = DBLayer::execute($sql, array());
		$n = (count($results) > 0) ? $results[0] : 0;
		return ToolsHelper::clean_int($n['n']);
	}
	
	private function __clean_colection($list = array(), $remove = array()){
		if (empty($list)) return $list;
		foreach ($list as $element) {
			foreach ($remove as $key) {
				if (isset($element->$key)) unset($element->$key);
			}
		}
		return $list;
	}


}
