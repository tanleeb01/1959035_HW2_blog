<?php
  session_start();
  require_once ("../db.php");
  
  if (isset($_POST['comment_content'])) {
    $comment_id = uniqid();
    if (setRow($conn,
    "INSERT INTO comments(comment_id,comment_post_id,comment_user_id,comment_content) VALUES (?,?,?,?)",
    "ssss",array($comment_id,$_SESSION['post_id'],$_SESSION['user_id'],$_POST['comment_content'])
    ) != false) echo true;
  };
?>