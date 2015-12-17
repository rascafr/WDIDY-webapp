<?php

/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 17/12/2015
 * Time: 23:10
 */

?>

<div class="commit_history_box">
    <span class="commit_history_label">Last server operations :</span>

    <span class="commit_history_text">
    <?php

    // Git start : <p class="commit-title">
    define("GIT_START", '<p class="commit-title">');
    // Git end : <div class="commit-branches">
    define("GIT_END", '<div class="commit-branches">');

    // create curl resource
    $ch = curl_init();

    // set url
    curl_setopt($ch, CURLOPT_URL, "https://github.com/rascafr/WDIDY-webapp/commit/master");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // skip https from Github

    //return the transfer as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // $output contains the output string
    $output = curl_exec($ch);

    // close curl resource to free up system resources
    curl_close($ch);

    $sta = strpos($output, GIT_START);
    $end = strpos($output, GIT_END);
    $gitLog = str_replace('-', '<br>-', strip_tags(substr($output, $sta, $end - $sta)));

    // Echo log to page
    echo $gitLog;
    ?>
    </span>
</div>