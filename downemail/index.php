<?php

    // connect with database
    $conn = new PDO("mysql:host=localhost:8080;dbname=collect_emails_while_downloading_files", "root", "root");

    // check if form is submitted, for admin panel only
    if (isset($_POST["upload"]))
    {
        // get the file
        $file = $_FILES["file"];
        
        // make sure it does not have any error
        if ($file["error"] == 0)
        {
            // save file in uploads folder
            $file_path = "uploads/" . $file["name"];
            move_uploaded_file($file["tmp_name"], $file_path);

            // save file path in database, prevent SQL injection too
            $sql = "INSERT INTO files(file_name, file_path) VALUES (:file_name, :file_path)";
            $result = $conn->prepare($sql);
            $result->execute([
                ":file_name" => $file["name"],
                ":file_path" => $file_path
            ]);
        }
        else
        {
            die("Error uploading file.");
        }
    }

    // get all files
    $sql = "SELECT * FROM files ORDER BY id DESC";
    $result = $conn->query($sql);
    $files = $result->fetchAll();

?>

<!-- form to upload files, for admin panel only -->
<form method="POST" action="index.php" enctype="multipart/form-data">
    <input type="file" name="file" required />
    <input type="submit" name="upload" value="Upload" />
</form>

<!-- show all files in a table -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?php echo $file["id"]; ?></td>
                <td><?php echo $file["file_name"]; ?></td>
                <td>
                    <!-- button to download file -->
                    <form method="POST" action="check-email.php" onsubmit="return onFormSubmit(this);">
                        <input type="hidden" name="id" value="<?php echo $file['id']; ?>" required />
                        <input type="hidden" name="email" />
                        <input type="submit" value="Download" />
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    function onFormSubmit(form) {
        // get email address and submit
        var email = prompt("Enter your email:", "adnanafzal565@gmail.com");
        if (email != null && email != "") {
            form.email.value = email;
            return true;
        }
        return false;
    }
</script>