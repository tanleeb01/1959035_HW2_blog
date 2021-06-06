<?php
    include 'include/header.php';
    include 'func/usermanager.php';
    include 'func/postmanager.php';
?>

<div> <?php if (!empty($rows)) printUser($rows);?></div>

<div class="container" id="user_posts">
    <div class="row"> 
        <?php if (!empty($userpost_rows)) printPostPreview($userpost_rows);?></div>
    </div>
</div>

<?php
    include 'include/footer.php';?>

