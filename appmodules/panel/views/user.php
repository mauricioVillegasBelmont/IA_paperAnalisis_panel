<?php
/**
* Vistas del ABM de Usuarios y SessionHandler
*/


class UserView { 

    public function show_form($user_exists=False, $badpwd=False, $model=NULL, $tit='Agregar') {
        $dict = $this->__set_dict($model, $tit, $user_exists, $badpwd);
        $this->__set_optleveldict($model->level, $dict);

        $basetemplate = APP_DIR . "appmodules/panel/views/templates/basetemplate.html";
        $str = file_get_contents( APP_DIR . "appmodules/panel/views/templates/add_user.html");
        $html = Template($str)->render($dict);
        $GLOBALS['DICT']['PNL_TITLE'] = $this->__get_pnl_title($tit); //set title page
        print Template(NULL, $basetemplate)->show($html);
    }

    public function listar($coleccion=array()) {
        foreach($coleccion as &$obj) {
            $obj->admin = ($obj->level == 1) ? "<i class='fa fa-meh-o'></i>" : "";
        }

        $options = array(
            "filter"=>USR_FILTER, "colno"=>USR_ORDERBYCOLUMN,
            "order"=>USR_ORDERDIRECTION, "length"=>USR_LENGTH,
            "addnew"=>USR_LINK_ADDNEW, "head"=>USR_TABLE_TITLE
        );

        $str = CollectorViewer($coleccion, 'panel', 'user', False, True, True, $options)->get_table();

        $basetemplate = APP_DIR . "appmodules/panel/views/templates/basetemplate.html";
        $GLOBALS['DICT']['PNL_TITLE'] = USR_TITLE_LIST; //set title page
        print Template(NULL, $basetemplate)->show($str);
    }

    public function show_login() {
        $basetemplate = APP_DIR . "appmodules/panel/views/templates/basetemplate.html";
        $file = APP_DIR . "appmodules/panel/views/templates/login_form.html";
        $form = file_get_contents($file);
        $GLOBALS['DICT']['PNL_TITLE'] = "Login"; //set title page
        $default = Template(NULL, $basetemplate)->show($form);
        $html = (CUSTOM_LOGIN_TEMPLATE) ? CUSTOM_LOGIN_TEMPLATE : $default;
        print $html;
    }

    # ==========================================================================
    #                       PRIVATE FUNCTIONS: Helpers
    # ==========================================================================

    private function __set_msgs($user_exists, $badpwd) {
        $msg = "";
        if($user_exists) $msg .= USR_MSGERROR_UNAME;
        if($badpwd) $msg .= USR_MSGERROR_PASS;
        return nl2br($msg);
    }


    private function __set_optleveldict($level, &$dict) {
        if($level > 0 and $level < 6) {
            $dict["opt$level"] = ' selected';
        } else {
            $dict["opt0"] = ' selected';
            $dict['level'] = $level;
            $dict["disabled"] = '';
        }
    }

    private function __set_dict($model, $tit, $user_exists, $badpwd) {
        $data = array(
            'opt1'=>'', 'opt2'=>'', 'opt3'=>'', 'opt4'=>'', 'opt5'=>'',
            'opt0'=>'', 'user'=>$model->user, 'level'=>'', 'pwd'=>$model->pwd,
            'name'=>$model->name, 'lastname'=>$model->lastname, 'email'=>$model->email,
            'disabled'=>'disabled', 'titulo'=>$tit, 'user_id'=>$model->user_id,
            'usererror'=>($user_exists) ? 'has-error' : '', 
            'pwderror'=>($badpwd) ? 'has-error' : '', 
            'levelerror'=>'',
            'msgerror'=>$this->__set_msgs($user_exists, $badpwd),
            'errorstyle'=> ($user_exists or $badpwd) ? 'block' : 'none');

        return array_merge($data, $GLOBALS['DICT']);
    }

    private function __get_pnl_title($tit='Agregar'){
        $titulos = array( 'Agregar' => USR_TITLE_ADD, 'Editar'=> USR_TITLE_EDIT);
        return $titulos[$tit];
    }
}

?>
