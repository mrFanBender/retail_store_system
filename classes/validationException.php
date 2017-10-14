<?php
namespace Application\Classes;

class validationException extends \Exception{
	public $messages = array();
	
	public function __construct($msg, $target='msgBox', $type='error'){
		$this->messages[] = array('message'=>$msg, 'target'=> $target, 'type'=>$type);
	}

	public function getMessages(){
		return $this->messages;
	}
} 


?>