<?php
function sanitize_input($data)
{
	return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$emailHelp = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$comments = isset($_POST['comments']) ? sanitize_input($_POST['comments']) : '';

if (!empty($name) && !empty($phone) && !empty($emailHelp) && filter_var($emailHelp, FILTER_VALIDATE_EMAIL)) {
	global $to_email, $vpb_message_body, $headers;
	$to_email = "ooguzhanerogluu@gmail.com";

	$vpb_message_body = nl2br("Dear Admin,\n
    The user whose detail is shown below has sent this message from " . $_SERVER['HTTP_HOST'] . " dated " . date('d-m-Y') . ".\n

    Name: " . $name . "\n
    Email Address: " . $emailHelp . "\n
    Phone: " . $phone . "\n
    Message: " . $comments . "\n
    User IP: " . $_SERVER['REMOTE_ADDR'] . "\n
    Thank You!\n\n");

	// Set up the email headers
	$headers  = "From: $name <$emailHelp>\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "Message-ID: <" . time() . rand(1, 1000) . "@" . $_SERVER['SERVER_NAME'] . ">\r\n";

	if (@mail($to_email, "Inquiry From Contact Page", $vpb_message_body, $headers)) {
		$status = 'Success';
		$output = "Congrats " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ", your email message has been sent successfully! We will get back to you as soon as possible. Thanks.";
	} else {
		$status = 'error';
		$output = "Sorry, your email could not be sent at the moment. Please try again or contact this website admin to report this error message if the problem persists. Thanks.";
	}
} else {
	$status = 'error';
	$output = "Please fill in all required fields with valid information.";
}

echo json_encode(array('status' => $status, 'msg' => $output));
