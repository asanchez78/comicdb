<?php
$userInfo = new userInfo ();
$userInfo->userMeta($userID);
$followList = preg_replace('/}{/', ',', $userInfo->user_follows);
$followList = substr($followList, 1, -1);
$userInfo->showFeed ($followList, $feedLength);
echo $userInfo->feed;