<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<?php
// Database connection
include 'db_connection.php';

// Get all main_subject from main_subject table
$sql = "SELECT * FROM main_subject";
$result = $conn->query($sql);
?>

<body>
    <div class="container p-5 my-5">
        <div class="p-5">
            <form action="missing_data.php" method="post">
                <div class="form-group">
                    <label for="main_subject">Select Main Subject:</label><br>
                    <select name="main_subject" id="" class="form-select">
                        <?php foreach ($result as $res) : ?>
                            <option value="<?= $res['id'] ?>"><?= $res['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input class="btn btn-primary" type="submit" value="Get Missing Websupports">
            </form>
        </div>
        <div class="p-5">
            <form action="uploaded_data.php" method="post">
                <div class="form-group">
                    <label for="main_subject">Select Main Subject:</label><br>
                    <select name="main_subject" id="" class="form-select">
                        <?php foreach ($result as $res) : ?>
                            <option value="<?= $res['id'] ?>"><?= $res['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input class="btn btn-primary" type="submit" value="Get Uploaded Websupports">
            </form>
        </div>
    </div>
</body>

</html>