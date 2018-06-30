function delete_image(id)
{
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/delete_image.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("imageId=" + id);
    xhr.onreadystatechange = function(event) {
      if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
          if ((this.responseText))
          {
            var div = document.createElement("div");
            var p = document.createElement("p");
            var alert = document.getElementById("alert");
            if (JSON.parse(this.responseText).error)
            {
              var text2 = document.createTextNode(JSON.parse(this.responseText).error);
              div.className = "alert alert-danger";
              p.className = "p_danger";
              p.appendChild(text2);
              div.appendChild(p);
              alert.appendChild(div);
              var p_success = document.getElementsByClassName("p_danger");
            }
            else if (JSON.parse(this.responseText).succes)
            {
              var text2 = document.createTextNode(JSON.parse(this.responseText).succes);
              div.className = "alert alert-success";
              p.className = "p_danger";
              p.appendChild(text2);
              div.appendChild(p);
              alert.appendChild(div);
              var p_success = document.getElementsByClassName("p_danger");
            }
            
            if (p_success[0]) {
              setTimeout(function() {
                var div = p_success[0].parentElement;
                div.remove();
              }, 2000);
            }
            setTimeout(function(){
              window.location.reload(1);
           }, 2000);
          }
        }
      }
    };
}