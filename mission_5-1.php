<!DOCTYPE html>
<html lang = "ja">

<head>
<meta charset = "UTF-8">
<title>mission_5-1.php</title>
</head>
<body>

    <?php
        //データベース定義
        $dsn = 'データベース名';/*ハイフンを入れない*/
        /*ユーザー名*/
        $user = 'ユーザー名';
        /*パスワード*/
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ERRMODE_WARNING));
        
        $sql = "CREATE TABLE IF NOT EXISTS table01"  //テーブル名をtable01とする
        ."("
        ."id INT AUTO_INCREMENT PRIMARY KEY,"       //id
        ."name char(32),"                           //名前(文字列)
        ."comment TEXT,"                            //コメント(文字列)
        ."time TEXT,"                               //時間(文字列)
        ."pass TEXT"                                //パスワード（文字列）
        .");";                                      
        
        $stmt = $pdo -> query($sql);       

        //各種変数定義
        $P_name = $_POST["name"];                   //取得した名前
        $P_comment = $_POST["comment"];             //取得したコメント
        $P_del = $_POST["del"];                     //取得した削除番号
        $P_edit = $_POST["edit"];                   //取得した編集番号
        $P_secret2 = $_POST["secret2"];             //取得した編集番号合わせ
        $P_pass = $_POST["pass"];                   //取得したパスワード

        $time = date("Y-m-d H:i:s");                //投稿、編集時間

        //条件分岐

        if($P_edit){
            //編集モード移行
            $sql = 'SELECT * FROM table01';
            $stmt = $pdo -> query($sql);
            $results = $stmt -> fetchAll();
            foreach($results as $row){
            if($row['id'] == $P_edit && $row['pass'] == $P_pass){
                $secret = $row['id'];
                $name2 = $row['name'];
                $comment2 = $row['comment'];
                }
            else{
                
                }
            }
            unset($P_pass);
            unset($P_edit);
        }
        else{
            if($P_name  && $P_comment){

                if($P_secret2){
                //投稿編集
                $id = $P_secret2;
                $name = "$P_name";
                $comment = "$P_comment";
                $sql = 'UPDATE table01 SET name = :name, comment = :comment, time = :time WHERE id = :id';
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':name',$name,PDO::PARAM_STR);
                $stmt -> bindParam(':comment',$comment,PDO::PARAM_STR);
                $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
                $stmt -> bindParam(':time',$time,PDO::PARAM_STR);
                $stmt -> execute();
                unset($P_secret2);
                }
                else{
                //新規投稿
                $sql = $pdo -> prepare("INSERT INTO table01(name, comment, time, pass) VALUES(:name, :comment, :time, :pass)");
                $sql -> bindParam(':name',$name,PDO::PARAM_STR);
                $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
                $sql -> bindParam(':time',$time,PDO::PARAM_STR);
                $sql -> bindParam(':pass',$pass,PDO::PARAM_STR);
                $name = $P_name;
                $comment = $P_comment;
                $pass = $P_pass;
                $sql -> execute();   
                }
            unset($P_name);
            unset($P_comment);
            }
            else{
                if($P_del){
                //投稿削除
                    $sql = 'SELECT * FROM table01';
                    $stmt = $pdo -> query($sql);
                    $results = $stmt -> fetchAll();
                    foreach($results as $row){
                    if($row['id'] == $P_del && $row['pass'] == $P_pass){
                        $id = $P_del;       
                        $sql = 'DELETE FROM table01 WHERE id = :id';
                        $stmt = $pdo -> prepare($sql);
                        $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
                        $stmt -> execute();
                    }else{

                        }
                    }
                unset($P_del);
                unset($P_pass);   
                }
            }
        }                    
    ?>

<form action = "" method = "post">
コメントを投稿する
<br>
        <input type = "text" name = "name" value = "<?php echo $name2 ?>" placeholder = "名前を入力">
        <input type = "text" name = "comment" value = "<?php echo $comment2 ?>" placeholder = "コメントを入力">
<br>
        <input type = "password" name = "pass" placeholder = "パスワードを入力">
        <input type = "submit" value = 送信・編集>
<br>
投稿を編集する(投稿時のパスワードが必要です)
        <input type = "number" name = "edit" placeholder = 編集する番号を入力>
        <input type = "hidden" name = "secret2" value = "<?php echo $secret?>">
<br>
投稿を削除する(投稿時のパスワードが必要です)
        <input type = "number" name = "del" placeholder = "削除する番号を入力">
<hr>
</form>

    <?php
    //ブラウザ表示
    $sql = 'SELECT * FROM table01';
    $stmt = $pdo -> query($sql);
    $results = $stmt -> fetchAll();
    foreach($results as $row){
        echo $row['id']."<>";
        echo $row['name']."<>";
        echo $row['comment']."<>";
        echo $row['time'].'<br>';
        echo "<hr>";            
    }    
    ?>
</body>
</html>