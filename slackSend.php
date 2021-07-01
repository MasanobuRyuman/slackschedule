<?php
require_once 'slack.php';
$link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
while (true){
    print("mkta");
    slackSend("xoxb-1970271611367-2173804107493-s77NYSVYmAQkk5SsNLWDxUro","スケジュールのお知らせ","tete");
    print("lota");
    $info = array();
    $nowTime = date("Y/m/d H:i:s");
    $betweenTime = date ('Y/m/d H:i:s' ,strtotime("+". 1 . " Minutes"));
    print($nowTime."\n");
    print($betweenTime."\n");
    #現在時刻から１分いないに送るデータを探す
    $stmt = mysqli_prepare($link,"select beforeTime and content from contentTime where beforeTime=? between beforeTime=? and status == 0");
    mysqli_stmt_bind_param($link,"ss",$nowTime,$betweenTime);
    mysqli_stmt_execute($link);
    mysqli_stmt_bind_result($link,$result,$result2);
    while (mysqli_stmt_fetch($link)){
        $result_array=array($result,$result2);
        array_push($info,$result_array);
    }
    $userID_list=array();
    #送信時間と内容からuserを特定
    foreach ($info as $value) {
        $prepare = mysqli_prepare($link,"selsect userID from contentTime where content = ? and beforeTime = ? ");
        mysqli_stmt_bind_param($link,"ss",$value[0],$value[1]);
        mysqli_stmt_execute($link);
        mysqli_stmt_bind_result($link,$result);
        while (mysqli_stmt_fetch($link)){
            $userID = $result;
            array_push($userID);
        }
        foreach ($userID_list as $user_info){
            $prepare = mysqli_prepare($link,"select token and channelName from slackSettings where userID = ?");
            mysqli_stmt_bind_param($link,"i",$user_info);
            mysqli_stmt_execute($link);
            mysqli_stmt_bind_result($link,$formalVariable,$formalVariable2);
            while (musqli_stmt_fetch($link)){
                $token = $formalVariable;
                $channelName = $formalVariable2;
            }
            slackSend("xoxb-1970271611367-2173804107493-s77NYSVYmAQkk5SsNLWDxUro","スケジュールのお知らせ","tete");
        }


    }


    sleep(1);
}
$close_flag = mysqli_close($link);
?>
