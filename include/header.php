<?php
    include 'func/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=<device-width>, initial-scale=1.0">
    <title>... by Tân Lê</title>  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css?v=<?=time();?>">
    <link rel="icon" type="image/png" href="imgs/css/logo.png"/>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light fixed-top">
  <button class="navbar-toggler float-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">        
        <li class="nav-item">            
            <a class="nav-link" href="index.php">Home</a>
        </li>

        <?php if ($_SESSION['logged_in'] == false):?>
            <li class="nav-item">
                <a class="nav-link" href='log_in.php'>Login</a>
            </li>
        <?php else:?>
            <li class="nav-item">
                <a class="nav-link" href='create_post.php'>Create Post</a>
            </li>
            <li class="nav-item">
                <?php echo'
                    <a class="nav-link" href="user.php?user_id='.$_SESSION['user_id'].'">Profile</a>';
                ?>         
            </li>
            <li class="nav-item">
                <a class="nav-link" href='log_in.php?log_out=true'>Log out</a>            
            </li>
        <?php endif?>
    </ul>
    
    <form action="post_search.php" method = "post" id = "search_form" name = "search_form" class="my-2 my-lg-0">
        <div class="d-flex align-items-center justify-content-center">
            <input class="fmr-sm-2" type="search" name="search_value">

            <div class="d-flex align-items-center justify-content-center">
                <div class="button-2">          
                    <div class="border"></div>
                        <button class="content" type="submit" form="search_form">Search</button>
                </div>
            </div>
        </div>
    </form>
    
  </div>
</nav>

</div>