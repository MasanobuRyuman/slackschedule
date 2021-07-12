<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="static/style.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">予定していること</h1>
        <?php
        $li = array();
        $link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
        $stmt = mysqli_prepare($link,"select content,scheduleTime,beforeTime from contentTime where userID=? and scheduleTime between ? and ?");
        mysqli_stmt_bind_param($stmt,"sss",$_SESSION["userID"],$_SESSION["serch_date_start"],$_SESSION["serch_date_end"]);
        echo $_SESSION["serch_date_strat"];
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt,$result,$result2,$result3);
        while (mysqli_stmt_fetch($stmt)){
            $temp = array($result,$result2,$result3);
            array_push($li,$temp);
        }
        ?>
        <p><?php
        foreach ($li as $content){
            foreach ($content as $content2){
                echo $content2 . '<br />';
            }
            echo "<button name=></button><br />";
        }
        ?></p>

    </div>
</body>

</html>
