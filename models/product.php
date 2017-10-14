<?php
namespace Application\Models;

use Application\Models\productGroup;

class product extends abstractModel{
/**
class Product
@property $id
@property $name
@property $description
@property $parent_id
@property $image
@property $purchase_price
@property $large_opt_price
@property $medium_opt_price
@property $small_opt_price
@property $retail_price 

**/
protected 	static $table = 'product';
public 		static $required_data = array(	'id'			=>false,
											'name'			=>true,
											'description' 	=>false,
											'parent_id'		=>false,
											'image'			=>false,
											'purchase_price'		=>false,
											'large_opt_price'		=>false,
											'medium_opt_price'		=>false,
											'small_opt_price'		=>false,
											'retail_price'			=>true,
											'company_id'			=>false);
private $parent = false;

public function getParent(){
	return $this->parent = productGroup::getOne(array('id'=>$this->parent_id));
}

}



?>