<?php
namespace Application\Classes;

use Application\Classes\DB;
use Application\Models\UserRights as URModel;
use Application\Models\UserRewards as UserRewardsModel;
use Application\Models\User;

class UserRights{

	private static $_instance = null;

	private function __construct(){

	}
	private function __clone(){

	}

	public static function getInstance(){
		if(is_null(self::$_instace)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	//права пользователей
	//новая версия
	const USER_RIGHTS = array(	'sell_point'		=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false,
																						'visible'	=>	false)),
								'warehouse'			=>	array(	'general'	=>	array(	'create'	=>	false, 			//имеет ли право пользователь создавать склады
																						'delete'	=>	false), 		//имеет ли право пользователь редактировать и удалять склады
																'object'	=>	array(	'access'	=>	false,			//определяет, есть ли доступ на этот склад для операций с изменением тов.запаса
																						'visible'	=>	false)),		//определяет, видит ли пользователь этот склад(в выпадающих списках, справочнике и т.д.)
								'supplier'			=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'buyer'				=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'product'			=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false)),
								'product_group'		=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'visible'	=>	false,
																						'access'	=>	false)),
								'rewards'			=>	array(	'general'	=>	array(	'create'	=>	false)),	//устанавливает, может ли пользователь назначать размер вознаграждения за продажи
								'sell'				=>	array(	'general'	=>	array(	'create'	=>	false, 		//может ли пользователь cjplfdfnm ghjlf;b 
																						'delete'	=>	false)),	//удалять и редактировать продажи
								'product_managment'	=>	array(	'general'	=>	array(	'create'	=>	false, 		//может ли пользователь создавать документы, изменяющие товарный запас
																						'delete'	=>	false)), 	//редактировать и удалять такие документы
								'user'				=>	array(	'general'	=>	array(	'create'	=>	false, 		//может ли пользователь создавать других пользователей
																						'delete'	=>	false)),	
								'company_info'		=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false)),
								'small_opt_price'	=>	array(	'general'	=>	array(	'visible'	=>	false, 
																						'access'	=>	false)), 
								'medium_opt_price'	=>	array(	'general'	=>	array(	'visible'	=>	false, 
																						'access'	=>	false)), 
								'large_opt_price'	=>	array(	'general'	=>	array(	'visible'	=>	false, 
																						'access'	=>	false)),
								'purchase_price'	=>	array(	'general'	=>	array(	'visible'	=>	false, 
																						'access'	=>	false))
							);
	public static $active_company_id = 0;
	public static function getRights($params = array()){
		$user_rights = URModel::get($params);
		return $user_rights;
	}

	public static function processingRights($user_rights){
		$user_rights_array = array();
		/*foreach(self::USER_RIGHTS as $object_type_name => $value){
			$user_rights_array[$object_type_name][0] = $value['general'];
		}*/
		foreach($user_rights as $user_right){
			if($user_right->object_id==0){
				$user_rights_array[$user_right->object_type][0][$user_right->right_code] = $user_right->value;
			}else{
				//$user_rights_array[$user_right->object_type][$user_right->object_id] = self::USER_RIGHTS[$user_right->object_type]['object'];
				$user_rights_array[$user_right->object_type][$user_right->object_id][$user_right->right_code] = $user_right->value;
			}
		}
		return $user_rights_array;
	}

	public static function checkRights(User $user, $object_type, $object_id, $right_type=false){
		if(!$user->getRights()){
			$user_rights = $user->loadRights();
		}
		$user_rights = $user->getRights();
		//var_dump($user);
		$user_rights_proto = array();
		$object_id_proto = 'general';
		if($object_id>0){
			$object_id_proto = 'object';
		}
		if(null == self::USER_RIGHTS[$object_type][$object_id_proto]){
			return;
		}
		$user_rights_proto = self::USER_RIGHTS[$object_type][$object_id_proto];
		if(!isset($user_rights[$object_type][$object_id]) && !$user->isAdmin()){
			//если среди разрешений пользователя нет нужного, возвращаем прототип из USER_RIGHTS
			return $user_rights_proto;
		}
		if($right_type){
			//если admin, то ему можно всё
			if($user->isAdmin()){
				return 1;
			}
			//если нам нужно проверить конкретное разрешение
			if(isset($user_rights[$object_type][$object_id][$right_type])){
				//если в таблице user_rights есть такая запись, возвращаем ее
				return $user_rights[$object_type][$object_id][$right_type];
			}
			//если такой записи нет, то у пользователя нет такого права.
			return;
		}else{
			//если нужно получить группу разрешений объекта
				//если admin, то ему можно всё
			if($user->isAdmin()){
				foreach($user_rights_proto as $user_rights_proto_right_type => $v){
					$user_rights_proto[$user_rights_proto_right_type] = 1;
				}
				return $user_rights_proto;
			}
			foreach($user_rights[$object_type][$object_id] as $right_type => $value){
				$user_rights_proto[$right_type] = $value;
			}
			return $user_rights_proto;
		}
		throw new Exception('Что-то пошло не так при проверке прав пользователя');
	}

	public static function delete($user_id, $company_id){
		return URModel::deleteAll($user_id, $company_id);
	}

	public static function getReward($user_id, $product_group_id, $price_type){
		$reward = UserRewardsModel::getOne(array('user_id'=>$user_id, 'product_group_id'=>$product_group_id, 'price_type'=>$price_type));
		if(!$reward){
			return 0;
		}
		return $reward->value;
	}

	public static function deleteRewards($user_id, $company_id){
		return UserRewardsModel::deleteAll($user_id, $company_id);
	}

	/*старая версия
	const USER_RIGHTS = array(	'sail_point' =>	array(	'spravochnik' 		=> 	array(	'create'=>false,
																					'delete'=>false),
														'access'			=>	false, //есть доступ к точке продаж. Используется при формировании списка точек при создании заказа/продажи
														),
								'warehouse'	=>	array(	'spravochnik'		=>	array(	'create'=>false,
																					    'delete'=>false),
														'view'				=>	false,	//просмотр остатков склада
														'sell_create'		=>	false,	//создавать продажи
														'sell_delete'		=>	false,	//редактировать и удалять заказы
														'stock_management'	=>	false),  //создавать, редактировать, удалять поставки, перемещения, списания, возвраты
								'supplier'	=>	array(),
								'buyer'		=>	array(),
								'product'	=>	array(),
								'product_group'	=>	array(),
	*/

}



?>