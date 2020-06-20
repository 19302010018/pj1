<?php
    session_start();
    require_once('src/config.php');
    function generate($result)
    {
        while (($result->rowCount() > 0) && ($row = $result->fetch())) {
            echo '
    		<div class="list-photo">
        <a href="src/photo.php?imageid='.$row['ImageID'].'">
            <div class="list-image">
                <img src="images/square/medium/'.$row['PATH'].'">
            </div>
        </a>
        <label class="name">' . $row['Title'] . '</label>
        <p class="list-info">' . $row['Description'] . '</p>
    </div>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>主页</title>
    <link href="css/index.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        function display_alert() {
            alert("图片已刷新！")
        }
    </script>
</head>

<body>

<?php
echo '<header id="head">
    <nav>
        <h3>
            <div class="div-left">
                <a href="index.php" class="link">首页</a>
                <a href="src/browse.php" class="link">浏览</a>
                <a href="src/search.php" class="link">搜索</a>
            </div>
        </h3>
    </nav>
    <div class="div-right">
        <h3>
            <ul id="main">';

if(isset($_SESSION['password'])){
    echo'
             <li>Welcome '.$_SESSION['username'].'
             <ul class="drop">
                  <div>
                      <li><a href="src/upload.php">上传</a></li>
                      <li><a href="src/myphoto.php">照片</a></li>
                      <li><a href="src/favor.php">喜欢</a></li>
                      <li><a href="src/logout.php">注销</a></li>
                  </div>
             </ul>
             </li>';
}else{
    echo '<li><a href="src/login.php">个人中心</a>
                    <ul class="drop">
                        <div>
                            <li><a href="src/login.php">登陆</a></li>
                            <li><a href="src/register.php">注册</a></li>
                        </div>
                    </ul>
            </li>
';
}
echo'
            </ul>
        </h3>
    </div>
</header>';
?><!-- 导航栏 -->

<div class="bigphoto">
    <img src="images/normal/medium/5855174537.jpg" class="title-photo">
</div>

<div class="photos">
    <?php
    $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    if(!$_SESSION['refresh']){
        $query = "select travelimagefavor.ImageID ,travelimage.PATH,travelimage.Title,travelimage.Description ,count(*) from travelimagefavor JOIN travelimage ON travelimage.ImageID=travelimagefavor.ImageID group by travelimage.ImageID order by count(*) DESC LIMIT 9";
    }else{
        $query = "SELECT ImageID,PATH,Title,Description FROM travelimage ORDER BY RAND() LIMIT 9";
        $_SESSION['refresh'] = false;
    }
    $result = $db -> query($query);
    generate($result);
    ?>
</div>
<div id="totop">
    <a href="index.php#head" style="color: #13227a">
        回到最上方
    </a>
</div>
<div id="refresh" onclick="display_alert()">
    <a style="color: #13227a" href="src/refresh.php">
        刷新图片
    </a>
</div>
<?php require_once('src/footer.php');?>
</body>

</html>