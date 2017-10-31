<?php

namespace Application\Models;

use Application\Classes\Db;
use Application\Classes\E404Exception;
use Application\Classes\validationException;
use Application\Classes\UserRights;
use Application\Controllers\MainController;

abstract class AbstractModel
{
	protected static $table;
	protected static $required_data;

	protected $data = [];

	public function __construct($id = false){
		if($id && $id>0){
			$model = static::getOne(array('id'=>$id));
			if($model && $model instanceof static){
				foreach(static::$required_data as $k => $v){
					$this->$k = $model->$k;
				}				
			}
		}
		return $this;
	}

	public function __set($k, $v){
		$this->data[$k] = $v;
	}

	public function __get($k){
		return isset($this->data[$k]) ? $this->data[$k] : '';
	}

	public function __isset($k){
		return isset($this->data[$k]);
	}

	public static function getTable(){
		return static::$table;
	}

	public static function get($params = array(), $limit=0, $offset=0, $index=false){
		//проверяем, есть ли активная компания. Если id компании нужен модели, добавляем к параметрам
		$add_params = static::checkActiveCompany($params);
		$params = !empty($add_params) ? $add_params : $params;
		$db = new Db();
		$class=get_called_class();
		$db->setClassName($class);
		$result = $db->select()->from(static::$table)->where($params)->offset($offset)->limit($limit)->doit()->resultObj();
		return $result;
	}
	public static function getOne($params = array(), $limit=0, $offset=0, $index=false){
		$result = static::get($params,$limit,$offset,$index);
		if(isset($result[0]))
			return $result[0];
		return $result;
	}

	protected function add(){
		foreach($this->data as $k => $v){
			$params[$k] = $v;
		}
		$db = new Db();
		$class=get_called_class();
		$db->setClassName($class);
		$insert_id = $db->insert()->into(static::$table)->values($params)->doit();
		if($insert_id){
			$this->id = $insert_id;
		}
		return $insert_id;
	}

	protected function update(){
		foreach($this->data as $k => $v){
			$params[$k] = $v;
		}
		$db = new Db();
		$class=get_called_class();
		$db->setClassName($class);
		$insert_id = $db->update(static::$table)->set($params)->where(array('id'=>$this->id))->doit();
		return $insert_id;
	}

	public function save(){
		//проверяем, есть ли активная компания. Если id компании нужен модели, добавляем к параметрам
		if(static::checkActiveCompany()){
			$this->company_id = UserRights::$active_company_id;
		}
		if(isset($this->id) && $this->id>0){
			return $this->update();
		}else{
			return $this->add();
		}
	}

	public function delete(){
		if(!$this->id){
			throw new Exception('Нельзя удалить объект без индификатора');
		}
		$db = new Db();
		$class=get_called_class();
		$db->setClassName($class);
		return $db->delete()->from(static::$table)->where(array('id'=>$this->id))->doit();
	}

	public function setPostData(){
		//функция не выполняет задачи валидации
		foreach(static::$required_data as $required_data_key => $required_data_value){
				if(isset($_POST[$required_data_key])){
					$this->$required_data_key = $_POST[$required_data_key];
				}
			}
	}

	protected static function checkActiveCompany($params=array()){
		if(isset(static::$required_data['company_id'])){
			if(UserRights::$active_company_id==0){
				throw new validationException('Нет выбранной компании');
			}
			if(!isset($params['company_id'])){
				$params['company_id'] = UserRights::$active_company_id;
			}
			return $params;
		}
		return false;
	}

		/*public static function findAll(){
		$class=get_called_class();
		$sql = 'SELECT * FROM '.static::$table;
		$db = new DB();
		$db->setClassName($class);
		return $db->query($sql);
	}

	public static function findOneByPK($id){
		$sql = 'SELECT * FROM '.static::$table.' WHERE id=:id';
		$db = new DB();
		$class=get_called_class();
		$db->setClassName($class);
		$res = $db->query($sql, [':id'=>$id]);
		if(!empty($res)){
			return $res[0];
		}
		//исключения не должны здесь выбрасываться
		$e = new E404Exception('Ничего не найдено');
		throw $e;
		return;
	}

	public static function  findOneByColumn($column, $value){
		$sql = 'SELECT * FROM '.static::$table.' WHERE '.$column.'=:'.$column;
		$db = new DB();
		$class = get_called_class();
		$db->setClassName($class);
		$res =  $db->query($sql, [':'.$column => $value]);
		
		if(!empty($res)){
			return $res[0];
		}
		return;
		
	}

	public static function  findByColumns($paramspairs = array()){
		$sql = 'SELECT * FROM '.static::$table;
		$params = array();
		if($paramspairs){
			$sql.=' WHERE ';
			foreach($paramspairs as $column => $value){
				$sql .= $column.'=:'.$column;
				$params[':'.$column] = $value;
			}		
		}
		$db = new DB();
		$class = get_called_class();
		$db->setClassName($class);
		$res =  $db->query($sql, $params);
		return $res;
	}

	protected function insert(){
		$cols = array_keys($this->data);
		$data=[];
		foreach($cols as $col){
			$data[':'.$col]= $this->data[$col];
		}
		$sql = 'INSERT INTO '.static::$table.
		'('.implode($cols, ',').') VALUES ('.implode(array_keys($data), ',').')';
		$db = new DB();
		if(false !== $db->execute($sql, $data)){
			return $this->id = $db->lastInsertId();
		}
		return;
	}

	protected function update(){
		$cols = array_keys($this->data);
		$data=[];
		$sql = 'UPDATE '.static::$table.' SET ';
		foreach($cols as $col){
			$data[':'.$col]= $this->data[$col];
			$sql .= $col.'=:'.$col.',';
		}
		$sql = substr($sql, 0, -1). ' WHERE id='.$this->id;
		var_dump($sql);
		$db = new DB();
		return $db->execute($sql, $data);

	}

	public function save(){
		if(isset($this->id) && $this->id > 0){
			return $this->update();
		}
		return $this->insert();
	}

	public function delete(){
		$sql = "DELETE FROM ".static::$table." WHERE id=".$this->id;
		$db = new DB();
		return $db->execute($sql);	
	}

	public static function validate($data){
		foreach(static::$required_data as $required_data_key => $required_data_value){
			if($required_data_value && empty($data[$required_data_key])){
				throw new validationException('Не заполнены обязательные поля модели'); //а имеет ли смысл?
				return;
			}
			return true;
		}
	}*/
}

?>