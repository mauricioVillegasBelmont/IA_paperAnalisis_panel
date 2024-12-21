<?php
/**
* Carga de módulo de panel para Pymeweb Engine
*/

import('appmodules.panel.helpers.setup');
import('appmodules.panel.helpers.tools');
import('appmodules.panel.helpers.menu');
import('appmodules.panel.helpers.validation');
import('appmodules.panel.helpers.template');
// import('appmodules.panel.helpers.file_upload');
// import('appmodules.panel.helpers.sendgrid');

import('appmodules.panel.settings');

/* user */
import('appmodules.panel.models.user');
import('appmodules.panel.views.user');

/* dashboard */
import('appmodules.panel.models.dashboard');
import('appmodules.panel.views.dashboard');

/* reporting */
// import('appmodules.panel.models.reporting');
// import('appmodules.panel.views.reporting');

/* site_data */
import('appmodules.panel.models.site_data');


if(SETUP) import('appmodules.panel.helpers.setup');
?>
