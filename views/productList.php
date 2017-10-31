<!DOCTYPE html>
<html>
<head>
	<title>Список Складов</title>
</head>
<body>
	<a class="btn button" href='/product/save'>Создать товар</a><br/>
	<table class="table">
		<thead>
			<tr>
				<th scope="col">#</th>
				<th scope="col">Наименование</th>
				<th scope="col">Описание</th>
				<th scope="col">Группа товаров</th>
				<th scope="col">Изображение</th>
				<th scope="col">Закупочная цена</th>
				<th scope="col">Крупный опт</th>
				<th scope="col">Средний опт</th>
				<th scope="col">Мелкий опт</th>
				<th scope="col">Розница</th>
			</tr>
		</thead>
		<?php if(empty($products)): ?>
			<tr><p>У вас нет товаров</p></tr>
		<?php endif; ?>
		<?php foreach($products as $product): ?>
			<tr>
				<td><?php echo $product->id; ?></td>
				<td><?php echo $product->name; ?></td>
				<td><?php echo $product->description; ?></td>
				<td><?php echo $product->getParent()->name; ?></td>
				<td><?php echo $product->image; ?></td>
				<td><?php echo $product->purchase_price; ?></td>
				<td><?php echo $product->large_opt_price; ?></td>
				<td><?php echo $product->medium_opt_price ? $product->medium_opt_price : 'нет'; ?></td>
				<td><?php echo $product->small_opt_price; ?></td>
				<td><?php echo $product->retail_price; ?></td>
				<td><a href="/product/save/<?= $product->id ?>">Изменить</a></td>
				<td><a href="/product/delete/<?= $product->id ?>">Удалить</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>