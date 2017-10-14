<?php
namespace Application\Controllers;

use Application\Models\User as UserModel;
use Application\Classes\Answer;
use Application\Classes\ValidationException;
use Application\Controllers\View;


class User extends \Application\Controllers\controller{
	public function actionIndex(){
		return $this->actionGetAll();
	}

	public function actionSave(){
		$user = new userModel($this->id);		
		//если есть post-данные, то сохраняем или создаем нового юзера.
		if(isset($_POST['name'])){
			$user->setPostData();
			$user->save();
		}

		$view = new View();
		$view->user = $user;
		$html =  $view->render('saveuser');
		$answer = new Answer($html);
		return $answer;
	}


	public function actionDelete(){
		$user = new userModel();
		if(!$this->id){
			throw new Exception('Нужно указать Идентификатор пользователя');
		}
		$user->id = $this->id;
		if(!$user->delete()){
			throw new Exception('Ошибка удаления Пользователя');
		}
		$answer = new Answer();
		$answer->statusMessage('Пользователь успешно удален');
		return $answer;
		
	}

	public function actionGetAll(){
		$users = userModel::get();
		$view = new View();
		$view->users = $users;
		$html = $view->render('userList');
		$answer = new Answer($html);
		$answer->users = $users;
		return $answer;
	}
	public static function getIP(){
		$IP = $_SERVER['REMOTE_ADDR'];
		if(array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
			$IP = array_pop(explode(',',  $_SERVER['HTTP_X_FORWARDED_FOR']));
		}
		return $IP;
	}
	
	public static function generateHash(){
		$user_ip = self::getIP();
		return md5($_SERVER['HTTP_USER_AGENT'].$user_ip);
	}
	/*protected function generateHash($count){
		$chars = 'qwertyuiopasdfghjklzxcvbnm1234567890MNBVCXZLKJHGFDSAPOIUYTREWQ';
		$len = strlen($chars)-1;
		$hash = '';
		while($count--){
			$hash .= $chars[rand(0,$len)];
		}
		return $hash;
	}*/

	public function actionRegistration(){
		$this->answer->setHtml($this->view->render('userRegistration'));
		if(isset($_POST['login'])){
			//добавить нормальную валидацию, когда появится класс
			$user = userModel::get(array('login'=>$_POST['login']));
			if($user){
				throw new ValidationException('Пользователь с таким Email уже зарегистрирован', 'login');
			}
			if($_POST['pass'] != $_POST['repass']){
				throw new ValidationException('Пароли не совпадают. Проверьте правильность', 'repass');	
			}
			$user = new userModel();
			$user->setPostData();
			$user->pass = md5(md5($_POST['pass']));
			//генерируем hash
			$user->hash = self::generateHash();
			$user_id = $user->save();
			if(!$user_id){
				throw new ValidationException('Что-то пошло не так. Попробуйте еще раз через некоторое время');	
			}
			$this->answer->addStatusMessage('Вы успешно зарегистрировались. На вашу почту выслано письмо с подтвержднием. Перейдите по ссылке в письме.');
			$this->answer->addRedirect('/user/confirm');
			//отправляем email для подтверждения. Когда появится соответсующий класс
			//mail($_POST['login'], 'Регистрация в системе складского учета', $this->mainController->host.'/user/confirm?user_id='.$user->id.'&hash='.$user->hash);
		}
		return $this->answer;
	}
	
	public function actionLogin(){
		/*if(isset($_SESSION['user_id']) && $_SESSION['user_id']>0){
			header('Location: /product');
		}*/
		$this->answer->setHtml($this->view->render('Login'));
		if(isset($_POST['login']) && isset($_POST['pass'])){
			//добавить нормальную валидацию, когда появится класс
			$user = userModel::getOne(array('login' => $_POST['login'], 'pass' => md5(md5($_POST['pass']))));
			if($user){
				//var_dump(self::generateHash());
				$_SESSION['user_id'] = $user->id;
				if(!$user->confirm){
					throw new ValidationException('На вашу почту было выслано письмо со ссылкой для подтверждения. Перейдите по ссылке.');
				}
				$_SESSION['user_hash'] = self::generateHash();
				$this->answer->addRedirect('/product');	
			}else{
				throw new ValidationException('Неверная пара Логин/Пароль', 'login');
			}
		}
		return $this->answer;

	}

	public function actionLogout(){
		session_destroy();
		header('Location:/user/login');
	}

	public function actionConfirm(){
		if(isset($this->mainController->get['user_id']) && isset($this->mainController->get['user_hash'])){
			$user = UserModel::getOne(array('user_id'=>$this->mainController->get['user_id'], 'hash'=>$this->mainController->get['user_hash']));
			if($user){
				//если данные верны, переводим пользователя в разряд подтвержденных, авторизуем его и делаем вход в систему
				$user->confirm = 1;
				$user->save();
				$_SESSION['user_id'] = $user->id;
				$_SESSION['user_hash'] = $user->hash;
				$this->answer->addRedirect('/product');
			}
		}
		//иначе выводим шаблон с ошибкой
		return $this->answer->setHtml($this->view->render('userConfirm'));
	}

	public function actionSendConfirmLetter(){
		if(!isset($this->mainController->post['login'])){
			throw new ValidationException('Введите почту', 'login');
		}
		$user = UserModel::getOne(array('login' => $this->mainController->post['login']));
		if(!$user){
			throw new ValidationException('Пользователь с такой почтой не найден', 'login');
		}
		$user->hash = self::generateHash();
		$user->save();
		//mail($this->mainController->post['login'], 'Регистрация в системе складского учета', $this->mainController->host.'/user/confirm?user_id='.$user->id.'&hash='.$user->hash);
		return $this->answer->addStatusMessage('На вашу почту отправлено письмо с ссылкой для подтверждения');
	}


}





?>