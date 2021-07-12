<?php
require_once 'slack.php';
$link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');

while (true){
    $info = array();
    $nowTime = date("Y/m/d H:i:s");
    $betweenTime = date ('Y/m/d H:i:s' ,strtotime("+". 1 . " Minutes"));
    $stmt = mysqli_prepare($link,"select token,channelName,content scheduleTime from contentTime join slackSettings ON contentTime.userID = slackSettings.userID where status = 1 and beforeTime between ? and ?");
    mysqli_stmt_bind_param($stmt,"ss",$nowTime,$betweenTime);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$result,$result2,$result3,$result4,$result5);
    while (mysqli_stmt_fetch($stmt)){
        slackSend($result,$result2,$result3);
        $prepare = mysqli_prepare($link,"update product set statsu=0 where content=? and scheduleTime=? and userID=?");
        mysqli_stmt_bind_param($prepare,"ssi",$result3,$result4,$result5);
        mysqli_stmt_execute($prepare);
    }
    sleep(1);

}
$close_flag = mysqli_close($link);
?>
