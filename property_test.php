<?php
header('Content-Type: application/json');
// Database connection parameters
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "propeta"; 

// Connect to MySQL
$link = mysqli_connect($host, $username, $password, $database);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// SQL query to retrieve data
$sql = "SELECT * FROM re_properties ORDER BY id DESC LIMIT 500";

// Execute the query
if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        // Initialize an empty array to hold the data
        $data = array();

        // Fetch and process each row
        while ($row = mysqli_fetch_array($result)) {
            // Sanitize each value in the row
            foreach ($row as $key => $value) {
                // Remove HTML tags
                $row[$key] = strip_tags($value);
                // Remove backslashes
                $row[$key] = str_replace('\\', '', $row[$key]);
            }

            // Convert numeric keys to sequential keys
            $row['10'] = json_decode($row['10'], true);
            $row['images'] = json_decode($row['images'], true);

            // Ensure arrays are not null
            $row['10'] = is_array($row['10']) ? $row['10'] : [];
            $row['images'] = is_array($row['images']) ? $row['images'] : [];

            // Append each sanitized row to the $data array
            $data[] = $row;
        }

        // Convert $data array to JSON
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        echo stripslashes($json); // Removed the single slash '/'
    } else {
        echo "No records matching your query were found.";
    }
} else {
    echo "ERROR: Could not execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
?>
