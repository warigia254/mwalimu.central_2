<?php

    // composer require phpmailer/phpmailer

    // include PHPMailer library
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require 'vendor/autoload.php';

    // connect with database
    $conn = new PDO("mysql:host=localhost:8080;dbname=collect_emails_while_downloading_files", "root", "root");

    // get all form values
    $id = $_POST["id"];
    $email = $_POST["email"];

    // generate a unique token for this email only
    $token = time() . md5($email);

    // get file from database
    $sql = "SELECT * FROM files WHERE id = :id";
    $result = $conn->prepare($sql);
    $result->execute([
        ":id" => $id
    ]);
    $file = $result->fetch();
    
    if ($file == null)
    {
        die("File not found");
    }

    // insert in download requests, prevent SQL injection too
    $sql = "INSERT INTO download_requests(file_id, email, token) VALUES (:id, :email, :token)";
    $result = $conn->prepare($sql);
    $result->execute([
        ":id" => $id,
        ":email" => $email,
        ":token" => $token
    ]);

    // send email to user
    $mail = new PHPMailer(true);

    try
    {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mwalimu.central@gmail.com';
        $mail->Password = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('mwalimu.central@gmail.com', 'Mwalimu Central');
        $mail->addAddress($email); // Add a recipient
        $mail->addReplyTo('mwalimu.central@gmail.com', 'Mwalimu Central');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Download your files';

        // mention download link in the email
        $email_content = "Kindly click the link below to download your files: <br />";
        $base_url = "http://localhost:8080/collect-emails-while-downloading-files-php-mysql";
        $email_content .= "<a href='" . $base_url . "/download.php?email=" . $email . "&token=" . $token . "'>" . $file['file_name'] . "</a>";
        $mail->Body = $email_content;

        $mail->send();
        echo '<p>Link to download files has been sent to your email address: ' . $email . '</p>';
    }
    catch (Exception $e)
    {
        die("Message could not be sent. Mailer Error: " . $mail->ErrorInfo);
    }