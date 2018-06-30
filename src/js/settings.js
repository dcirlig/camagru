function upload_photo_profil(element) {
    var filesSelected = element.files;
    if (filesSelected.length > 0) {
      var fileToLoad = filesSelected[0];
      var fileReader = new FileReader();
      fileReader.onload = function(fileLoadedEvent) {
        var srcData = fileLoadedEvent.target.result;
        if (srcData.match(/data:image\/png;base64/))
        {
            var data = srcData.replace("data:image/png;base64,", "");
            var type = "png";
        }
        else if (srcData.match(/data:image\/jpg;base64/) || srcData.match(/data:image\/jpeg;base64/))
        {
            var data = srcData.replace("data:image/jpeg;base64,", "");
            var type = "jpeg";
        }
        if (data)
        {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../php/upload_photo_profil.php", true);
            xhr.setRequestHeader(
              "Content-Type",
              "application/x-www-form-urlencoded"
            );
            xhr.send("base64=" + data + "&type=" + type);
            xhr.onreadystatechange = function(event) {
              if (this.readyState === XMLHttpRequest.DONE) {
                if (this.status === 200) {
                  if (this.responseText)
                  {
                    var photo = document.getElementById('photo_profil_settings');
                    photo.setAttribute("src", JSON.parse(this.responseText).file);
                  }
                  else
                  {
                    var div = document.createElement("div");
                    var p = document.createElement("p");
                    var text2 = document.createTextNode(
                      "Incorect format image. Please chose an image!"
                    );
                    div.className = "alert alert-danger";
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
                  }
                }
              }
            };
        }
        else
        {
            var div = document.createElement("div");
            var p = document.createElement("p");
            var text2 = document.createTextNode(
              "Incorect format image. Please chose an image!"
            );
            div.className = "alert alert-danger";
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
        }
            
            
      };
      if (fileToLoad) fileReader.readAsDataURL(fileToLoad);
    }
  }
let toto = 0;
  function change_theme(id)
  {
    var sel = document.getElementById(id);
    for ( var i = 0, len = sel.options.length; i < len; i++ ) {
      if (sel.options[i].selected == true)
      {
        var opt = sel.options[i];
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../php/theme.php", true);
        xhr.setRequestHeader(
          "Content-Type",
          "application/x-www-form-urlencoded"
        );
        xhr.send("theme=" + opt.text);
        xhr.onreadystatechange = function(event) {
          if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 200) {
              var div = document.createElement("div");
              var p = document.createElement("p");
              var text2 = document.createTextNode('You have successfully changed the theme');
              div.className = "alert alert-success";
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
              setTimeout(function(){
                window.location.reload(1);
             }, 2000);
            }
          }
        };
      }
    }       
  }