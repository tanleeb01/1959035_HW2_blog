<?php
    require_once 'func/filemanager.php';
//user errors assoc array to track errors and use as a bool
// decide on log in / creating account. Passed by reference
    $errors = [];

    if (isset($_GET['log_out'])){        
        session_destroy();
    } 

    else if (isset($_GET['user_id'])) {
        $rows = getRows($conn,'SELECT u.user_id,u.user_password, u.user_name, u.user_email,u.user_quote,u.user_img,u.user_role FROM users u where u.user_id = ?','s',array($_GET['user_id']));
        
        if (!empty($rows)){
            $row = $rows[0];
            $_POST['img'] = $row['user_img'];
        }

        if (isset($_POST['edit'])) {
            checkEdit($_POST, $row, $errors, $conn);
        }
        else if (isset($_POST['delete'])) {
            if (isset($_POST['delete_check'])) {
                checkDelete($_POST,$row,$errors,$conn);
            }
        }
    }
    
    if(isset($_POST['log_in'])) checkLogin($_POST, $errors, $conn);
    else if (isset($_POST['create'])) checkCreate($_POST, $errors, $conn);

    function checkLogin($POST, &$errors, $conn) {
        $email = $POST['login_email'];
        $password = $POST['login_password'];
        //username checks, first check db if user exists
        // checkforuser() returns either 0 (doesnt exist) or the user ID
        //$user_rows = getUserRows($conn,"WHERE user_email = ?",$email);
        $user_rows = getRows($conn,'SELECT * FROM users WHERE user_email = ?','s',array($email));

        if( (empty($user_rows)?0:count($user_rows)) != 1) {
            $errorMsg = "Email not found!";
            $errors['login_email'] = $errorMsg;
        } else {
        // if user exists, get their record and check the user submitted pw
        // against the hash in the DB
            $user_row = $user_rows[0];

            if(!password_verify($password, $user_row['user_password'])) {
                $errorMsg = "Incorrect Password!";
                $errors['login_password'] = $errorMsg;
            }
        }
        // if there are no errors in the array then login and redirect
        if(empty($errors)) {
            loginUser($user_row['user_name'], $user_row['user_id'], $user_row['user_role']);
        }
    }

    function loginUser($user_name, $user_id, $user_role){
        $_SESSION['logged_in'] = true;
        $_SESSION['user_name'] = $user_name;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_role'] = $user_role;
        if (headers_sent()) {
            echo '<script>window.location.href = \'user.php?user_id='.$_SESSION['user_id'].'\';</script>';
        }
        else{
            header('Location: user.php?user_id='.$_SESSION['user_id']);
        }
    }

    function checkCreate($POST, &$errors, $conn){        
        $email = $POST['create_email'];
        $name = $POST['create_name'];
        $quote = $POST['create_quote']==''?null:$POST['create_quote'];
        $password1 = $POST['create_password1'];
        $password2 = $POST['create_password2'];     

        // validate email, should add sanitation as well
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg = "Invalid email!";
            $errors['create_email'] = $errorMsg;
        } else {
            $user_rows = getRows($conn,'SELECT * FROM users WHERE user_email = ?','s',array($email));
            if (!(empty($user_rows))) {
                $errorMsg = "Email existed!";
                $errors['create_email'] = $errorMsg;
            }
        }

        //check username length, the ensure the name doesn't exist in the DB
        if ($name == '') {
            $errorMsg = "Please fill in your name";
            $errors['create_name'] = $errorMsg;
        } else if(!minmaxChars($name, 0, 255)) {
            $errorMsg = "Your name is too long";
            $errors['create_name'] = $errorMsg;
        }

        if(!minmaxChars($quote, 0, 2**16-1)) {
            $errorMsg = "Your quote is too long";
            $errors['create_quote'] = $errorMsg;
        }

        // check pw length and matching
        if ($password1 == '') {
            $errorMsg = "Please fill in your password";
            $errors['create_password'] = $errorMsg;
        }else if(!minmaxChars($password1, 5, 255)) {
            $errorMsg = "Password is too short";
            $errors['create_password'] = $errorMsg;
            if($password1 != $password2) {
                $errorMsg = "& does not match!";
                $errors['create_password'] .= $errorMsg;
            }
        } else if($password1 != $password2) {
            $errorMsg = "Password does not match!";
            $errors['create_password'] = $errorMsg;
        }

        if ($_FILES['create_user_img']['tmp_name']!=''){
            $img = checkFile($_FILES['create_user_img'],"imgs/users/",$errors);
            if ($img == false){    
                $errorMsg = "Error processing file.";
                $errors['create_file'] = $errorMsg;
            }
        } else $img='imgs/users/default.png';

        // if there are no errors, insert the user into the db and login
        if(empty($errors)) {
            $id = createUser($conn,$name,$password1,$email,$quote,$img);
            if ($id != false){
                loginUser($name,$id,2);
            }
        }
    }

    function createUser($conn,$user_name,$user_password,$user_email,$user_quote,$user_img) {
        $user_id = uniqid();
        $user_hash = password_hash($user_password, PASSWORD_BCRYPT);
        $user_role = 2;
        $sql = "INSERT INTO users (user_id,user_name,user_password, user_email,user_quote,user_img,user_role) VALUES (?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi",$user_id,$user_name,$user_hash,$user_email,$user_quote,$user_img,$user_role);
        $stmt->execute();
        if($stmt->affected_rows == 1) {
          return $user_id;
        } else {
          return false;
        }
    }

    function checkEdit($POST, $OG, &$errors, $conn){
        $name = $POST['edit_name'];
        $quote = $POST['edit_quote']==''?null:$POST['edit_quote'];
        $password0 = $POST['edit_password0'];
        $password1 = $POST['edit_password1'];
        $password2 = $POST['edit_password2'];
        
        if ($name == '') {
            $errorMsg = "Please fill in your name";
            $errors['edit_name'] = $errorMsg;
        } else if(!minmaxChars($name, 0, 255)) {
            $errorMsg = "Your new name is too long";
            $errors['edit_name'] = $errorMsg;
        }       

        if(!minmaxChars($quote, 0, 2**16-1)) {
            $errorMsg = "Your new quote is too long";
            $errors['edit_quote'] = $errorMsg;
        }

        if ($password0 == '') {
            $errorMsg = "Please fill in your current password";
            $errors['edit_password0'] = $errorMsg;
        } else if (!password_verify($password0, $OG['user_password'])) {
            $errorMsg = "Incorrect current password";
            $errors['edit_password0'] = $errorMsg;
        }

        if ($password1 != '') {            
            if(!minmaxChars($password1, 5, 255)) {
                $errorMsg = "New password is too short";
                $errors['edit_password1'] = $errorMsg;
                if($password1 != $password2) {
                    $errorMsg = "& does not match!";
                    $errors['edit_password1'] .= $errorMsg;
            }
            } else if($password1 != $password2) {
                $errorMsg = "Password does not match!";
                $errors['edit_password1'] = $errorMsg;
            }
        } else $password1 = $password0;

        if ($_FILES['create_user_img']['tmp_name']!=''){
            $img = checkFile($_FILES['create_user_img'],"imgs/users/",$errors);
            if ($img == false){    
                $errorMsg = "Error processing file.";
                $errors['create_file'] = $errorMsg;
            }
        } else $img=isset($POST['img'])?$POST['img']:null;

        if(empty($errors)) {
            if (setRow($conn,
                "UPDATE users
                SET user_name = ?, user_quote = ?, user_img = ?, user_password = ?
                WHERE user_id = ?
                ",
                "sssss",array($name,$quote,$img,password_hash($password1,PASSWORD_DEFAULT),$OG['user_id'])
                ) != false) {
                    if (headers_sent()) {
                        echo '<script>window.location.href = \'user.php?user_id='.$OG['user_id'].'\';</script>';
                    }
                    else{
                        header('Location: user.php?user_id='.$OG['user_id']);
                    }
                }
            } else $errors['edit_none'] = 'nothing has been edited';
    }

    function checkDelete($POST, $OG, &$errors, $conn){
        $password0 = $POST['edit_password0'];
        if ($password0 == '') {
            $errorMsg = "Please fill in your current password";
            $errors['edit_password0'] = $errorMsg;
        } else if (!password_verify($password0, $OG['user_password'])) {
            $errorMsg = "Incorrect current password";
            $errors['edit_password0'] = $errorMsg;
        }

        if(empty($errors)) {
            if (setRow($conn,
                    "DELETE from users
                    WHERE user_id=?",
                    "s",array($_GET['user_id'])
                ) != false) {
                    if (headers_sent()) {
                        echo '<script>window.location.href = \'login.php?log_out=true\';</script>';
                    }
                    else{
                        header('Location: log_in.php?log_out=true');
                    }
                }
        }
    }

    function checkAdmin($POST, &$errors, $conn){
        $admin_password = getRows($conn,'SELECT u.user_password FROM users u WHERE u.user_role = ?','i',array(1))[0]['user_password'];
        $errorMsg = "Incorrect password";
        $errors['admin_password'] = $errorMsg;

        return password_verify($POST['admin_password'],$admin_password);
    }

    function printUser($users){
        foreach($users as $user){
            $button_value = "'".json_encode(array('user_id'=>$user['user_id']))."'";

            echo '
            <div class="jumbotron jumbotron-fluid d-flex jutify-content-center align-items-center text-center" id="user_img_background">            
                <div class="container">
                    <img class="user_img" src="'.$user['user_img'].'">
                </div>
            </div>
            
            <div class="user_info">
                <h1>'.$user['user_name'].'</h1>
                <h2>'.$user['user_email'].'</h2>

                <blockquote><p>'.$user['user_quote'].'</p></blockquote>
            </div>';

            if ($_SESSION['logged_in'] && isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
                if ($_SESSION['user_id'] == $user['user_id']){
                    echo '
                    <div class="text-center">
                        <button class="button-1" id ="edit_post" value='.$button_value.' onclick="location.href=\'edit_user.php?user_id='.$user['user_id'].'\'"><i class="bi bi-pen"></i>
                        </button>
                    </div>';
                }
                /*else if ($_SESSION['user_role'] == 1){
                    echo '
                    <button id ="edit_post" value='.$button_value.' onclick="location.href=\'edit_post.php?post_id='.$post['post_id'].'\'"><i class="fas fa-edit"></i></button>
                    ';
                }*/
            }
        }
    }   

    // character length checker
    function minmaxChars($string, $min, $max = 1000) {
        if(strlen($string) < $min || strlen($string) > $max) {
            return false;
        } else {
            return true;
        }
    }