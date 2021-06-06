<?php
    include 'include/header.php';
    include 'func/postmanager.php';
    $rows = [];

    if (isset($_POST['search_value'])){
        $rows = getRows($conn,'
        SELECT p.post_id, p.post_img,u.user_id, u.user_name,p.post_time,p.post_title,p.post_content 
        FROM posts p, users u
        WHERE p.post_title LIKE ? AND p.post_author_id = u.user_id','s',array('%'.$_POST['search_value'].'%'));
    } else $rows = getRows($conn,'
    SELECT p.post_id, p.post_img,u.user_id, u.user_name,p.post_time,p.post_title,p.post_content 
    FROM posts p, users u
    WHERE p.post_author_id = u.user_id
    ORDER BY p.post_time DESC','',array());
?>

<div id="post_search">
    <div class="row">
        <?php 
        if (!empty($rows)) printPostPreview($rows);
        else echo '<h1>No results found. Go ahead and create !<h1>';
        ?>
    </div>
</div>

<?php include 'include/footer.php'?>