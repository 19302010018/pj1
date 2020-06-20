<?php
session_start();
header("Content-Type: text/html;charset=utf-8");
require_once ('config.php');
$db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
$destination = '../images/normal/medium/';
$destination2 = '../images/square/medium/';
$file = $_FILES['file']; // 获取上传的图片
$filename = $file['name'];
$name = $_POST['name'];
$description = $_POST['description'];
$city = $_POST['city'];
$country = $_POST['country'];
$topic = $_POST['topic'];
$uploader = $_SESSION['id'];

$query = "SELECT MAX(ImageID) AS newimageid FROM travelimage";
$result = $db -> prepare($query);
$result -> execute();
$stmt = $result -> fetch();
$imageid = $stmt['newimageid']+1;
$savefilename = $file['tmp_name'];
$sqlsave =  iconv("UTF-8", "gb2312", $filename);
$insert = "INSERT INTO travelimage(ImageID,Title,Description,CityCode,CountryCodeISO,UID,PATH,Topic)
        VALUES ('$imageid','$name','$description','$city','$country','$uploader','$sqlsave','$topic')";
$test = move_uploaded_file($savefilename, $destination . iconv("UTF-8", "gb2312", $filename));
$test2 = move_uploaded_file($savefilename, $destination2 . iconv("UTF-8", "gb2312", $filename));
$result = $db -> prepare($insert);
$result -> execute();
echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "上传成功" . "\"" . ")" . ";" . "</script>";
echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "myphoto.php" . "\"" . "</script>";
?>