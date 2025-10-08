<?php
// Ensure the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form inputs
    $name    = isset($_POST['name'])    ? trim($_POST['name'])    : '';
    $email   = isset($_POST['email'])   ? trim($_POST['email'])   : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Basic validation (you can expand as needed)
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        // One or more fields are empty—redirect back or show an error
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Build the WhatsApp message text
    $wa_text  = "New Contact Form Submission:%0A";
    $wa_text .= "Name: "    . urlencode($name)    . "%0A";
    $wa_text .= "Email: "   . urlencode($email)   . "%0A";
    $wa_text .= "Service: " . urlencode($subject) . "%0A";
    $wa_text .= "Message: " . urlencode($message);

    // Your WhatsApp number in international format without '+' or dashes
    $whatsapp_number = '+918744867110';

    // Construct the WhatsApp URL
    $wa_url = "https://api.whatsapp.com/send?phone={$whatsapp_number}&text={$wa_text}";

    // Redirect the user to WhatsApp
    header("Location: {$wa_url}");
    exit;
}

// If accessed directly without POST, redirect back
header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
exit;
