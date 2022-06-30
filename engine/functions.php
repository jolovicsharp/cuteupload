<?php
function readableBytes($bytes) { //to convert bytes into readable file size MB,KB,GB etc etc
    if(!$bytes) {
        header("Location: ../index.php");
    }
    else {
    $i = floor(log($bytes) / log(1024));
    $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    return sprintf('%.02F', $bytes / pow(1024, $i)) * 1 . ' ' . $sizes[$i];
    }
}
function mathQuestion($lo, $hi) { //in future version to keep off bots
    $a = mt_rand($lo, $hi);
    $b = mt_rand($lo, $hi);
    $c = $a + $b;
    $hash = md5($c);
    $q = "$a + $b";
    return array($q, $hash);
}
function mathQuestionVerify($c, $hash) {
    return (md5($c) === $hash);
}
?>