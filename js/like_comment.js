function like_commentAjax(comment_id) {
    let xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        "./func/ajax/ajax_like_comment_add.php",
        true
    );
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if(this.status == 200) {
        }
    }
    xhr.send("comment_id="+comment_id);
}