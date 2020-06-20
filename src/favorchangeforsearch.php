<?php
session_start();
require_once ('config.php');
$db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
$picture = $_GET['picture'];
$id = $_SESSION['id'];
$favor = $_GET['favor'];
if($favor){
    $query = "DELETE FROM travelimagefavor WHERE UID = :id AND ImageID = :picture";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $_SESSION['id']);
    $stmt->bindValue(':picture', $picture);
    $stmt->execute();
}else{
    $query = "INSERT INTO travelimagefavor(UID,ImageID) VALUES (:id,:picture)";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':id', $_SESSION['id']);
    $stmt->bindValue(':picture', $picture);
    $stmt->execute();
}
$db = null;
header("Location:".$_SERVER['HTTP_REFERER']);
?>