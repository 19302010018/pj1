<?php
session_start();
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php
    if(!$_GET['imageid']){

    echo '<title>上传</title>';
    }else{
        echo'<title>修改</title>';
    }
    ?>
    <link href="../css/upload.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        var file = document.getElementById('file');
        var image = document.getElementById("PicFromUser");
        file.onchange = function() {
            var fileData = this.files[0];
            var pettern = /^image/;
            console.info(fileData.type)
            if (!pettern.test(fileData.type)) {
                alert("图片格式不正确");
                return;
            }
            var reader = new FileReader();
            reader.readAsDataURL(fileData);
            reader.onload = function(e) {
                console.log(e);
                console.log(this.result);
                image.setAttribute("src", this.result);
            }
            document.getElementById("placeholder").style.display = "none";
        }

    </script>
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
    </script><!--二级联动 --!>
</head>
<body>
<?php
require_once ('nav.php');
?>
<br>
<br>

<main>
    <?php
        if(!isset($_GET['imageid'])){
            echo'<form method="post" action="img.php" enctype="multipart/form-data">';
        }else{
            $imageid = $_GET['imageid'];
            echo'<form method="post" action="imgchange.php?imageid='.$imageid.'" enctype="multipart/form-data">';
        }
    ?>
        <fieldset>
            <?php
            if(!isset($_GET['imageid'])){
                echo '<legend>上传图片</legend>';
            }else{
                echo '<legend>修改图片</legend>';
            }
            ?>
            <div class="uploadPic">
                <img id="PicFromUser" src="">
                <p id="placeholder">暂未上传图片</p>
            </div>
            <div class="wrap">
                <input type="file" id="file" name="file" accept="image/*">
                <span>选择</span>
            </div>

            <label>图片名: <input type="text" name = "name" required></label>
            <label>图片描述: <textarea name = "description" required></textarea></label>
            <form name="form1" method="post">
                <select name="topic" class="select" required>
                    <option value="">选择主题</option>
                    <option value="scenery">风景</option>
                    <option value="city">城市</option>
                    <option value="figure">人像</option>
                    <option value="animals">动物</option>
                    <option value="building">建筑</option>
                    <option value="wonder">奇观</option>
                </select>
                <select name="country" class="select" id="address" onclick="change()" required>
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
                <select name="city" class="select" id="city" required>
                    <option value="">选择城市</option>
                    <option value="2643743">伦敦</option>
                    <option value="3169070">罗马</option>
                    <option value="3176959">佛罗伦萨</option>
                    <option value="5913490">卡尔加里</option>
                </select>
                <?php
                $buttoninformation = (isset($_GET['imageid']))?"修改":"上传";
            echo'<input type="submit" value="'.$buttoninformation.'">';
            ?>
            </form>
        </fieldset>
    </form>
</main>
<?php require_once('footer.php');?>
</body>
<script type="text/javascript">
    var file = document.getElementById('file');
    var image = document.getElementById("PicFromUser");
    file.onchange = function() {
        var fileData = this.files[0];//获取到一个FileList对象中的第一个文件( File 对象),是我们上传的文件
        var pettern = /^image/;

        console.info(fileData.type)

        if (!pettern.test(fileData.type)) {
            alert("图片格式不正确");
            return;
        }
        var reader = new FileReader();
        reader.readAsDataURL(fileData);//异步读取文件内容，结果用data:url的字符串形式表示
        /*当读取操作成功完成时调用*/
        reader.onload = function(e) {
            console.log(e); //查看对象
            console.log(this.result);//要的数据 这里的this指向FileReader（）对象的实例reader
            image.setAttribute("src", this.result);
        }
        document.getElementById("placeholder").style.display = "none";
    }
</script>
</html>