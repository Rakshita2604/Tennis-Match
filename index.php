<?php
// Database connection configuration
// Database connection configuration
$databaseFile = 'table_tennis_db.db'; // Change to your SQLite database file name

// Create a database connection
$conn = new SQLite3($databaseFile);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . $conn->lastErrorMsg());
}

// Function to assign referees based on the given rules
function assignReferee($day, $refereeList) {
    // Check if it's not Sunday and the referee list is not empty
    if ($day != 0 && count($refereeList) > 0) {
        // Get and remove the first referee from the list
        $referee = array_shift($refereeList);
        return $referee;
    }
    return null; // No referee assigned
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $csvFilePath = $_FILES['csv_file']['tmp_name'];

        // Open and parse the CSV file
        if (($handle = fopen($csvFilePath, 'r')) !== false) {
            // Initialize referee list and day counter
            $refereeList = ["Referee_1", "Referee_2"];
            $dayCounter = 0;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $name = $conn->real_escape_string($data[0]);
                $gender = $conn->real_escape_string($data[1]);

                // Insert player data into the 'players' table
                $sql = "INSERT INTO players (player_name, gender) VALUES ('$name', '$gender')";
                if ($conn->query($sql) === TRUE) {
                    // Player data inserted successfully

                    // Assign a referee based on the rules
                    $matchDate = date('Y-m-d', strtotime("2023-10-03 +$dayCounter day"));
                    $referee = assignReferee($dayCounter, $refereeList);

                    // Insert match data into the 'matches' table
                    $sql = "INSERT INTO matches (match_date, player_name, referee_name) VALUES ('$matchDate', '$name', '$referee')";
                    if ($conn->query($sql) !== TRUE) {
                        echo "Error inserting match data: " . $conn->error;
                    }

                    $dayCounter++;
                } else {
                    echo "Error inserting player data: " . $conn->error;
                }
            }
            fclose($handle);

            // Close the database connection
            $conn->close();
            echo "Data processing complete.";
        } else {
            echo "Error opening the CSV file.";
        }
    } else {
        echo "File upload error.";
    }
}
?>
