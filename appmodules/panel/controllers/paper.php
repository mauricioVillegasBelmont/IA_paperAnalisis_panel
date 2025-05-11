<?php

use Dom\Document;

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

		$collection = CollectorObject::get('Paper')->collection;
		$tableConfig = array(
			"collection" => $collection,
			"modulo" => 'panel',
			"modelo" => 'paper',
			
			'buttons' => array(
				'ver' => false,
				'editar' => false,
				'eliminar' => false,
			)
		);
		$this->view->dict['TABLE'] = CollectorTable($tableConfig)->get_table();
		$this->view->dict['UPLOAD_MAX_FILESIZE'] = ini_get('upload_max_filesize');


		$document =  new Document_pdf();
		$collection = $document->get_ia_unprocessed();
		$tableConfig = array(
			
			"collection" => $collection,
			"modulo" => 'panel',
			"modelo" => 'paper',
			"template" => 'appmodules/panel/views/templates/documents_pdf/doc_table.html',
			'buttons' => array(
				'ver' => false,
				'procesar' => true,
			),
			'options' => array(
				"title" => "Parsed PDF's",
			)
		);
		$this->view->dict['DOCUMENT_PDF'] = CollectorTable($tableConfig)->get_table();


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
			$conf = array(
				'name' => $_FILES['file']['name'],
				'content' => $document,
			);
			$this->save_Document($conf);

			$this->process_file($document);

			HTTPHelper::go("/panel/paper/reporte");
		} catch (Exception $e) {
			$resJSON['msg'] = $e->getMessage();
			return $this->view->show_json($resJSON);
		}
	}

	public function equeue_files()
	{
		$resJSON = $this->resJSON;
		try {
			$papers_folder = APP_DIR."site_media/public/queue_papers/";
			$dir = new DirectoryIterator($papers_folder);
			$resJSON['test'] = array();
			// $documents = array();
			foreach ($dir as $fileinfo) {
				if (!$fileinfo->isDot() && $fileinfo->getExtension() == 'pdf') {
					$file_name = $fileinfo->getFilename();
					$file_path = $papers_folder. $file_name;
					$resJSON['test'][] = $file_path;
					$content = $this->get_file_content($file_path);
					$conf = array(
						'name' => $file_name,
						'content' => $content,
					);
					$this->save_Document($conf);
				}
			}
			
			
			HTTPHelper::go("/panel/paper/reporte");
		} catch (Exception $e) {
			$resJSON['msg'] = $e->getMessage();
			return $this->view->show_json($resJSON);
		}
	}
	public function procesar_archivos($id){
		$resJSON = $this->resJSON;
		// $id = $_POST['id'];
		try {
			$document = new Document_pdf();
			$document->document_pdf_id = (int)$id;
			$document->get();
			$this->process_file($document->content);
			$document->processed = 1;
			$document->save();


			HTTPHelper::go("/panel/paper/reporte");
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
	private function save_document($conf)
	{
		try {
			$document = new Document_pdf();
			$document->set_values(
				$conf
			);
			$document->save();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	/**
	 * Verifica que el archivo sea válido
	 * @param array $fields
	 * @throws Exception
	 */
	private function get_file_content($file)
	{
		$file_contents = PythonHelpers()->get_pdf_contents($file);
		return $file_contents;
	}

	private function process_file($document)
	{
		try {
			
			$queryMessage = 'Procesa el siguiente documento y devuelve un JSON estructurado como se indicó con los contenidos en español:\n' . $document;
			$config = array(
				"IArole" => "Eres un asistente analítico. Procesarás los siguientes documentos, los traduciras al español y generarás un JSON con el suguiente schema: {title: '',authors: [''],labels: [''],abstract: '',metodology: '',conclusions: '',bibliography: ['']}. Debe ser especifico en los campos titulo, autores, etiquetas, y las referencias deven mantener formato APA actualizados. El JSON debe ser estructurado y no contener texto adicional ni campos diferentes a los mecionados. El JSON debe ser válido y no contener errores de sintaxis. El JSON debe ser legible y fácil de entender. El JSON debe ser breve, pero sin perder información importante. El JSON debe contener solo la información necesaria para describir el documento.",
				"deepThink" => false,
				"response_type" => 'json_object',
				"max_tokens" => 4000,
				"temperature" => 0.3,
			);

			$response = IAService($config)->get($queryMessage);
			$response = json_decode($response, true);
			$response =  json_decode($response['choices'][0]['message']['content']);
			$response =  (array)$response;
			$this->model->set_values($response);
			$this->model->save();
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	// private function store_service($service)
	// {
	// 	try {
	// 		$this->model->set_values($service);
	// 		$this->model->save();
	// 		return true;
	// 	} catch (Exception $e) {
	// 		throw new Exception($e->getMessage());
	// 	}
	// }



}
