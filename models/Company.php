<?php
namespace Application\Models;


class Company extends abstractModel{
/**
class Product
@property $id
@property $name
@property $full_name
@property $address

**/
protected 	static $table = 'companies';
public 		static $required_data = array(	'id'			=>false,
											'name'			=>true,
											'full_name' 	=>false,
											'address'		=>false,
										);


}



?>