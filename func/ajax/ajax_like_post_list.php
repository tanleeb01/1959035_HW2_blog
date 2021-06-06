<?php
    session_start();
    require_once ("../db.php");

    $comment_rows = getRows($conn,'SELECT COUNT(*) FROM p_likes WHERE plike_post_id = ?','s',array($_SESSION['post_id']));

    if (isset($_SESSION['user_id'])) $liked = empty(getRows($conn,'SELECT * FROM p_likes WHERE plike_post_id = ? AND plike_user_id = ?','ss',array($_SESSION['post_id'],$_SESSION['user_id'])))?0:1;
    else $liked = 0;
    
    echo json_encode(array('count'=>$comment_rows[0]['COUNT(*)'],'liked'=>$liked));

?>