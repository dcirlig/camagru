<?php
 require_once  '../class/sql.class.php';

if (!isset($_GET['log']) || !isset($_GET['cle']))
{
    header ('location: register.php');
}
else
{
    $pdo = myPDO::getInstance();

    $login = htmlspecialchars($_GET['log']);
    $cle = htmlspecialchars($_GET['cle']);
    $sql = $pdo->prepare("SELECT user_id FROM users WHERE username=:username AND user_activation_code=:user_activation_code AND user_email_status=:user_email_status");
    $sql->execute(
        array(
            ':username'             => $login,
            ':user_activation_code' => $cle,
            ':user_email_status'    => 'not verified'
        )
    );
    $sql->execute();
    $count_row = $sql->rowCount();

    if ($count_row > 0)
    {
        $sql = $pdo->prepare("UPDATE users SET user_email_status=:user_email_status, user_activation_code=:user_activation_code WHERE username=:username");
        $sql->execute(
            array(
                ':user_email_status'    => 'verified',
                ':user_activation_code' => '',
                ':username'             => $login
            )
        );
        echo "Your email has been verified! You can log in now!";
        echo "<a href='../pages/login.php' class='loginlink'>Log in</a>";
    }
    else
    {
        header ('location: ../pages/register.php');
    }
}
?>