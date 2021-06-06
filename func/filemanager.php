<?php
    function checkFile($file,$dir,&$errors) {
        $fname = $file['name'];
        $ftype = $file['type'];
        $ftemp = $file['tmp_name'];
        $ferr = $file['error'];
        $fsize = $file['size'];
        $allowed_ext = ['png', 'jpeg', 'jpg'];
      
        // check to ensure there is no error with the upload
        if($ferr != 0) {
          $errors['create_file'] = "File error.";
          return false;
        }  

        // explore the filetype and check the type and extension
        $ftype = explode("/", $ftype);
        if($ftype[0] != "image" || !in_array(end($ftype), $allowed_ext)) {
            $errors['create_file'] = "We only accept png,jpeg or jpg.";
            return false;
        }

        // check filesize
        if($fsize > 5000000) {
            $errors['create_file'] = "File is too large.";
            return false;
        }

        if(empty($errors)) {
            $new_filename = uniqid('', false) . "." . end($ftype);
            $new_dest = $dir . $new_filename;
            if(move_uploaded_file($ftemp, $new_dest)) {
              return $new_dest;
            } else {
              return false;
            }
          }
    }
?>