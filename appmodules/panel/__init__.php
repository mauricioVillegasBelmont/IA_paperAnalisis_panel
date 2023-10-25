<?php
/**
* Carga de módulo de panel para Pymeweb Engine
*/

import('appmodules.panel.helpers.setup');
import('appmodules.panel.helpers.tools');
import('appmodules.panel.helpers.menu');

import('appmodules.panel.settings');
import('appmodules.panel.models.user');
import('appmodules.panel.views.user');
import('appmodules.panel.models.dashboard');
import('appmodules.panel.views.dashboard');
import('appmodules.panel.models.site_data');

if(SETUP) import('appmodules.panel.helpers.setup');
?>
