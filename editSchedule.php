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
    <form method="POST" action="main.php">
    <?php
    $stmt = mysqli_prepare($link,"select content,scheduleTime,beforeTime from contentTime where userID=? and content = ? and scheduleTime between ? and ?");
    mysqli_stmt_bind_param($stmt,"ssss",$_SESSION["userID"],$_SESSION["edit_schedule_date"],$_SESSION["serch_date_start"],$_SESSION["serch_date_end"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$result,$result2,$result3);
    while (mysqli_stmt_fetch($stmt)){
        $_SESSION["before_content"] = $result;
        $_SESSION["before_date"] = $result2;
        $date = mb_substr($result2,0,11);
        $time = mb_substr($result2,11,5);
        $call_time = mb_substr($result3,11,5);
        echo "<p>予定日</p>";
        echo "<p>$date</p>";
        echo "<p>内容</p>";
        echo "<input name='edit_content' value='$result'></input>";
        echo "<p>予定時間</p>";
        echo "<input name='edit_time' type = time value='$time'></input>";
        //echo "<p>$result3</p>";
        echo "<p>通知時間</p>";
        echo "<input name='edit_after_time' type=time value='$call_time'></input>";
        echo "<button name='schedule_decision'>決定</button>";
        echo "<button name='schedule_delete'>削除</button>";
    }

    ?>
   </form>
</body>
<script type="text/javascript" src="move.js"></script>
</html>
