<?php
$link = mysqli_connect('localhost:8889', 'root', 'root', 'mydb');
while (true){
    $stmt = sqli_prepare($link,"select beforeTime from contentTime where beforeTime between ? and ?");

}
?>
