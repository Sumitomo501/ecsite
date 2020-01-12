<ul class="l_menu_ul">
    <?php
        if (in_array('admin',$authority)) {
            echo '<a href="manage.php"><li id="user" class="l_menu">ユーザー管理</li></a>',
            '<a href="registration.php"><li id="adduser" class="l_menu">ユーザー登録</li></a>';
        }
    ?>
    <a href="content.php"><li id="content" class="l_menu">コンテンツ</li></a>
    <?php
        if(in_array('admin', $authority)||in_array('subadmin', $authority)){
            echo '<a href="r_content.php"><li id="addcontent" class="l_menu">コンテンツ追加</li></a>',
            '<a href="category.php"><li id="category" class="l_menu">カテゴリー</li></a>',
            '<a href="r_category.php"><li id="addcategory" class="l_menu">カテゴリー追加</li></a>';
        }
    ?>
    <a href="logout.php"><li class="l_menu">Logout</li></a>
</ul>