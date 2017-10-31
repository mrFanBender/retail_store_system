<?php

namespace Application\Controllers;

use Application\Models\User as UserModel;
use Application\Controllers\User;
use Application\Classes\UserRights;
class mainController{

	public $post = array();
	public $get = array();
	//protected $user = false;
	protected $data = array();
	protected $styles = array();
	protected $scripts = array();
	private static $_instance = null;

	private function __construct(){
			$this->post = $_POST;
			$this->get = $_GET;
			$this->host = $_SERVER['HTTP_HOST'];
			$this->request = $_SERVER['REQUEST_URI'];
			$this->path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$pathParts = explode('/', $this->path);
			$i=0;
			while(isset($pathParts[$i]) && (empty($pathParts[$i]) || preg_match('/\.php/', $pathParts[$i]))){
				$i++;
			}
			$this->ctrl = !empty($pathParts[$i]) ? ucfirst($pathParts[$i]) : 'User';
			$this->act = !empty($pathParts[$i+1]) ? ucfirst($pathParts[$i+1]) : 'Index';
			$this->obj_id = (!empty($pathParts[$i+2]) && (int)$pathParts[$i+2]>0) ? (int)$pathParts[$i+2] : false;
			//проверяем, авторизован ли пользователь
			if(!isset($_SESSION['user_id']) && (($this->ctrl == 'User' && ($this->act != 'Confirm' && $this->act != 'Login' && $this->act != 'Registration'&& $this->act != 'SendConfirmLetter')) ||  $this->ctrl != 'User')){
				header('Location: /User/Login');
			}
			if(isset($_SESSION['user_id'])){
				//если есть User_id, проверяем, существует ли такой пользователь
				$this->user = UserModel::getOne(array('id'=>$_SESSION['user_id']));
				if(!$this->user){
					header('Location: /User/logout');
				}
				//если hash в сессии не совпадает с генерируемым, выходим
				if(!isset($_SESSION['user_hash']) || $_SESSION['user_hash'] != User::generateHash()){
					header('Location: /User/logout');
				}
				//если пользователь не подтвердил свой email, отправляем его на подтверждение
				if(!$this->user->confirm && $this->act != 'Confirm'){
					header('Location: /User/confirm');
				}
				// если пользователь существует и имеет подтвержденный email, но пытается войти, перенаправляем на /product
				if($this->user->confirm && ($this->ctrl == 'User' && ($this->act == 'Confirm' || $this->act == 'Login' || $this->act == 'Registration'))){
					header('Location: /product');
				}
				//подгружаем компании пользователя и выбираем основную
				$this->user->getCompanies();
				UserRights::$active_company_id = $this->user->getActiveCompany()->id;
				//подгружаем права пользователя
				$this->user->getRights();
				//var_dump(UserRights::checkRights($this->user, 'warehouse', 0));
				//var_dump($this->user);
			}
			$this->maintemplate = true;
			$this->json = false;
			foreach($this->data as $k => $v){
				if(isset($this->get[$k])){
					$this->$k = $this->get[$k];
				}
				elseif(isset($this->post[$k])){
					$this->$k = $this->post[$k];
				}
			}
	}

	public static function getinstance(){
		if(is_null(self::$_instance)){
			return self::$_instance = new self;
		}
		return self::$_instance;
	}

	public function output(){
		if($this->json==='true'){
			//если нужен ответ в формате json, главный шаблон не нужен
			//пока что криво работает, надо преобразование в json делать нормальным
			return json_encode(array('data'=> $this->content->getData(), 
									'statusMessages'=>$this->content->getStatusMessages(), 
									'errors' => $this->content->getErrors(), 
									'redirect'=>$this->content->getRedirect()),
				 				true);
		}
		elseif($this->maintemplate === 'false'){
			//если вдруг нужен шаблон контроллера без главного
			return $this->content->getHtml();
		}
		else{
			$view = new View();
			$view->mainController = $this;
			return $view->render('maintemplate');
		}
	}

	public function __set($k, $v){
		$this->data[$k] = $v;
	}

	public function __get($k){
		return isset($this->data[$k]) ? $this->data[$k] : '';
	}

	public function __isset($k){
		return isset($this->data[$k]);
	}

	public function addScript($script){
		$this->scripts[] = array('path'=>__DIR__.'/../views/js/'.$script.'.js', 'link'=>'/views/js/'.$script.'.js');
	}
	public function getScripts($type='link'){
		$scripts=array();
		foreach($this->scripts as $script)
		{
			$scripts[] = $script[$type];
		}
		return $scripts;
	}

	public function addStyle($style){
		$this->styles[] =array('path'=> __DIR__.'/../views/css/'.$style.'.css', 'link'=>'/views/css/'.$style.'.css');
	}

	public function getStyles($type='link'){
		$styles=array();
		foreach($this->styles as $style)
		{
			$styles[] = $style[$type];
		}
		return $styles;
	}

}

?>