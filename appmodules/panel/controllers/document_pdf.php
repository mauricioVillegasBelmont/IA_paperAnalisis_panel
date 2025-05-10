<?php

/**
 * Controlador para el ABM de Document_pdf y el SessionHandler
 *
 */


class Document_pdfController extends Controller {

    # ==========================================================================
    #                    Recursos básicos (estándar)
    # ==========================================================================

    public function reporte() {
        // var_dump($GLOBALS['DICT']);die();
        SessionHandler()->check_state(2);
        $this->view->str = TemplateHeplpers::get_template('/papers/index.html', 'panel');
        $this->view->globals['PNL_TITLE'] = "Documentos subidos";
        $dict['UPLOAD_MAX_FILESIZE'] = ini_get('upload_max_filesize');
        $dict['PHP_VERSION'] = phpversion();
        $dict['POST_MAX_SIZE'] = ini_get('post_max_size');
        // $this->view->show_page($dict);
    }

 
}

?>
