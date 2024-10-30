<?php
// Disable error display for users and log errors instead
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); // Ensure all errors are reported in the logs

// Start the session if it isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php'; // Include the header.php to maintain the header layout
include 'sidebar.php'; // Include sidebar.php to ensure the sidebar is rendered
include 'db_connection.php'; // Ensure you include your database connection

// Get company name from session
$company_name = $_SESSION['company_name'];

$page_title = "Dashboard";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <style>
        /* General styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden; /* Prevent scroll bars from appearing */
            background: linear-gradient(135deg, #B06AB6, #E0D5E3); /* Darker gradient colors */
            animation: gradient 15s ease infinite; /* Apply animation */
            background-size: 300% 300%; /* Allow for larger background area */
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%; /* Start position */
            }
            50% {
                background-position: 100% 50%; /* Middle position */
            }
            100% {
                background-position: 0% 50%; /* End position */
            }
        }

        /* Sidebar */
        #sidebar {
            width: 250px; /* Sidebar width */
            background-color: #333; /* Example color */
            flex-shrink: 0; /* Prevent the sidebar from shrinking */
        }

        /* Main content section */
        .main-content {
            flex-grow: 1; /* Take the remaining space beside the sidebar */
            margin-left: 250px; /* Align with the right margin of the sidebar */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 50px; /* Add top margin to lower the content */
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 80%;
            max-width: 1200px;
            margin: auto;
        }

        .left-section {
            flex: 1;
            padding: 20px;
            color: #333333;
        }

        .left-section h1 {
            font-size: 48px;
            font-weight: bolder; /* Make the heading bolder */
            color: #4A00E0; /* Purple color for TrashTech */
            margin-top: 20px;
        }

        .left-section h1 span {
            color: #854AE0; /* Slightly darker purple for emphasis */
        }

        .left-section p {
            font-size: 18px;
            font-weight: bold; /* Make paragraph text bolder */
            color: #666666;
            margin-top: 20px;
        }

        .right-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .right-section img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                text-align: center;
            }

            .left-section {
                padding: 10px;
            }

            .left-section h1 {
                font-size: 36px;
            }

            .right-section {
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="container">
        <div class="left-section">
            <h1>Welcome to <span>TrashTech</span></h1>
            <p>Where your waste is in our hands.</p>
        </div>
        <div class="right-section">
            <img src="images/Other 07.png" alt="TrashTech">
        </div>
    </div>
</div>

</body>
</html>
