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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $_POST['title']?>の編集</title>
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
    <form method="POST" action="update.php">
        <p>title</p>
        <input type="text" id="title" name="title" value=<?php echo $_POST['title'];?>><br>
        <p>本文</p>
        <textarea name="txt" id="txt" cols="30" rows="10"><?php echo $_POST['txt'];?></textarea>
        <div>
            <p>カテゴリー</p>
        <?php
            foreach($rows as $row){
            ?>
            <input type="radio" name="categories" value="<?php echo $row['category'];?>" <?php if($row['category'] == $_POST['categories']){echo 'checked="checked"';}?>><?php echo $row['category']?><br>
            <?php
            }
            ?>
        </div>
        <input type="hidden" name="id" id="id" value="<?php echo $_POST['id']?>">
        <input type="submit" id="submit" name='submit' value='登録'>
    </form>
    </div>
</body>
</html>