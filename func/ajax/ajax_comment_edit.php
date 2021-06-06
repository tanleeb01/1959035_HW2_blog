<?php
  session_start();
  require_once ("../db.php");
  if (isset($_POST['comment_id'])) {
    if (setRow($conn,
    "UPDATE comments SET comment_content = ? WHERE comment_id = ?",
    "ss",array($_POST['comment_content'],$_POST['comment_id'])
    ) != false) echo true;
  };
?>