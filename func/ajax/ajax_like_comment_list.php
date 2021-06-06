<?php
    session_start();
    require_once ("../db.php");

    $comment_rows = getRows($conn,'SELECT COUNT(*) FROM c_likes WHERE clike_comment_id = ?','s',array($_POST['comment_id']));

    $liked = empty(getRows($conn,'SELECT * FROM c_likes WHERE clike_comment_id = ? AND clike_user_id = ?','ss',array($_POST['comment_id'],$_SESSION['user_id'])))?0:1;

    echo json_encode(array('count'=>$comment_rows[0]['COUNT(*)'],'liked'=>$liked));
?>