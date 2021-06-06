<?php
    session_start();
    require_once ("../db.php");

    if (isset($_SESSION['user_id'])) {
        $comment_rows = getRows($conn,'
        SELECT t2.*,ifnull(t3.liked,0) as user_liked
        FROM
            (select 
            u.user_id, u.user_name, u.user_img, c.comment_id, c.comment_content, c.comment_parent_id, c.comment_time, t.comment_likes
            from 
                (select c.comment_id, COUNT(cl.clike_user_id) as comment_likes 
                from comments c 
                left join c_likes cl 
                on c.comment_id = cl.clike_comment_id 
                group by c.comment_id) t, comments c, users u
            where 
            c.comment_post_id = ? AND c.comment_user_id = u.user_id AND c.comment_id = t.comment_id) t2
        LEFT JOIN
            (SELECT cl.clike_comment_id, 1 as liked
            FROM c_likes cl
            WHERE cl.clike_user_id = ?) t3 
        ON t2.comment_id = t3.clike_comment_id
        ORDER BY t2.comment_time DESC
        ','ss',array($_SESSION['post_id'],$_SESSION['user_id']));
    } else $comment_rows = getRows($conn,'
    SELECT
        u.user_id, u.user_name, u.user_img, c.comment_id, c.comment_content, c.comment_parent_id, c.comment_time, t.comment_likes
    FROM 
        (select c.comment_id, COUNT(cl.clike_user_id) as comment_likes 
        from comments c 
        left join c_likes cl 
        on c.comment_id = cl.clike_comment_id 
        group by c.comment_id) t, comments c, users u
    WHERE 
    c.comment_post_id = ? AND c.comment_user_id = u.user_id AND c.comment_id = t.comment_id    
    ORDER BY c.comment_time DESC
    ','s',array($_SESSION['post_id']));

    echo json_encode($comment_rows,JSON_UNESCAPED_UNICODE);