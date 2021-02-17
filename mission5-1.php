<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
     //データベースに接続
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass TEXT,"
	. "date TEXT"
	.");";
	$stmt = $pdo->query($sql);
    //編集する行番号を受信
    if(!empty($_POST["edit"]) and !empty($_POST["pass3"])){
        $edit = $_POST["edit"];
        $pass3 = $_POST["pass3"];
        $sql = 'SELECT * FROM tbtest';
	    $stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    	    if($row['id']==$edit and $row['pass']==$pass3){
    	        $editname = $row['name'];
    	        $editcomment = $row['comment'];
    	        break;
    	    }
    	}
    }
    ?>
    <br>
    <form action="" method="post">
    <input type="text" name="name" placeholder="名前" value=
    "<?php if(isset($editname)){echo($editname);}?>">
    <input type="text" name="comment" placeholder="コメント"  value=
    "<?php if(isset($editcomment)){echo($editcomment);}?>">
    <input type="hidden" name="judge" value=
    "<?php if(isset($edit)){echo($edit);}?>">
    <input type="text" name="pass1" placeholder="パスワード">
    <input type="submit" name="submit">
    </form>
    <form action="" method="post">
    <input type="text" name="delete" placeholder="削除対象番号">
    <input type="text" name="pass2" placeholder="パスワード">
    <input type="submit" name="submit">
    </form>
    <form action="" method="post">
    <input type="text" name="edit" placeholder="編集対象番号">
    <input type="text" name="pass3" placeholder="パスワード">
    <input type="submit" name="編集">
    ←正しいパスワードを入力すると一番上のフォームで名前とコメント、パスワードを更新できます。
    </form><br>

    <?php
    //書き込み
    if(!empty($_POST["name"] and !empty($_POST["comment"]))
    and !empty($_POST["pass1"]) and empty($_POST["judge"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $pass1 = $_POST["pass1"];
        $date = date("Y/m/d H:i:s");
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, pass, date)
        VALUES (:name, :comment, :pass, :date)");
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	    $sql -> bindParam(':pass', $pass1, PDO::PARAM_STR);
	    $sql -> bindParam(':date', $date, PDO::PARAM_STR);
	    $sql -> execute();
    }

    //削除
    if(!empty($_POST["delete"]) and !empty($_POST["pass2"])){
        $id = $_POST["delete"];
        $pass2 = $_POST["pass2"];
        $sql = 'delete from tbtest where id=:id and pass=:pass2';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':pass2', $pass2, PDO::PARAM_STR);
        $stmt->execute();
    }

    //編集
    if(!empty($_POST["name"]) and !empty($_POST["comment"])
    and !empty($_POST["pass1"]) and !empty($_POST["judge"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $pass1 = $_POST["pass1"];
        $date = date("Y/m/d H:i:s");
        $id = $_POST["judge"];
        $sql = 'UPDATE tbtest SET name=:name,comment=:comment,pass=:pass1,date=:date WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':pass1', $pass1, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

  //表示
  $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].', ';
		echo $row['name'].', ';
		echo $row['date'].'<br>';
		echo $row['comment'];
		//echo $row['pass'];
	    echo "<hr>";
	}
    ?>



</body>
</html>
