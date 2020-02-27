<?php
/**
 * Constructs email from the submitted form on the landing page and sends them
 *
 * Data is submitted from the "Contact Me" form present in index.php via an AJAX call.
 * The data is collected from $_POST[] and validated.
 * An email is then constructed with 'recepient', 'subject' and 'body' fields.
 * A header is added with the 'sender' mail address and a 'reply-to' field.
 * PHP's mail() function is used to send emails to mfs.akash@gmail.com.
 */

////////////////////////////
// Check for empty fields //
////////////////////////////
if (empty($_POST['name'])
	|| empty($_POST['email'])
	|| empty($_POST['phone'])
	|| empty($_POST['message'])
	|| !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	http_response_code(500);
	exit();
}

//////////////////////////////////////////
// Set the email content from form data //
//////////////////////////////////////////
$name = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
$email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
$phone = htmlentities($_POST['phone'], ENT_QUOTES, 'UTF-8');
$message = htmlentities($_POST['message'], ENT_QUOTES, 'UTF-8');

//////////////////////
// Create the email //
//////////////////////
$to = "mfs.akash@gmail.com"; // This is where the form will send a message to.
$subject = "Website Contact Form:  $name";
$body = "You have received a new message from your website contact form.\n\n" .
	"Here are the details:\n\n
		Name: $name\n\n
		Email: $email\n\n
		Phone: $phone\n\n
		Message:\n$message";
$header = "From: noreply@profilemaker.com\n"; // This is the email address the generated message will be from
$header .= "Reply-To: $email";

//////////////////////////////////////////////
// Send the email or trigger error response //
//////////////////////////////////////////////
if (!mail($to, $subject, $body, $header)) {
	http_response_code(500);
}
