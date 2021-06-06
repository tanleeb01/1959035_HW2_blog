<?php
    include 'include/header.php';
    include 'func/usermanager.php';
?>

<?php if(isset($_GET['log_out'])): ?>
  <h1>Comeback soon</h1>
<?php endif?>

<div class="container mt-3">
   <?php if (isset($errorMsg)): ?>
     <div class="alert alert-danger" role="alert">
       <?php echo $errorMsg; ?>
     </div>
   <?php endif; ?>
  <div class="row">
    <div class="col-md-6">
      <h1>Create a new account</h1>
      <hr>
      <form class="" action="log_in.php" method="post" enctype="multipart/form-data">
        <label for="create_email">Email</label>
        <input type="email" name="create_email" class="form-control" placeholder="Input your email..." value=
        "<?php if (isset($_POST['create_email'])) { echo htmlspecialchars($_POST['create_email']);} ?>"
        >
        <p class="error">
          <?php if(isset($errors['create_email'])) { echo $errors['create_email'];} ?>
        </p>
        
        <div id="create_img" class="d-flex align-items-center jusity-contents-center">
            <img id="previewImg" src="imgs/users/default.png" alt="Placeholder">
        </div>

        <label for="create_user_img">Profile Picture</label>
        <input type="file" name="create_user_img" class="form-control" onchange="previewFile(this);">
        
        <label for="create_name">Your Name</label>
        <input type="text" name="create_name" class="form-control" placeholder="Input your name..." value
        ="<?php if (isset($_POST['create_name'])) {
          echo htmlspecialchars($_POST['create_name']);}?>">
        <p class="error"><?php if(isset($errors['create_name'])) {echo $errors['create_name'];} ?></p>

        <label for="create_quote">Your Quote</label>
        <textarea name="create_quote" rows="5" cols="50" placeholder="Your quote here..."><?php
        if (isset($_POST['create_quote']))
        {echo htmlspecialchars($_POST['create_quote']);}
        ?></textarea>
        
        <label for="create_password1">Password</label>
        <input type="password" name="create_password1" class="form-control" placeholder="Input your password...">
        <label for="create_password2">Confirm Password</label>
        <input type="password" name="create_password2" class="form-control" placeholder="Input your password...">
        <p class="error"><?php if(isset($errors['create_password'])) { echo $errors['create_password'];} ?></p>
      
        <div class="button-2">          
          <div class="border"></div>
          <button type="submit" name="create" class="content">Create account</button>
        </div>
      </form>
    </div>

    <div class="col-md-6">
      <h1>Login</h1>
      <hr>
      <form class="" action="log_in.php" method="post">
        <label for="login_email">Email</label>
        <input type="email" name="login_email" class="form-control" placeholder="Input your email..." value="<?php if (isset($_POST['login_email'])) { echo htmlspecialchars($_POST['login_email']);} ?>">
        <p class="error"><?php if(isset($errors['login_email'])) {echo $errors['login_email'];} ?></p>
        <label for="login_password">Password</label>
        <input type="password" name="login_password" class="form-control" placeholder="Input your password..." value="">
        <p class="error"><?php if(isset($errors['login_password'])) {echo $errors['login_password'];} ?></p>

        <div class="button-2">          
          <div class="border"></div>
          <button type="submit" name="log_in" class="content">Login</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="js/img_preview.js" charset="utf-8"></script>

<?php include'include/footer.php'?>