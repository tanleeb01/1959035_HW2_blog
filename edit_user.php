<?php
    include 'include/header.php';
    include 'func/usermanager.php';
?>

<form class="" id="form_post" action="" method="post" enctype="multipart/form-data">

    <div id="edit_img" class="d-flex align-items-center jusity-contents-center">
        <img id="previewImg" alt="Placeholder"src="<?php if (isset($row['user_img'])) echo $row['user_img'];?>" >
    </div>

    <label for="edit_user_img">Profile Picture</label>
    <input type="file" name="create_user_img" class="form-control" onchange="previewFile(this);">

    <label for="edit_name">New Name</label>
    <input type="text" name="edit_name" class="form-control" placeholder="Input your name..." value
    ="<?php 
        if (isset($_POST['edit_name'])) echo htmlspecialchars($_POST['edit_name']);
        else if (isset($row['user_name'])) echo htmlspecialchars($row['user_name']);
    ?>">
    <p class="error"><?php if(isset($errors['edit_name'])) {echo $errors['edit_name'];} ?></p>

    <label for="edit_quote">Your Quote</label>
    <br>
    <textarea name="edit_quote" rows="5" cols="50" placeholder="Your quote here..."><?php 
        if (isset($_POST['edit_quote'])) echo htmlspecialchars($_POST['edit_quote']);
        else if (isset($row['user_quote'])) echo htmlspecialchars($row['user_quote']);
    ?></textarea>
    <p class="error">
      <?php if(isset($errors['edit_email'])) { echo $errors['edit_quote'];} ?>
    </p>

    <label for="edit_password0">Original Password</label>
    <input type="password" name="edit_password0" class="form-control" placeholder="Input your current password...">
    <p class="error">
        <?php if(isset($errors['edit_password0'])) { echo $errors['edit_password0'];} ?>
    </p>

    <label for="edit_password1">New Password</label>
    <input type="password" name="edit_password1" class="form-control" placeholder="Input your password...">
    <label for="edit_password2">Confirm New Password</label>
    <input type="password" name="edit_password2" class="form-control" placeholder="Input your password...">
    <p class="error">
        <?php if(isset($errors['edit_password1'])) { echo $errors['edit_password1'];} ?>
    </p>

    <p class="error"><?php if(isset($errors['create_password'])) { echo $errors['create_password'];} ?></p>

    <div class="d-flex">
        <div class="button-2">          
            <div class="border"></div>
            <button type="submit" name="edit" class="content">Save edits</button>
        </div>

        <div class="button-2">          
            <div class="border"></div>
            <button type="submit" name="delete" class="content">Delete</button>
        </div>

        <input type="checkbox" id="delete_check" name="delete_check">
        <label for="delete_check">Yep, wipe me from existence</label>
    </div>
</form>

<script src="js/img_preview.js" charset="utf-8"></script>
<?php
    include 'include/footer.php';
?>