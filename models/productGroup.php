<?php
namespace Application\Models;

use Application\Classes\Db;
use Application\Classes\E404Exception;
use Application\Classes\validationException;

class productGroup extends abstractModel{
/**
class ProductGroup
@property $id
@property $name
@property $description
@property $parent_id 

**/
protected 	static $table = 'product_group';
public 		static $required_data = array(	'id'			=>false,
											'name'			=>true,
											'description' 	=>false,
											'parent_id'		=>false,
											'company_id'	=>false);



}




?>