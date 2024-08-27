<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Websupport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js" defer></script>
</head>
<style>
    body {
        margin: 1rem 3rem;
    }
</style>

<body>

    <?php
    include 'db_connection.php';

    // Get main_subject id from form
    $main_subject_id = $_POST['main_subject'];

    $sql = "SELECT c.id AS category_id, c.name AS category_name, s.id AS subject_id, s.name AS subject_name FROM category c CROSS JOIN subject s WHERE EXISTS (SELECT 1 FROM websupport w WHERE w.type = c.id AND w.subject = s.id) AND s.sid = $main_subject_id ORDER BY c.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='table table-striped table-bordered' id='myTable'><thead><tr><th>Serial</th><th>Category</th><th>Uploaded Subjects Name</th></tr></thead><tbody>";
        // output data of each row
        $serial = 1;
        $last_category_id = null;
        $missing_subjects = [];
        while ($row = $result->fetch_assoc()) {
            if ($last_category_id != $row["category_id"]) {
                if (!empty($missing_subjects)) {
                    echo "<tr><td>" . $serial++ . "</td><td>" . $last_category_name . "</td><td>" . implode("<br>", $missing_subjects) . "</td></tr>";
                }
                $missing_subjects = [];
                $last_category_id = $row["category_id"];
                $last_category_name = $row["category_name"];
            }
            $missing_subjects[] = $row["subject_name"];
        }
        if (!empty($missing_subjects)) {
            echo "<tr><td>" . $serial++ . "</td><td>" . $last_category_name . "</td><td>" . implode("<br>", $missing_subjects) . "</td></tr></tbody>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                'pageLength': 5
            });
        });
    </script>
</body>

</html>