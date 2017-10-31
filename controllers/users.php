<?php
namespace Application\Controllers;

use Application\Models\User as UserModel;
use Application\Models\Warehouse as WarehouseModel;
use Application\Models\ProductGroup as ProductGroupModel;
use Application\Classes\Answer;
use Application\Classes\ValidationException;
use Application\Classes\UserRights;
use Application\Controllers\View;

class Users extends \Application\Controllers\controller{
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
		$this->view->user = $user;		
		return $this->answer->setHtml($this->view->render('saveuser'));
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
		return $this->answer->addStatusMessage('Пользователь успешно удален');
		
	}

	public function actionGetAll(){
		$users_in_company = userModel::getUsersInCompany();
		$users_in_company_ids = array();
		foreach($users_in_company as $user){
			$users_in_company_ids[] = $user->user_id;
		}
		$users = userModel::get(array('id'=>$users_in_company_ids));
		$this->view->users = $users;
		$this->answer->users = $users;
		return $this->answer->setHtml($this->view->render('userList'));
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

	public function actionSaveRights(){
		$user = UserModel::getOne(array('id'=>$this->id));
		if(!$user){
			throw new ValidationException('Пользователь не найден');
		}
		if(isset($_POST['user_rights'])){
			//сохраняем права пользователя
			$user_rights_array = $_POST['user_rights'];
			foreach($user_rights_array as $user_rights_object_type){
				foreach($user_rights_object_type as $user_rights_object_id){
					foreach($user_rights_object_id as $user_rights_object_right_type){
						var_dump('типа сохранили');
					}
				}
			}
		}
		$rights = $user->getRights();
		$user_rights_array = array();
		//подгружаем точки продаж

		//подгружаем склады
		$warehouses = WarehouseModel::get();
		$user_rights_array['warehouse'][] = UserRights::checkRights($user, 'warehouse', 0);
		//var_dump($user_rights_array['warehouse']);
		foreach($warehouses as $warehouse){
			$user_rights_array['warehouse'][] = UserRights::checkRights($user, 'warehouse', $warehouse->id);
		}
		//подгружаем права на поставщиков

		//подгружаем права на покупателей

		//подгружаем права на товары
		$user_rights_array['product'][] = UserRights::checkRights($user, 'product', 0);
		//подгружаем права на товарные группы
		$user_rights_array['product_group'][] = UserRights::checkRights($user, 'product_group', 0);
		$product_groups = ProductGroupModel::get();
		foreach($product_groups as $product_group){
			$user_rights_array['product_group'][] = UserRights::checkRights($user, 'product_group', $product_group->id);
		}
		//подгружаем права на продажи
		$user_rights_array['sell'][] = UserRights::checkRights($user, 'sell', 0);
		//подгружаем права на управление товарным запасом
		$user_rights_array['product_managment'][] = UserRights::checkRights($user, 'sell', 0);
		//подгружаем права на управление пользователями
		$user_rights_array['user'][] = UserRights::checkRights($user, 'user', 0);
		//подгружаем права на редактирование информации о компании
		$user_rights_array['company_info'][] = UserRights::checkRights($user, 'company_info', 0);
		//подгружаем права на доступ к ценам
		$user_rights_array['small_opt_price'][] = UserRights::checkRights($user, 'small_opt_price', 0);
		$user_rights_array['medium_opt_price'][] = UserRights::checkRights($user, 'medium_opt_price', 0);
		$user_rights_array['large_opt_price'][] = UserRights::checkRights($user, 'large_opt_price', 0);
		$user_rights_array['purchase_price'][] = UserRights::checkRights($user, 'purchase_price', 0);
		//var_dump($user_rights_array);
		$this->view->user = $user;
		$this->view->user_rights = $user_rights_array;

		return $this->answer->setHtml($this->view->render('userrights'));
	}


}





?>