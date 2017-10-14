<?php
namespace Application\Controllers;

use Application\Classes\Answer;
use Application\Controllers\View;
use Application\Controllers\MainController;

abstract class controller{
	protected $answer;
	protected $view;
	protected $mainController;

	public function __construct(){
		$this->view = new View();
		$this->answer = new Answer();
		$this->mainController = mainController::getInstance();
	}

	public function actionIndex(){
		return $this->actionGetAll();
	}

	public function actionGetAll(){

	}
}


?>