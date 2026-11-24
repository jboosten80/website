<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.html");
    exit;
}

// Honeypot: bots vullen dit verborgen veld in
if (!empty($_POST["website"])) {
    exit;
}

function schoon($waarde) {
    return trim(strip_tags($waarde ?? ""));
}

$naam    = str_replace(["\r", "\n"], " ", schoon($_POST["name"] ?? ""));
$email   = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
$bericht = schoon($_POST["message"] ?? "");

if (!$naam || !$email || !$bericht) {
    http_response_code(400);
    echo "Vul alle velden correct in.";
    exit;
}

$to      = "info@josephboosten.eu";
$subject = mb_encode_mimeheader("Contactformulier: $naam", "UTF-8");
$body    = "Naam: $naam\nE-mail: $email\n\n$bericht";
$headers = "From: info@josephboosten.eu\r\n"
         . "Reply-To: $email\r\n"
         . "Content-Type: text/plain; charset=UTF-8";

if (mail($to, $subject, $body, $headers)) {
    header("Location: bedankt.html");
} else {
    http_response_code(500);
    echo "Het versturen is mislukt, probeer het later opnieuw.";
}
exit;