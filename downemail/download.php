<?php

    // connect with database
    $conn = new PDO("mysql:host=localhost:8080;dbname=collect_emails_while_downloading_files", "root", "root");

    // get variables from email
    $email = $_GET["email"];
    $token = $_GET["token"];

    // check if the download request is valid
    $sql = "SELECT *, download_requests.id AS download_request_id FROM download_requests INNER JOIN files ON files.id = download_requests.file_id WHERE download_requests.email = :email AND download_requests.token = :token";
    $result = $conn->prepare($sql);
    $result->execute([
        ":email" => $email,
        ":token" => $token
    ]);
    $file = $result->fetch();

    if ($file == null)
    {
        die("File not found.");
    }

    // download the file
    $url_encoded_file_name = rawurlencode($file["file_name"]);
    // $file_url = "http://localhost:8888/tutorials/collect-emails-while-downloading-files-php-mysql/uploads/" . 
    $file_url = "http://localhost:8080/xampp/htdocs/send-file-download-link-in-an-email-php-and-mysql/uploads/" . 
    $url_encoded_file_name;
    // die($file_url);

    // C:\xampp\htdocs\send-file-download-link-in-an-email-php-and-mysql\uploads
        

    // headers to download any type of file
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file["file_name"] . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file["file_path"]));
    readfile($file["file_path"]);
