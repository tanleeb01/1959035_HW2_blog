<?php
  session_start();
  require_once ("../db.php");

  $user_rows = getRows($conn,'SELECT * FROM c_likes WHERE clike_comment_id = ? AND clike_user_id = ?','ss',array($_POST['comment_id'],$_SESSION['user_id']));
  
  if (empty($user_rows)) {
    if (setRow($conn,
    "INSERT INTO c_likes(clike_comment_id,clike_user_id)VALUES (?,?)",
    "ss",array($_POST['comment_id'],$_SESSION['user_id'])) != false) echo true;
  } else {
    if (setRow($conn,
    "DELETE FROM c_likes WHERE clike_comment_id = ? AND clike_user_id = ?",
    "ss",array($_POST['comment_id'],$_SESSION['user_id'])) != false) echo false;
  }
?>