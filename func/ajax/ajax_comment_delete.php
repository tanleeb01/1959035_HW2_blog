<?php
  session_start();
  require_once ("../db.php");
  
  if (isset($_POST['comment_id']) && $_SESSION['logged_in']) {
    $rows = getRows($conn,"
    SELECT comment_user_id 
    FROM comments 
    WHERE comment_id = ?","s",array($_POST['comment_id']));

    if (!empty($rows)) {
      $row = $rows[0];
      if ($_SESSION['user_role'] == 1 || $_SESSION['user_id'] == $row['comment_user_id']){
        if (setRow($conn,
        "DELETE FROM comments WHERE comment_id = ?","s",array($_POST['comment_id'])
        ) != false) echo true;
      }
    }
  };
?>