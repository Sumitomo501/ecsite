<?php
session_start();

if (isset($_SESSION['USERID'])){
    header('Location: index.php');
    exit;
}
$db['host'] = '172.20.0.2';
$db['user'] = 'admin';
$db['pass'] = 'password';
$db['dbname'] = 'cms';
$error = '';
try {
    $hash = password_hash('password', PASSWORD_DEFAULT);
    $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    $stmt = $pdo->query('SELECT * FROM users');
    $checkmaster = $stmt->fetch();
    if (in_array('master', $checkmaster) == false) {
        $mname = 'master';
        $mauthority = 'admin,subadmin,readonly';
        $stmt = $pdo->prepare('insert into `users`(`name`, `password`, `userrole`) values (:name, :password, :authority)');
        $stmt->bindParam(':name', $mname, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->bindParam(':authority', $mauthority, PDO::PARAM_STR);
        $stmt->execute();
        $stmt = '';
    } 
    $pdo = null;
} catch (PDOException $e) {
}
if (isset($_POST['login'])) {
    if(empty($_POST['username'])){
        $error = 'Username is empty';
    }else if (empty($_POST['password'])) {
        $error = 'Password is empty';
    }
    if(!empty($_POST['username']) && !empty($_POST['password'])){
        $username = $_POST['username'];
        try {
            $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            $stmt = $pdo->prepare('select*from users where name = ?');
            $stmt->execute(array($username));
            $password = $_POST['password'];
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $result['password'])) {
                $_SESSION['USERID'] = $username;
                $_SESSION['ROLE'] = $result['userrole'];
                header('Location: index.php');
                exit();
            } else {
                $error = 'username or password dose not match';
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>
<html>
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/login.css">
    </head>
    <body>
        <p style="color: #fff;"><?php echo $error; ?></p>
        <div class="form_r">
            <div class="login_form">
                <form id="loginForm" name="loginForm" action="" method="POST">
                    <div class="inp01">
                        <p class="ttl">Username</p>
                        <label class="ef" for="username">
                            <input type="text" id="username" name="username">
                        </label>
                    </div>
                    <div class="inp01">
                        <p class="ttl">Password</p>
                        <label class="ef">
                            <input type="password" id="password" name="password">
                        </label>
                    </div>
                    <div class="b">
                        <input class="sub_b" type="submit" id="login" name="login" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>