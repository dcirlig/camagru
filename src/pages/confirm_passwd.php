<?php

require_once  '../class/sql.class.php';

if (!isset($_GET['log']) || !isset($_GET['cle']))
{
	header('location: register.php');
}
else
{
	$pdo = myPDO::getInstance();

	$login = htmlspecialchars($_GET['log']);
	$cle = htmlspecialchars($_GET['cle']);
	$sql = $pdo->prepare("SELECT user_id FROM users WHERE username=:username AND user_reset_passwd_code=:user_reset_passwd_code");
	$sql->execute(
		array(
			':username'             => $login,
			':user_reset_passwd_code' => $cle,
		)
	);
	$sql->execute();
	$count_row = $sql->rowCount();
	if ($count_row > 0)
	{
		if (isset($_POST['submit']))
		{
			if (isset($_POST['new_passwd']))
			{

				$values = [
					'new_passwd'	=> htmlspecialchars($_POST['new_passwd']),
					'conf_passwd'	=> htmlspecialchars($_POST['conf_passwd'])
				];

				$error = [];
				if (empty($values['new_passwd']) || empty($values['conf_passwd'])){
					$error['passwd'] = "Wrong new password! Your password must contain at least 1 number, 1 lowercase and 1 upper case letter";
				}

				if (!empty($values['new_passwd']))
				{
					if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $values['new_passwd']))
						$error['passwd'] = "Wrong new password! Your password must contain at least 1 number, 1 lowercase and 1 upper case letter";
					if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $values['conf_passwd']) || empty($values['conf_passwd']))
						$error['passwd'] = "Wrong confirm password! Your password must contain at least 1 number, 1 lowercase and 1 upper case letter";
					if ($values['new_passwd'] != $values['conf_passwd'])
						$error['passwd'] = "New password and confirm password are different. Try again!";

				}

				if (!empty($values['conf_passwd']) && empty($values['new_passwd']))
					$error['passwd'] = "Empty new password. Try again!";

				if (empty($error))
				{
					$passwd = hash('whirlpool', $values['new_passwd']);
					$sql = $pdo->prepare("UPDATE users SET passwd=:passwd, user_reset_passwd_code=:user_reset_passwd_code  WHERE username=:username");
					$sql->execute(
						array(
							':passwd'                    => $passwd,
							':user_reset_passwd_code'    => md5(rand()),
							':username'                  => $login
						)
					);

					?>
					<div class="alert alert-success">
						<a class="close" aria-label="close">&times;</a>
						<p>You have successfully changed your password</p>
					</div>
					<?php
					header ('location: login.php');
				}
				else
				{?>
					<div class="alert alert-danger">
						<a class="close" aria-label="close">&times;</a>
					<?php 
					foreach($error as $value)
						echo "Error: " . $value . "<br>";
					?>
					</div>
					<?php
				}
			}
		}
	}
	else
	{
		header ('location: register.php');
	}
}
require_once 'header.php';
?>
<section class="section login">
<div class="row">
	<div class="container">
		<div class="forgot_form">
			<div class="row divform">
			<form  method="POST" id="loginform">
				<input type="password" name="new_passwd" value="" placeholder="New password" required>
				<input type="password" name="conf_passwd" value="" placeholder="Confirm Password" required>
				<button type="submit" name="submit">Reset</button>
			</form>
			</div>
		</div>
	</div>
</div>
</section>

<?php
	require_once 'footer.php';
?>