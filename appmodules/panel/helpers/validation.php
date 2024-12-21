<?php
class FormFieldValidation{
  public static function isset_not_Empty($value){
    return (isset($_POST[$value]) AND !empty($_POST[$value]));
  }
  public static function file_uploaded($name){
    return is_uploaded_file($_FILES[$name]["tmp_name"]);
  }
  public static function user_agreement($value){
    return (isset($_POST[$value]) && boolval($_POST[$value]));
  }
}