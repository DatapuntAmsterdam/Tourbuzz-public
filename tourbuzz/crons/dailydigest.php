<?php

$apiUri = "http://api.tourbuzz.nl";
$buzzProc = "http://";
$buzzUri = "www.tourbuzz.nl";
$mailTo = "m.sloothaak@amsterdam.nl, thartevelt@amsterdam.nl, j.groenen@amsterdam.nl, a.zwiers@amsterdam.nl, johan.groenen@gmail.com";

$res = json_decode(file_get_contents($apiUri . "/berichten/" . date("Y/m/d")));
$messages = (array) $res->messages;
$title = "Overzicht van tour buzz berichten voor " . date("d-m-Y");
$content = "Overzicht van tour buzz berichten voor " . date("d-m-Y") . ":\r\n\r\n";
$messages = array_filter($messages, function ($message) {
    return $message->is_live === "is_live";
});
foreach ($messages as $message) {
    if ($message->important === "important") {
        $content .= " - {$message->title}\r\n";
    }
}
foreach ($messages as $message) {
    if ($message->important !== "important") {
        $content .= " - {$message->title}\r\n";
    }
}
$content .= "\r\nKijk voor het overzicht op {$buzzProc}{$buzzUri}/" . date("Y/m/d");

mail(
    $mailTo,
    $title,
    $content,
    "From: dashboard@{$buzzUri}\r\n" .
    "Reply-To: noreply@{$buzzUri}\r\n" .
    "X-Mailer: PHP/" . phpversion()
);

