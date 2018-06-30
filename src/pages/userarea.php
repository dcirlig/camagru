<?php
require_once  '../class/sql.class.php';
require_once  '../class/session_start.php';

$title = 'UserArea';
$js_source = ['../js/webcam_photo.js', '../js/buttons_functions.js', '../js/delete_image.js'];

if (isset($_SESSION['user_id']))
{

 echo '<div id="alert"></div>';
  require_once 'header.php';
?>
<section>
<div class="usearea_container">
  <div class="image_publish">
    <div class="row">
      <img  src="" id="photo" alt="photo">
    </div>
    <div class="row buttons-row">
      <div class="buttons col-md-12">
        <button type="submit" id="publishbutton" class="col-md-6  btn-success" onclick="add_photogallery()">Publish</button>
        <button type="submit" id="deletebutton" class="col-md-6  btn-danger" onclick="cancel_photogallery()">Cancel</button>
      </div>
    </div>
  </div>
</div>
<div class="container">
<div class="col-md-6">
  <div class="row">
    <div class="div_take_photo">
      <video id="video"></video>
      <canvas id="canvas"></canvas>
      <div class="buttons col-md-12">
        <button type="submit" id="startbutton" class="col-md-6 btn-primary">Prendre une photo</button>
        <div id="upload_photo" class="col-md-6 btn-primary label-file">
        <label for="file">Upload a image</label>
        <input type="file" class="input-file" name="pic" accept="image/png" id='file' src="" onchange="encodeImageFileAsURL(this)" onclick="this.value=null;">
        </div>
      </div>
    </div>
  </div>
  <div class="row filters">
  <h3 class="center">Please choose a filter </h3>
    <form class="form-horizontal " role="form">
      <div class="row" >
        <div class="col-md-3 col-xs-6">
          <img src="../assets/images/dog.png" class="img-responsive img-radio" draggable="false">
          <button type="button" class="btn btn-primary btn-radio active" onclick="selectOnlyThis(this.id)" id="1">Dog</button>
          <input type="checkbox" name="src" class="hidden">
        </div>
        <div class="col-md-3 col-xs-6">
          <img src="../assets/images/border_flower.png" class="img-responsive img-radio" draggable="false">
          <button type="button" class="btn btn-primary btn-radio" onclick="selectOnlyThis(this.id)" id="2">Flowers border</button>
          <input type="checkbox" name="src" class="hidden">
        </div>
        <div class="col-md-3 col-xs-6">
          <img src="../assets/images/cat.png" class="img-responsive img-radio" draggable="false">
          <button type="button" class="btn btn-primary btn-radio" onclick="selectOnlyThis(this.id)" id="3">Cat</button>
          <input type="checkbox" name="src" class="hidden">
          </div>
        <div class="col-md-3 col-xs-6">
          <img src="../assets/images/heart_border.png" class="img-responsive img-radio" draggable="false">
          <button type="button" class="btn btn-primary btn-radio" onclick="selectOnlyThis(this.id)" id="4">Hearts border</button>
          <input type="checkbox" name="src" id="4" class="hidden">
        </div>  
      </div>
    </form>
  </div>
</div>

    <div class="col-md-6 last_photos">
<?php
  $query = '
    SELECT image_url, image_id
  FROM users
  INNER JOIN galerie ON users.user_id = galerie.user_id
  WHERE galerie.user_id=:user_id
  ORDER BY galerie.date_time_photo DESC
  LIMIT 6';

  $query = $pdo->prepare($query);
  $query->execute(
    array(
      ':user_id' => $_SESSION['user_id']
    )

  );
  $count = $query->rowCount();
 
  if ($count > 0)
  {
    $result = $query->fetchAll();
      foreach ($result as $row)
      {
        ?>
        <div class="col-md-6 col-xs-6">
        <img src="<?php echo $row['image_url']?>" alt="">
        <button class="delete_last_photo" onclick="delete_image(<?php echo  $row['image_id'];?>)">Delete</button>
        </div>
        <?php
      }

    
  }
    ?>
  </div>
  </div>
</section>

<?php
require_once 'footer.php';
}
else
  header ('location: login.php');
?>
