
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
        <form method="POST" action="main.php">
            <textarea name="contentfield" id = "contentfield" cols = "30" rows = "10" placeholder = "投稿内容を入力"></textarea><br>
            <p>投稿時刻</p>
            <input type = "time" name = "time" id = "time" ></input><br>
            <input type = "time" name = "beforeTime" id = "beforeTime"></input><br>
            <input name = "schedule" type = "submit" vaule = "決定"></input>

        </form>
        <form method="POST" acthon="main.php">
            <input name = "back" type = "submit" value = "戻る"></input>
        </form>
    </div>
    <footer>
    </footer>

    <script type="text/javascript" src="move.js"></script>
</body>
</html>
