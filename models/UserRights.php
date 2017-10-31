<?php
namespace Application\Models;

use Application\Models\abstractModel;
use Application\Classes\Db;

class UserRights extends abstractModel{

	protected 	static $table = 'user_rights';
	public 		static $required_data = array(	'id'				=>false,
												'user_id'			=>true,
												'object_id'			=>false,
												'object_type' 		=>true,
												'right_code'		=>true,
												'value'				=>true,
												'company_id'		=>false);

	public static function deleteAll($user_id, $company_id){
		$db = new Db();
		$result = $db->delete()->from('user_rights')->where(array('user_id'=>$user_id, 'company_id'=>$company_id))->resultObj();
		return $result;
	} 

}



?>