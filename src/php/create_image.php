<?php
require_once '../class/session_start.php';

    if (isset($_POST['base64']) && isset($_POST['src']))
    {
         if (!is_dir('../../img_users'))
         {
            mkdir('../../img_users');
            if (!is_dir('../../img_users/'.$login))
            mkdir('../../img_users/'.$login);
         }
           
         else
         {
         if (!is_dir('../../img_users/'.$login))
             mkdir('../../img_users/'.$login);
         }
       
        $name_img = md5(rand());
        $data = str_replace(" ", "+", htmlspecialchars($_POST['base64']));
        $plainText = base64_decode($data);
        $filename = '../../img_users/'.$login.'/'.$name_img.'.png';
        file_put_contents($filename, $plainText);
        if (exif_imagetype($filename) == 3)
        {
            $filter = htmlspecialchars($_POST['src']);
            $source = imagecreatefrompng($filter);
           $destination = imagecreatefrompng($filename);
           
            $largeur_source = imagesx($source);
            $hauteur_source = imagesy($source);
            
            $largeur_destination = imagesx($destination);
            $hauteur_destination = imagesy($destination);
            $destination_x = $largeur_destination / 2 - $largeur_source / 2;
            $destination_y =  $hauteur_destination / 2 - $hauteur_source / 2;
            imagecopy($destination, $source, $destination_x, $destination_y, 0, 0, $largeur_source, $hauteur_source); 
            
            imagepng($destination, $filename);
            $arr = ["file" => $filename];
            echo json_encode($arr);
        }
    }
?>