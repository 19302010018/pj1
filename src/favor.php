<?php
session_start();
require_once ('config.php');
function generate($imageid){
    try {
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
        while($row = $result -> fetch()){//查看目前登录用户是否有喜欢该图片
            if($row['UID'] == $_SESSION['id']) {
                $is_favor = true;
                break;
            }
            else  $is_favor =false;
        }

        $query = "SELECT UserName FROM travelimage JOIN users ON travelimage.UID =UserID WHERE travelimage.ImageID=:imageid"; //获取上传者信息 right
        $result = $db -> prepare($query);
        $result -> bindValue(':imageid',$imageid);
        $result -> execute();
        $details = $result -> fetch();
        $uploader = $details['UserName'];

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
                        <div style="color: #ef9cd5;font-size:1.7vw" >
                            有'.$favor.'人喜欢该图
                        </div>
                        <div style="font-size:1.7vw">
                            该图由'.$uploader.'上传
                        </div>
                    </figcaption>
                    <form action="#" method="get">
                        <a href="favordelete.php?picture='.$imageid.'">
                            <input type="button" name="Remove" value="取消收藏">
                        </a>
                    </form>
                </figure>
            </div>
        </li>
        ';

    }catch (Exception $e){
        echo"ERROR:".$e -> getMessage();
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>我的收藏</title>
    <link href="../css/favor.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        function display_alert(){
            alert("确定要删除图片吗？")
        }
    </script>
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
            if (!isset($_SESSION['id'])){//个人信息站点登录检测
                echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "请登录后再进入本页面吧！" . "\"" . ")" . ";" . "</script>";
                echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "login.php" . "\"" . "</script>";
            }
            $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
            $query = "SELECT ImageID FROM travelimagefavor WHERE UID =:userid";
            $stmt = $db -> prepare($query);
            $stmt -> bindValue(':userid',$_SESSION['id']);
            $stmt -> execute();
            $row = $stmt -> rowCount();//存放有多少张喜欢的图

        //生成页码
        $TotalPages = ceil($row/6);//总页数
        $pagenow = isset($_GET['page'])?$_GET['page']:1;
        $back = ($pagenow == 1) ? 1 : $pagenow - 1;
        $next = ($pagenow == $TotalPages) ? $TotalPages : $pagenow + 1;
        $picturecount = 1;
        if($row>0) {
            while ($result = $stmt->fetch()) {
                if (($picturecount > (($pagenow - 1) * 6)) && ($picturecount < ($pagenow * 6 + 1)))
                    generate($result['ImageID']);
                $picturecount++;
            }
            echo '
        </ul>
    </main>
        <div class = pages>';

            echo '<a href=favor.php?page=' . $back . ' style = "color : #5f95ff">前一页</a>';
            $isfull = false;
            if ($TotalPages > 5) $isfull = true;
            for ($i = 1; $i <= $TotalPages; $i++) {
                if ($i == $pagenow) echo '<a href=favor.php?page=' . $i . ' style = "color : red">' . $i . '</a>';
                else echo '<a href=favor.php?page=' . $i . ' style = "color : #5f95ff">' . $i . '</a>';
                if ($isfull) {
                    echo '<p>......</p>';
                    break;
                }
            }
            echo '<a href=favor.php?page=' . $next . ' style = "color : #5f95ff">后一页</a>';
            echo '</div>';
        }else{
            echo '<h1 style = "color:#ff96f9;text-align: center">你还没有收藏照片，快去收藏吧！</h1>';
        }
?>
</body>

<?php require_once('footer.php');?>
</html>