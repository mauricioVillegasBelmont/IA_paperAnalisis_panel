<?php

/**
 * Vistas del ABM de Dashboard y SessionHandler
 */
class ReportingView {
		public function listar($data = NULL, $type = NULL){
				$options = array(
						"filter" => ACT_FILTER, "colno" => ACT_ORDERBYCOLUMN,
						"order" => ACT_ORDERDIRECTION, "length" => ACT_LENGTH,
						"addnew" => ACT_LINK_ADDNEW, "head" => ACT_TABLE_TITLE
				);

				switch ($type) {
						case "reportes":
								$cv = CollectorViewer($data->list, 'panel', 'actividad', False, False, False, $options);
								$cv->table = file_get_contents(APP_DIR . "appmodules/panel/views/templates/reporte/lista.html");
								$cv->buttons['reporte'] = True;
								$cv->set_buttons();
								$str = $cv->get_table();
								break;
						case "registro":
								$cv = CollectorViewer($data->list, 'panel', 'dashboard', False, False, False, $options);
								$cv->table = file_get_contents(APP_DIR . "appmodules/panel/views/templates/reporte/lista.html");
								$cv->buttons['reporte'] = False;
								$cv->buttons['descarga'] = False;
								$cv->buttons['descarga_doc'] = True;
								$cv->set_buttons();
								$str = $cv->get_table();
								break;
						default:
								$cv = CollectorViewer($data->list, 'panel', 'dashboard', False, False, False, $options);
								$cv->table = file_get_contents(APP_DIR . "appmodules/panel/views/templates/reporte/lista.html");
								$cv->buttons['reporte'] = False;
								$cv->buttons['descarga'] = False;
								$cv->buttons['descarga_doc'] = True;
								$cv->set_buttons();
								$str = $cv->get_table();
								//$str = CollectorViewer($data->list, 'panel', 'actividad', False, True, True, $options)->get_table();
								break;
				}
				$dict = array('tipo_reporte' => $type);

				$basetemplate = $GLOBALS['BASE_TEMPLATE_PANEL'];
			
				$GLOBALS['DICT']['PNL_TITLE'] = "Reportes"; //set title page
				$html = Template(NULL, $basetemplate)->show($str);
				if ($type !== "votacion") $html = Template($html)->delete("votacion");
				if ($type === "votacion") $html = Template($html)->render_regex("votoinfo", $data->votoinfo);
				$html = Template($html)->render($dict);
				print $html;
		}
		public function descarga($data = NULL, $tipo = "default"){
				if ($data == NULL || !is_array($data->list) || count($data->list) == 0) {
						HTTPHelper::error_response();
				}
				$basetemplate = APP_DIR . "appmodules/panel/views/templates/reporte/basetemplate_blank.html";
				$str = file_get_contents(APP_DIR . "appmodules/panel/views/templates/reporte/descarga.html");
				//GET TITLLES AND RENDER
				$cadena = "";
				$fuente = Template($str)->get_substr('tdtitle', False);
				foreach ($data->list[0] as $k => $v) {
						$title = str_replace("_", " ", ucwords($k));
						$cadena .= str_replace("{tdtitle}", $title, $fuente);
				}
				$columnas = str_replace('<!--tdtitle-->', '', $cadena);
				$fuente = Template($str)->get_substr('tdtitle', False);
				$str = str_replace($fuente, $columnas, $str);
				//END TITLLES AND RENDER
				//GET DATA LIST AND PREPARE TO RENDER
				$cadena = "";
				$fuente = Template($str)->get_substr('tdvalue', False);
				foreach ($data->list[0] as $k => $v) {
						$cadena .= str_replace("{tdvalue}", "{{$k}}", $fuente);
				}
				$columnas = str_replace('<!--tdvalue-->', '', $cadena);
				$fuente = Template($str)->get_substr('tdvalue', False);
				$str = str_replace($fuente, $columnas, $str);
				//END GET DATA LIST AND PREPARE TO RENDER
				//REDER DATA
				$str = Template($str)->render_regex('listado', $data->list);
				//END REDER DATA
				$fecha = date("d-m-Y_His", time());
				header("Content-Type:  application/vnd.ms-excel; charset=utf-8");
				header("Content-Disposition: attachment; filename=reporte_{$tipo}_{$fecha}.xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				print Template(NULL, $basetemplate)->show($str);
		}
}


