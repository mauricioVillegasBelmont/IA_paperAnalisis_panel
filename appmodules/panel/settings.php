<?php

$config_file = APP_DIR . 'appmodules/panel/config.ini';
if(!file_exists($config_file)) $config_file .= '.dist';
 
$constants = parse_ini_file($config_file, False);

if(!isset($GLOBALS['DICT'])) $GLOBALS['DICT'] = array();

foreach($constants as $constant=>$value) {
    define($constant, $value);
    $GLOBALS['DICT'][$constant] = $value;
}
$GLOBALS['BASE_TEMPLATE_PANEL'] = APP_DIR . "core/templates/basetemplate_panel.html";


?>
