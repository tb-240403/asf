<!DOCTYPE html>
<html lang="ja">
    
    <head>
        
        <meta charset="UTF-8">
        <title>mission_5-1</title>
        
    </head>
    
        <body>
            
            <h1>K・E・I・J・I・B・A・N</h1>
            
            <?php
            
                //接続設定
                $dsn = 'mysql:dbname=***;host = localhost';
                $user = '***';
                $password = '***';
                $pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
                    echo "接続完了<br>";
                    
                        //テーブル作成【=mission4-2の一部改変】
                        $sql = "CREATE TABLE IF NOT EXISTS soccer"   //【好きなテーブル名を書く】
                        ."("
                        ."id INT AUTO_INCREMENT PRIMARY KEY," // 投稿番号（INTは数字、AUT以下は「自動で入力される要素」という意味）
                        ."name varchar(32)," //【変更】名前を入力する列（varchar(32)は「32文字以下の文字列」という意味）
                        ."comment varchar(256),"  //【変更】コメントを入力する列（varchar(256)は「256文字以下の文字列」という意味）
                        ."pass varchar(32),"  //【追加】パスワードを入力する列（varchar(32)は「32文字以下の文字列」という意味）
                        ."date TEXT"  //【追加】日付を入力する列（TEXTは「制限のない文字列」という意味）
                        .");";
                        $stmt = $pdo -> query($sql);
                        
                            echo "作業完了<br>";
                            
                            
                            //コメントの内容が存在する場合(=投稿時)
                            if(empty($_POST["comment_to_post"])==false){
                                
                                //名前・コメントを取得する
                                $name_to_post = $_POST["name_to_post"];
                                $comment_to_post = $_POST["comment_to_post"];
                                
                                    //パスワードが入力されている場合
                                    if(empty($_POST["pass_to_post"])==false){
                                        
                                        //パスワードを取得する
                                        $pass_to_post = $_POST["pass_to_post"];
                                        
                                            //投稿済みの投稿番号が存在する場合(=編集投稿)
                                            if(empty($_POST["posted_id"])==false){
                                                
                                                //投稿済みの投稿番号を取得する
                                                $id_to_post = $_POST["posted_id"];
                                                
                                                    //テーブルデータ編集【mission4-7を一部改変】
                                                    $id = $id_to_post;  //【変更】編集投稿する投稿番号
                                                    $name = $name_to_post;  //【変更】編集投稿する名前
                                                    $comment = $comment_to_post;  //【変更】編集投稿するコメント
                                                    $pass = $pass_to_post;   //【変更】編集投稿するパスワード
                                                    $date = date("Y/m/d H:i:s");   //【変更】編集投稿日時
                                                    $sql = 'UPDATE soccer SET name=:name , comment=:comment , pass=:pass , date=:date WHERE id=:id';    
                                                    //【追加】　UPDATEの後ろをテーブル名（syuukastu）にする。comment=:commnetの後ろに、pass=:passと,date=:dateを追加。
                                                    $stmt = $pdo -> prepare($sql);
                                                    $stmt -> bindParam(':name',$name,PDO::PARAM_STR);  //名前
                                                    $stmt -> bindParam(':comment',$comment,PDO::PARAM_STR);   //コメント
                                                    $stmt -> bindParam(':id',$id,PDO::PARAM_INT);   //投稿番号
                                                    $stmt -> bindParam(':pass',$pass,PDO::PARAM_STR);   //【追加】パスワード
                                                    $stmt -> bindParam(':date',$date,PDO::PARAM_STR);   //【追加】日時
                                                    $stmt -> execute();
                                                    
                                                        echo "編集投稿完了<br>";
                                            
                                            
                                            
                                            
                                            //投稿済みの投稿番号が存在しない場合（＝新規投稿）      
                                            }else{
                                                
                                                //テーブルデータ書き込み【mission4-5を一部改変】
                                                $sql = $pdo -> prepare("INSERT INTO soccer (name,comment,pass,date) VALUES (:name,:comment,:pass,:date)");
                                                //【追加】INSET INTOの後ろにテーブル名（syuukatsu）にする。commentの後ろに,passと,dateを、:commentの後ろに,:passと,:dateを追加。
                                                
                                                    $sql -> bindParam(':name',$name,PDO::PARAM_STR);   //名前
                                                    $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);    //コメント
                                                    $sql -> bindParam(':pass',$pass,PDO::PARAM_STR);   //【追加】パスワード
                                                    $sql -> bindParam(':date',$date,PDO::PARAM_STR);   //【追加】日時
                                                    $name = $name_to_post;   //【変更】名前
                                                    $comment = $comment_to_post;   //【変更】コメント
                                                    $pass = $pass_to_post;   //【変更】
                                                    $date = date("Y/m/d H:i:s");   //【変更】日時
                                                    $sql -> execute();
                                                    
                                                        echo "新規投稿完了<br>";
                                                
                                                
                                            }
                                            
                                            
                                    }
                                
                            }
                            
                            
                            
                            
                            //削除すべき投稿番号が存在する場合（＝削除時）
                            elseif(empty($_POST["id_to_delete"])==false){
                                
                                //削除すべき投稿番号を取得する
                                $id_to_delete = $_POST["id_to_delete"];
                                
                                    //パスワードが入力されている場合
                                    if(empty($_POST["pass_to_delete"])==false){
                                        
                                        //パスワードを取得する
                                        $pass_to_delete = $_POST["pass_to_delete"];
                                        
                                            //テーブルデータの抽出【mission4-6の一部改変】
                                            $id = $id_to_delete;   //【変更】削除すべき投稿番号
                                            $sql = 'SELECT * FROM soccer WHERE id=:id';   //【変更】SELECT * FROMの後ろをテーブル名（syuukatsu）に変更
                                            $stmt = $pdo -> prepare($sql);
                                            $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
                                            $stmt -> execute();
                                            $results = $stmt -> fetchAll();
                                            foreach($results as $row){
                                                
                                                //【変更】このforeach分の中に、テーブルデータ削除のコードを書き込む。
                                                
                                                    //抽出したパスワードが一致した場合
                                                    if($row['pass']==$pass_to_delete){
                                                        
                                                        //テーブルデータの削除
                                                        $sql = 'delete from soccer where id = :id';   //【変更】delete fromの後ろをテーブル名（syuukatsu）に変更
                                                        $stmt = $pdo -> prepare($sql);
                                                        $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
                                                        $stmt -> execute();
                                                        
                                                            echo "削除完了<br>";
                                                        
                                                    }
                                                
                                            }
                                            
                                    }
                                
                            }
                            
                            
                            
                            //編集すべき投稿番号が存在する場合（＝編集時）
                            elseif(empty($_POST["id_to_edit"])==false){
                                
                                //編集すべき投稿番号を取得する
                                $id_to_edit=$_POST["id_to_edit"];
                                
                                    //パスワードが入力されている場合
                                    if(empty($_POST["pass_to_edit"])==false){
                                        
                                        //パスワードを取得する
                                        $pass_to_edit = $_POST["pass_to_edit"];
                                        
                                            //テーブルデータの抽出【mission4-6後半を一部改変】
                                            $id = $id_to_edit;   //【変更】編集すべき投稿番号
                                            $sql = 'SELECT *FROM soccer WHERE id = :id';    //SELECT * FROM の後ろをテーブル名（syuukatsu）に変更
                                            $stmt = $pdo -> prepare($sql);
                                            $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
                                            $stmt -> execute();
                                            $results = $stmt -> fetchAll();
                                            foreach($results as $row){
                                                
                                                //【変更】このforeach文の中に、テーブルデータの内容を取得するコードを書く。
                                                
                                                    //パスワードが一致する場合
                                                    if($row['pass']==$pass_to_edit){
                                                        
                                                        $posted_id = $row['id'];   //投稿番号
                                                        $posted_name = $row['name'];    //名前
                                                        $posted_comment = $row['comment'];   //コメント
                                                        $posted_pass = $row['pass'];    //パスワード
                                                        
                                                            echo "編集準備完了<br>";
                                                        
                                                    }
                                                
                                            }
                                        
                                    }
                                
                            }
            
            ?>
            
            
            
            <hr>
            
                <h2>メッセージを投稿</h2>
                
                <form method = 'POST' action =''> 
                    
                        <input
                            type = 'hidden'
                            name = 'posted_id'
                            value = <?php if(isset($posted_id)==true){echo $posted_id;}?>
                        >
                    
                    →名前：
                        <input
                            type = 'text'
                            name = 'name_to_post'
                            value = <?php if(isset($posted_name)==true){echo $posted_name;}else{echo "名前がないよ？";}?>
                        >
                        
                    <br>
                        
                    →メッセージ：
                        <input
                            type = 'text'
                            style = "width: 400px;"
                            name = 'comment_to_post'
                            placeholder = 'コメント入力'
                            value = <?php if(isset($posted_comment)==true){echo $posted_comment;}?>
                        >
                    
                    <br>
                    
                    →PASS：
                        <input
                            type = 'text'
                            name = 'pass_to_post'
                            placeholder = 'パスワード入力'
                            value = <?php if(isset($posted_pass)==true){echo $posted_pass;}?>
                        >
                        
                    <br>
                    
                        <input
                            type = 'submit'
                            name = 'submit'
                            value = '送信'
                        >
                    
                </form>
                
                <br>
                <hr>
                
                    <h2>メッセージを削除</h2>
                    
                        <form method='POST' action=''>
                            
                            →投稿番号：
                                <input
                                    type = 'number'
                                    name = 'id_to_delete'
                                    placeholder = '投稿番号を入力'
                                >
                                
                            <br>
                            
                            →PASS：
                                <input
                                    type = 'text'
                                    name = 'pass_to_delete'
                                    placeholder = 'パスワードを入力'
                                >
                                
                            <br>
                            
                                <input
                                    type = 'submit'
                                    name = 'delete'
                                    value = '削除'
                                >
                            
                        </form>
                        
                <br>
                <hr>
                
                    <h2>メッセージを編集</h2>
                    
                        <form method = 'POST' action=''>
                            
                            →投稿番号：
                                <input
                                    type = 'number'
                                    name = 'id_to_edit'
                                    placeholder = '投稿番号を入力'
                                    
                                >
                            
                            <br>
                                
                            →PASS：
                                <input
                                    type = 'text'
                                    name = 'pass_to_edit'
                                    placeholder = 'パスワードを入力'
                                
                                >
                            
                            <br>
                                    
                                <input
                                    type = 'submit'
                                    name = 'delete'
                                    value = '編集'
                                
                                >
                                
                        </form>
                    
                    <br>    
                    <hr>
                    
                        <h2>スレッド</h2>
                        
                            <?php
                            
                                //テーブルデータの表示【mission4-6前半を一部改変】
                                $sql = 'SELECT * FROM soccer';   //【変更】　SELECT * FROMの後ろをテーブル名（syuukatsu）に変更
                                $stmt = $pdo -> query($sql);
                                $results = $stmt -> fetchAll();
                                foreach($results as $row){
                                    
                                    echo $row['id'].' ';   //投稿番号
                                    echo $row['name'].' ';   //名前
                                    echo $row['comment'].' ';    //コメント
                                    echo $row['date'].'<br>';    //【追加】日時
                                    
                                }
                                
                            ?>    
            
                
        </body>
        
</html>