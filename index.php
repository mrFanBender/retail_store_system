<?php
	use Application\Classes\Config;
	use Application\Classes\Log;
	use Application\Controllers\View;
	use Application\Classes\E404Exception;
	use Application\Classes\validationException;
	use Application\Controllers\mainController;
	use Application\Classes\Answer;

	require_once __DIR__.'/autoload.php';
	session_start();
	$mainController = MainController::getInstance();

	try{
		$controllerClassName = 'Application\Controllers\\'.$mainController->ctrl;
		$method = 'action'.$mainController->act;
		$controller = new $controllerClassName;
		$controller->id = $mainController->obj_id ? $mainController->obj_id : 0;
		$mainController->content = $controller->$method();
		if(isset($mainController->content->_route)){
			$maincontroller->redirect();
		}
		echo $mainController->output();
	}
	catch(validationException $e){
		$log = new Log();
		$log->write($e);
		$view = new View();
		$view->error = $e;
		$answer = new Answer($view->render('error'), $e);
		$mainController->content = $answer;
		echo $mainController->output();
	}
	catch(\Exception $e){
		var_dump($e);
		//временно, переделать
		$log = new Log();
		$log->write($e);
		$view = new View();
		$view->error = $e;
		$answer = new Answer($view->render('error'), $e);
		$mainController->content = $answer;
		echo $mainController->output();
	}


?>