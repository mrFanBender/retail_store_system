<?php

namespace Application\Controllers;

use Application\Models\warehouse as warehouseModel;
use Application\Controllers\View;
use Application\Classes\Answer;
use Application\Classes\EmptyListException;
use Application\Classes\Db;
use Application\Controllers\Controller;

class warehouse extends Controller{

	public function actionIndex(){
		return $this->actionGetAll();
	}
	public function actionSave(){
		$warehouse = new warehouseModel($this->id);
		//если есть post-данные, то сохраняем или создаем новый склад.
		if(isset($_POST['name'])){
			$warehouse->setPostData();
			$warehouse->save();
		}
		$view = new View();
		$view->warehouse = $warehouse;
		$html =  $view->render('saveWarehouse');
		$answer = new Answer($html);
		$answer->warehouse = $warehouse;
		return $answer;
	}

	public function actionDelete(){
		$warehouse = new warehouseModel();
		if(!$this->id){
			throw new E404Exception('Не указан идентификатор склада');
		}
		$warehouse->id = $this->id;
		if(!$warehouse->delete()){
			throw new Exception('Ошибка удаления Склада');
		}
		$answer = new Answer();
		$answer->statusMessage('Склад успешно удален');
		return $answer;
		
	}

	public function actionGetAll($message = false){
		$warehouses = warehouseModel::get();
		$view = new View();
		$view->warehouses = $warehouses;
		$html = $view->render('warehouseList');
		$answer = new Answer($html);
		$answer->warehouses = $warehouses;
		return $answer;
	}

}