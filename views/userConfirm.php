<!DOCTYPE html>
<html>
<head>
	<title>Подтверждение почты пользователя</title>
</head>
<body>
	<a href='/user/login'>Вход в систему</a><br/>
	<form name="user_confirm_form" action='/user/sendConfirmLetter' method="POST">
		<h3>К сожалению, что-то пошло не так... </h3>
		<p>Введите адрес электронной почты, указанный при регистрации, мы вышлем письмо повторно.</p>
		<input type="text" name="login" /><br/>
		<input type="button" name="button" value = "Выслать повторно" />
	</form>
</body>
</html>