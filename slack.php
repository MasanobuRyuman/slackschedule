<?php
function slackSend($token,$channelName,$sendContent){

    $headers = [
        "Authorization: Bearer $token", //（1)
        'Content-Type: application/json;charset=utf-8'
    ];

    $url = "https://slack.com/api/chat.postMessage"; //(2)

    //(3)
    $post_fields = [
        "channel" =>  $channelName,
        "text" => $sendContent,
        "as_user" => true
    ];

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($post_fields)
    ];
    $ch = curl_init();
    curl_setopt_array($ch, $options);

    $result = curl_exec($ch);

    curl_close($ch);
}


slackSend("xoxb-1970271611367-2173804107493-s77NYSVYmAQkk5SsNLWDxUro","スケジュールのお知らせ","test");


print($result);


?>
