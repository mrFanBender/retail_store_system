<?php
namespace Application\Classes;

class Answer{
	private $html='';
	private $data=array();
	private $statusMessages = array();
	private $errors = array();
	private $_route = false;

	public function __construct($html='', $data = array()){
		$this->html = $html;
		if($data instanceof \Application\Classes\ValidationException){
			foreach($data->getMessages() as $error){
				$this->addError($error['message'], $error['target'], $error['type']);
			}
		}elseif($data instanceof \Exception){
			$this->addError($data->getMessage(),'msgBox', 'error');
			$this->data['error'] = $data;
		}else{
			$this->data = $data;
		}
	}

	public function __set($k, $v){
		$this->data[$k] = $v;
	}

	public function __get($k){
		return isset($this->data[$k]) ? $this->data[$k] : false;
	}

	public function __isset($k){
		return isset($this->data[$k]);
	}

	public function getHtml(){
		return $this->html;
	}
	public function setHtml($html){
		$this->html = $html;
		return $this;
	}

	public function getData(){
		return $this->data;
	}

	public function addStatusMessage($msg, $target='msgBox', $msgtype='success'){
		//задаем сообщение и его тип. Возможные типы: success, warning, error
		$this->statusMessages[] = array(	'type' 		=> 	$msgtype,
											'message'	=>	$msg,
											'target'	=>	$target);
		return $this;
	}

	public function getStatusMessages(){
		return $this->statusMessages;
	}

	public function addError($msg, $target='msgBox', $msgtype='success'){
		//задаем сообщение и его тип. Возможные типы: success, warning, error
		$this->errors[] = array(	'type' 		=> 	$msgtype,
											'message'	=>	$msg,
											'target'	=>	$target);
	}

	public function getErrors(){
		return $this->errors;
	}

	public function addRedirect($rout){
		$this->redirect = $rout;
		return $this;
	}

	public function getRedirect(){
		return $this->redirect;
	}

}


?>