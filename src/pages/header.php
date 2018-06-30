
<html>
<head>
<title><?php

    if(isset($title) && !empty($title))
    {
        echo $title; 
    }
    else
    { 
        echo "Camagru"; 
	} ?></title>
	<?php if (isset($_SESSION['user_id'])) {
		if (isset($_SESSION['theme']))
		{
			if ($_SESSION['theme'] == 'Default')
				echo '<link rel="stylesheet" type="text/css" href="../assets/css/default.css" id="css2"/>';
			else if ($_SESSION['theme'] == 'Pink')
				echo '<link rel="stylesheet" type="text/css" href="../assets/css/pink.css" id="css3"/>';
			else if ($_SESSION['theme'] == 'Green')
				echo '<link rel="stylesheet" type="text/css" href="../assets/css/green.css" id="css4"/>';
			else if ($_SESSION['theme'] == 'Blue')
				echo '<link rel="stylesheet" type="text/css" href="../assets/css/blue.css" id="css5"/>';
		}
		?>
	<link rel="stylesheet" type="text/css" href="<?php echo "../assets/css/style.css";?>" id="css1"/>
	<?php }
	else {
		?>
	<link rel="stylesheet" type="text/css" href="<?php echo "../assets/css/style.css";?>" id="css1"/>
	<link rel="stylesheet" type="text/css" href="<?php echo "../assets/css/default.css";?>" id="css2"/>
	<?php
	}
	?>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.0/css/bulma.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css" integrity="sha384-G0fIWCsCzJIMAVNQPfjH08cyYaUtMwjJwqiRKxxE/rx96Uroj1BtIQ6MLJuheaO9" crossorigin="anonymous">
</head>
<body>
<?php
if (isset($_SESSION['user_id']))
{
	$pdo = myPDO::getInstance();
	$query = "
		SELECT user_id, firstname, lastname, username FROM users
		WHERE user_id=:user_id
		";
	$sql = $pdo->prepare($query);
	$sql->execute(
			array(
				':user_id' => $_SESSION['user_id']
				)
			);
	$count = $sql->rowCount();
	if ($count > 0)
	{
	  $result = $sql->fetchAll();
	  foreach($result as $row)
		$login = $row['username'];
	}
?>
	<header class="navbar-inverse navbar-fixed-top">
  		<div class="container-fluid">
    		<div class="navbar-header">
      			<a class="navbar-brand" href="galerie.php">Camagru</a>
    		</div>
			<ul class="nav navbar-nav">
				<li><a href="userarea.php"><i class="fa fa-home"><?php echo ' @'.$login?></i></a></li>
				<li><a href="user_galerie.php"><i class="fa fa-images"></i></a></li>
				<li><a href="settings.php"><i class="fa fa-cog"></i></a></li>    
				<li><a href="logout.php"><i class="fa fa-sign-out-alt"></i></a></li>
		  	</ul>
  		</div>
	</header>
<?php
}
else
{ ?>
	<header class="navbar-inverse navbar-fixed-top">
  		<div class="container-fluid">
    		<div class="navbar-header">
      			<a class="navbar-brand" href='galerie.php'>Camagru</a>
    		</div>
		<ul class="nav navbar-nav">
	  		<li><a href="login.php">Log in</a></li>
			<li><a href="register.php">Register</a></li>
    	</ul>
  		</div>
	</header>
<?php
}