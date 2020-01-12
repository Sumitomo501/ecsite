<?php
session_start();

if (!isset($_SESSION['USERID'])){
    header('Location: login.php');
    exit;
}
    if (!empty($_POST['title'] && $_POST['txt'] && $_POST['categories'])) {
        $pdo = new PDO('mysql:host=172.20.0.2;dbname=cms;charset=utf8','admin','password',array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $id = $_POST['id'];
        $title = $_POST['title'];
        $categories = $_POST['categories'];
        $txt = $_POST['txt'];
        try {
            $sql = "update contents set title = :title, categories = :categories, txt = :txt where id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':categories', $categories, PDO::PARAM_STR);
            $stmt->bindParam(':txt', $txt, PDO::PARAM_STR);
            $stmt->execute();
            header('Location: content.php');
        } catch(Exception $e) {
            $error = $e->getMessage();
        }
    }