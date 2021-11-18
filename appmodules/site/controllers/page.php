<?php
/**
* Controlador para el Home
**/


class PageController extends Controller {

    # ==========================================================================
    #                    Recursos básicos (estándar)
    # ==========================================================================

    public function default() {
        $this->view->show_home();
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function __test_validar($test=0) {
    }

}

?>