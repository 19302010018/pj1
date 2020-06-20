<?php
    session_start();
    require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <link href = "../css/login.css" rel="stylesheet" type = "text/css">
    <script type = "text/javascript">
        function attention(){
            alert("attention")
        }
    </script>
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>
<?php
require_once ('nav.php');
?>
<div class = "login">
    <h3 class = "login-title">
        登录
    </h3>
    <div class = "login-text">
        <form action = "login.php" method="post">
            <p>
                用户名:
            </p>
            <input type = "text" name = "account" pattern="^\w+$" required>
            <p>
                密码：
            </p>
            <input type = "password" name = "password" id="input1" minlength = "8" pattern="^\w+$" required>
            <input class = "login-submit" type = "submit" value = "登录">
            <br>
            <a href="register.php" style="color: #13227a">没有账号？点我注册</a>
        </form>
    </div>
</div>
</body>
<?php
$username = $_POST['account'];
$password = $_POST['password'];
    try{
        $db = new PDO(DBCONNSTRING,DBUSER,DBPASS);
        $query = "SELECT UserPassword,UserID FROM users WHERE UserName = :username";
        $stmt = $db -> prepare($query);
        $stmt -> bindParam(':username',$username);
        $stmt -> execute();
        $result = $stmt -> fetch();

        $check_password = $result['UserPassword'];
        $UID = $result['UserID'];
        //提出加密后密码
        if(password_verify($password,$check_password))//与用户输入密码进行比对
            $check = true;
        else $check = false;
    }catch (Exception $e){
        echo"Error:".$e->getMessage();
        exit;
}
if(($password!=null)&&($username!=null)) {
    if ($check) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['id'] = $UID;
        echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "登录成功，现为您跳转至主页" . "\"" . ")" . ";" . "</script>";
        echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "../index.php" . "\"" . "</script>";
    } else {
        echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.alert" . "(" . "\"" . "错误的用户名或密码，请尝试重新登录！" . "\"" . ")" . ";" . "</script>";
        echo "<script type=" . "\"" . "text/javascript" . "\"" . ">" . "window.location=" . "\"" . "login.php" . "\"" . "</script>";
    }
}
$db = null;
?>
<?php require_once('footer.php');?>
</html>
