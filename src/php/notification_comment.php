<?php
require_once '../class/session_start.php';
if (isset($_POST['checked']))
{
    $pdo = myPDO::getInstance();
    $checked = htmlspecialchars($_POST['checked']);
            $query = "
            UPDATE users SET notif_comment=:value WHERE user_id=:user_id;
            ";
            $sql = $pdo->prepare($query);
            $sql->execute(
              array(
                  ':value'       => $checked,
                  ':user_id'     => $_SESSION['user_id']
                  )
              );
          $count = $sql->rowCount();
          if ($count > 0)
          {
            if ($checked == 'yes')
                $arr = ["succes" => "You have successfully enabled notification comments"];
            else if ($checked == 'no')
                $arr = ["succes" => "You have successfully disabled notification comments"];
            echo json_encode($arr);
          }
}
?>