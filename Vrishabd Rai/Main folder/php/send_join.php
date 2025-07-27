<?php
// send_join.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Method Not Allowed');
}

// 1) Grab & sanitize
$name   = trim($_POST['fullName'] ?? '');
$email  = trim($_POST['email']    ?? '');
$year   = trim($_POST['year']     ?? '');
$skills = trim($_POST['skills']   ?? '');
$why    = trim($_POST['whyJoin']  ?? '');

// 2) Validate (simple)
$errors = [];
if (strlen($name) < 2)                $errors[] = 'Name too short';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
if (empty($year))                     $errors[] = 'Year is required';

if ($errors) {
  echo '<p>Errors:</p><ul><li>'.implode("</li><li>", $errors).'</li></ul>';
  exit;
}

// 3) Build the CSV row
$row = [
  date('c'), // ISO datetime
  $name,
  $email,
  $year,
  str_replace(["\r","\n"], ' ', $skills), // one‑liner
  str_replace(["\r","\n"], ' ', $why),
];

// 4) Append to file
$file = __DIR__ . '/submissions.csv';
$fp = fopen($file, 'a');
if (!$fp) {
  exit('<p>Could not open storage. Check permissions.</p>');
}
fputcsv($fp, $row);
fclose($fp);

// 5) Thank you!
echo '<p>Thanks! Your info has been saved.</p>';
?>
