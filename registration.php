<?php 
session_start();

if (!isset($_SESSION['USERID'])){
    header('Location: login.php');
    exit;
}
$db['host'] = '172.20.0.2';
$db['user'] = 'admin';
$db['pass'] = 'password';
$db['dbname'] = 'cms';
$error = '';
if (isset($_POST['signup'])){
    if (empty($_POST['name'])){
        $error = 'Empty user name';
    } else if (empty($_POST['password'])){
        $error = 'Empty password';
    }
    if(!empty($_POST['name']) && !empty($_POST['password'])){
        $username = $_POST['name'];
        $password = $_POST['password'];
        $authority = implode(",",$_POST['authority']);
        $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        function check($count) {
            if ($count > 0){
                throw new Exception('Username is already used');
            }
        }
        try {
            $sqlname = "select count(*) from users where name like'$username'";
            $ss = $pdo->query($sqlname);
            $count = $ss->fetchColumn();
            check($count);
            $stmt = $pdo->prepare('insert into `users`(`name`, `password`, `userrole`) values (:username, :password, :authority)');
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
            $stmt->bindParam(':authority', $authority, PDO::PARAM_STR);
            $stmt->execute();
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>新規ユーザーを追加</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <?php
    $authoritys = $_SESSION['ROLE'];
    $authority = explode(',',$authoritys);
    ?>
    <div class="left_menu">
    <?php include('left_menu.php'); ?>
    </div>
    <div class="r_content">
        <?php echo $error; ?>
        <form method="POST">
            <input type="text" id="name" name="name">
            <input type="password" id="password" name="password">
            <input type="checkbox" name="authority[]" value="admin">admin
            <input type="checkbox" name="authority[]" value="subadmin">subadmin
            <input type="checkbox" name="authority[]" value="readonly" required>readonly
            <input type="submit" id="signup" name="signup" value="新規登録">
        </form>
    </div>
</body>
</html>