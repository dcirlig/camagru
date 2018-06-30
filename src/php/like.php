<?php
require_once '../class/session_start.php';
if ($_POST['imageId']) {
    if (isset($_SESSION['user_id']))
    {
        $pdo = myPDO::getInstance();
        if ($_POST['imageId'])
        {
            $query = "
            
            SELECT  likes
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
                    $like = $row['likes'];
                    $like += 1;
                    $query = "
                        UPDATE galerie SET likes=:likes WHERE image_id=:image_id;
                    ";
                    $sql = $pdo->prepare($query);
                    $sql->execute(
                        array(
                            ':likes'     => $like,
                            ':image_id'     => $_POST['imageId']
                        )
                    );
                }
            }
        }
    }
    else
    {
        $arr = ["error" => "You need to sign in or sign up before continuing."];
        echo json_encode($arr);
    }
    
}
?>
