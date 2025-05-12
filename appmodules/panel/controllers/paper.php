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

		$paper_collection = CollectorObject::get('Paper')->collection;
		$tableConfig = array(
			"collection" => $paper_collection,
			"modulo" => 'panel',
			"modelo" => 'paper',
			
			'buttons' => array(
				'ver' => false,
				'editar' => false,
				'eliminar' => false,
			)
		);
		$table = CollectorTable($tableConfig)->get_table();



		$this->view->dict['TABLE'] = $table;
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


	public function update_sheet(){
		try{
			$paper_collection = CollectorObject::get('Paper')->collection;
			foreach ($paper_collection as $key => $row) {
				
				$range = "papers!A" . 2 + $key . ":L" . 2 + $key . "";
				$row = ((array)$row);
				$implode_char = "\n - ";
				$row['authors'] = implode($implode_char, unserialize($row['authors']));
				$row['labels'] = implode($implode_char, ($row['labels']));
				$row['bibliography'] = implode($implode_char, ($row['bibliography']));
				$collumn = array();
				foreach ($row as $col) {
					$collumn[] = mb_convert_encoding($col, "UTF-8", "HTML-ENTITIES");
				}
				$spreadSheet = Google_sheets()->appendValues('1KCTtxS4by73QHUZhDADlVtLOKlO19RnQJHv8rHV0QnI', $range, [$collumn]);
			}
			
			HTTPHelper::go("/panel/paper/reporte");
		}catch (Exception $e){
			return $e->getMessage();
		}
	}


	
	public function registrar()
	{
		$resJSON = $this->resJSON;
		$fields = array(
			'file',
		);
		try {
			FormFieldValidation::is_complete_form($fields);
			$document = $this->get_file_content($_FILES['file']['tmp_name']);
			$conf = array(
				'name' => $_FILES['file']['name'],
				'content' => $document,
			);
			$this->save_Document($conf);

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
	/**
	 * Procesa el archivo PDF sintesis por ia
	 * @param int $id
	 * @throws Exception
	 */
	public function procesar_archivos($id){
		$resJSON = $this->resJSON;
		try {
			$document = new Document_pdf();
			$document->document_pdf_id = (int)$id;
			$document->get();
			$this->ia__process_file((array)$document);
			$document->processed = 1;
			$document->save();


			HTTPHelper::go("/panel/paper/reporte");
		} catch (Exception $e) {
			$resJSON['msg'] = $e->getMessage();
			return $this->view->show_json($resJSON);
		}
	}
	
	


	/**
	 * Upload file
	 * @param array $fields
	 * @throws Exception
	 */
	private function upload_pdf(){
		$config = array(
			'prefix' => "paper",
			'uploaddir' => $this->papers_folder,
		);
		$fileName = FileUploader::upload_file('file', $config);
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

	private function ia__process_file($config)
	{
		extract($config);
		try {
			
			$queryMessage = 'Procesa el siguiente documento y devuelve un JSON estructurado como se indicó con los contenidos en español:\n' . $content;
			$response = Razonador::procesar_paper($queryMessage);
			
			$response['document'] = $document_pdf_id;
			$this->model->set_values($response);
			$this->model->save();

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	// public function paper_save_labels()
	// {
	// 	$labels = $this->model->get_documeny_labels();
	// 	$label = new Label();
	// 	$label->batch_labels($labels);
	// 	$bibliography = new Bibliography();
	// 	$bibliography->batch_bibliography($this->model->get_document_bibliography());
	// }

		

}
