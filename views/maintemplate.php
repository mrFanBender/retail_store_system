<?php
//подключаем стили
//$mainController->addStyle('bootstrap/bootstrap-grid');

$mainController->addStyle('bootstrap/bootstrap');
$mainController->addStyle('bootstrap/bootstrap-reboot');

//подсключаем скрипты
$mainController->addScript('jquery-3.2.1');
$mainController->addScript('popper');
$mainController->addScript('bootstrap/bootstrap.min');
$mainController->addScript('common');

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset='utf-8'/>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<style type="text/css" src="/views/css/bootstrap/bootstrap.css"></style>
	<?php foreach($mainController->getStyles() as $style): ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $style; ?>">
	<?php endforeach; ?>
	<?php foreach($mainController->getScripts() as $script): ?>
		<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php endforeach; ?>
</head>
<body>
	<nav class="navbar navbar-fixed-top navbar-light bg-faded">
		<a class="navbar-brand" href="#">
		  	Облачный склад
		</a>
  		<div class="btn-group" role="group">
  			<?php if($mainController->user && $mainController->user->getCompanies()){ ?>
			  	<div class="btn-group" role="group">
			  		<button id="btn1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  			<?php echo $mainController->user->getCompany(0)->name; ?>
			  		</button>
			  		<div class="dropdown-menu" aria-labelledby="btn1">
			  			<?php foreach($mainController->user->getCompanies() as $company): ?>
			  				<a class="dropdown-item" href="#"><?= $company->name ?></a>
			  			<?php endforeach; ?>
			  		</div>
			  	</div>
			 <?php } ?>
		  	<?php if(!$mainController->user){ ?>
		  		<a class="btn" href="user/login">Войти</a>
		  	<?php }else{ ?>
		  		<div class="btn-group" role="group">
			  		<button id="btn2" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  			<?php echo $mainController->user->name; ?>
			  		</button>
			  		<div class="dropdown-menu" aria-labelledby="btn2">
			  			<a class="dropdown-item" href="#">Мой профиль</a>
			  			<a class="dropdown-item" href="/user/logout">Выход</a>
			  		</div>
			  	</div>
		  	<?php } ?>
  		</div>

	</nav>
	<!--<nav class="navbar navbar-expand-md navbar-light bg-light">
		<div class="collapse navbar-collapse" id="mainNav">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="/sells">Продажи<span class="sr-only"></span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/product">Товары<span class="sr-only"></span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/reports">Отчеты<span class="sr-only"></span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/system">Справочник<span class="sr-only"></span></a>
				</li>
			</ul>	
		</div>
		<div class="collapse navbar-collapse" id="mainNav">
			
		</div>
	</nav>
	-->
	<header>
		<?php if($mainController->user): ?>
		<ul class="nav nav-tabs" id="mainTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link" id="sell_tab" data-toggle="tab" href="#sell_pane" role="tab" aria-controls="sell_pane">Продажи</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="product_tab" data-toggle="tab" href="#product_pane" role="tab" aria-controls="product_pane">Товары</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="report_tab" data-toggle="tab" href="#report_pane" role="tab" aria-controls="report_pane">Отчеты</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="company_tab" data-toggle="tab" href="#company_pane" role="tab" aria-controls="company_pane">Компания</a>
				</li>
			</ul>
			<div class="tab-content" id="mainTabContent">
				<div class="tab-pane" id="sell_pane" role="tabpanel" aria-labelledby="sell_tab">
					<nav class="nav nav-pills">
						<a class="nav-item nav-link" href="/sells" target_tab="#sell_tab">Продажи</a>
						<a class="nav-item nav-link" href="/sells/getOrders" target_tab="#sell_tab">Заказы</a>
						<a class="nav-item nav-link" href="returns" target_tab="#sell_tab">Возвраты</a>
					</nav>
				</div>
				<div class="tab-pane" id="product_pane" role="tabpanel" aria-labelledby="product_tab">
					<nav class="nav nav-pills">
						<a class="nav-item nav-link" href="/product" target_tab="#product_tab">Товары</a>
						<a class="nav-item nav-link" href="/productMovements" target_tab="#product_tab">Перемещения</a>
						<a class="nav-item nav-link" href="/supplies" target_tab="#product_tab">Поставки</a>
						<a class="nav-item nav-link" href="/vendorreturns" target_tab="#product_tab">Возвраты</a>
						<a class="nav-item nav-link" href="/cancellations" target_tab="#product_tab">Списания</a>					
					</nav>
				</div>
				<div class="tab-pane" id="report_pane" role="tabpanel" aria-labelledby="report_tab">
					<nav class="nav nav-pills">
						<a class="nav-item nav-link" href="#"></a>
					</nav>
				</div>
				<div class="tab-pane" id="company_pane" role="tabpanel" aria-labelledby="company_tab">
					<nav class="nav nav-pills">
						<a class="nav-item nav-link" href="/warehouse" target_tab="#company_tab">Склады</a>
						<a class="nav-item nav-link" href="/sell_points" target_tab="#company_tab">Точки продаж</a>
						<a class="nav-item nav-link" href="/suppliers" target_tab="#company_tab">Поставщики</a>
						<a class="nav-item nav-link" href="/buyers" target_tab="#company_tab">Покупатели</a>
						<a class="nav-item nav-link" href="/catalog" target_tab="#company_tab">Каталог товаров</a>
						<a class="nav-item nav-link" href="/users" target_tab="#company_tab">Пользователи</a>
						<a class="nav-item nav-link" href="/company_info" target_tab="#company_tab">Данные компании</a>
					</nav>					
				</div>
			</div>
		<?php endif; ?>
		<!--<nav class="nav nav-pills">
			<?php if($mainController->ctrl == ("sells2" || "returns2")){ ?>
				<a class="nav-item nav-link active" href="/sells">Продажи</a>
				<a class="nav-item nav-link" href="/sells/getOrders">Заказы</a>
				<a class="nav-item nav-link" href="returns">Возвраты</a>
			<?php }elseif($mainController->ctrl == ("product" || "productMovements" || "supplies" || "vendorreturns" || "cancellations")){ ?>
				<a class="nav-item nav-link" href="/product">Товары</a>
				<a class="nav-item nav-link" href="/productMovements">Перемещения</a>
				<a class="nav-item nav-link" href="/supplies">Поставки</a>
				<a class="nav-item nav-link" href="/vendorreturns">Возвраты</a>
				<a class="nav-item nav-link" href="/cancellations">Списания</a>
			<?php }elseif($mainController->ctrl == ("reports")){ ?>
				<a class="nav-item nav-link" href="#"></a>
			<?php }elseif($mainController->ctrl == ("users" || "sail_points" || "warehouses" || "suppliers" || "buyers" || "catalog")){ ?>
				<a class="nav-item nav-link" href="#"></a>
				<a class="nav-item nav-link" href="#"></a>
				<a class="nav-item nav-link" href="#"></a>
			<?php } ?>
		</nav>-->
	</header>
	<div class="container">
		<?php echo $mainController->content->getHtml(); ?>
	</div>
	<footer class="">
		Футер
	</footer>
</body>
</html>