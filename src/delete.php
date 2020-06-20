<?php
    session_start();
    require_once ('config.php');
    $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $picture = $_GET['imageid'];
    $id = $_SESSION['id'];
    $query = "DELETE FROM travelimagefavor WHERE ImageID = :picture";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':picture', $picture);
    $stmt->execute();
    $query = "DELETE FROM travelimage WHERE ImageID = :picture";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':picture', $picture);
    $stmt->execute();
    $db = null;
    echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "已为您删除图片！注意！该项操作无法还原！" . "\"" . ")" . ";" . "</script>";
    echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "myphoto.php" . "\"" . "</script>";
?>