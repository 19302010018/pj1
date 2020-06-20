<?php
    session_start();
    require_once ('config.php');
    $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $picture = $_GET['picture'];
    $id = $_SESSION['id'];
    $query = "DELETE FROM travelimagefavor WHERE UID = :id AND ImageID = :picture";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $_SESSION['id']);
    $stmt->bindValue(':picture', $picture);
    $stmt->execute();
    $db = null;
    echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "已取消收藏" . "\"" . ")" . ";" . "</script>";
    echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "favor.php" . "\"" . "</script>";
?>