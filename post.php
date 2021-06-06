<?php
    include 'include/header.php';
    include 'func/postmanager.php';
?>

<div><?php printPost($rows);?></div>

<form action="" method="post" id="comment_form">
    <textarea name="comment_content" rows="4" placeholder="Write something..."></textarea>

    <!--<button type="submit" form="comment_form" name="comment_submit">Post</button>-->

    <div class="button-2">          
        <div class="border"></div>
        <button class="content" type="submit" form="comment_form" name="comment_submit">Post</button>
    </div>        
</form>

<div id='comment_div'>
</div>

<script src="js/main.js" charset="utf-8"></script>
<script src="js/comment.js" charset="utf-8"></script>
<script src="js/like_post.js" charset="utf-8"></script>
<script src="js/like_comment.js" charset="utf-8"></script>
<?php
    include 'include/footer.php';?>