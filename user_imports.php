<?php

$GLOBALS['menu'] = array(
    array(
        'href'=>'/', 
        'anchor'=>'Home'
    ),
);

if(isset($_SESSION['level']) && $_SESSION['level']>0){
    $GLOBALS['menu'] = array();
    switch($_SESSION['level']){
        case 1:
            
            $GLOBALS['menu'][] = array(
                'href'=>'/panel/user/listar',
                'anchor'=>'Usuarios',
                'icon'=>'fa-users'
            );
        case 2:
        default:
            $GLOBALS['menu'][] = array(
                'href'=>'/logout',
                'anchor'=>'Salir',
                'icon'=>'fa-sign-out '
            );
        break;
    }
}
?>