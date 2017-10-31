<!DOCTYPE html>
<html>
<head>
	<title>Список Пользователей</title>
</head>
<body>
	<a href='/user/save'>Создать пользователя</a><br/>
	<table>
		<trh>
			<tdh>#</tdh>
			<tdh>ФИО</tdh>
			<tdh>Должность</tdh>
			<tdh>Описание</tdh>
			<tdh>email</tdh>
			<tdh>Телефон</tdh>
		</trh>
		<?php if(empty($users)): ?>
			<tr><p>Как-то пустовато у вас в компании...</p></tr>
		<?php endif; ?>
		<?php foreach($users as $user): ?>
			<tr>
				<td><?php echo $user->id; ?></td>
				<td><?php echo $user->name; ?></td>
				<td><?php echo $user->role; ?></td>
				<td><?php echo $user->description; ?></td>
				<td><a href="/user/saveRights/<?= $user->id ?>">Изменить</a></td>
				<td><a href="/user/delete/<?= $user->id ?>">Удалить</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
</body>
</html>