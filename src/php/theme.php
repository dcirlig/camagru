<?php 
require_once '../class/session_start.php';
if (isset($_SESSION['user_id']))
{
    if (isset($_POST['theme']))
    {
        $query = "
        UPDATE users SET theme=:theme WHERE user_id=:user_id;
        ";
        $sql = $pdo->prepare($query);
        $sql->execute(
        array(
            ':theme'     => htmlspecialchars($_POST['theme']),
            ':user_id'              => $_SESSION['user_id']
        )
        );
        $_SESSION['theme'] = htmlspecialchars($_POST['theme']);
    }
}
?>