<!DOCTYPE html>
<html>
<head>
	<title>Пользователь</title>
</head>
<body>
	<form name="user" action="/users/save<?php echo isset($user->id) ? '/'.$user->id : ''  ?>" method="POST">
		<h1><?php echo isset($user->id) ? 'Редактирование товара' : 'Создание товара'  ?></h1>
		<label>ФИО</label>
		<input type="text" name="name" value="<?php echo $user->name; ?>"/><br/>
		<label>Должность</label>
		<input type="text" name="job" value="<?php echo $user->job; ?>"/><br/>
		<label>Логин</label>
		<input type="text" name="login" value="<?php echo $user->login; ?>"/><br/>
		<label>Пароль</label>
		<input type="text" name="pass" value=""/><br/>
		<label>Подтверждение пароля</label>
		<input type="text" name="pass_2" value=""/><br/>
		<label>О себе</label>
		<input type="text" name="description" value="<?php echo $user->description; ?>"/><br/>
		<label>Права администратора</label>
		<input type="checkbox" name="admin" <?php echo ($user->admin ? 'checked' : ''); ?>/><br/>
		<label>Пароль</label>
		<input type="submit" value = "Сохранить"/>
	</form>
</body>
</html>