<meta charset="utf-8" />

<?php
/**
 * Created by PhpStorm.
 * User: Rascafr
 * Date: 01/12/2015
 * Time: 22:33
 */

echo '<p><b>=== API TEST BEGIN ===</b></p>';

include ('../../model/track/getPointsAPI.php');

$api_id = '47856230';
$track_id = '1';

$resp = getPointsAPI($api_id, $track_id);

print_r($resp);

echo '<p><b>=== API TEST END ===</b></p>';