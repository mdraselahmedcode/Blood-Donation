<?php

include __DIR__ . '/config/config.php';
// send_contact_email.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to = 'mdraselahmed.code@gmail.com';
    $subject = $_POST['subject'];
    $message = "Name: " . $_POST['name'] . "\n";
    $message .= "Email: " . $_POST['email'] . "\n\n";
    $message .= "Message:\n" . $_POST['message'];
    $headers = "From: " . $_POST['email'];

    if (mail($to, $subject, $message, $headers)) {
        // Redirect with success message
        header('Location: ' . BASE_URL . '/contact.php?status=success');
    } else {
        // Redirect with error message
        header('Location: ' . BASE_URL . '/contact.php?status=error');
    }
    exit;
}
?>