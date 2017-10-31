<?php
namespace Application\Models;

use Application\Classes\Db;
use Application\Classes\E404Exception;
use Application\Classes\validationException;

class userRewards extends abstractModel{
/**
class UserRewards
@property $id
@property $user_id
@property $product_group_id
@property $price_type 
@property $value 

**/
protected 	static $table = 'user_rewards';
public 		static $required_data = array(	'id'				=>false,
											'user_id'			=>true,
											'product_group_id' 	=>true,
											'price_type'		=>true,
											'value'				=>false);

public static function deleteAll($user_id, $company_id){
	$db = new Db();
	$result = $db->delete()->from('user_rewards')->where(array('user_id'=>$user_id, 'company_id'=>$company_id))->resultObj();
	return $result;
} 


}




?>