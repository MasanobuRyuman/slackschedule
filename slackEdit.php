<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="static/style.css">
</head>

<body class="slackEdit_page">
    <div class="slackEdit_frame">
        <form method="POST" action="main.php">
            <?php
            $token = "";
            $channale_name = "";
            $link = mysqli_connect('localhost:8889','root','root','mydb');
            $link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
            $stmt = mysqli_prepare($link,"select token,channelName from slackSettings where userID=?");
            mysqli_stmt_bind_param($stmt,"i",$_SESSION["userID"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt,$result,$result2);
            while (mysqli_stmt_fetch($stmt)){
                $token = $result;
                $channale_name = $result2;
            }
            echo "<p class='token_input_name'>変更後のtoken</p>";
            echo "<input class='token_input' name='changeToken' value='$token'></input>";
            echo "<p class='edit_channel_name'>変更後のchannel名</p>";
            echo "<input class='edit_channel' name='changeChannelName' value='$channale_name'></input>"
            ?>
            <button class="change_slack" name="changeDecsion">変更</button>
            <button class="back_slack" name="back_slack">戻る</button>
        </form>
    </div>
</body>

</html>
