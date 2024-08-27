 <html>
<head>
    <title>Orange Upload missing Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #446688;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            width: 100px;
            text-align: right;
            margin-right: 10px;
        }
        select {
            margin-bottom: 10px;
        }
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #446688;
            color: white;
        }
    </style>
</head>
<body>
    <h1>Orange Upload missing Checker</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="get">
      
        <label>Select Category</label>
        <select name="type">
            <?php
            // Connect to database
            $connection = new mysqli('35.154.100.160', 'orangedb_user', 'Y9M3qNU6c@QCve', 'orange_db');
            // Check connection
            if ($connection->connect_error) {
                die("Connection failed: ". $connection->connect_error);
            }

            // Query
            $query = "SELECT id, name FROM category";

            // Execute query
            $result = $connection->query($query);

            // Check if query was successful
            if (!$result) {
                die("Query failed: ". $connection->error);
            }

            // Display results
            while ($row = $result->fetch_assoc()) {
                $selected = (isset($_GET['type']) && $_GET['type'] == $row['id'])? 'selected' : '';
                echo "<option value='".$row['id']."' $selected>".$row['name']. "</option>";
            }

            // Close connection
            $connection->close();
          ?>
        </select>
        <br>
        
        <input type="submit" value="Search">
    </form>

    <?php
    if (isset($_GET['type']) ) {
        $exists = "EXISTS";
        $type = $_GET['type'];
       

        // Connect to database
        $connection = new mysqli('35.154.100.160', 'orangedb_user', 'Y9M3qNU6c@QCve', 'orange_db');
        // Check connection
        if ($connection->connect_error) {
            die("Connection failed: ". $connection->connect_error);
        }

        // Query
        $query = "SELECT name FROM subject WHERE NOT EXISTS ( SELECT * FROM websupport WHERE subject.id = websupport.subject AND websupport.type = $type ); ";
        
        // Execute query
        $result = $connection->query($query);

        // Check if query was successful
        if (!$result) {
            die("Query failed: ". $connection->error);
        }

        // Display results
        echo "<table>";
        echo "<tr><th>Name</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>". $row['name']. "</td></tr>";
        }
        echo "</table>";

        // Close connection
        $connection->close();
    }
  ?>
</body>
</html>