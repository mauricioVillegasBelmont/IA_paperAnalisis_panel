<?php
class FormFieldValidation
{
  public static function isset_not_Empty($value)
  {
    return (isset($_POST[$value]) and !empty($_POST[$value]));
  }
  public static function file_uploaded($name)
  {
    return is_uploaded_file($_FILES[$name]["tmp_name"]);
  }
  public static function user_agreement($value)
  {
    return (isset($_POST[$value]) && boolval($_POST[$value]));
  }

  public static function post_verification($fields = array())
  {
    $values = array();
    foreach ($fields as $field) {
      // var_dump($field);die();
      switch ($field) {
        case 'file':
        case 'picture':
          $values[$field] = self::file_uploaded($field);
          break;
        case 'agree':
          $values[$field] = self::user_agreement($field);
          break;
        default:
          $values[$field] = self::isset_not_Empty($field);
      }
    }
    return $values;
  }


  /**
   * Verifica que el formulario sea válido
   * @param array $fields
   * @throws Exception
   */
  public static function is_complete_form($fields = array())
  {
    $verification = self::post_verification($fields);
    if (in_array(false, $verification, true)) {
      throw new Exception('Por favor, complete el formulario.');
    };
  }
}
