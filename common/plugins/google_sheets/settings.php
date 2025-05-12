<?php

$config_file = __DIR__ . '/config.ini';
if (!file_exists($config_file)) $config_file .= '.dist';

$constants = parse_ini_file($config_file, False);

if (!isset($GLOBALS['DICT'])) $GLOBALS['DICT'] = array();

foreach ($constants as $constant => $value) {
  define($constant, $value);
}