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
if (isset($_POST['submit'])){
    if (empty($_POST['category'])){
        $error = 'Empty category name';
    }
    if (!empty($_POST['category'])) {
        $category = $_POST['category'];
        $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        function check($count) {
            if ($count > 0){
                throw new Exception('Category is already used');
            }
        }
        try {
            $sqlname = "select count(*) from categories where category like'$category'";
            $ss = $pdo->query($sqlname);
            $count = $ss->fetchColumn();
            check($count);
            $stmt = $pdo->prepare('insert into `categories`(`category`) values (:category)');
            $stmt->bindParam(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>カテゴリー登録</title>
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
    <p><?php echo $error; ?></p>
        <form method="POST">
            <input type="text" id="category" name="category">
            <input type="submit" id="submit" name="submit" value="登録">
        </form>
    </div>
</body>
</html>