<?php
// Database connection settings
$host = 'localhost';
$user = 'orangedb_user';
$password = 'Y9M3qNU6c@QCve';
$dbname = 'orange_db';

// Connect to the database
$conn = new mysqli($host, $user, $password, $dbname);

// Check for errors
if ($conn->connect_error) {
  die('Connection failed: ' . $conn->connect_error);
}

// Check if a CSV file has been uploaded
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
  // Get the file name
  $filename = $_FILES['csv_file']['name'];

  // Get the file extension
  $ext = pathinfo($filename, PATHINFO_EXTENSION);

  // Check if the file is a CSV
  if ($ext == 'csv') {
    // Open the CSV file
    $file = fopen($_FILES['csv_file']['tmp_name'], 'r');

    // Read the CSV data
    while (($data = fgetcsv($file, 1000, ',')) !== FALSE) {
      // Insert the data into the `web_user` table
      $stmt = $conn->prepare('INSERT INTO web_user (board, publication, subject, msubject, classes, title, file_name, file_url, book_image, description, type, states, date, edition, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
      $stmt->bind_param('sssssssssssssss', $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14]);

      // Check for errors
      if (!$stmt->execute()) {
        echo 'Error inserting data: ' . $stmt->error . '<br>';
      }
    }

    // Close the CSV file
    fclose($file);

    // Close the database connection
    $conn->close();

    // Redirect the user back to the index page
    header('Location: index.php');
  } else {
    echo 'Invalid file type. Please upload a CSV file.';
  }
} else {
  echo 'Error uploading file. Please try again.';
}
?>