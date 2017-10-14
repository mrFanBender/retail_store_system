<?php
namespace Application\Classes;

use Application\Classes\DB;
use Application\Models\UserRights as URModel;

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
	const USER_RIGHTS = array(	'sail_point'		=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false,
																						'visible'	=>	false)),
								'warehouse'			=>	array(	'general'	=>	array(	'create'	=>	false, 			//имеет ли право пользователь создавать склады
																						'delete'	=>	false), 		//имеет ли право пользователь редактировать и удалять склады
																'object'	=>	array(	'access'	=>	false,			//определяет, есть ли доступ на этот склад
																						'visible'	=>	false)),		//определяет, видит ли пользователь этот склад(в выпадающих списках, справочнике и т.д.)
								'supplier'			=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'buyer'				=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'product'			=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'product_group'		=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'sell'				=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false)),
								'product_managment'	=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'user'				=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'company_info'		=>	array(	'general'	=>	array(	'create'	=>	false, 
																						'delete'	=>	false), 
																'object'	=>	array(	'access'	=>	false)),
								'prices'			=>	array(	'small_opt_price'	=>	array(	'visible'	=>	false, 
																								'access'	=>	false), 
																'medium_opt_price'	=>	array(	'visible'	=>	false, 
																								'access'	=>	false), 
																'large_opt_price'	=>	array(	'visible'	=>	false, 
																								'access'	=>	false),
																'purchase_price'	=>	array(	'visible'	=>	false, 
																								'access'	=>	false))
							);
	public static $active_company_id = 0;
	public static function getRights($params = array()){
		$userrights = URModel::get($params);
		
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