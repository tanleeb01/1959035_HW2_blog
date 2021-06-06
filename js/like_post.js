let like_post = document.querySelector("#like_post");

setInterval(function(){
    let xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        "./func/ajax/ajax_like_post_list.php",
        true
    );
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if(this.status == 200) {
            let result = JSON.parse(this.responseText);
            like_post.querySelector("i").innerHTML = result['count'];
            if (result['liked']) like_post.querySelector("i").className = "bi bi-heart-fill";
            else like_post.querySelector("i").className = "bi bi-heart";
        }
    }
    xhr.send();
}, 200);

like_post.addEventListener("click",e=>{
    e.preventDefault();
    like_postAjax(like_post);
})

function like_postAjax(like_post) {
    let like_data = JSON.parse(like_post.value);
    let xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        "./func/ajax/ajax_like_post_add.php",
        true
    );
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if(this.status == 200) {
        }
    }
    xhr.send("post_id="+like_data['post_id']);
}
