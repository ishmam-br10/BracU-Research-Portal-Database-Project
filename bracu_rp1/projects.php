<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <style>
        body {
            background-size: cover;
            margin: 0;
        }

        .profile-page {
            display: flex;
            gap: 10px;
            height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.2)), url(./bg.webp);
            margin: 0;
        }

        .item-list {
            margin-top: 10%;
            color: #eeeae6;
        }

        .item {
            margin-top: 4%;
        }

        .button-container h1 {
            font-style: bold;
            margin-left: 45%;
            color: #e6dad3;
            border-bottom: solid 1px #f5f1f1;
            font-size: xx-large;
            border-bottom-left-radius: 10px;
        }

        form {
            margin-top: 0.5%;
            margin-left: 500%;
        }

        button {
            background-color: #d2b292f8;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #b49f8aa9;
            font-size: 15px;
        }
    </style>
</head>
<body>
<div class="profile-page">
    <div class="button-container">
        <h1>Projects</h1>
        <form action="search_project.php" method="GET">
          <label for="search">Search:</label>
          <input type="text" id="search" name="query" placeholder="Enter keyword">
          <button type="submit">Search</button>
    </form>
    </div>
    <ul class="item-list">
        <?php
        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bracu_research_portal";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the Volunteer button is clicked
if (isset($_POST['volunteer_project_id'])) {
  // Assuming you have the volunteer_id stored in session or POST data
  $user_id = $_SESSION['user_id']; // Adjust this according to your session variable storing user_id

  // Get the project_id from the form submission
  $project_id = $_POST['project.project_id'];

  // Insert a new record into the project_volunteer table
  $sql_insert = "INSERT INTO project (project_id, volunteer_id) VALUES ('$project_id', '$volunteer_id')";

  if ($conn->query($sql_insert) === TRUE) {
      echo "Volunteered successfully!";
  } else {
      echo "Error: " . $sql_insert . "<br>" . $conn->error;
  }
}

// Fetch data from projects table, joining with project_volunteer to get volunteers for each project
$sql = "SELECT p.*, pa.*, u.*, v.*, uv.name AS volunteer_name
FROM project p
JOIN project_author pa ON p.project_id = pa.project_id
JOIN user u ON pa.user_id = u.user_id
JOIN volunteer v ON p.volunteer_id = v.volunteer_id
JOIN user uv ON v.user_id = uv.user_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Output data of each row
  while ($row = $result->fetch_assoc()) {
      echo "<li class='item'>";
      echo "<h3>Title: <a href='" . $row['drive_link'] . "' target='_blank'>" . $row['drive_link'] . "</a></h3>";
      echo "<p>Author: " . $row['name'] . "</p>";
      echo "<p>Volunteer User IDs: " . $row['volunteer_name'] . "</p>";
      // Include a form for each project to volunteer
      echo "<form method='post'>";
      echo "<input type='hidden' name='volunteer_project_id' value='" . $row['project_id'] . "'>";
      echo "<button class='btn' type='submit'>Volunteer in the project</button>";
      echo "</form>";
      echo "</li>";
  }
} else {
  echo "No projects found";
}

// Close connection
$conn->close();
?>


