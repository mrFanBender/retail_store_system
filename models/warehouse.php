<?php
namespace Application\Models;

class warehouse extends abstractModel{
/**
class ProductGroup
@property $id
@property $name
@property $description

**/
protected 	static $table = 'warehouse';
public 		static $required_data = array(	'id'			=>false,
											'name'			=>true,
											'description' 	=>false);



}



?>