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
    <p class="token">token</p>
    <p class="token_setting"><?php
    $link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
    $stmt = mysqli_prepare($link,"select token from slackSettings where userID=?");
    mysqli_stmt_bind_param($stmt,"i",$_SESSION["userID"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$result);
    while (mysqli_stmt_fetch($stmt)){
        $token=$result;
        echo $token;
    }
    ?></p>
    <p class="channel_name">channel名<p>
    <p class="channel_setting"><?php
    $link = mysqli_connect('localhost:8889','root','root','mydb');
    $stmt = mysqli_prepare($link,"select channelName from slackSettings where userID = ?");
    mysqli_stmt_bind_param($stmt,"i",$_SESSION["userID"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$result);
    while (mysqli_stmt_fetch($stmt)){
        $channelName = $result;
        echo $channelName;
    }
    ?></p>
    <form method="POST" action="slackEdit.html">
        <button class="slack_change" name = "slackChange">変更</button>
    </form>
    <form method="POST" action="main.php">
        <button class="slack_back" name="slackSettingEditBack">戻る</button>
    </form>

</body>

</html>
