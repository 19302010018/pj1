<?php
    echo '<header id="head">
    <nav>
        <h3>
            <div class="div-left">
                <a href="../index.php" class="link">首页</a>
                <a href="browse.php" class="link">浏览</a>
                <a href="search.php" class="link">搜索</a>
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
                      <li><a href="upload.php">上传</a></li>
                      <li><a href="myphoto.php">照片</a></li>
                      <li><a href="favor.php">喜欢</a></li>
                      <li><a href="logout.php">注销</a></li>
                  </div>
             </ul>
             </li>';
    }else{
        echo '<li><a href="login.php">个人中心</a>
                    <ul class="drop">
                        <div>
                            <li><a href="login.php">登陆</a></li>
                            <li><a href="register.php">注册</a></li>
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
?>