<?php
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //function for random string generation
function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}
function encryptFile($file) { //function for file encryption
    $key = "jolovic";
    $secret_iv = 'vector';
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $secretHash = hash('sha256', $key);
    $encryptionMethod = "AES-256-CBC";
    $content = file_get_contents($file);
    $encryption = openssl_encrypt($content, $encryptionMethod, $secretHash,0,$iv);
    $encription_write = fopen($file,"w");
    fwrite($encription_write,$encryption);
    fclose($encription_write);
    }
    function decryptFile($current,$encryptedFile) { //function for file decryption
        $key = "jolovic";
    $secret_iv = 'vector';
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $secretHash = hash('sha256', $key);
        $encryptionMethod = "AES-256-CBC";
        $content = file_get_contents($current . $encryptedFile);
        $decryption = openssl_decrypt($content, $encryptionMethod, $secretHash,0,$iv);
        $decryption_write = fopen($current . $encryptedFile,"w");
        fwrite($decryption_write,$decryption);
        fclose($decryption_write);
        }
?>