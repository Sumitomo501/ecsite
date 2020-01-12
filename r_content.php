<?php 
session_start();

if (!isset($_SESSION['USERID'])){
    header('Location: login.php');
    exit;
}
$error = '';
try {
    $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    $stmt = $pdo->query('SELECT * FROM categories');
    while($row = $stmt->fetch()){
        $rows[] = $row;
    }
    $pdo = null;
} catch (PDOException $e) {
    echo $e->getMessage();
}
if (isset($_POST['submit'])){
    if (empty($_POST['title'])) {
        $error = 'empty title';
    } elseif (empty($_POST['txt'])){
        $error = 'empty text';
    }
    if (!empty($_POST['title'] && $_POST['txt'] && $_POST['categories'])) {
        $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $title = $_POST['title'];
        $categories = $_POST['categories'];
        $txt = $_POST['txt'];
        try {
            $stmt = $pdo->prepare('insert into `contents`(`title`,`categories`,`txt`) values (:title, :categories, :txt)');
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':categories', $categories, PDO::PARAM_STR);
            $stmt->bindParam(':txt', $txt, PDO::PARAM_STR);
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
    <title>コンテンツ登録</title>
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
        <p>title</p>
        <input type="text" id="title" name="title"><br>
        <p>本文</p>
        <textarea name="txt" id="txt" cols="30" rows="10"></textarea>
        <div>
            <p>カテゴリー</p>
        <?php
            foreach($rows as $row){
            ?>
            <input type="radio" name="categories" value="<?php echo $row['category']?>" <?php if($row['category'] == '未分類'){echo 'checked="checked"';}?>><?php echo $row['category']?><br>
            <?php
            }
            ?>
        </div>
        <input type="submit" id="submit" name='submit' value='登録'>
    </form>
    </div>
</body>
</html>