function add_photogallery() {
  var photo = document.getElementById("photo").src;
  photo = photo.replace("http://localhost:8080/camagru", "../..");
  document.getElementsByClassName("image_publish")[0].style.display = "block";
  document.getElementsByClassName("div_take_photo")[0].style.display = "none";
  document.getElementsByClassName("last_photos")[0].style.display ="none";
  document.getElementsByClassName("filters")[0].style.display = "none";
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/publish_and_delete.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("publish=" + photo);
  xhr.onreadystatechange = function(event) {
    if (this.readyState === XMLHttpRequest.DONE) {
      if (this.status === 200) {
        var div = document.createElement("div");
        var p = document.createElement("p");
        var text2 = document.createTextNode(JSON.parse(this.responseText).succes);
        div.className = "alert alert-success";
        p.className = "p_danger";
        p.appendChild(text2);
        div.appendChild(p);
        var alert = document.getElementById("alert");
        alert.appendChild(div);
        var p_danger = document.getElementsByClassName("p_danger");
        if (p_danger[0]) {
          setTimeout(function() {
            var div = p_danger[0].parentElement;
            div.remove();
          }, 2000);
        }
        document.getElementsByClassName("usearea_container")[0].style.display ="none";
        document.getElementsByClassName("div_take_photo")[0].style.display = "block";
        document.getElementsByClassName("last_photos")[0].style.display ="block";
        document.getElementsByClassName("filters")[0].style.display = "block";
        setTimeout(function(){
          window.location.reload(1);
       }, 2000);
      }
    }
  };
}

function cancel_photogallery() {
  var photo = document.getElementById("photo").src;
  photo = photo.replace("http://localhost:8080/camagru", "../..");
  document.getElementsByClassName("usearea_container")[0].style.display = "block";
  document.getElementsByClassName("div_take_photo")[0].style.display = "none";
  document.getElementsByClassName("div_take_photo")[0].style.display = "none";
  document.getElementsByClassName("filters")[0].style.display = "none";
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../php/publish_and_delete.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.send("cancel=" + photo);
  xhr.onreadystatechange = function(event) {
    if (this.readyState === XMLHttpRequest.DONE) {
      if (this.status === 200) {
        var div = document.createElement("div");
        var p = document.createElement("p");
        var text2 = document.createTextNode(JSON.parse(this.responseText).succes);
        div.className = "alert alert-danger";
        p.className = "p_danger";
        p.appendChild(text2);
        div.appendChild(p);
        var alert = document.getElementById("alert");
        alert.appendChild(div);
        var p_success = document.getElementsByClassName("p_danger");
        if (p_success[0]) {
          setTimeout(function() {
            var div = p_success[0].parentElement;
            div.remove();
          }, 2000);
        }
        document.getElementsByClassName("usearea_container")[0].style.display = "none";
        document.getElementsByClassName("div_take_photo")[0].style.display = "block";
          document.getElementsByClassName("last_photos")[0].style.display = "block";
        document.getElementsByClassName("filters")[0].style.display = "block";
        if (close[0]) {
          setTimeout(function() {
            var div = close[0].parentElement;
            div.remove();
          }, 2000);
        }
        setTimeout(function(){
          window.location.reload(1);
       }, 2000);
      }
    }
  };
}
