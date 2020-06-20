<?php
session_start();
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href = ../css/photo.css rel="stylesheet" type="text/css">
    <title>照片详情</title>
    <script type="text/javascript">
        function display_alert(){
            alert("收藏状态已更新")
        }
    </script>
</head>
<body>
<?php
require_once ('nav.php');
?>
<?php
    if(!isset($_SESSION['id'])){
        echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "登录查看图片详情" . "\"" . ")" . ";" . "</script>";
        echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "login.php" . "\"" . "</script>";
    }
    try {
        $db = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $query = "SELECT Description,Title,PATH FROM travelimage WHERE ImageID=:imageid";//获得图片信息 right
        $result = $db->prepare($query);
        $result->bindValue(':imageid', $_GET['imageid']);
        $result->execute();
        $details = $result->fetch();
        $description = $details['Description'];
        $title = $details['Title'];
        $path = $details['PATH'];

        $query = "SELECT UID FROM travelimagefavor WHERE ImageID=:imageid";
        $result = $db -> prepare($query);//查询有多少人喜欢，right
        $result -> bindValue(':imageid',$_GET['imageid']);
        $result -> execute();
        $favor = $result -> rowCount();
        while($row = $result -> fetch()){//查看目前登录用户是否有喜欢该图片
            if($row['UID'] == $_SESSION['id']) {
                $is_favor = true;
                break;
            }
            else  $is_favor =false;
            }

        $query = "SELECT CountryName FROM travelimage JOIN geocountries ON travelimage.CountryCodeISO=geocountries.ISO WHERE ImageID=:imageid";//获取地区信息 right
        $result = $db -> prepare($query);
        $result -> bindValue(':imageid',$_GET['imageid']);
        $result -> execute();
        $details = $result -> fetch();
        $country = $details['CountryName'];


        $query = "SELECT AsciiName FROM travelimage JOIN geocities ON travelimage.CityCode=geocities.GeoNameID WHERE ImageID=:imageid"; //获取城市信息 right
        $result = $db -> prepare($query);
        $result -> bindValue(':imageid',$_GET['imageid']);
        $result -> execute();
        $details = $result -> fetch();
        $city = $details['AsciiName'];

        $query = "SELECT UserName FROM travelimage JOIN users ON travelimage.UID =UserID WHERE travelimage.ImageID=:imageid"; //获取上传者信息 right
        $result = $db -> prepare($query);
        $result -> bindValue(':imageid',$_GET['imageid']);
        $result -> execute();
        $details = $result -> fetch();
        $uploader = $details['UserName'];

    }catch (Exception $e){
        echo"ERROR:".$e -> getMessage();
    }

?>

    <div class="photo">
        <figure>
            <?php
            $favor_diaplay = $is_favor ? "取消收藏" : "收藏";
            $_SESSION['favor'] = $is_favor;
            $_SESSION['picture'] = $_GET['imageid'];
            echo'
            <div class="bg">
                <a><img class="normalPic" src="../images/normal/medium/'.$path.'"></a>
            </div>
            <figcaption>
                <div class="name">'.$title.'</div>
                <p>
                <div class="description">
                    '.$description.'
                </div>
                </p>
                <p>
                <div class="description">
                    地区:'.$country.'
                </div>
                </p>
                <p>
                <div class="description">
                    城市:'.$city.'
                </div>
                </p>
                <p>
                <div class="description">
                    上传者:'.$uploader.'
                </div>
                </p>
                <p>
                <div class="description" style="color:#ef9cd5">
                    已收藏：'.$favor.'
                </div>
                </p>
            </figcaption>
            <a action="#" method="post">
            <a href = "favorchange.php">
                <input type="button" name="like" value="'.$favor_diaplay.'" onclick="display_alert()">
                </a>
            </form>';
            ?>
        </figure>
    </div>
<?php require_once('footer.php'); ?>
</body>
</html>