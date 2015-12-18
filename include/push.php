<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 18/12/2015
 * Time: 17:01
 */

pushUser(
    array(
        'd8kceidNjIc:APA91bHzU_H68QuBVe9fjGk9fFABGydq-5w0VlkMx8fliiYyHU3fS0G4wf_hZa0l-Nrc4-Alq3wqyO7_KoCg3ugz2BZTh9undg75AyHV6InpKSxfFPAbvgbCC4B0ZLx9gQje7S96XdHA'
    ),
    'Nouveau message',
    'Timé : ça te dit un berthom ? :D',
    'intent.com.wdidy.app.push.conversation.new'
);

function pushUser ($IDdevices, $title, $content, $intent) {

    // API Key
    define('PUSH_ANDROID_SENDER_ID', '327033001800');
    define('PUSH_ANDROID_API_KEY', 'AIzaSyCKyxTZuMJAG0EKfRBQ04hBebLkcvW5wd0');

    // Set POST variables
    $url = 'https://android.googleapis.com/gcm/send';

    $fields = array(
        'registration_ids' => $IDdevices,
        'data' => array(
            'title' => $title,
            'message' => $content,
            'intent' => $intent
        )
    );

    $headers = array(
        'Authorization: key=' . PUSH_ANDROID_API_KEY,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    // Execute post
    $result = curl_exec($ch);

    echo $result;
}
