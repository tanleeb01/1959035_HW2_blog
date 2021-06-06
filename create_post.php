<?php
    include 'include/header.php';
    include 'func/postmanager.php';
?>

<form action="create_post.php" method="post" id="form_post" enctype="multipart/form-data" name="form_post">

    <div class="jumbotron">
        <img id="previewImg" src="imgs/posts/default.png" alt="Placeholder">
    </div>

    <label for="create_post_img">Thumbnail picture</label>
    <input type="file" name="create_post_img" class="form-control" onchange="previewFile(this);">
    <p class="error"><?php if(isset($errors['post_file'])) {echo $errors['post_file'];} ?></p>

    <label for="title">Title</label>
    <input type="text" id="title" name="title" 
    value="<?php if (isset($_POST['title'])) echo htmlspecialchars($_POST['title']);?>">
    <p class="error"><?php if(isset($errors['post_title'])) {echo $errors['post_title'];} ?></p>
      
    <textarea name="content" placeholder="Write something..."><?php if (isset($_POST['content'])) echo htmlspecialchars($_POST['content']);?></textarea>
    <p class="error"><?php if(isset($errors['post_content'])) {echo $errors['post_content'];} ?></p>

    <div class="button-2">          
        <div class="border"></div>
        <button class="content" type="submit" name="post_submit" form="form_post" value='create'>Post</button>
    </div>
</form>

<script src="js/img_preview.js" charset="utf-8"></script>
<?php
    include 'include/footer.php';
?>

