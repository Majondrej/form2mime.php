<?php
/**
 * This takes all POSTed key/values and sends them via MIME eMail.
 * File uploads (RFC-1867: Form-based File Upload in HTML) are supported.
 *
 * Florian Sesser <florian ÄT sesser DÓT at> 2016-07-01, 2016-12-08
 */


// Configuration
define("FROM", "your-friendly-form2mime-bot@mydomain.com");
//define("TO", "fs-accu");
define("TO", "static-address-since-we-do-no-auth-at-all@mydomain.com");
define("SUBJECT", "New request coming through the tubes");
define("acMIME_BORDER", "XXXXYovercomeYbordersYXXXX");



// Initialize variables
$headers = "From: " . FROM . "\n"
         . "MIME-Version: 1.0\n"
         . "Content-Type: multipart/mixed; boundary=" . acMIME_BORDER . "\n\n"
         . "This is a message with multiple parts in MIME format.\n";
$message = "";
$attachments = "";



// Helper: Add file attachment
function encode($displayFilename, $onDiskFilename, $contentType) {
    return "--" . acMIME_BORDER . "\r\n"
        . "Content-Type: " . $contentType . "\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment;\r\n"
        . "        filename=\"" . $displayFilename. "\"\r\n\r\n"
        . chunk_split(base64_encode(file_get_contents($onDiskFilename)))
        . "\r\n";
}



// Dynamic text data
$message .= "--" . acMIME_BORDER . "\r\n";
$message .= "Content-Type: text/plain\r\n\r\n";
$message .= "This eMail was sent using the accu:rate Summon Support feature.\r\n\r\n";

foreach ($_POST as $key => $value) {
    $message .= $key . ": " . $value . "\r\n";
}

foreach ($_FILES as $fileField) {
    if (!array_key_exists('error', $fileField) || $fileField['error'] != UPLOAD_ERR_OK) {
        $message .= "WARNING: Could not read file: " + print_r($fileField, true);
        continue;
    }

    $attachments .= encode($fileField['name'],
                           $fileField['tmp_name'],
                           $fileField['type']);
}

$message .= "\r\n\r\n";

$message .= $attachments;


// end message
$message .= "\r\n--" . acMIME_BORDER . "--\r\n";

if (mail(TO, SUBJECT, $message, $headers)) {
    echo("OK.");
} else {
    header("HTTP/1.0 500 Internal Server Error");
    echo("Could not send eMail.");
    trigger_error("mail() returned an error code.", E_ERROR);
}

?>

