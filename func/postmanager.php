<?php
    require_once 'func/filemanager.php';
    var_dump($_POST);
    $errors = [];

    if (!empty($_GET)){
        if (isset($_GET['post_id'])){           
            $_SESSION['post_id'] = $_GET['post_id'];

            $rows = getRows($conn,'
            SELECT p.post_id, p.post_img,u.user_id, u.user_name,DATE(p.post_time) as post_time,p.post_title,p.post_content
            FROM (
                SELECT *
                FROM posts
                WHERE post_id= ?
            ) p
            LEFT JOIN users u on p.post_author_id = u.user_id','s',array($_SESSION['post_id']));

            if (!empty($rows)){
                $row = $rows[0];            
                $_POST['img'] = $row['post_img'];
            }
        }
        else if (isset($_GET['user_id'])){ 
            $userpost_rows = getRows($conn,'SELECT * FROM posts p where p.post_author_id = ? ORDER BY p.post_time DESC','s',array($_GET['user_id']));
        }
    }

    if(isset($_POST['post_submit'])) {            
            checkPost($_POST, $errors, $conn);
    }    
    else if (isset($_POST['admin_verify'])) {
        if (checkAdmin($_POST, $errors, $conn) && isset($_GET['post_id'])){
            deletePost($_GET['post_id'],$conn);
        }
    }

    function checkPost($POST, &$errors, $conn){        
        if ($_POST['post_submit']=='delete'){
            if (setRow($conn,
            "DELETE from posts
            WHERE post_id=?",
            "s",array($_SESSION['post_id'])
            ) != false) {
                if (headers_sent()) {
                    echo '<script>window.location.href = \'index.php\';</script>';
                }
                else{
                    header('Location: index.php');
                }
            }
            return;
        }

        $title = $POST['title'];
        $content = $POST['content'];        
        $author_id = $_SESSION['user_id'];

        if ($title == '') $errors['post_title']='Please fill in the title.';
        if ($content == '') $errors['post_content']='Please fill in the content.';

        if ($_FILES['create_post_img']['tmp_name']!=''){
            $img = checkFile($_FILES['create_post_img'],"imgs/posts/",$errors);
            if ($img == false){    
                $errorMsg = "Error processing file.";
                $errors['post_file'] = $errorMsg;
            }
        } else $img=isset($POST['img'])?$POST['img']:'imgs/posts/default.png';

        // if there are no errors, insert the user into the db and login
        if(empty($errors)) {
            if ($POST['post_submit']=='create'){
                $post_id = uniqid();
                if (setRow($conn,
                "INSERT INTO posts(post_id,post_author_id,post_title,post_content,post_img) VALUES (?,?,?,?,?)",
                "sssss",array($post_id,$author_id,$title,$content,$img)
                ) != false) {
                    if (headers_sent()) {
                        echo '<script>window.location.href = \'post.php?post_id='.$post_id.'\';</script>';
                    }
                    else{
                        header('Location: post.php?post_id='.$post_id);
                    }
                }
            } else if ($POST['post_submit']=='edit') {
                if (setRow($conn,
                "UPDATE posts
                SET post_title = ?,post_content = ?,post_img = ?
                WHERE post_id=?",
                "ssss",array($title,$content,$img,$_SESSION['post_id'])
                ) != false) {
                    if (headers_sent()) {
                        echo '<script>window.location.href = \'post.php?post_id='.$_SESSION['post_id'].'\';</script>';
                    }
                    else{
                        header('Location: post.php?post_id='.$_SESSION['post_id']);
                    }
                }
                else $errors['edit_none'] = 'nothing has been edited';
            }
        }
    }

    function deletePost($post_id,$conn){        
        if (setRow($conn,
            "DELETE from posts
            WHERE post_id=?",
            "s",array($post_id)
            ) != false) {
                if (headers_sent()) {
                    echo '<script>window.location.href = \'index.php\';</script>';
                }
                else{
                    header('Location: index.php');
                }
            }
        return;
    }

    function printCarousel(){
        $dir_imgs   = "imgs/posts";
        $files_imgs = scandir($dir_imgs);        
        $class_active = true;

        foreach ($files_imgs as $key_imgs => $value_imgs){
            $php_imgs = explode(".", $value_imgs);
            $php_imgs = end($php_imgs);
            if ($php_imgs == "jpg" || $php_imgs == "jpeg" || $php_imgs == "png"){    
                echo '
                <div class="carousel-item';
                
                if($class_active == true){
                    echo ' active';                           
                    $class_active = false;
                }

                echo '">
                    <img src="'.$dir_imgs.'/'.$value_imgs.'">
                </div>
            ';
            }
        }
    }

    function printIndexPost($conn){
        $rows = getRows($conn,
        'SELECT p.post_img, p.post_id, p.post_title, u.user_name FROM posts p LEFT JOIN users u on p.post_author_id = u.user_id','',array());
        shuffle($rows);
        array_slice($rows,0,6);
        printPostPreview($rows);
    }

    function printPost ($posts){
        foreach($posts as $post){
            $button_value = "'".json_encode(array('post_id'=>$post['post_id']))."'";
            echo '

            <div class="jumbotron jumbotron-fluid d-flex justify-content-center align-items-center" style="background-image:url(\''.$post['post_img'].'\')">
                <div class="container">
                    <h1 id = "post_title">'.$post['post_title'].'</h1>
                    <div class="d-md-flex">                
                        <a href="user.php?user_id='.$post['user_id'].'"><h2>by '.$post['user_name'].'</h2></a>                 
                        <h2>'.$post['post_time'].'</h2>
                    </div>
                </div>
            </div>

            <div id=\'post_content\'>
                <p>'.$post['post_content'].'</p>
            </div>
                 
            <div class ="text-center">
                <button class ="button-1" id ="like_post" value='.$button_value.'><i class="bi bi-heart">0</i></button>
            ';
            
            if ($_SESSION['logged_in'] && isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
                if ($_SESSION['user_id'] == $post['user_id']){
                    echo '
                    <button class="button-1" id ="edit_post" value='.$button_value.' onclick="location.href=\'edit_post.php?post_id='.$post['post_id'].'\'"><i class="bi bi-pen"></i></button>
                    ';
                }
                if ($_SESSION['user_role'] == 1){
                    echo '
                    <button class ="button-1 btn_admin_delete" onclick="location.href=\'admin_delete_post.php?post_id='.$post['post_id'].'\'"><i class="bi bi-trash2-fill"></i></button>
                    ';
                }
            }

            echo 
            '</div>';
        }
    }

    function printPostPreview ($posts){
        foreach($posts as $post){
            echo '
            <div class="post_preview col-md-4 col-sm-12">
                <div class="post_thumbnail_border">
                    <img class ="post_thumbnail" src="'.$post['post_img'].'">
                </div>
            ';
            
            if (isset($post['user_name'])) echo '<h3 class="preview_author">'.$post['user_name'].'</h3>';

            echo'
            <a href="post.php?post_id='.$post['post_id'].'"><h1>'.$post['post_title'].'</h1></a>';
            
            if (isset($post['post_time'])) echo '<h3>at '.$post['post_time'].'</h3>';      
            
            echo '</div>';
        }
    }