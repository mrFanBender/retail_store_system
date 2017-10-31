<form name="productGroup" action="/productGroup/save<?php echo isset($productGroup->id) ? '/'.$productGroup->id : ''  ?>" method="POST">
	<h1><?php echo isset($productGroup->id) ? 'Редактирование товарной группы' : 'Создание товарной группы'  ?></h1>
	<label>Имя группы</label>
	<input type="text" name="name" value="<?php echo $productGroup->name; ?>"/><br/>
	<label>Описание группы</label>
	<input type="text" name="description" value="<?php echo $productGroup->description; ?>"/><br/>
	<label>Родительская группа</label>
	<input type="text" name="parent_id" value="<?php echo $productGroup->parent_id; ?>"/><br/>
	<input type="submit" value = "Сохранить"/>
</form>