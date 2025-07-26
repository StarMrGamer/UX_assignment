<?php
// send_join.php

// 1) Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

// 2) Sanitize & grab your fields
$name    = trim($_POST['fullName']   ?? '');
$email   = trim($_POST['email']      ?? '');
$year    = trim($_POST['year']       ?? '');
$skills  = trim($_POST['skills']     ?? '');
$why     = trim($_POST['whyJoin']    ?? '');

// 3) Basic validation
$errors = [];
if (strlen($name) < 2)    { $errors[] = 'Please enter your name.'; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = 'Please enter a valid email.';
}
if (empty($year))         { $errors[] = 'Please select your year.'; }

if (!empty($errors)) {
  // you could re‑render the form here with errors; for simplicity:
  echo '<p>The following errors occurred:</p><ul><li>'
     . implode('</li><li>',$errors)
     . '</li></ul>';
  exit;
}

// 4) Build the email
$to      = 'vrishabdrai200@gmail.com';          // <–– your address
$subject = 'New Robotics Club Signup';
$body    = "Name: $name\n"
         . "Email: $email\n"
         . "Year: $year\n"
         . "Skills: $skills\n"
         . "Why Join: $why\n";

$headers = [];
$headers[] = "From: Robotics Club <no-reply@yourdomain.com>";
$headers[] = "Reply-To: $name <$email>";
$headers[] = "Content-Type: text/plain; charset=UTF-8";

// 5) Send it
if (mail($to, $subject, $body, implode("\r\n",$headers))) {
  echo '<p>Thanks for signing up! We’ll be in touch soon.</p>';
} else {
  echo '<p>Sorry, something went wrong sending your email.</p>';
}
