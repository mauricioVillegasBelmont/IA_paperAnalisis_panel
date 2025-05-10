<?php
/**
* Helper administrativo para validacion y utillerias del sitio y/o panel.
*/

class FileUploader{

	// static function upload_file($name="file", $uploaddir = "/site_media/uploads/", $prefix = 'file', $digits = 5){
	static function upload_file($name="file", $config = array()){

		$defaultConfig = array(
			'uploaddir' => "/site_media/uploads/",
			'prefix' => 'file',
			'digits' => 5,
			'date_format' => 'Ymd-His'
		);
		$config = array_merge($defaultConfig, $config);

		$uploaddir = $config['uploaddir'];
		$prefix = $config['prefix'];
		$digits = $config['digits'];
		$date_format = $config['date_format'];

		if(!isset($_FILES[$name]) || $_FILES[$name]["name"]==""){
			$_SESSION["error_msg"] = '003 - No File';
			HTTPHelper::go("/participar");
			die();
		}
		//UPLOAD IMAGE
		$mime_types_valid = array(
			'png' => 'image/png',
			'jpeg' => 'image/jpeg',
			'jpg' => 'image/jpeg',
			'pjpeg' => 'image/jpeg',
			'jfif' => 'image/jpeg',
			'webp' => 'image/webp',
			'pdf' => 'application/pdf'
		);
		$uploaddir = APP_DIR.$uploaddir;
		$ext = $_FILES[$name]["name"];
		$ext = explode('.', $ext);
		$ext = end( $ext );
		$ext = strtolower( $ext );

		if( !array_key_exists( $ext, $mime_types_valid ) ){
			$_SESSION["error_msg"] = 'MIME TYPE No valido';
			HTTPHelper::go("/participar");
			die();
		}

		$fileName = $prefix.'__'.date($date_format)."--".ToolsHelper::randHash($digits).".".$ext ;
		while( file_exists( $uploaddir.$fileName) ){
			$fileName = $prefix.'__'.date($date_format)."--".ToolsHelper::randHash($digits).".".$ext ;
		}

		if( !move_uploaded_file($_FILES[$name]["tmp_name"], $uploaddir.$fileName)){
			$phpFileUploadErrors = array(
					0 => 'No hay ningún error, el archivo se cargó correctamente',
					1 => 'El archivo cargado excede la directiva upload_max_filesize en php.ini',
					2 => 'El archivo cargado excede la directiva MAX_FILE_SIZE que se especificó en el formulario HTML',
					3 => 'El archivo cargado solo se cargó parcialmente',
					4 => 'Ningun archivo fue subido',
					6 => 'Falta una carpeta temporal',
					7 => 'No se pudo escribir el archivo en el disco',
					8 => 'Una extensión PHP detuvo la carga del archivo',
			);
			// $_SESSION["error_msg"] = $phpFileUploadErrors[$_FILES[$name]["error"]];
			// HTTPHelper::go("/registro");
			// die();
			$error = $_FILES[$name]["error"];
			throw new Exception($phpFileUploadErrors[$error]??'Error desconocido');
		}
		return $fileName;
	}

}
