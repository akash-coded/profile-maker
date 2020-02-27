<?php

if (empty($_GET['name']) || empty($_GET['email']) || empty($_GET['phone']) || empty($_GET['message']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
	http_response_code(500);
	exit();
}

$name = htmlentities($_GET['name'], ENT_QUOTES, 'UTF-8');
$email = htmlentities($_GET['email'], ENT_QUOTES, 'UTF-8');
$phone = htmlentities($_GET['phone'], ENT_QUOTES, 'UTF-8');
$message = htmlentities($_GET['message'], ENT_QUOTES, 'UTF-8');

// Create the email and send the message
$to = "mfs.akash@gmail.com"; // This is where the form will send a message to.
$subject = "Website Contact Form:  $name";
$body = "You have received a new message from your website contact form.\n\n" . "Here are the details:\n\nName: $name\n\nEmail: $email\n\nPhone: $phone\n\nMessage:\n$message";
$header = "From: noreply@profilemaker.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$header .= "Reply-To: $email";

if (!mail($to, $subject, $body, $header)) {
	echo $to, PHP_EOL, $subject, PHP_EOL, $body, PHP_EOL, $header;
//http_response_code(500);
}

/*$name = "Akash Das";
$email = "akashdas838@gmail.com";
$phone = "8594824595";
$message = "Test Message";

// Create the email and send the message
$to = "mfs.akash@gmail.com"; // This is where the form will send a message to.
$subject = "Website Contact Form:  $name";
$body = "You have received a new message from your website contact form.\n\n" . "Here are the details:\n\nName: $name\n\nEmail: $email\n\nPhone: $phone\n\nMessage:\n$message";
$header = "From: noreply@profilemaker.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$header .= "Reply-To: $email";

if (!mail($to, $subject, $body, $header)) {
echo $to, PHP_EOL, $subject, PHP_EOL, $body, PHP_EOL, $header;
//http_response_code(500);
} else {
echo "Message Sent!";
}*/