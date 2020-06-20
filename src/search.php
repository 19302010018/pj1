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

        $favor_diaplay = $is_favor ? "取消收藏" : "收藏";
        $_SESSION["$imageid"] = $is_favor;
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
                        <div class="description">
                            有'.$favor.'人喜欢该图
                        </div>
                    </figcaption>
                    <form action="#" method="get">
                    <a href = "favorchangeforsearch.php?picture='.$imageid.'&favor='.$is_favor.'">
                        <input type="button" name="Remove" value="'.$favor_diaplay.'">
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
    <title>搜索</title>
    <link href="../css/search.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        function display_alert1(){
            alert("已加入喜欢的图片！")
        }
        function display_alert2(){
            alert("为您收到以下图片！")
        }
    </script>
</head>
<body>
<?php
require_once ('nav.php');
?>
<br>
<br>

<div class="main-top">
    <div class="search-area">
        <h4 class="search-title">搜索</h4>
        <form method="get" action="search.php">
            <input type="radio" name="search-radio" value="bytitle" checked>标题筛选<br>
            <div class="search-line">
                <input type="text" class="search-text" name="title-text">
            </div>
            <input type="radio" name="search-radio" value="bydescription">描述筛选<br>
            <div class="search-line">
                <textarea class="search-textarea" name="description-text"></textarea>
            </div>
            <input type="submit" id="search-submit" onclick="display_alert2()" value="筛选">
        </form>
    </div>
</div>
<main>
    <ul id="favorlist">
        <?php
        if(!isset($_SESSION['id'])){
            echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "请先注册登录再使用搜索功能！" . "\"" . ")" . ";" . "</script>";
            echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "register.php" . "\"" . "</script>";
        }
        $searchtype = $_GET['search-radio'];
        $searchtitle = $_GET['title-text'];
        $searchdescription = $_GET['description-text'];

        if($searchtype == "bytitle") {
            //搜索标题
            $db = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            $query = "SELECT ImageID FROM travelimage WHERE Title like :title";
            $result = $db->prepare($query);
            $result->bindValue(':title', "%$searchtitle%");
            $result->execute();
            $row = $result -> rowCount();
        }elseif ($searchtype == "bydescription"){
            //搜索描述
            $db = new PDO(DBCONNSTRING, DBUSER, DBPASS);
            $query = "SELECT ImageID FROM travelimage WHERE Description like :description";
            $result = $db->prepare($query);
            $result->bindValue(':description', "%$searchdescription%");
            $result->execute();
            $row = $result -> rowCount();
            $searchfinsh = true;
        }
        $TotalPages = ceil($row/6);//总页数
        $pagenow = isset($_GET['page'])?$_GET['page']:1;
        $back = ($pagenow == 1) ? 1 : $pagenow - 1;
        $next = ($pagenow == $TotalPages) ? $TotalPages : $pagenow + 1;
        $picturecount = 1;
        if($row>0) {
            while ($stmt = $result->fetch()) {//生成图片和分页
                if (($picturecount > (($pagenow - 1) * 6)) && ($picturecount < ($pagenow * 6 + 1)))
                    generate($stmt['ImageID']);
                $picturecount++;
            }
        }else{
            if($searchfinsh)echo '<h1 style = "color:#ff96f9;text-align: center">暂无搜索结果，不如自己上传一张吧！</h1>';
        }

        echo'
    </ul>
</main>';
        if($row>0){
        echo'<div class="pages">';
        echo '<a href=search.php?title-text='.$searchtitle.'&description-text='.$searchdescription.'&search-radio='.$searchtype.'&page='.$back.' style = "color : #5f95ff">前一页</a>';
        $isfull = false;
        for ($i = 1; $i <= $TotalPages; $i++) {
            if($i == $pagenow) echo '<a href=search.php?title-text='.$searchtitle.'&description-text='.$searchdescription.'&search-radio='.$searchtype.'&page=' . $i . ' style = "color : red">' . $i . '</a>';
            else echo '<a href=search.php?title-text='.$searchtitle.'&description-text='.$searchdescription.'&search-radio='.$searchtype.'&page=' . $i . ' style = "color : #5f95ff">' . $i . '</a>';
            if($pagenow>5){
                echo'<p>......</p>';
                break;
            }
        }
        echo '<a href=search.php?title-text='.$searchtitle.'&description-text='.$searchdescription.'&search-radio='.$searchtype.'&page='.$next.' style = "color : #5f95ff">后一页</a>';
        echo'</div>';
        }
 ?>
</body>
<?php  require_once('footer.php'); ?>
</html>