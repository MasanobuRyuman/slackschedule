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
    $_SESSION["before_schedule_time"] = mb_substr($_SESSION["edit_schedule_date"],0,19);
    $_SESSION["before_schedule_calltime"] = mb_substr($_SESSION["edit_schedule_date"],21,18);
    $_SESSION["before_schedule_content"] = mb_substr($_SESSION["edit_schedule_date"],40);

    $date = mb_substr($_SESSION["before_schedule_time"],0,11);
    $time = mb_substr($_SESSION["before_schedule_time"],11,5);
    $call_time = mb_substr($_SESSION["before_schedule_calltime"],10,5);
    $content = $_SESSION["before_schedule_content"];

    echo "<p class='edit_scheudle_day_name'>予定日</p>";
    echo "<p class='edit_schedule_date'>$date</p>";
    echo "<p class='edit_schedule_content_name'>内容</p>";
    echo "<input class='edit_schedule_content' name='edit_content' value='$content'></input>";
    echo "<p class='edit_schedule_date_name'>予定時間</p>";
    echo "<input class='edit_schedule_date' name='edit_time' type = time value='$time'></input>";
    //echo "<p>$result3</p>";
    echo "<p class='edit_schedule_calltime_name'>通知時間</p>";
    echo "<input class='edit_schedule_calltime' name='edit_after_time' type=time value='$call_time'></input>";
    echo "<button class='edit_schedule_decision' name='schedule_decision'>決定</button>";
    echo "<button class='edit_scehdule_delete' name='schedule_delete'>削除</button>";
    ?>
   </form>
</body>
<script type="text/javascript" src="move.js"></script>
</html>
