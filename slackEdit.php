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
        echo "<p>変更後のtoken</p>";
        echo "<input name='changeToken' value='$token'></input>";
        echo "<p>変更後のchannel名</p>";
        echo "<input name='changeChannelName' value='$channale_name'></input>"
        ?>
        <button name="changeDecsion">変更</button>
    </form>

</body>

</html>
