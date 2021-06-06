<?php
    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "y2s3w_hw2";

    // #1: create the conn object
    $conn = new mysqli($host, $user, $pw, $db);    
    mysqli_set_charset($conn,"utf8mb4");

    function getRows($conn,$sql,$type,array $params) {
        $stmt = $conn->prepare($sql);
        if ($type!='') $stmt->bind_param($type,...$params);
        $stmt->execute();
        $results = $stmt->get_result();
        return $results->fetch_all(MYSQLI_ASSOC);
    }

    function setRow($conn,$sql,$type,array $params){
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($type,...$params);
        $stmt->execute();
        if($stmt->affected_rows != 1) return false;
        else return true;
    }
?>