<?php

require_once  '../class/sql.class.php';

$title = 'Register';

if (isset($_POST['fname']) && $_POST['fname'] && isset($_POST['lname']) && $_POST['lname'] && 
    isset($_POST['useremail']) && $_POST['useremail'] && isset($_POST['username']) && $_POST['username'] &&
    isset($_POST['usrpasswd']) && $_POST['usrpasswd'] && isset($_POST['submit']) && $_POST['submit'] == 'OK')
    {
        $pdo = myPDO::getInstance();
        
        $values = [
            'firstname' => htmlspecialchars($_POST['fname']),
            'lastname' => htmlspecialchars($_POST['lname']),
            'email' => htmlspecialchars($_POST['useremail']),
            'username' => htmlspecialchars($_POST['username']),
            'passwd' => htmlspecialchars($_POST['usrpasswd'])
        ];

        $error = [];

        $sql = $pdo->prepare("SELECT username, email FROM users");
        $sql->execute();
        $result = $sql->fetchAll();
        foreach($result as $row) {
            if ($values['username'] === $row['username'])
                $error['usernameexist'] = "This username is used! Please choose another username!";
            if ($values['email'] === $row['email'])
                $error['emailexist'] = "This email is used! Please choose another email!";

        }

        if (!preg_match('/^[a-zA-Z]+$/', $values['firstname']) || empty($values['firstname'])){
            $error['firstname'] = "Invalid first name! Your first name must contain only upper and lower case letters!";
        }

        if (!preg_match('/^[a-zA-Z]+$/', $values['lastname']) || empty($values['lastname'])){
            $error['lastname'] = "Invalid last name! Your last name must contain only upper and lower case letters!";
        }

        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL) || empty($values['email'])){
            $error['email'] = "Invalid email!";
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $values['username']) || empty($values['username'])){
            $error['username'] = "Forbidden characters! Your username can only contain letters, numbers or '_'!";
        }

        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $values['passwd']) || empty($values['passwd'])){
            $error['passwd'] = "Wrong password! Your password must contain at least 1 number, 1 lowercase and 1 upper case letter";
        }

        if (empty($error))
        {
            $user_activation_code = md5(rand()); 
            $user_reset_passwd_code = md5(rand());           
            $values['passwd'] = hash('whirlpool', $values['passwd']);
            $sql = $pdo->prepare(<<<SQL
            INSERT INTO `users` (`firstname`, `lastname`, `email`, `username`, `passwd`, `user_activation_code`, `user_reset_passwd_code`, `user_email_status`)
            VALUES (:firstname, :lastname, :email, :username, :passwd, :user_activation_code, :user_reset_passwd_code, :user_email_status);
SQL
);
            if ($sql->execute(
                array(
                    ':firstname' => $values['firstname'],
                    ':lastname' => $values['lastname'], 
                    ':email' => $values['email'],
                    ':username' => $values['username'],
                    ':passwd' => $values['passwd'],
                    ':user_activation_code' => $user_activation_code,
                    'user_reset_passwd_code'=> '',
                    ':user_email_status' => 'not verified'
                    )
                ))

            {
                $email = $values['email'];
                $login = $values['username'];
                $sql = $pdo->prepare("UPDATE users SET user_activation_code=:user_activation_code WHERE username like :username");
                $sql->bindParam(':user_activation_code', $user_activation_code);
                $sql->bindParam(':username', $login);
                $sql->execute();
                $destinataire = $email;
                $sujet = "Activer votre compte" ;
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= "From: Camagru" ;
                $message =" 
                <div class='row'>
                    <div class='container'>
                        <p><strong>@$login</strong>, Bienvenue sur VotreSite !</p>
                        <p>Pour activer votre compte, veuillez cliquer sur le button ci dessous</p>
                        <a href='http://localhost:8080/camagru/src/php/email_verification.php?log=".urlencode($login)."&cle=".urlencode($user_activation_code)."'>
                        <button type='button' style='color:white;background-color:#ff9f1c;padding:10px;cursor:pointer;border-radius:5px;'>Click Here</button></a>
                        <hr>
                        <p>Ceci est un mail automatique. </p>
                        <p><strong>Merci de ne pas y repondre!</strong></p>
                    </div>
                </div>";             
                 
                mail($destinataire, $sujet, $message, $headers) ;
                ?>
                <div class="alert alert-success">
                    <a class="close" aria-label="close">&times;</a>
                    <p>You have successfully registered your account. Please, verify your email for login!</p>
                </div>
                <?php
            }
            else
                echo "error insertions\n";
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

require_once 'header.php';
?>
<section class="section register">
    <div class="row">
        <div class="container">
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <img src="../assets/images/photoshopimage.png" draggable="false">
            </div>
            <div class="col-md-4">
                <div class="row divform"> 
                <form method="POST"  id="registerform" >
                    <h1>Camagru</h1>
                    <input type="text" name="fname" value="" placeholder="First name" pattern="^[a-zA-Z]+$" required>
                    <input type="text" name="lname" value="" placeholder="Last Name" pattern="^[a-zA-Z]+$" required>
                    <input type="email" name="useremail" value="" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required>
                    <input type="text" name="username" value="" placeholder="Username" pattern="^[a-zA-Z0-9_]+$" required>
                    <input type="password" name="usrpasswd" value="" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}" required>
                    <button type="submit" name="submit" value="OK">Sign up</button>
                </form>
                </div>
                <div class="row divform">
                     <p class="center">Have an account? <a href="login.php" class="loginlink">Log in</a></p>
                </div> 
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</section>

<?php
    require_once 'footer.php';
?>