<?php
namespace Application\Models;

use Application\Models\abstractModel;
use Application\Models\Company as Company;
use Application\Classes\DB;
use Application\Classes\UserRights;
use Application\Models\UserRights as UserRightsModel;

class User extends abstractModel{
	/**
	class User
	@property $id
	@property $name
	@property $job
	@property $login
	@property $pass
	@property $description
	@property $role

	**/

	protected 	static $table = 'user';
	public 		static $required_data = array(	'id'				=>false,
												'name'				=>true,
												'job'				=>true,
												'login' 			=>true,
												'pass'				=>true,
												'description'		=>false,
												'role'				=>false);
	protected 	$user_rights 	= 	array();
	protected	$contacts		=	array();
	protected 	$companies      =	array();
	protected	$active_company = 	array();
	protected	$is_admin 		= 	null;

	public function isAdmin(){
		if(null != $this->is_admin){
			return $this->is_admin;
		}
		if(!isset(UserRights::$active_company_id)){
			throw new Exception('Не выбрана активная компания');
		}
		$db = new DB();
		$is_admin = $db->select()->from('users_in_companies')->where(array('user_id'=>$this->id, 'company_id'=>UserRights::$active_company_id, 'admin'=>1))->resultObj();
		if($is_admin){
			return $this->is_admin=1;
		}
		return $this->is_admin=0;
	}

	//это надо переписать. неверно с точки зрения распределения зон ответственности
	public function getCompanies($offset=0, $limit=0){
		if($this->companies){
			return $this->companies;
		}
		$db = new DB();
		$user_companies = $db->select()->from('users_in_companies')->where(array('user_id'=>$this->id))->orderby('active', 'DESC')->offset($offset)->limit($limit)->doit()->resultObj();
		if(!$user_companies){
			return;
		}
		//var_dump($user_companies);
		$user_companies_ids = array();
		$active_company_id = $user_companies[0]->company_id;
		foreach($user_companies as $user_company) {
			$user_companies_ids[] = $user_company->company_id;
		}
		$db->setClassName = 'Application\Models\Company';
		$this->companies = Company::get(array('id'=>$user_companies_ids));
		$this->active_company = $this->companies[0];
		UserRights::$active_company_id = $this->active_company->id;
		return $this->companies;
	}

	public function loadRights(){
		$this->user_rights = array();
		if(!$this->isAdmin()){
			$rights = UserRightsModel::get(array('user_id'=>$this->id, 'company_id'=>UserRights::$active_company_id));
			$this->user_rights = UserRights::processingRights($rights);
		}
		return $this->user_rights;
	}

	public function getRights(){
		if(null == !$this->is_admin){
			$this->isAdmin();
		}
		if(!$this->user_rights){
			$this->loadRights();
		}
		return $this->user_rights;
	}

	public function delete(){
		if(!$this->id){
			throw new Exception('Пользователь не найден');
		}
		$db = new Db();
		$result = $db->delete()->from('users_in_companies')->where(array('user_id'=>$this->id, 'company_id'=>$company_id))->resultObj();
	}
	
	public static function getUsersInCompany($company_id=0){
		$company_id = $company_id==0 ? UserRights::$active_company_id : $company_id;
		$db = new Db();
		$result = $db->select()->from('users_in_companies')->where(array('company_id'=>$company_id))->resultObj();
		return $result;
	}

	public function getCompany($index){
		
		return isset($this->companies[$index]) ? $this->companies[$index] : array();

	}

	public function getActiveCompany(){
		return $this->active_company;
	}

	/*public function __construct($user_id = false){
		if((int)$user_id>0){
			$result = self::get(array('id'=>$user_id));
			if(isset($result[0]) && $result[0] instanceof self){
				foreach(self::$required_data as $k => $v){
					$this->$k = $result[0]->$k;
				}				
			}
		}
		return $this;
	}*/

}

?>