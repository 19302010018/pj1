<?php
    session_start();
    require_once('config.php');
    function generate($imageid){
        $db = new PDO(DBCONNSTRING, DBUSER, DBPASS);
        $query = "SELECT PATH FROM travelimage WHERE ImageID=:imageid";//获得图片信息 right
        $result = $db->prepare($query);
        $result->bindValue(':imageid',$imageid);
        $result->execute();
        $details = $result->fetch();
        $path = $details['PATH'];
        echo'<td><a href="photo.php?imageid='.$imageid.'"><img  src="../images/square/medium/'.$path.'"></a></td>';
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>浏览</title>
    <link href="../css/browse.css" rel="stylesheet" type="text/css">
    <script src="http://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function change(){
            let countryMenu = document.getElementById("address");
            let cityMenu = document.getElementById("city");
            let index = countryMenu.selectedIndex;
            let country = countryMenu.options[index].value;
            cityMenu.length = 1;
            if(index !== 0){
                let request = new XMLHttpRequest();
                request.onreadystatechange = function () {
                    if(request.readyState === 4 && request.status ===200){
                        let info = request.responseText.split("|");
                        //document.getElementById("test").innerText = info;
                        for(let i = 0 ;i < info.length;i++){
                            if(info[i]!== 'null'){
                                let infos = info[i].split('&')
                                let cityCode = infos[0];
                                let cityName = infos[1];
                                cityMenu[cityMenu.length] = new Option(cityName,cityCode);
                            }
                        }
                    }
                };
                request.open("GET", "address.php?country=" + country, true);
                request.send();
            }
        }
    </script>
    <?php

    /*
    $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $query = "SELECT CountryName,ISO FROM geocountries";
    $result = $db -> prepare($query);
    $result -> execute();
    while($stmt = $result -> fetch()){
        $ISO = $stmt['ISO'];
        $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $query = "SELECT GeoNameID,AsciiName FROM geocities WHERE CountryCodeISO = :ISO";
        $result2 = $db -> prepare($query);
        $result2 -> bindValue(':ISO',$ISO);
        $result2 -> execute();
        $count = 1;
        echo'
                        if(x.selectedIndex == '.$count.')
                            {';
        while($stmt2 = $result2 -> fetch()) {
            $cityname = $stmt2['AsciiName'];
            $citycode = $stmt2['GeoNameID'];
            echo 'y . options . add(new Option("'.$cityname.'", "'.$citycode.'"));';
        }
        echo '}
                    ';
        $count++;
    }
    echo'
    <script type="text/javascript">
        function display_alert() {
            alert("已为您呈现筛选内容！")
        }
        function change()
        {
            var x = document.getElementById("country");
            var y = document.getElementById("city");
            y.options.length = 0;}</script>';
*/
            ?>
</head>
<body>
<?php
    require_once ('nav.php');
?>
<br>
<br>
<br>

<div class="main-info">
    <div class="main-left">
        <div class="search-area">
            <h4 class="search-title">标题搜索</h4>
            <div class="search-line">
                <form method="get" action="browse.php">
                    <input type="text" id="search-text" name = "search-text">
                    <input type="submit" id="search-submit" onclick="display_alert()">
                </form>
            </div>
        </div>

        <div class="hot-content">
            <h4 class="search-title">热门内容</h4>
            <div class="search-line">
                <p><a href = "browse.php?topic=scenery">风景</a></p>
                <p><a href = "browse.php?topic=city">城市</a></p>
                <p><a href = "browse.php?topic=figure">人像</a></p>
                <p><a href = "browse.php?topic=animals">动物</a></p>
                <p><a href = "browse.php?topic=builing">建筑</a></p>
                <p><a href = "browse.php?topic=wonder">奇观</a></p>
            </div>
        </div>

        <div class="hot-content">
            <h4 class="search-title">热门国家</h4>
            <div class="search-line">
                <p><a href = "browse.php?ISO=CA">加拿大</a></p>
                <p><a href = "browse.php?ISO=GB">英国</a></p>
                <p><a href = "browse.php?ISO=GR">希腊</a></p>
                <p><a href = "browse.php?ISO=IT">意大利</a></p>
            </div>
        </div>

        <div class="hot-content">
            <h4 class="search-title">热门城市</h4>
            <div class="search-line">
                <p><a href = "browse.php?city=2643743">伦敦</a></p>
                <p><a href = "browse.php?city=3169070">罗马</a></p>
                <p><a href = "browse.php?city=3176959">佛罗伦萨</a></p>
                <p><a href = "browse.php?city=5913490">卡尔加里</a></p>
            </div>
        </div>
    </div>

    <div class="main-right">
        <div class="filter">
            <h4 class="search-title">筛选</h4>
            <div class="filter-select">
                <form name="form1" method="get" action="browse.php">

                    <select name="topic" class="select">
                        <option value="">选择主题</option>
                        <option value="scenery">风景</option>
                        <option value="city">城市</option>
                        <option value="figure">人像</option>
                        <option value="animals">动物</option>
                        <option value="building">建筑</option>
                        <option value="wonder">奇观</option>
                    </select>

                    <select name="country" class="select" id="address" onclick="change()">
                        <option value="">选择国家</option>
                        <?php
                            $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
                            $query = "SELECT CountryName,ISO FROM geocountries";
                            $result = $db -> prepare($query);
                            $result -> execute();
                            while($stmt = $result -> fetch()){
                                $ISO = $stmt['ISO'];
                                $countryname = $stmt['CountryName'];
                                echo'
                                    <option value='.$ISO.' id="address">'.$countryname.'</option>
                                ';
                            }
                        ?>
                    </select>

                    <select name="city" class="select" id="city">
                        <option value="">选择城市</option>
                        <option value="2643743">伦敦</option>
                        <option value="3169070">罗马</option>
                        <option value="3176959">佛罗伦萨</option>
                        <option value="5913490">卡尔加里</option>
                    </select>

                    <input type="submit" class="select-button" value="筛选" onclick="display_alert()">
                </form>
            </div>

            <div class="filter-result">
                <table id="BrowserPic">
                    <?php
                    $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);

                    if($_GET['search-text']!=""){//1。输入标题内容搜索
                        $searchtitle = $_GET['search-text'];
                        $query = "SELECT ImageID FROM travelimage WHERE Title like :title";
                        $result = $db->prepare($query);
                        $result->bindValue(':title', "%$searchtitle%");
                        $result -> execute();
                        $row = $result -> rowCount();
                    }

                    if($_GET['topic']!=""){//2。按主题搜索
                        if($_GET['ISO']=="" && $_GET['city']=="") {
                            $searchtopic = $_GET['topic'];
                            $query = "SELECT ImageID FROM travelimage WHERE Topic = :topic";
                            $result = $db->prepare($query);
                            $result->bindValue(':topic', $searchtopic);
                            $result->execute();
                            $row = $result->rowCount();
                        }elseif ($_GET['city']!=""){
                            $searchtopic = $_GET['topic'];
                            $searchcity = $_GET['city'];
                            $query = "SELECT ImageID FROM travelimage WHERE Topic = :topic AND CityCode = :city";
                            $result = $db->prepare($query);
                            $result -> bindValue(':topic', $searchtopic);
                            $result -> bindValue(':city',$searchcity);
                            $result->execute();
                            $row = $result->rowCount();
                        }elseif ($_GET['ISO']!=""){
                            $searchtopic = $_GET['topic'];
                            $searchcountry = $_GET['ISO'];
                            $query = "SELECT ImageID FROM travelimage WHERE Topic = :topic AND CountryCodeISO = :country";
                            $result = $db->prepare($query);
                            $result -> bindValue(':topic', $searchtopic);
                            $result -> bindValue(':country',$searchcountry);
                            $result->execute();
                            $row = $result->rowCount();
                        }
                    }

                    if($_GET['ISO']!=""){//3。按国家搜索
                        $searchcountry = $_GET['ISO'];
                        $query = "SELECT ImageID FROM travelimage WHERE CountryCodeISO = :country";
                        $result = $db->prepare($query);
                        $result->bindValue(':country', $searchcountry);
                        $result -> execute();
                        $row = $result -> rowCount();
                    }

                    if($_GET['city']!=""){//4。按城市搜索
                        $searchcity = $_GET['city'];
                        $query = "SELECT ImageID FROM travelimage WHERE CityCode = :city";
                        $result = $db->prepare($query);
                        $result->bindValue(':city', $searchcity);
                        $result -> execute();
                        $row = $result -> rowCount();
                    }

                    $TotalPages = ceil($row/24);//总页数
                    $pagenow = isset($_GET['page'])?$_GET['page']:1;
                    $back = ($pagenow == 1) ? 1 : $pagenow - 1;
                    $next = ($pagenow == $TotalPages) ? $TotalPages : $pagenow + 1;
                    $picturecount = 1;
                    if($row>0) {
                    while ($stmt = $result->fetch()) {//生成图片和分页
                        if($picturecount%8 == 1) echo '<tr>';
                        if (($picturecount > (($pagenow - 1) * 24)) && ($picturecount < ($pagenow * 24 + 1)))
                            generate($stmt['ImageID']);
                        if($picturecount%8 == 0) echo '</tr>';
                        $picturecount++;
                    }}

                    echo'
                </table>
                <div class="pages">';
                    if($row>0) {
                        echo '<a href="browse.php?search-text='.$searchtitle.'&topic='.$searchtopic.'&ISO='.$searchcountry.'&city='.$searchcity.'&page=' . $back . '" style = "color : #5f95ff">前一页</a>';
                        $isfull = false;
                        if ($TotalPages > 5) $isfull = true;
                        for ($i = 1; $i <= $TotalPages; $i++) {
                            if ($i == $pagenow) echo '<a href="browse.php?search-text='.$searchtitle.'&topic='.$searchtopic.'&ISO='.$searchcountry.'&city='.$searchcity.'&page=' . $i . '" style = "color : red">' . $i . '</a>';
                            else echo '<a href="browse.php?search-text='.$searchtitle.'&topic='.$searchtopic.'&ISO='.$searchcountry.'&city='.$searchcity.'&page=' . $i . '" style = "color : #5f95ff">' . $i . '</a>';
                            if ($isfull) {
                                echo '<p>......</p>';
                                break;
                            }
                        }
                        echo '<a href="browse.php?search-text='.$searchtitle.'&topic='.$searchtopic.'&ISO='.$searchcountry.'&city='.$searchcity.'&page=' . $next . '" style = "color : #5f95ff">后一页</a>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once('footer.php');?>
</body>
</html>