<?php

namespace mksms;

require_once 'contact.php';

/**
 * This class represent a Message
 *
 * @package default
 * @author 
 **/
class Message
{
	public $contact;
	public $body;
	public $direction;
	public $read;

	public static $OUT = 1;
	public static $IN = -1;
	public static $BOTH = 0;
	
	function __construct($p_contact, $p_body, $p_direction=Message::OUT, $p_read=false)
	{
		$this->body = $p_body;
		if (is_string($p_contact)){
			$this->contact = new Contact($p_contact);
		}
		else{
			$this->contact = $p_contact;
		}
		$this->direction = $p_direction;
		$this->read = $p_read;
	}

	/**
	 * This method return an array representing the message
	 *
	 * @return Array
	 **/
	public function to_array()
	{
		$res = array('contact' => $this->contact->to_array(), 'body' => $this->body);
		return $res;
	}

	/**
	 * This method get an array representing a message and return a Contact object
	 *
	 * @return Contact
	 **/
	public static function from_array($arr_msg)
	{
		$body = $arr_msg['body'];
		$contact_arr = $arr_msg['contact'];
		$contact = Contact::from_array($contact_arr);

		if (isset($arr_msg['direction']))
			$direction = $arr_msg['direction'];
		else
			$direction = Message::OUT;

		if (isset($arr_msg['read']))
			$read = $arr_msg['read'];
		else
			$read = false;

		$res = new Message($contact, $body, $direction, $read);
		return $res;
	}

	public function __toString()
	{
		return "Message({contact: $this->contact, body: $this->body, direction: $this->direction, read:$this->read})";
	}
}

?>