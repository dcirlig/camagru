<?php
require_once '../class/session_start.php';

if (isset($_POST['publish']))
{
    $file = htmlspecialchars($_POST['publish']);
    $time = time();
    $pdo = myPDO::getInstance();
    $query = "
        INSERT INTO `galerie` (`image_url`, `user_id`, `date_time_photo`, likes, comments)
        VALUES (:image_url, :user_id, :date_time_photo, :likes, :comments)
        ";
    $sql = $pdo->prepare($query);
    $sql->execute(
        array(
            ':image_url'       => $file,
            ':user_id'         => $_SESSION["user_id"],
            ':date_time_photo' => $time,
            ':likes'           => 0,
            ':comments'        => 0
            )
        );
    $count_image = $sql->rowCount();
    if ($count_image > 0)
    {
        $arr = ["succes" => "The photo has been saved successfully."];
        echo json_encode($arr);
    }
}
else if (isset($_POST['cancel']))
{
    $file = $_POST['cancel'];
    if (file_exists($file))
    {
        unlink($file);
        $arr = ["succes" => "You not save the photo."];
        echo json_encode($arr);
    }
        
}