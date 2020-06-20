<?php
    require_once('config.php');
    $username = $_POST["account"];
    $password1 = $_POST["password"];
    $password2 = $_POST["confirm"];
    $email = $_POST["email"];
    /*
    $concode1 = $_POST["concode1"];
    $concode2 = $_POST["concode2"];
    if($concode1 != $concode2)$wrong_alert="验证码错误！";
    if($password1 != $password2)$wrong_alert="两次输入密码不一致！";
*/

    /*$timeTarget = 0.05;
    $cost = 8;
    do{
        $cost++;
        $start = microtime(true);
        password_hash($password1,PASSWORD_BCRYPT,["cost" => $cost]);
        $end = microtime(true);
    }while (($end - $start) < $timeTarget);
    echo $cost;
    //测试服务器所能承受的最高cost，不拖慢服务器的情况下最大幅度提高安全性
    */
    $options=[
        'cost' => 10,
        ];
    $password3 = password_hash($password1,PASSWORD_BCRYPT,$options);//经加盐hash后的散列，用于后续login验证

    try{
        $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $query = "SELECT UserName FROM users WHERE UserName = :username";
        $stmt = $db ->prepare($query);
        $stmt -> bindParam(':username',$username);
        $stmt -> execute();
        if($stmt -> rowCount() > 0) $wrong_alert = "该用户名已被注册！";
    }catch (Exception $e){
        echo "Error:".$e -> getMessage();
        exit;
    }
    //查找是否用户名已被注册
/*
    try {
        $query = "SELECT * FROM users WHERE UserName = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
    }catch(Exception $e){
        echo'<p style="color:red">查询出错！</p>';
    }
*/
    if($username!=null) {
        if ($wrong_alert != null) {
            echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "$wrong_alert" . "\"" . ")" . ";" . "</script>";
            echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "register.php" . "\"" . "</script>";
            exit;
        } else {
            $stmt = null;
            $query = "INSERT INTO users(UserName,UserPassword,UserEmail) VALUES(:username,:password,:email)";//将注册信息加入数据库
            $stmt = $db->prepare($query);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password3);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
        }
        if ($stmt) {
            $db = NULL;//关闭数据库
            echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "注册成功，将为您跳转至登陆界面！" . "\"" . ")" . ";" . "</script>";
            echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "login.php" . "\"" . "</script>";
        } else {
            echo "<h1 style='color: red'>数据导入不成功！</h1>";
        }
    }
    $db = NULL;//关闭数据库
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
    <link href = "../css/register.css" rel="stylesheet" type = "text/css">
    <script type = "text/javascript">
        function attention(){
            alert("attention")
        }
    </script>
    <script>
        var str1,str2;
        function check2pwd(str1,str2) {
            if(str1.value != str2.value) {
                alert("两次输入密码不一致！")
                str1.value = "";
                str2.value = "";
            }
        }
    </script>
</head>

<body>
<?php
    require_once ('nav.php');
?>
<div class = "login">
    <h3 class = "login-title">
        注册
    </h3>
    <div class = "login-text">
        <form action = "register.php" method="post">
            <p>
                用户名:
            </p>
            <input type = "text" name = "account" pattern="^\w+$" required>
            <p>
                请设置密码（不小于八位）：
            </p>
            <input type = "password" name = "password" id="input1" minlength = "8" pattern="^\w+$" required>
            <p>
                重新确认密码：
            </p>
            <input type = "password" name = "confirm" id = "input2" onblur="check2pwd(input1,input2)">
            <p>
                请输入邮箱：
            </p>
            <input type = "email" name = "email" required>
            <p>
                请输入验证码：
            </p>
            <div id="code" name = "concode1"></div>
            <script>
                var codeStr = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                var oDiv = document.getElementById('code');
                function getRandom(n, m) {
                    n = Number(n);
                    m = Number(m);
                    if (n > m) {
                        var temp = n;
                        n = m;
                        m = temp;
                    }
                    return Math.floor(Math.random()*(m - n) + n);
                }
                function getCode() {
                    var str = '';
                    for (var i = 0;i < 4;i ++) {
                        var ran = getRandom(0, 62);
                        str += codeStr.charAt(ran);
                    }
                    oDiv.innerHTML = str;
                }
                getCode();
                oDiv.onclick = function(){
                    getCode();
                }
            </script>
            <input type = "text" id = "concode2" required>
            <input class = "register" type = "submit" value = "注册" name = "submit">
            <br>
            <a href="login.php" style="color: #13227a">已有账号？点我登陆</a>
        </form>
    </div>
</div>
</body>
<?php require_once('footer.php');?>
</html>
