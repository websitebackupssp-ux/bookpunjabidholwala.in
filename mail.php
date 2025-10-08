<?php

// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form fields and remove whitespace.
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r", "\n"), array(" ", " "), $name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST["phone"]);

    // Normalize phone for validation (strip all non-digit characters)
    $phone_digits = preg_replace('/\D+/', '', $phone);
    $message = trim($_POST["message"]);

    // Check that data was sent to the mailer.
    // Basic validation: name, message, email and phone digits
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete the form and provide a valid name, email and message.";
        exit;
    }

    // Validate phone: require 7 to 15 digits (common mobile range)
    if (empty($phone_digits) || strlen($phone_digits) < 7 || strlen($phone_digits) > 15) {
        http_response_code(400);
        echo "Please provide a valid mobile number (7 to 15 digits).";
        exit;
    }

    // Set the recipient email address.
    // FIXME: Update this to your desired email address.
    $recipient = "info@vecuro.com";

    // Set the email subject.
    $subject = "New contact from your website"; // You can customize this if needed

    // Build the email content.
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Phone: $phone\n\n";
    $email_content .= "Message:\n$message\n";

    // Build the email headers.
    $email_headers = "From: $name <$email>";

    // Send the email.
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        // Set a 200 (okay) response code.
        http_response_code(200);
        echo "Thank You! Your message has been sent.";
    } else {
        // Set a 500 (internal server error) response code.
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
    }
} else {
    // Not a POST request, set a 403 (forbidden) response code.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
