<?php session_start(); ?>
<?php
ini_set('display_errors', "On");
#mysqlに繋いでいる。
$link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
//DBに繋いでいる。
$db_selected = mysqli_select_db($link,"mydb");
#form画面から「ログイン・新規登録」が押された
if (isset ($_POST["registration"])) {
    $doc = new DOMDocument();
    $doc -> loadHTMLFile("login.html");
    echo $doc -> saveHTML();
}

#ログイン画面から「ログイン」が押されたら
if (isset ($_POST["login"])) {
    $userName = $_POST['user'];
    $password = $_POST['password'];
    #名前とパスワードが一致していることを条件としている。
    $stmt = mysqli_prepare($link,"select count(*) from user use index(name_password) where name = ? and password = ?");
    mysqli_stmt_bind_param($stmt, 'ss',$userName,$password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$col);
    $existence = 0;
    while (mysqli_stmt_fetch($stmt)){
        $existence=$col;
    }
    if ($existence == 1){
        $prepare = mysqli_prepare($link,"select userID from user where name = ? ");
        mysqli_stmt_bind_param($prepare,'s',$userName);
        mysqli_stmt_execute($prepare);
        mysqli_stmt_bind_result($prepare,$result);
        while (mysqli_stmt_fetch($prepare)){
            $_SESSION["userID"] = $result;
        }
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("main.html");
        echo $doc -> saveHTML();
    } else {
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("login.html");
        echo $doc -> saveHTML();
        $alert = "<script type='text/javascript'>alert('名前かパスワードが間違っています。');</script>";
        echo $alert;
    }
}

#ログイン画面から「新規登録」が押されたら
if (isset ($_POST["newlogin"])) {
    $userName = $_POST['user'];
    $password = $_POST['password'];
    #名前が被っていないことを条件としている。
    $stmt = mysqli_prepare($link,"select count(*) from user use index(name) where name = ?");
    mysqli_stmt_bind_param($stmt, 's',$userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$col);
    $existence = 0;
    while (mysqli_stmt_fetch($stmt)){
        $existence=$col;
    }
    if ($existence == 0){
        /* プリペアドステートメントを作成します */
        $st = mysqli_prepare($link, "INSERT INTO user(name,password) values(?,?)");
        /* マーカにパラメータをバインドします */
        mysqli_stmt_bind_param($st, 'ss',$userName,$password);
        mysqli_stmt_execute($st);
        $prepare = mysqli_prepare($link,"select userID from user where name = ? ");
        mysqli_stmt_bind_param($prepare,'s',$userName);
        mysqli_stmt_execute($prepare);
        mysqli_stmt_bind_result($prepare,$result);
        while (mysqli_stmt_fetch($prepare)){
            $_SESSION["userID"] = $result;
        }
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("main.html");
        echo $doc -> saveHTML();
    } else {
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("login.html");
        echo $doc -> saveHTML();
        $alert = "<script type='text/javascript'>alert('名前が同じ人がいます。名前を変えてください。');</script>";
        echo $alert;
    }
}

#ログイン画面から戻るが押されたらform画面に戻る
if(isset($_POST["formBack"])){
    $doc = new DOMDocument();
    $doc -> loadHTMLFile("index.html");
    echo $doc -> saveHTML();
}

#main画面から日付がクリックされたら
if (isset($_POST["scheduleSetting"])){

    $_SESSION['date'] = $_POST["date"];
    $schedule_date_complete = $_SESSION['date'] ." " . "00:00";
    $_SESSION["serch_date_start"] = date('Y-m-d H:i',strtotime($schedule_date_complete));
    $_SESSION["serch_date_end"] = date('Y-m-d H:i',strtotime($schedule_date_complete."+". 23 ."hour"."+". 59 ."minutes"));
    $stmt = mysqli_prepare($link,"select count(*) from contentTime where scheduleTime between ? and ?");
    mysqli_stmt_bind_param($stmt,"ss",$_SESSION["serch_date_start"],$_SESSION["serch_date_end"]);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$cou);
    while (mysqli_stmt_fetch($stmt)){
        $count = $cou;
    }

    if ($count >= 1){
        require "settingSchedule.php";
    }else{
        require "registration.php";
    }
}

#予定入力ページから「決定」が押された後
if (isset ($_POST["contentfield"])){
    #予定の内容と時間と通知時間が書かれているかをチェック
    #内容がどれか一つでも入っていないものがあったら移動しない
    if (empty($_POST["time"]) || empty($_POST["beforeTime"]) || empty($_POST["contentfield"])) {
        require("registration.php");
        $alert = "<script type='text/javascript'>alert('必要な項目を入力されていません');</script>";
        echo $alert;
    }else{
        $userID = $_SESSION["userID"];
        $schedule_date = $_SESSION["date"];
        $content = $_POST["contentfield"];
        $schedule_Time = $_POST["time"];
        $before_Time = $_POST["beforeTime"];
        #deta型で読み込めるように形を整えている。
        $schedule = $schedule_date ." " . $schedule_Time;
        $beforeHour = substr($before_Time,0,2);
        $beforeMinutes = substr($before_Time,3);
        #通知を送る時間を作っている。
        $before = date('Y/m/d H:i',strtotime($schedule . "-" . $beforeHour . "hour" . "-" . $beforeMinutes . "minutes"));
        echo $before;
        echo $schedule;
        /* プリペアドステートメントを作成します */
        $stmt = mysqli_prepare($link, "INSERT INTO contentTime(userID,content,scheduleTime,beforeTime,status) values(?,?,?,?,1)");
        /* マーカにパラメータをバインドします */
        mysqli_stmt_bind_param($stmt, 'isss',$userID,$content,$schedule,$before);
        mysqli_stmt_execute($stmt);
        print(mysqli_error($link));
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("main.html");
        echo $doc -> saveHTML();
    }
}

#registration.phpから戻るが押されたら
if (isset($_POST["back"])){
    $doc = new DOMDocument();
    $doc -> loadHTMLFile("main.html");
    echo $doc -> saveHTML();
}

#main.htmlからslackの設定が押されたら
if (isset ($_POST["slackSetting"])){
    $stmt = mysqli_prepare($link,"select count(*) from slackSettings use index(userID) where userID=?");
    mysqli_stmt_bind_param($stmt, 'i',$_SESSION["userID"],);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$col);
    $existence = 0;
    while (mysqli_stmt_fetch($stmt)){
        $existence=$col;
    }
    #すでにTokenとchannel名が設定されているか
    if ($existence >= 1){
        require "slackSettingEdit.php";
    }else{
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("slacksetting.html");
        echo $doc -> saveHTML();
    }
}

#slackSetting.htmlから決定が押されたら
if (isset ($_POST["slackDecision"])){
    if (empty($_POST["token"]) || empty($_POST["channelName"])){
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("slackSetting.html");
        echo $doc -> saveHTML();
        $alert = "<script type='text/javascript'>alert('tokenかchannel名が入力されていません。');</script>";
        echo $alert;
    }else{
        $_SESSION["Token"] = $_POST["token"];
        $_SESSION["channelName"] = $_POST["channelName"];
        $userID = $_SESSION["userID"];
        /* プリペアドステートメントを作成します */
        $stmt = mysqli_prepare($link, "INSERT INTO slackSettings(userID,token,channelName) values(?,?,?)");
        /* マーカにパラメータをバインドします */
        mysqli_stmt_bind_param($stmt,'iss',$userID,$_SESSION["token"],$_SESSION["channelName"]);
        mysqli_stmt_execute($stmt);
        print(mysqli_error($link));
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("main.html");
        echo $doc -> saveHTML();
    }
}

#slackSetting.htmlから戻るが押されたら
if (isset ($_POST["slackBack"])){
    $doc = new DOMDocument();
    $doc -> loadHTMLFile("main.html");
    echo $doc -> saveHTML();
}

#slackSettingEdit.htmlから戻るが押されたら
if (isset ($_POST["slackSettingEditBack"])){
    $doc = new DOMDocument();
    $doc -> loadHTMLFile("main.html");
    echo $doc -> saveHTML();
}

#slackSettingEdit.htmlから変更が押されたら
if (isset ($_POST["changeDecsion"])){
    $changeToken = $_POST["changeToken"];
    $changeChannelName = $_POST["changeChannelName"];
    $stmt = mysqli_prepare($link,"update slackSettings set token = ? , channelName = ? where userID = ?");
    mysqli_stmt_bind_param($stmt,"ssi",$changeToken,$changeChannelName,$_SESSION["userID"]);
    mysqli_stmt_execute($stmt);
    echo $changeToken;
    echo $changeChannelName;
    echo $_SESSION["userID"];
    $doc = new DOMDocument();
    $doc -> loadHTMLFile("main.html");
    echo $doc -> saveHTML();
}


$close_flag = mysqli_close($link);
