<?php
    include 'include/header.php';
    include 'func/usermanager.php';
    include 'func/postmanager.php';
?>

<div class="col-md-6">
      <h3>Verify password</h3>
      <hr>
      <form class="" action="" method="post">
        <label for="admin_password">Password</label>
        <input type="password" name="admin_password" class="form-control" placeholder="Input your password..." value="">
        <p class="error"><?php if(isset($errors['admin_password'])) {echo $errors['admin_password'];} ?></p>
        <button type="submit" name="admin_verify" class="btn btn-outline-primary">Verfiy</button>
      </form>
    </div>
  </div>

<?php include'include/footer.php'?>