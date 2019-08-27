<?php

namespace mksms;

/**
 * This class represent the contact

 Args:
 	- number: the number of the contact
 	- name (optional): the name of the contact
 */
class Contact
{
	public $number;
	public $name;
	
	function __construct($p_number, $p_name="")
	{
		$this->number = $p_number;
		$this->name = $p_name;
	}

	/**
	 * This method return an array representing the contact
	 *
	 * @return Array
	 **/
	public function to_array()
	{
		$res = array('name' => $this->name, 'number' => $this->number);
		return $res;
	}

	/**
	 * This method get an array representing a contact and return a Contact object
	 *
	 * @return Contact
	 **/
	public static function from_array($arr_contact)
	{
		$number = $arr_contact['number'];
		$name = $arr_contact['name'];
		$res = new Contact($number, $name);
		return $res;
	}

	public function __toString()
	{
		return "Contact({number: $this->number, name:$this->name})";
	}
}

?>