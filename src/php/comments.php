<?php
require_once '../class/session_start.php';

if (isset($_SESSION['user_id']))
{
    if ($_POST['imageId'] && $_POST['value'])
    {
        $value_comm = htmlspecialchars($_POST['value']);
        $time = time();
        $image_id = htmlspecialchars($_POST['imageId']);
        $user_id = htmlspecialchars($_SESSION['user_id']);
        $pdo = myPDO::getInstance();
        $query = "
            INSERT INTO `comments` (`comment_content`, `date_time_comment`, `image_id`, user_id)
            VALUES (:content, :time, :image_id, :user_id)
            ";
        $sql = $pdo->prepare($query);
        $sql->execute(
            array(
                ':content'          => $value_comm,
                ':time'             => $time,
                ':image_id'         => $image_id,
                ':user_id'          => $user_id
                )
            );
        $count = $sql->rowCount();
        if ($count > 0)
        {
            $query = "
            
            SELECT galerie.user_id
            FROM galerie
            INNER JOIN comments ON galerie.image_id = comments.image_id
            WHERE galerie.image_id=:image_id
            ";
            $sql = $pdo->prepare($query);
            $sql->execute(
                array(
                    ':image_id' => $_POST['imageId']
                )
            );
            $count = $sql->rowCount();
            if ($count > 0)
            {
                $result = $sql->fetchAll();
                foreach($result as $row)
                    $user_id = $row['user_id']; 
            }
            $query = "
            
            SELECT  comments
            FROM galerie
            WHERE image_id=:image_id
            ";
            $sql = $pdo->prepare($query);
            $sql->execute(
                array(
                    ':image_id' => $_POST['imageId']
                )
            );
            $count = $sql->rowCount();
            if ($count > 0)
            {
                $result = $sql->fetchAll();
                foreach($result as $row)
                {
                    $comment = $row['comments'];
                    $comment += 1;
                    $query = "
                        UPDATE galerie SET comments=:comments WHERE image_id=:image_id;
                    ";
                    $sql = $pdo->prepare($query);
                    $sql->execute(
                        array(
                            ':comments'     => $comment,
                            ':image_id'     => $_POST['imageId']
                        )
                    );
                }

                $query = "
                SELECT notif_comment, email, username
                FROM users
                WHERE user_id=:user_id
                ";
                $sql = $pdo->prepare($query);
                $sql->execute(
                    array(
                        ':user_id' => $user_id
                    )
                );
                $count = $sql->rowCount();
                if ($count > 0)
                {
                    $result = $sql->fetchAll();
                    foreach($result as $row)
                    {
                        $notif = $row['notif_comment'];
                        $email = $row['email'];
                        $login = $row['username'];
                    }
                    if ($notif == 'yes')
                    {
                        $destinataire = $email;
                        $sujet = "Vous avez recu un nouveau commentaire" ;
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= "From: Camagru" ;
                        $message =" 
                        <div class='row'>
                            <div class='container'>
                                <p><strong>@$login</strong>, Vous avez recu un nouveau commentaire !</p>
                                <p>Ceci est un mail automatique. </p>
                                <p><strong>Merci de ne pas y repondre!</strong></p>
                            </div>
                        </div>";             
                         
                        mail($destinataire, $sujet, $message, $headers) ;
                    }
                }
                
                
                $arr = ["succes" => "You add a new comment"];
                echo json_encode($arr);
            }
        }
    }
    else
    {
        $arr = ["error" => "Please add comment."];
        echo json_encode($arr);
    }
}
else
{
    $arr = ["error" => "You need to sign in or sign up before continuing."];
    echo json_encode($arr);
}

?>
