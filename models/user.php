<?php
namespace Application\Models;

use Application\Models\abstractModel;
use Application\Models\Company as Company;
use Application\Classes\DB;

class User extends abstractModel{
	/**
	class ProductGroup
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
	protected	$active_company = 	false;


	public function getCompanies($offset=0, $limit=0){
		$db = new DB();
		$user_companies = $db->select()->from('users_in_companies')->where(array('user_id'=>$this->id))->offset($offset)->limit($limit)->doit()->resultObj();
		if(!$user_companies){
			return;
		}
		$user_companies_ids = array();
		$active_company_id = false;
		foreach($user_companies as $user_company) {
			$user_companies_ids[] = $user_company->company_id;
			if(!$active_company_id && $user_company->active){
				$active_company_id = $user_company->company_id;
			}
		}
		$db->setClassName = 'Application\Models\Company';
		$this->companies = Company::get(array('id'=>$user_companies_ids));
		if(!$active_company_id){
			$this->active_company = $this->companies[0];
			return $this->companies;
		}
		foreach($this->companies as $company){
			if($company->id == $active_company_id){
				$this->active_company = $company;
				break;
			}
		}
		return $this->companies;
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