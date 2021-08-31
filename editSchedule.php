<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <p>gaga</p>
    <?php
    $stmt = mysqli_prepare($link,"select content,scheduleTime,beforeTime from contentTime where userID=? and content = ? and scheduleTime between ? and ?");
    mysqli_stmt_bind_param($stmt,"ssss",$_SESSION["userID"],$_SESSION["edit_schedule_date"],$_SESSION["serch_date_start"],$_SESSION["serch_date_end"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$result,$result2,$result3);
    while (mysqli_stmt_fetch($stmt)){
        echo "lita";
        echo "<p>内容</p>";
        echo "<p>$result</p>";
        echo "<p>$result2</p>";
        echo "<p>$result3</p>";
    }
    ?>
</body>
<script type="text/javascript" src="move.js"></script>
</html>
