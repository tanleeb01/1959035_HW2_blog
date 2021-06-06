let commentform = document.querySelector("#comment_form");
let pausePrintComment = false;
let comment_list_limit = 5;

setInterval(function(){
    if (pausePrintComment == false){
        let xhr = new XMLHttpRequest();
        xhr.open(
            "GET",
            "./func/ajax/ajax_comment_list.php",
            true
        );
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if(this.status == 200) {
                comment_list = JSON.parse(this.responseText);
                printComment(comment_list); 
            }
        }
        xhr.send();
    }
    else if (document.querySelector("#comment_div").querySelectorAll("input").length < 1)pausePrintComment = false;
}, 300);

commentform.addEventListener("submit", e=> {
    e.preventDefault();
    let commentinput = commentform.querySelector("textarea");
    if (commentinput.value!='') commentAjax(commentinput);
  })

function commentAjax(commentinput) {
    let xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        "./func/ajax/ajax_comment_add.php",
        true
    );
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if(this.status == 200) {
            if (this.responseText == true) commentinput.value = '';            
        }
    }
    xhr.send("comment_content=" + commentinput.value);
}

function printComment(comment_list){
    
    let load_more = (comment_list_limit < comment_list.length)?true:false;

    comment_list = comment_list.slice(0,comment_list_limit);

    let commentdiv = document.querySelector("#comment_div");    
    let innerhtml = '';
    comment_list.forEach(comment => {
        let btn_icon = '';     
        if (comment['user_liked']) btn_icon = `<i class="bi bi-heart-fill">${comment['comment_likes']}</i>`;
        else btn_icon = `<i class="bi bi-heart">${comment['comment_likes']}</i>`;

        innerhtml+=`
        <div class="container d-flex">
            <div class="comment_info_div"> 
                <div class="comment_thumbnail_div d-flex justify-content-center align-items-center">
                    <div class= "comment_thumbnail_border_wrapper">
                        <div class="comment_thumbnail_border">
                            <img class="comment_thumbnail" src='${comment['user_img']}'></img>
                        </div>
                    </div>
                </div>

                <div class="comment_info">
                    <svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="7 0 100 100">
                        <!-- Path for text -->
                        <path id="top-sector" style="fill:none;stroke:none" d="
                        M 13,50 
                        A 46,60 0 0 1 100.5,50" />        
                        <path id="bottom-sector" style="fill:none;stroke:none" d="
                        M 9,50 
                        A 46,46.5 0 0 0 100.8,50"/>

                        <a href='user.php?user_id=${comment['user_id']}'>
                        <text text-anchor="end">                    
                            <textPath class="commenter" xlink:href="#top-sector" startOffset="40%">${comment['user_name']}</textPath>
                        </text>   
                        </a>

                        <text text-anchor="start">                  
                            <textPath class="comment_time" xlink:href="#bottom-sector" startOffset="40%">${comment['comment_time']}</textPath>
                        </text>
                    </svg>
                </div>
            </div>
            
            <div class="d-flex flex-column justify-content-center">
                <p>${comment['comment_content']}</p>

                <div class="btns_div">
                    <button class ="button-1 btn_like" onclick="like_commentAjax('${comment['comment_id']}')">${btn_icon}</button>`;

    if (comment['user_id'] == session['user_id']) {
        innerhtml += `<button class ="button-1 btn_edit" onclick="editComment(this.parentNode.parentNode,'${comment['comment_id']}')"><i class="bi bi-pen"></i></i></button>`;
    }
    if (session['user_role'] == 1) {
        innerhtml += `<button class ="button-1 btn_admin_delete" onclick="deleteCommentAjax('${comment['comment_id']}')"><i class="bi bi-trash2-fill"></i></button>`;
    }

    innerhtml+=`</div>
            </div>
        </div>`;
    });

    commentdiv.innerHTML = innerhtml;

    if (load_more){
        let btn_load = document.createElement('button');
        btn_load.className = "button-1 float-right";
        btn_load.innerHTML = "load more";        
        btn_load.onclick = function(){
            comment_list_limit += 5;
        };
        commentdiv.appendChild(btn_load);
    }
}

function editComment(commentdiv,comment_id){
    pausePrintComment = true;
    commentdiv.querySelectorAll(".btn_like, .btn_edit, .btn_admin_delete").forEach(e=>{e.style.display = "none";});

    let p = commentdiv.querySelector("p");
    let input = document.createElement('input');
    input.value = p.innerHTML;
    commentdiv.replaceChild(input,p);

    let submitbtn = document.createElement("button");
    submitbtn.className = "button-1";
    submitbtn.innerHTML = '<i class="bi bi-check2"></i>';
    submitbtn.onclick = function(){ 
        if (input.value!='') editCommentAjax(input,comment_id)
    };

    let deletebtn = document.createElement("button");
    deletebtn.className = "button-1";
    deletebtn.innerHTML = '<i class="bi bi-trash2"></i>';
    deletebtn.onclick = function(){
        deleteCommentAjax(comment_id);
    };

    let cancelbtn = document.createElement("button");
    cancelbtn.className = "button-1";
    cancelbtn.innerHTML = '<i class="bi bi-x"></i>';
    cancelbtn.onclick = function(){  
        commentdiv.replaceChild(p,input);
        commentdiv.querySelectorAll(".btn_like, .btn_edit, .btn_admin_delete").forEach(e=>{e.style.display = "inline-block";});
        submitbtn.style.display = "none";
        deletebtn.style.display = "none";
        cancelbtn.style.display = "none";
        return;
    };
    
    /*commentdiv.insertBefore(submitbtn,input.nextSibling);
    commentdiv.insertBefore(deletebtn,submitbtn.nextSibling);
    commentdiv.insertBefore(cancelbtn,deletebtn.nextSibling);*/
    commentdiv.querySelector(".btns_div").appendChild(submitbtn);
    commentdiv.querySelector(".btns_div").appendChild(deletebtn);
    commentdiv.querySelector(".btns_div").appendChild(cancelbtn);
}

function editCommentAjax(commentinput,comment_id) {
    console.log(comment_id);
    let xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        "./func/ajax/ajax_comment_edit.php",
        true
    );
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if(this.status == 200) {                       
            pausePrintComment=false;
        }
    }
    xhr.send("comment_content="+commentinput.value+"&comment_id="+comment_id);
}

function deleteCommentAjax(comment_id) {
    let xhr = new XMLHttpRequest();
    xhr.open(
        "POST",
        "./func/ajax/ajax_comment_delete.php",
        true
    );
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if(this.status == 200) {                       
            pausePrintComment=false;
        }
    }
    xhr.send("comment_id=" + comment_id);   
}