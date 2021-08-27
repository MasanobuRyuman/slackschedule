<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <header>
        <h1>Slackカレンダ</h1>
    </header>
    <div name="wrap">
        <h1 name="date">日付</h1>
        <?php
        echo $_SESSION['date'];
        $dom = new DOMDocument();
        $element = $dom->createElement('input',);
        $element -> setAttribute("name", "scheduleDate");
        $element -> setAttribute("type" ,"hidden");
        $element -> setAttribute("vaule" , $_SESSION['date']);
        $dom->appendChild($element);
        echo $dom->saveHTML();
        ?>

        <p>予定内容</p>

        <?php
        $li = array();
        $link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
        $stmt = mysqli_prepare($link,"select content,scheduleTime,beforeTime from contentTime where userID=? and scheduleTime between ? and ?");
        mysqli_stmt_bind_param($stmt,"sss",$_SESSION["userID"],$_SESSION["serch_date_start"],$_SESSION["serch_date_end"]);
        echo $_SESSION["serch_date_start"];
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$result,$result2,$result3);
        while (mysqli_stmt_fetch($stmt)){
            $temp = array($result,$result2,$result3);
            array_push($li,$temp);
        }
        ?>
        <p><form method="POST" action="main.php"><?php
        foreach ($li as $content){
            foreach ($content as $content2){
                $content2 = htmlentities($content2, ENT_QUOTES, 'UTF-8');
                echo $content2 . '<br />';
                echo "<button class='$content2' name='editButton' id='editButton'>編集</button><br />";
            }
        }
        echo "<input name='scheduleKey' id='scheduleKey' type='hidden'>";
        ?></form></p>

        <form method="POST" action="main.php">
            <textarea name="contentfield" id = "contentfield" cols = "30" rows = "10" placeholder = "投稿内容を入力"></textarea><br>
            <p>投稿時刻</p>
            <input type = "time" name = "time" id = "time" ></input><br>
            <input type = "time" name = "beforeTime" id = "beforeTime"></input><br>
            <input name = "schedule" type = "submit" vaule = "決定"></input>
        </form>
        <form method="POST" action="main.php">
            <input name = "back" type = "submit" value = "戻る"></input>
        </form>
    </div>
    <footer>
    </footer>

    <script type="text/javascript" src="move.js"></script>
</body>
</html>
