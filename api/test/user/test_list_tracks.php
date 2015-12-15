<meta charset="utf-8" />

<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 01/12/2015
 * Time: 22:33
 */

echo '<p><b>=== API TEST BEGIN ===</b></p>';

include ('../../model/user/getTracksAPI.php');

$api_id = '47856230';
$user_id = '251f563068e8636da4092490d6aeac94';

$resp = getTracksAPI($api_id, $user_id);

print_r($resp);

echo '<p><b>=== API TEST END ===</b></p>';