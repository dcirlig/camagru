<?php
require_once '../class/session_start.php';
if ($_POST['imageId']) {
    if (isset($_SESSION['user_id']))
    {
        $pdo = myPDO::getInstance();
        
            $query = "
            
                SELECT image_id, user_id, image_url
                FROM galerie
                WHERE image_id=:image_id AND user_id=:user_id
                ";
            $sql = $pdo->prepare($query);
            $sql->execute(
                array(
                    ':image_id' => htmlspecialchars($_POST['imageId']),
                    ':user_id' => $_SESSION['user_id']
                    )
                );
            $count = $sql->rowCount();
            if ($count > 0)
            {
                $result = $sql->fetchAll();
                foreach($result as $row)
                {
                    $image_id = $row['image_id'];
                    $user_id = $row['user_id'];
                    $img_url = $row['image_url'];
                    $query = "
            
                    DELETE
                    FROM galerie
                    WHERE image_id=:image_id
                    ";
                $sql = $pdo->prepare($query);
                $sql->execute(
                    array(
                        ':image_id' => $image_id,
                        )
                    );

                    $file = str_replace("http://localhost:8080/", "../../", $img_url);
                    if (file_exists($file))
                        unlink($file);
                }
                $arr = ["succes" => "your photo has been successfully deleted"];
                echo json_encode($arr);
            }
            else
            {
                $arr = ["error" => "Forbidden. You can only delete the pictures that belong to you."];
                echo json_encode($arr);
            }

    }
    else
    {
        $arr = ["error" => "You need to sign in or sign up before continuing."];
        echo json_encode($arr);
    }
    
}
?>
