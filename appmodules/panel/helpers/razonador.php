<?php

/**
 * Pymengine IA Services PlugIn v1.0
 * 
 * @package IAServices
 * @author  Pymeweb
 * @license MIT
 * @link
 */

/**
 * 
  `bibliography_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `autors` VARCHAR(255),
  `type` ENUM('libro', 'articulo_revista', 'articulo_periodico', 'fuente_web', 'tesis', 'informe', 'capitulo_libro', 'ley', 'norma', 'otros') NOT NULL,
  `created` datetime NOT NULL
 * 
 */

class Razonador
{
  /**
   * @param string $queryMessage
   * @return array
   */
  static function procesar_paper($queryMessage)
  {
    try {
      $queryMessage = json_encode($queryMessage);
      $config = array(
        "IArole" => "Eres un asistente analítico. Procesarás los siguientes documentos, los traduciras al español y generarás un JSON con el suguiente schema: {title: '',authors: [''],labels: [''],abstract: '',metodology: '',conclusions: '',bibliography: ['']}. Debe ser especifico en los campos titulo, autores, etiquetas, y las referencias deven mantener formato APA actualizados. El JSON debe ser estructurado y no contener texto adicional ni campos diferentes a los mecionados. El JSON debe ser válido y no contener errores de sintaxis. El JSON debe ser legible y fácil de entender. El JSON debe ser breve, pero sin perder información importante. El JSON debe contener solo la información necesaria para describir el documento.",
        "deepThink" => false,
        "response_type" => 'json_object',
        "max_tokens" => 4000,
        "temperature" => 0.3,
      );
  
      $response = IAService($config)->get($queryMessage);
      $response = json_decode($response, true);
      $response = json_decode($response['choices'][0]['message']['content']);
      return (array)$response;
      
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
  
}
