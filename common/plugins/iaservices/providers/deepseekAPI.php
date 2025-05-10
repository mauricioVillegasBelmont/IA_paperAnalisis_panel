<?php
class DeepseekAPI{
  public $name = 'Deepseek API';
  private $headers = array(
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer '. DEEPSEEK_APIKEY
  );
  public $config = array(
    "IArole" => "You are a helpful assistant.",
    "deepThink" => false,
    "response_type" => 'text',
  );
  function __construct($config = array()){
    $this->config = array_merge($this->config, $config);
  }
  public function get($message ){
    try{
      if (!$message) {
        throw new Exception("User message is required");
      }
      // $post_fields = '{
      //   "messages": [
      //     {
      //       "content": ' . $this->config['IArole'] . ',
      //       "role": "system"
      //     },
      //     {
      //       "content": ' . $message . ',
      //       "role": "user"
      //     }
      //   ],
      //   "model": "' . $this->config['deepThink'] ? "deepseek-reasoner" : "deepseek-chat" . '",
      //   "response_format": {
      //     "type": "' . $this->config['response_type'] . '"
      //   },
      //   "max_tokens": "' . $this->config['max_tokens'] . '",
      //   "temperature": "' . $this->config['temperature'] . '",
      //   "stop": null,
      //   "stream": false,
      //   "stream_options": null,
      //   "frequency_penalty": 0,
      //   "presence_penalty": 0,
      //   "top_p": 1,
      //   "tools": null,
      //   "tool_choice": "none",
      //   "logprobs": false,
      //   "top_logprobs": null
      // }';
      $post_fields = array(
        "messages" => array(
          array(
            "content" => $this->config['IArole'],
            "role" => "system"
          ),
          array(
            "content" => $message,
            "role" => "user"
          )
        ),
        "response_format" => array(
          "type" => $this->config['response_type']
        ),
        "model" => $this->config['deepThink']? "deepseek-reasoner":"deepseek-chat",
        "max_tokens" => $this->config['max_tokens'],
        "temperature" => $this->config['temperature'],
        "frequency_penalty" => 0,
        "presence_penalty" => 0,
        "stop" => null,
        "stream" => false,
        "stream_options" => null,
        "top_p" => 1,
        "tools" => null,
        "tool_choice" => "none",
        "logprobs" => false,
        "top_logprobs" => null
      );
      // var_dump($post_fields);die();

      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => DEEPSEEK_BASEURL,
        CURLOPT_HTTPHEADER => $this->headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($post_fields)
      ));
      $response = curl_exec($curl);
      if (curl_errno($curl)) {
        $error = curl_error($curl);
        curl_close($curl);
        throw new Exception($error);
      }
      curl_close($curl);
      return $response;
    }catch(Exception $e){
      return 'Caught exception: ' .  $e->getMessage();
    }

  }
}

