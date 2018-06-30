var count = 0;
function show_comment(id){
    count += 1;
    if (count % 2 == 1)
    { 
        var p = document.getElementsByClassName('display_none');
        for (var i = 0; i < p.length; i++)
            p[i].style.display = "block";
    }
    else
    {
        var p = document.getElementsByClassName('display_none');
        for (var i = 0; i < p.length; i++)
            p[i].style.display = "none";
    }
}

function add_comment(id){
    var value = document.getElementsByClassName(id)[0].value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/comments.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("imageId=" + id + "&value=" + value);
    xhr.onreadystatechange = function(event) {
      if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
          if (JSON.parse(this.responseText).error)
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
              document.getElementsByClassName(id)[0].value = "";
            }
            else
            {
              document.getElementsByClassName(id)[0].value = "";
              location.reload();
            }
        }
      }
    };
}