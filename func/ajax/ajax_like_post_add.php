<?php
  session_start();
  require_once ("../db.php");

  $user_rows = getRows($conn,'SELECT * FROM p_likes WHERE plike_post_id = ? AND plike_user_id = ?','ss',array($_SESSION['post_id'],$_SESSION['user_id']));
  
  if (empty($user_rows)) {
    if (setRow($conn,
    "INSERT INTO p_likes(plike_post_id,plike_user_id)VALUES (?,?)",
    "ss",array($_SESSION['post_id'],$_SESSION['user_id'])) != false) echo true;
  } else {
    if (setRow($conn,
    "DELETE FROM p_likes WHERE plike_post_id = ? AND plike_user_id = ?",
    "ss",array($_SESSION['post_id'],$_SESSION['user_id'])) != false) echo false;
  }
?>