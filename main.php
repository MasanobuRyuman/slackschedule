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
    $stmt = mysqli_prepare($link,"select exists (select * from user where name = ? and password = ?)");
    mysqli_stmt_bind_param($stmt, 'ss',$userName,$password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$col);
    $existence = 0;
    while (mysqli_stmt_fetch($stmt)){
        $existence=$col;
    }
    if ($existence == 1){
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("main.html");
        echo $doc -> saveHTML();
    } else {
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("login.html");
        echo $doc -> saveHTML();
    }

}

#ログイン画面から「新規登録」が押されたら
if (isset ($_POST["newlogin"])) {
    $userName = $_POST['user'];
    $password = $_POST['password'];
    $stmt = mysqli_prepare($link,"select exists (select * from user where password = ?)");
    mysqli_stmt_bind_param($stmt, 's',$password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt,$col);
    $existence = 0;
    while (mysqli_stmt_fetch($stmt)){
        $existence=$col;
    }
    if ($existence == 0){
        /* プリペアドステートメントを作成します */
        $st = mysqli_prepare($link, "INSERT INTO u(name,password) values(?,?)");
        /* マーカにパラメータをバインドします */
        mysqli_stmt_bind_param($st, 'ss',$userName,$password);
        mysqli_stmt_execute($st);
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("main.html");
        echo $doc -> saveHTML();
    } else {
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("login.html");
        echo $doc -> saveHTML();
    }

}

#予定入力ページから「決定」が押された後
if (isset ($_POST["contentfield"])) {
    #予定の内容と時間と通知時間が書かれているかをチェック
    #内容が全て入っていたら
    if (empty($_POST["time"]) || empty($_POST["beforeTime"]) || empty($_POST["contentfield"])) {
        require("registration.php");
    }else{
        $userID = 1;
        $schedule_date = $_SESSION["date"];
        $content = $_POST["contentfield"];
        $schedule_Time = $_POST["time"];
        $before_Time = $_POST["beforeTime"];
        /* プリペアドステートメントを作成します */
        $stmt = mysqli_prepare($link, "INSERT INTO contentTime(userID,content,scheduleTime,beforeTime,cou) values(?,?,?,?,1)");
        /* マーカにパラメータをバインドします */
        mysqli_stmt_bind_param($stmt, 'isss',$userID,$content,$schedule_Time,$before_Time);
        mysqli_stmt_execute($stmt);
        print(mysqli_error($link));
        $doc = new DOMDocument();
        $doc -> loadHTMLFile("main.html");
        echo $doc -> saveHTML();


    }
}



$close_flag = mysqli_close($link);
