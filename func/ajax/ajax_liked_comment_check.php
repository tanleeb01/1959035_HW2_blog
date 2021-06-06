<?php
  session_start();
  require_once ("../db.php");
  
  $rows = getRows($conn,'SELECT * FROM c_likes WHERE clike_comment_id = ? AND clike_user_id = ?','ss',array($_POST['comment_id'],$_SESSION['user_id']));
  
  if (empty($rows)) echo false;
  else echo true;
?>