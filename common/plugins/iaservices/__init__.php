<?php
/**
* Pymengine IA Services PlugIn v1.0
*/
require_once __dir__."/settings.php";
require_once __dir__."/providers/deepseekAPI.php";



class IAService{
	public $config = array(
		"IArole" => "You are a helpful assistant.",
		"deepThink" => false,
		"response_type" => 'text',
	);
	private $servicePrivder;
	
	
	function __construct($config = array(), $serviceProvider = 'deepseek'){
		$this->set($config, $serviceProvider);
	}
	
	/**
	* @param string $message
	* @return string
	*/
	public function set($config = array(), $serviceProvider = 'deepseek'){
		$this->config = array_merge($this->config, $config);
		switch ($serviceProvider) {
			case 'deepseek':
			default:
				$this->servicePrivder = new DeepseekAPI($this->config);
				break;
		}
	}
		
	/**
	* @param string $message
	* @return string
	*/
	public function get($message){
		try{
			if (!$message) {
				throw new Exception("User message is required");
			}
			$response = $this->servicePrivder->get($message);
			if ($response) {
				return $response;
			} else {
				throw new Exception("No response from IA service: ". $this->servicePrivder->name);
			}
		} catch (Exception $e) {
			return 'Caught exception: ' .  $e->getMessage();
		}
	}
		
		
}
	
	# Alias para compatibilidad pseudo estática
	function IAService($config = array(), $serviceProvider = 'deepseek'){
		return new IAService($config, $serviceProvider);
	}
