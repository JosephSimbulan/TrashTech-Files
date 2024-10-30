<?php
// faq.php
include 'db_connection.php';
include 'header.php';  // Include the header.php to maintain the header layout
include 'sidebar.php'; // Include sidebar.php to ensure the sidebar is rendered

// Disable error display for users and log errors instead
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); // Ensure all errors are reported in the logs

// Start the session if it isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch FAQs from the database
$sql = "SELECT * FROM faqs";
$result = $conn->query($sql);

$page_title = "FAQ Page";

// Initialize page content
$page_content = '
    <h1>FAQs</h1> <!-- Keep the <h1> above the faq-container -->
    <div class="faq-container">';

// Check if there are any FAQs and build the content
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $page_content .= '
            <div class="faq-item">
                <div class="faq-question">
                    <strong>' . htmlspecialchars($row['question']) . '</strong>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">' . htmlspecialchars($row['answer']) . '</div>
            </div>';
    }
} else {
    $page_content .= '<p>No FAQs found.</p>';
}

// Close the faq-container div
$page_content .= '
    </div>';

?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding-top: 60px; /* Adjust this value to match the header height */
        display: flex;
        flex-direction: column; /* Ensure elements stack vertically */
        background: linear-gradient(135deg, #D187F5, #FFFFFF); /* Gradient background for the entire page */
    }

    #content {
        margin-left: 280px; /* Shift content more to the right */
        padding: 20px;
        width: calc(100% - 280px); /* Adjust content width */
        background-color: rgba(255, 255, 255, 0.9); /* Optional: white background with transparency */
        border-radius: 5px; /* Optional: rounded corners */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: shadow for depth */
    }

    h1 {
        margin-top: 20px; /* Space above h1 */
        margin-left: 300px; /* Align h1 beside the sidebar */
        margin-bottom: 20px; /* Add space below h1 */
    }

    .faq-container {
        width: calc(100% - 280px); /* Make sure it takes the remaining width */
        margin-left: 280px; /* Align left side with sidebar */
        padding: 20px;
        height: auto; /* Allow it to grow with content */
        max-height: none; /* Disable max height to show all FAQs */
        overflow-y: auto; /* Allow scrolling if content exceeds window height */
        background-color: #ffffff; /* Ensure visibility */
        border-radius: 5px; /* Optional: rounded corners */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: shadow for depth */
    }

    .faq-item {
        margin-bottom: 15px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
    }

    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        transition: background-color 0.3s ease;
    }

    .faq-question:hover {
        background-color: #e9e9e9;
    }

    .faq-toggle {
        font-size: 20px;
        font-weight: bold;
        transition: transform 0.3s ease;
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.3s ease, opacity 0.3s ease;
        margin-top: 10px;
        font-size: 16px;
        padding: 10px 15px;
        background-color: #f4f4f4;
        border-radius: 5px;
    }

    .faq-answer.active {
        max-height: 500px; /* Allow larger space for content */
        opacity: 1;
    }

    @media (max-width: 600px) {
        #content {
            margin-left: 0;
            width: 100%;
            padding: 10px;
        }
        h1 {
            margin-left: 0; /* Reset margin for small screens */
        }
        .faq-container {
            margin-left: 0; /* Reset margin for small screens */
            padding: 10px;
        }
    }
</style>

<!-- Link the external JavaScript file -->
<script src="faq.js"></script>

<?php
// Output the page content
echo $page_content;
?>
