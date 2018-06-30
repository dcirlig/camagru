<?php

require_once  '../class/sql.class.php';
require_once  '../class/session_start.php';

$js_source = ['../js/like.js', '../js/comments.js', '../js/delete_image.js'];
$title = 'Galerie';

if (isset($_SESSION['user_id']))
{
  echo '<div id="alert"></div>';
  require_once 'header.php';
  $pdo = myPDO::getInstance();
  
  $query = "
  
  SELECT * FROM users
  INNER JOIN galerie ON users.user_id = galerie.user_id
  WHERE galerie.user_id=:user_id
      ";
  $sql = $pdo->prepare($query);
  $sql->execute(
    array(
      ':user_id' => $_SESSION['user_id']
    )

  );
  $count = $sql->rowCount();
  $imagesonpage = 5;
  $totalimages = $count;
  $totalpage = ceil($count / $imagesonpage);
  if (isset($_GET['page']) && $_GET['page'])
  {
    $_GET['page'] = intval($_GET['page']);
    if ($_GET['page'] > 0)
      $currentpage = $_GET['page'];
    else
      $currentpage = 1;
  }
  else
  {
    $currentpage = 1;
  }

  $start = ($currentpage - 1) * $imagesonpage;

  $query = '
  
  SELECT firstname, lastname, username, image_url, likes, image_id, date_time_photo, comments, photo_profil_url
  FROM users
  INNER JOIN galerie ON users.user_id = galerie.user_id
  WHERE galerie.user_id=:user_id
  ORDER BY galerie.date_time_photo DESC
  LIMIT '.$start.','.$imagesonpage;

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
    echo "<section>";
    foreach($result as $row)
    {
      date_default_timezone_set('Europe/Paris');
      $fname = $row['firstname'];
      $lname = $row['lastname'];
      $login = $row['username'];
      $url_image = $row['image_url'];
      $like = $row['likes'];
      $image_id = $row['image_id'];
      $time = str_replace("T", " ", date("Y-m-d\TH:i", $row['date_time_photo']));
      $comment = $row['comments'];
      $photo_profil = $row['photo_profil_url'];
      ?>
<div class="row">
  <div class="container-card" id="<?php echo $image_id?>">
  
    <div class="card">
      <div class="card-header">
        <div class="media-left">
              <figure class="image is-48x48">
              <img src="<?php echo $photo_profil?>" alt="Placeholder image" draggable="false">
            </figure>
          </div>
          <div class="media-content">
            <p class="title is-4"><?php echo $fname." ".$lname?></p>
            <p class="subtitle is-6"> <?php echo "@".$login ?>
            <p class="subtitle is-6"><?php echo $time?></p>
            </p>
          </div>
      </div>
      <div class="card-body">
        <img class="card-img-top" src="<?php echo $url_image?>" alt="Card image" draggable="false">
        <div class="media-content">
          <ul class="list-inline">
            <li class="list-inline-item">
              <i class="far fa-heart" aria-hidden="true" onclick="like_photo(<?php echo $image_id;?>)"></i><?php echo " ".$like?>
            </li>
            <li class="list-inline-item">
              <i class="far fa-comments" aria-hidden="true" onclick="show_comment(<?php echo $image_id;?>)"></i><?php echo " ".$comment?>
            </li>
            <li class="media-right">
              <i class="fa fa-trash" aria-hidden="true" onclick="delete_image(<?php echo $image_id;?>)"></i>
            </li>
          </ul>
        </div>
      </div>
      <div id="<?php echo $image_id;?>"></div>
      <div id="comments_container">
      <?php
       $query = "
       SELECT username, comment_content, date_time_comment
       FROM galerie
       INNER JOIN comments ON galerie.image_id = comments.image_id
       INNER JOIN users ON comments.user_id = users.user_id
       WHERE comments.image_id=:image_id
       ORDER BY comments.date_time_comment DESC
           ";
       $sql = $pdo->prepare($query);
       $sql->execute(
         array (
          ':image_id' => $image_id
       )
      );
       $count = $sql->rowCount();
       if ($count > 0)
       {
         $result = $sql->fetchAll();
         $nr = 0;
         foreach($result as $row)
         {
            $nr += 1;
            date_default_timezone_set('Europe/Paris');
            $username = $row['username'];
            $content = $row['comment_content'];
            $time = str_replace("T", " ", date("Y-m-d\TH:i", $row['date_time_comment']));
           if ($nr > 3)
           {
            ?>
            <p class="subtitle is-6 display_none"><?php echo $time?></p>
            <p class="display_none"><strong><?php echo $username."  "?> </strong> <?php echo $content?></p>
         <?php
           }
           else
           {
            ?>
            <p class="subtitle is-6"><?php echo $time?></p>
            <p><strong><?php echo $username."  "?> </strong> <?php echo $content?></p>
         <?php
           }
           
     }
     ?>
       <?php }
       ?>
      </div>

      <div class="card-footer">
      
        <div class="add_comment_form">
          <textarea value="" aria-label="Add a comment..." name="textarea" placeholder="Add a comment..."  autocomplete="off" autocorrect="off" class="card-textarea <?php echo $image_id;?>"></textarea></textarea>
          <button  class="add_comment_button btn btn-primary" onclick="add_comment(<?php echo $image_id?>)">Add</button>
        </div>
      </div>
    </div>
  </div>
</div>
    <?php
    }
    echo "<div class='row'>";
    echo "<div class='pagination'>";
    for ($i=1; $i <= $totalpage; $i++)
    {
      if ($i == $currentpage)
        echo '<a class="active" href="user_galerie.php?page='.$i.'">'.$i.'</a>';
      else
        echo '<a href="user_galerie.php?page='.$i.'">'.$i.'</a>';
      
    }
    echo "</div>";
    echo "</div>";
    echo "</section>";
  }


?>
<?php
require_once 'footer.php';
}
else
  header ('location: login.php');
?>
