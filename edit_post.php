<?php
    include 'include/header.php';
    include 'func/postmanager.php';
?>

<form action="" method="post" id="form_post" enctype="multipart/form-data" name="form_post">
    
    <div class="jumbotron">
        <img id="previewImg" src="<?php if (isset($row['post_img'])) echo $row['post_img'];?>" alt="Placeholder">
    </div>

    <label for="create_post_img">New thumbnail picture</label>
    <input type="file" name="create_post_img" class="form-control" onchange="previewFile(this);">
    <p class="error"><?php if(isset($errors['post_file'])) {echo $errors['post_file'];} ?></p>

    <label for="title">Title</label>
    <input type="text" id="title" name="title" 
    value="<?php if (isset($row['post_title'])) echo htmlspecialchars($row['post_title']);?>">
    <p class="error"><?php if(isset($errors['post_title'])) {echo $errors['post_title'];} ?></p>
      
    <textarea name="content" rows="15" cols="65" placeholder="Write something..."><?php if (isset($row['post_content'])) echo htmlspecialchars($row['post_content']);?></textarea>
    <p class="error"><?php if(isset($errors['post_content'])) {echo $errors['post_content'];} ?></p>

    <p class="error"><?php if(isset($errors['edit_none'])) {echo $errors['edit_none'];} ?></p>
    <div class="d-flex">
        <div class="button-2">          
            <div class="border"></div>
            <button class="content" type="submit" name="post_submit" form="form_post" value="edit">Save</button>
        </div>

        <div class="button-2">          
            <div class="border"></div>
            <button class="content" type="submit" name="post_submit" form="form_post" value="delete">Delete</button>
        </div>
    </div>
</form>

<script src="js/img_preview.js" charset="utf-8"></script>
<?php
    include 'include/footer.php';
?>