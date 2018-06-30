// var like = 0;
function like_photo(id){
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/like.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("imageId=" + id);
    xhr.onreadystatechange = function(event) {
      if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
          if ((this.responseText))
          {
            var div = document.createElement("div");
            var p = document.createElement("p");
            var text2 = document.createTextNode(JSON.parse(this.responseText).error);
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
          }
          else
          location.reload();
        }
      }
    };
}