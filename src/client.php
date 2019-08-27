<?php

namespace mksms;

require_once 'message.php';

/**
 * This class represent a response from the API
 *
 * Attrs:
 *		success: true if all went well else false
 *		message: if success if false then the error is here
 *		data: the read data returned by the api
 */
class Response
{
	public $message;
	public $success;
	public $data;
	
	function __construct($res_arr)
	{
		$this->success = $res_arr['success'];
		if (!$this->success)
			$this->message = $res_arr['message'];
		else
			$this->message = null;
		$this->data = $res_arr;
	}

	/**
	 * Returns the internal data of the response as an array
	 *
	 * @return an array of the response
	 * @author 
	 **/
	public function to_array()
	{
		return $this->data;
	}

	public function __toString()
	{
		return "Response({success: $this->success, data: ".json_encode($this->data)."})";
	}
}


/**
 * This class is the client to be used to interact with the API
 */
class Client
{
	public $api_key;
	public $ap_hash;

	public static $ENDPOINTS = array('send_sms' => '/sms/send/', 
			'get_sms' => '/sms/available/', 'start_verify' => '/phone/verify/start/',
			'confirm_verify' => '/phone/verify/confirm/');

	public static $BASE_URL = "https://api.mksms.cm";
	
	function __construct($p_api_key, $p_api_hash)
	{
		$this->api_key = $p_api_key;
		$this->api_hash = $p_api_hash;
	}

	/**
	 * This method send a message via the API
	 * @args:
	 * 		Message object
	 *
	 * @return Response object
	 * @author 
	 **/
	public function send_message($p_message)
	{
		$data = $p_message->to_array();
		$data['api_key'] = $this->api_key;
		$data['api_hash'] = $this->api_hash;

		$path = Client::$ENDPOINTS['send_sms'];

		$res_arr = $this->_post($path, $data);
		$res = new Response($res_arr);
		return $res;
	}

	/**
	 * This method get a list of messages via the API
	 * @args:
	 * 		p_direction: the direction of the message, see Message
	 *		p_read: true for read messages, false for unread
	 *		p_timestamp: the timestamp of date where to begin
	 *
	 * @return Response object
	 * @author 
	 **/
	public function get_messages($p_direction=null, $p_read=null, $p_timestamp=null)
	{
		$data = array (
        	'direction' => $p_direction,
        	'read' => $p_read,
        	'timestamp' => $p_timestamp,
        	'api_hash' => $this->api_hash,
        	'api_key' => $this->api_key
        );

        $path = Client::$ENDPOINTS['get_sms'];

        $res_arr = $this->_get($path, $data);
		$res = new Response($res_arr);
		return $res;
	}

	/**
	 * This method starts a phone verification via the API
	 * @args:
	 * 		$p_number: string, the number to verify
	 *		$p_name: string, the name of your service
	 *
	 * @return Response object
	 * @author 
	 **/
	public function start_verify($p_number, $p_name)
	{
		$data = array("number"=>$p_number, "name"=>$p_name);
		$data['api_key'] = $this->api_key;
		$data['api_hash'] = $this->api_hash;

		$path = Client::$ENDPOINTS['start_verify'];

		$res_arr = $this->_post($path, $data);
		$res = new Response($res_arr);
		return $res;
	}

	/**
	 * This method confirms a phone verification via the API
	 * @args:
	 * 		$p_number: string, the number to verify
	 *		$p_name: string, the name of your service
	 *
	 * @return Response object
	 * @author 
	 **/
	public function confirm_verify($p_number, $p_code)
	{
		$data = array("number"=>$p_number, "code"=>$p_code);
		$data['api_key'] = $this->api_key;
		$data['api_hash'] = $this->api_hash;

		$path = Client::$ENDPOINTS['confirm_verify'];

		$res_arr = $this->_post($path, $data);
		$res = new Response($res_arr);
		return $res;
	}	

	/**
	 * This private method help sending post requests to the API
	 *
	 * @return Array object
	 * @author 
	 **/
	private function _post($path, $data)
	{
		$data_string = json_encode($data);
		$url = Client::$BASE_URL.$path;
                
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                              
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);

        $resp = json_decode($response, true);
        return $resp;
	}

	/**
	 * This private method help sending get requests to the API
	 *
	 * @return Array object
	 * @author 
	 **/
	private function _get($path, $data=array())
	{        
        $params = '';
    	foreach($data as $key=>$value)
    		if($value !== null)
                $params .= $key.'='.$value.'&';
         
        $params = trim($params, '&');
		$url = Client::$BASE_URL.$path.'?'.$params;
                
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");                                          
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        curl_close($ch);

        $resp = json_decode($response, true);
        return $resp;
	}
}

?>