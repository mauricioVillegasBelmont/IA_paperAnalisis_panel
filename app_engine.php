<?php
/**
* PymEngine
*
* Motor principal de la aplicación.
* Importa todos los archivos necesarios, del núcleo de la aplicación e
* inicializa el controlador del motor MVC
*
* This file is part of PymEngine.
* PymEngine is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
* PymEngine is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with PymEngine.  If not, see <http://www.gnu.org/licenses/>.
*
*
* @package    PymEngine
* @license    http://www.gnu.org/licenses/gpl.txt  GNU GPL 3.0
* @author     LinK <contacto@pymeweb.mx>
* @link       https://pymeweb.mx
* @version    4.0
*/

require_once 'settings.php';

import('core.api.server');
import('core.api.client');

import('core.helpers.autoload');
import('core.helpers.patterns');
import('core.helpers.http');
import('core.helpers.template');
import('core.helpers.files');
import('core.helpers.dict');

import('core.libs.europio_code');

import('core.data.datahandler');

import('core.orm_engine.dblayer');
import('core.orm_engine.objects.object');
import('core.orm_engine.objects.standardobject');
#import('core.orm_engine.objects.serializedobject');
import('core.orm_engine.objects.collectorobject');
import('core.orm_engine.objects.multiplierobject');
import('core.orm_engine.objects.logicalconnector');
import('core.orm_engine.objects.composerobject');
import('core.orm_engine.objects.branchedobject');

import('core.sessions.handler');

import('core.mvc_engine.controller');
import('core.mvc_engine.front_controller');

import('core.dev_tools.helper');
import('core.dev_tools.debugger');
import('core.dev_tools.logger');
import('core.dev_tools.error_handler');
import('core.dev_tools.lang_improvement.class2func');
import('core.dev_tools.lang_improvement.phpfunc2method');

import('core.aliases');


import('common.composer.vendor.autoload');
# Importación de plugins
$apps = isset($options['PLUGINS']) ? $options['PLUGINS'] : array();
foreach($apps as $plugin=>$enabled) {
    if($enabled) import("common.plugins.$plugin.__init__"); 
}


# Autocarga de módulos
Autoload::get_installed_modules();

# Archivos del usuario son cargados desde user_imports.php
# Si este archivo no existe en la raíz de la app, debe ser creado
import('user_imports', False);

# Arrancar el motor de la aplicación
FrontController::start();
?>
