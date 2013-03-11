<?php
session_start();

$name      = stripslashes($_POST["name"]);
$email     = stripslashes($_POST["email"]);
$attending = $_POST["attending"] === "on" ? "yes" : "no";
$number    = $_POST["number"];
$wedding   = $_POST["wedding"] === "on" ? "yes" : "no";
$friday    = $_POST["friday"]  === "on" ? "yes" : "no";
$notes     = stripslashes($_POST["notes"]);
$errors    = array();

if (trim($name) === "") {
  array_push($errors, "no_name");
}

if (trim($email) === "") {
  array_push($errors, "no_email");
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  array_push($errors, "bad_email");
}

if ($attending === "yes") {
  if ($number === "0") {
    array_push($errors, "no_one");
  }

  if ($wedding === "no" && $friday === "no") {
    array_push($errors, "no_event");
  }
}

if (count($errors) !== 0) {
  $_SESSION["name"]      = $name;
  $_SESSION["email"]     = $email;
  $_SESSION["attending"] = $attending;
  $_SESSION["number"]    = $number;
  $_SESSION["wedding"]   = $wedding;
  $_SESSION["friday"]    = $friday;
  $_SESSION["notes"]     = $notes;
  $_SESSION["errors"]    = $errors;

  header("Location: /wedding/rsvp");
  exit(0);
}

$to = "Bryan McKelvey <bryan.mckelvey@gmail.com>, $name <$email>";
$headers = "MIME-Version: 1.0\r\n" .
  "From: Wedding Site <wedding@brymck.com>\r\n" .
  "Reply-To: Bryan McKelvey <bryan.mckelvey@gmail.com>\r\n" .
  "Content-Type: text/html; charset=UTF-8\r\n";
$subject = "RSVP for $name";
$message = $attending ? "We look forward to seeing you there!" : "sorry you can't make it!";
$content = <<<END
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>RSVP for $name</title>
  </head>
  <body>
    <p>Thanks! You have submitted the following details:</p>
    <ol>
      <li><b>Name:</b> $name</li>
      <li><b>Email:</b> $email</li>
      <li><b>Attending:</b> $attending</li>
      <li><b>Number:</b> $number</li>
      <li><b>Wedding:</b> $wedding</li>
      <li><b>Friday:</b> $friday</li>
      <li><b>Notes:</b> $notes</li>
    </ol>
    <p>$message</p>
    <p>Best wishes,<br>Bryan &amp; Maiko</p>
  </body>
</html>
END;

mail($to, $subject, $content, $headers)
  or die("Your mail could not be sent. Feel free to send this content to bryan.mckelvey@gmail.com, though:\n$content");
session_destroy();
header("Location: /wedding/thanks");
?>
