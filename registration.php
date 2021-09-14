<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <div class="hedder">
        <h1 class="titleName">MySchedlue</h1>
    </div>
    <div name="wrap">
        <h1 name="date" class="date">日付</h1>
        <?php
        //echo $_SESSION['date'];
        $dom = new DOMDocument();
        $element = $dom->createElement('input',);
        $element -> setAttribute("name", "scheduleDate");
        $element -> setAttribute("type" ,"hidden");
        $element -> setAttribute("vaule" , $_SESSION['date']);
        $dom->appendChild($element);
        echo $dom->saveHTML();
        ?>

        <?php
        $li = array();
        $link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
        $stmt = mysqli_prepare($link,"select content,scheduleTime,beforeTime from contentTime where userID=? and scheduleTime between ? and ?");
        mysqli_stmt_bind_param($stmt,"sss",$_SESSION["userID"],$_SESSION["serch_date_start"],$_SESSION["serch_date_end"]);
        $year = substr($_SESSION["serch_date_start"],0,4);
        $month = substr($_SESSION["serch_date_start"],5,2);
        $day = substr($_SESSION["serch_date_start"],8,2);
        echo "<p class='schedule_day'>$year 年 $month 月 $day 日</p>";
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$result,$result2,$result3);
        while (mysqli_stmt_fetch($stmt)){
            $temp = array($result,$result2,$result3);
            array_push($li,$temp);
        }
        ?>


        <p><form method = "POST" action="main.php"><?php
        foreach ($li as $content){
            $schedule_content = "temp";
            $cou = 0;
            echo "<p class='schedule_content'>予定内容</p>";
            foreach ($content as $content2){
                if ($cou == 0){
                    $content2 = htmlentities($content2, ENT_QUOTES, 'UTF-8');
                    echo "<p class='content'>$content2</p>";
                }else if ($cou == 1){
                    echo "<p class='schedule_time_name'>予定時間</p>";
                    $hour = substr($content2,11,2);
                    $minutes = substr($content2,14,2);
                    echo "<p class='schedule_time'>$hour 時　$minutes 分</p>";
                }else if($cou == 2){
                    echo "<p class='notice_time'>通知時間</p>";
                    $hour = substr($content2,11,2);
                    $minutes = substr($content2,14,2);
                    echo "<p class='schedule_time'>$hour 時　$minutes 分</p>";
                }

                $cou += 1;
            }
            $cou = 0;
            echo "<button class='$schedule_content' name='editButton' id='editButton' onclick='edit()'>編集</button><br />";
        }
        echo "<input name='scheduleKey' id='scheduleKey' type='hidden'>";
        ?></form></p>
        <p>予定追加</p>
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
