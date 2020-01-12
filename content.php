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
try {
    $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    $stmt = $pdo->query('SELECT * FROM contents');
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
    <title>コンテンツ</title>
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
    <table class="usertable">
        <tbody>
            <tr class="tablecell">
                <th>タイトル</th>
                <th>カテゴリー</th>
            </tr>
            <?php
            foreach($rows as $row){
            ?>
            <tr class="tablecell">
                <td><?php echo $row['title'];?></td>
                <td><?php echo $row['categories'];?></td>
                <?php
        if(in_array('admin', $authority)||in_array('subadmin', $authority)){
            echo <<<EOM
            <td>
                    <form action="edit.php" method="post">
                        <input type="submit" value="編集する">
                        <input type="hidden" name="id" id="id" value="{$row['id']}">
                        <input type="hidden" name="title" id="title" value="{$row['title']}">
                        <input type="hidden" name="categories" id="categorirs" value="{$row['categories']}">
                        <input type="hidden" name="txt" id="txt" value="{$row['txt']}">
                    </form>
                </td>
EOM;
        }
            ?>

            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    </div>
</body>
</html>