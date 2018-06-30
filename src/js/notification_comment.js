function myFunction() {
    var checkBox = document.getElementById("myCheck");
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/notification_comment.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    if (checkBox.checked == true){
        xhr.send("checked=yes");
    } else {
        xhr.send("checked=no");
    }
    xhr.onreadystatechange = function(event) {
      if (this.readyState === XMLHttpRequest.DONE) {
        if (this.status === 200) {
          if ((this.responseText))
          {
            var div = document.createElement("div");
            var p = document.createElement("p");
            var text2 = document.createTextNode(JSON.parse(this.responseText).succes);
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
          }
        }
      }
    };
}