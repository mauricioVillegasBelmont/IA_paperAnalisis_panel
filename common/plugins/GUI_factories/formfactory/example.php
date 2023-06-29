<?php

$config = array(
        'action' => '',
        'method' => '/panel/quizzes/guardar',
      );
      $form = FormFactory('',$config);
      $quizz_obj = array(
        (array)[
          'element'=>'INPUT',
          'type'=>'hidden',
          'dict'=>array(
            'text' => '',
            'label' => '',
            'name' => 'quizz_id',
            'placeholder' => '',
            'error' => '',
            'value' => '0',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'text',
          'dict'=>array(
            'text' => '',
            'label' => 'Tipo:',
            'name' => 'tipo',
            'placeholder' => '',
            'error' => '',
            'value' => '',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'text',
          'dict'=>array(
            'text' => '',
            'label' => 'Nombre:',
            'name' => 'nombre',
            'placeholder' => '',
            'error' => '',
            'value' => '',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'checkbox',
          'dict'=>array(
            'text' => '',
            'label' => 'Activo:',
            'name' => 'enabled',
            'placeholder' => '',
            'error' => '',
            'value' => '',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'date',
          'dict'=>array(
            'text' => '',
            'label' => 'Inicio:',
            'name' => 'inicio',
            'placeholder' => '',
            'error' => '',
            'value' => '',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'date',
          'dict'=>array(
            'text' => '',
            'label' => 'Fin:',
            'name' => 'fin',
            'placeholder' => '',
            'error' => '',
            'value' => '',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'select',
          'dict'=>array(
            'label' => 'Medio:',
            'name' => 'medio',
          ),
          'collection'=>array(
            array(
              'text' => 'parentesis',
              'placeholder' => 'parentesis',
              'error' => '',
              'value' => 'parentesis',
              'extras' => '',
            ),
            array(
              'text' => 'harmonia',
              'placeholder' => 'harmonia',
              'error' => '',
              'value' => 'harmonia',
              'extras' => '',
            ),
            array(
              'text' => 'pijamasurf',
              'placeholder' => 'pijamasurf',
              'error' => '',
              'value' => 'pijamasurf',
              'extras' => '',
            ),
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'text',
          'dict'=>array(
            'text' => '',
            'label' => 'Segmentos:',
            'name' => 'segmentos',
            'placeholder' => '',
            'error' => '',
            'value' => '',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'INPUT',
          'type'=>'text',
          'dict'=>array(
            'text' => '',
            'label' => 'Permalink:',
            'name' => 'pl',
            'placeholder' => '',
            'error' => '',
            'value' => '',
            'extras' => '',
          ),
        ],
        (array)[
          'element'=>'BUTTON',
          'type'=>'submit',
          'dict'=>array(
            'text' => 'submit',
            'label' => '',
            'name' => 'submit',
            'placeholder' => '',
            'error' => '',
            'value' => 'submit',
            'extras' => '',
          ),
        ],
      );
      foreach($quizz_obj as $inp){
        if(array_key_exists('collection', $inp)){
          $form->add_input_group( $inp['type'], $inp['collection'], $inp['dict'] );
        }else{
          $form->add_input_type( $inp['element'], $inp['type'], $inp['dict'] );
        }
      }
      $form_ = $form->get_form();

      print($form_);
      die();
?>
