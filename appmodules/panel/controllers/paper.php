<?php
/**
* Controlador para el ABM de Papers
*
* @package IAServices
* @author  Pymeweb
* @license MIT
* @link
*/



class PaperController extends Controller {
	
	public $papers_folder = "/site_media/public/papers/";
	public $resJSON = array(
		"status" => "fail",
		"msg" => "Respuesta por default."
	);
	public function reporte() {
		SessionHandler()->check_state(2);
		$this->view->str = TemplateHeplpers::get_template('/papers/index.html', 'panel');
		$this->view->globals['PNL_TITLE'] = "Papers";
		$this->view->dict['MESSAGE'] = 'El palelon de tu vida bebe...!';
		$this->view->dict['COLLECTION'] = json_encode(CollectorObject::get('Paper')->collection);;
		$this->view->dict['TABLE'] = $this->get_papers();
		$this->view->dict['UPLOAD_MAX_FILESIZE'] = ini_get('upload_max_filesize');
		$this->view->render_page();
	}


	private function get_papers()
	{
		$collection = CollectorObject::get('Paper')->collection;
		$tableConfig = array(
			"collection"=>$collection, 
			"modulo"=>'panel',
			"modelo"=>'paper',
			'buttons'=>array(
				'ver' => false,
			)
		);
		$table = CollectorTable($tableConfig)->get_table();
		return $table;
	}


	public function label(){
		SessionHandler()->check_state(2);
		$this->view->str = TemplateHeplpers::get_template('/papers/index.html', 'panel');
		$this->view->globals['PNL_TITLE'] = "Papers";
		$this->view->dict['MESSAGE'] = 'El palelon de tu vida bebe...!';
		$this->view->dict['TABLE'] = json_encode($this->model->get_labels());
		$this->view->dict['UPLOAD_MAX_FILESIZE'] = ini_get('upload_max_filesize');
		$this->view->render_page();
	}
	
	
	public function registrar()
	{
		$resJSON = $this->resJSON;
		$fields = array(
			'file',
		);
		try {
			$this->is_valid_form($fields);
			$document = $this->get_file_content($_FILES['file']['tmp_name']);
			$queryMessage = 'Procesa el siguiente documento y devuelve un JSON estructurado como se indicó:\n' . $document;
			$service = $this->process_file($queryMessage);
			$service = json_decode($service, true);
			$service = json_decode($service['choices'][0]['message']['content']);
			$service = (array) $service;
			$service['document'] = $document;
			$this->model->set_values();
			$this->model->save();

			

			// $resJSON['redirect'] = '/panel/paper/reporte';
			$resJSON['model'] = $this->model;
			$resJSON['status'] = 'ok';
			$resJSON['msg'] = 'ok';
			return $this->view->show_json($resJSON);
		} catch (Exception $e) {
			$resJSON['msg'] = $e->getMessage();
			return $this->view->show_json($resJSON);
		}
	}
	



	/**
	 * Verifica que el formulario sea válido
	 * @param array $fields
	 * @throws Exception
	 */
	private function is_valid_form($fields = array())
	{
		$verification = FormFieldValidation::post_verification($fields);
		if ( in_array(false, $verification, true)) {
			throw new Exception('Por favor, complete el formulario.');
		};
	}

	/**
	 * Verifica que el archivo sea válido
	 * @param array $fields
	 * @throws Exception
	 */
	private function upload_pdf(){
		$config = array(
			'prefix' => "paper",
			'uploaddir' => $this->papers_folder,
		);
		$fileName = FileUploader::upload_file('picture', $config);
		return $fileName;
	}

	private function get_file_content($file)
	{
		$file_contents = PythonHelpers()->get_pdf_contents($file);
		return $file_contents;
	}

	private function process_file($queryMessage)
	{
		$config = array(
			"IArole" => "Eres un asistente analítico. Procesarás los siguientes documentos, los traduciras al español y generarás un JSON con el suguiente schema: {title: '',authors: [''],labels: [''],abstract: '',metodology: '',conclusions: '',bibliography: ['']}. Debe ser especifico en los campos titulo, autores, etiquetas, y las referencias deven mantener formato APA actualizados. El JSON debe ser estructurado y no contener texto adicional ni campos diferentes a los mecionados. El JSON debe ser válido y no contener errores de sintaxis. El JSON debe ser legible y fácil de entender. El JSON debe ser breve, pero sin perder información importante. El JSON debe contener solo la información necesaria para describir el documento.",
			"deepThink" => false,
			"response_type" => 'json_object',
			"max_tokens" => 4000,
			"temperature" => 0.3,
		);
		
		$IAService = IAService($config)->get($queryMessage);
		return $IAService;
	}
}
