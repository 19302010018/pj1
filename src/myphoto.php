<?php
session_start();
require_once('config.php');
function generate($imageid){
    $db = new PDO(DBCONNSTRING, DBUSER, DBPASS);
    $query = "SELECT Description,Title,PATH FROM travelimage WHERE ImageID=:imageid";//获得图片信息 right
    $result = $db->prepare($query);
    $result->bindValue(':imageid', $imageid);
    $result->execute();
    $details = $result->fetch();
    $description = $details['Description'];
    $title = $details['Title'];
    $path = $details['PATH'];

    $query = "SELECT UID FROM travelimagefavor WHERE ImageID=:imageid";
    $result = $db -> prepare($query);//查询有多少人喜欢，right
    $result -> bindValue(':imageid',$imageid);
    $result -> execute();
    $favor = $result -> rowCount();

    echo'
        <li>
            <div class="photo">
                <figure>
                    <div class="bg">
                        <a href="photo.php?imageid='.$imageid.'"><img class="normalPic" src="../images/normal/medium/'.$path.'"></a>
                    </div>
                    <figcaption>
                        <div class="name">'.$title.'</div>
                        <div class="description">
                            '.$description.'
                        </div>
                    </figcaption>
                    <form action="#" method="get">
                            <input type="button" name="edit" value="修改" onclick="window.location.href=\'upload.php?imageid='.$imageid.'\'">
                            <input type="button" name="Remove" value="删除" onclick="window.location.href=\'delete.php?imageid='.$imageid.'\'">
                    </form>
                </figure>
            </div>
        </li>
    ';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的照片</title>
    <link href="../css/myphoto.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php
require_once ('nav.php');
?>
<br>
<br>

<main>
    <ul id="favorlist">
        <?php
            $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $query = "SELECT ImageID FROM travelimage WHERE UID = :userid";
            $stmt = $db -> prepare($query);
            $stmt -> bindValue(':userid',$_SESSION['id']);
            $stmt -> execute();

            while($result = $stmt -> fetch()){
                generate($result['ImageID']);
            }
        ?>
    </ul>
</main>
<?php require_once('footer.php');?>
</body>


</html>