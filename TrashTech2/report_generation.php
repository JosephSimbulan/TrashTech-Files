<?php
// report_generation.php
include 'db_connection.php';
include 'header.php'; // Include the header file for the page
include 'sidebar.php'; // Include the sidebar file for the page

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    echo "User ID is not set in the session.";
    exit();
}

// Set the timezone
date_default_timezone_set('Asia/Manila');

// Get company name from session
$company_name = $_SESSION['company_name'];

// Get the current timestamp values
$currentYear = date('Y');
$currentMonth = date('m');
$currentWeek = date('W'); // ISO week number

// Get the selected report type (weekly or monthly)
$reportType = isset($_POST['report_type']) ? $_POST['report_type'] : 'weekly';

// Helper function to generate SQL based on report type
function getReportQuery($company_name, $currentYear, $currentValue, $type) {
    $interval = ($type === 'weekly') ? "WEEK(timestamp, 1)" : "MONTH(timestamp)";
    
    $sql = "
        SELECT 'paper' AS category, SUM(weight) AS total_weight 
        FROM paper_weight 
        WHERE company_name = ? AND YEAR(timestamp) = ? AND $interval = ?
        
        UNION ALL
        
        SELECT 'plastic', SUM(weight) 
        FROM plastic_weight 
        WHERE company_name = ? AND YEAR(timestamp) = ? AND $interval = ?
        
        UNION ALL
        
        SELECT 'metal', SUM(weight) 
        FROM metal_weight 
        WHERE company_name = ? AND YEAR(timestamp) = ? AND $interval = ?
        
        UNION ALL
        
        SELECT 'glass', SUM(weight) 
        FROM glass_weight 
        WHERE company_name = ? AND YEAR(timestamp) = ? AND $interval = ?";
        
    return $sql;
}

// Determine the value to filter by (week or month)
$currentValue = ($reportType === 'weekly') ? $currentWeek : $currentMonth;

// Prepare the SQL statement
$sql = getReportQuery($company_name, $currentYear, $currentValue, $reportType);
$stmt = $conn->prepare($sql);

// Bind parameters for all material types (paper, plastic, metal, glass)
$stmt->bind_param(
    "sii" . str_repeat("sii", 3), 
    $company_name, $currentYear, $currentValue, 
    $company_name, $currentYear, $currentValue, 
    $company_name, $currentYear, $currentValue, 
    $company_name, $currentYear, $currentValue
);

$stmt->execute();
$result = $stmt->get_result();

// Initialize weights
$weights = ['paper' => 0, 'plastic' => 0, 'metal' => 0, 'glass' => 0];

// Process the results
while ($row = $result->fetch_assoc()) {
    $weights[$row['category']] = $row['total_weight'] ?: 0;
}

// For weekly and monthly comparison, fetch previous data
$prevValue = ($reportType === 'weekly') ? ($currentWeek - 1) : ($currentMonth - 1);
$prevYear = ($reportType === 'weekly' && $currentWeek === 1) ? $currentYear - 1 : $currentYear;

// Prepare the SQL statement for previous week/month
$prevSql = getReportQuery($company_name, $prevYear, $prevValue, $reportType);
$prevStmt = $conn->prepare($prevSql);

// Bind parameters for previous material types (paper, plastic, metal, glass)
$prevStmt->bind_param(
    "sii" . str_repeat("sii", 3), 
    $company_name, $prevYear, $prevValue, 
    $company_name, $prevYear, $prevValue, 
    $company_name, $prevYear, $prevValue, 
    $company_name, $prevYear, $prevValue
);

$prevStmt->execute();
$prevResult = $prevStmt->get_result();

// Initialize previous weights
$prevWeights = ['paper' => 0, 'plastic' => 0, 'metal' => 0, 'glass' => 0];

// Process the previous results
while ($row = $prevResult->fetch_assoc()) {
    $prevWeights[$row['category']] = $row['total_weight'] ?: 0;
}

// Function to generate comparison messages
function generateComparisonMessage($currentWeight, $previousWeight, $material) {
    $difference = $currentWeight - $previousWeight;
    if ($difference > 0) {
        return "Wow! You've increased $difference kg of $material this " . ($material === 'week' ? 'week!' : 'month!');
    } elseif ($difference < 0) {
        return "You've decreased by " . abs($difference) . " kg of $material this " . ($material === 'week' ? 'week!' : 'month!');
    } else {
        return "You've maintained the same weight of $material this " . ($material === 'week' ? 'week!' : 'month!');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Generation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #8e44ad, #e4b7ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #content {
            width: 600px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .report-header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        select {
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 15px;
        }
        .stat {
            text-align: center;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .stat-value {
            font-size: 28px;
            color: #8e44ad;
            font-weight: bold;
        }
        .stat-label {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }
        .comparison {
            margin-top: 20px;
            font-size: 18px;
        }
        .comparison span {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div id="content">
    <form method="POST" action="report_generation.php">
        <div class="report-header">
            <h2><?php echo ucfirst($reportType); ?> Report</h2>
            <select name="report_type" onchange="this.form.submit()">
                <option value="weekly" <?php if ($reportType === 'weekly') echo 'selected'; ?>>Weekly</option>
                <option value="monthly" <?php if ($reportType === 'monthly') echo 'selected'; ?>>Monthly</option>
            </select>
        </div>

        <div class="stats-container">
            <div class="stat">
                <div class="stat-value"><?php echo $weights['paper']; ?> kg</div>
                <div class="stat-label">Paper</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?php echo $weights['plastic']; ?> kg</div>
                <div class="stat-label">Plastic</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?php echo $weights['metal']; ?> kg</div>
                <div class="stat-label">Metal</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?php echo $weights['glass']; ?> kg</div>
                <div class="stat-label">Glass</div>
            </div>
        </div>

        <div class="comparison">
            <h3>Comparison with Previous <?php echo ucfirst($reportType); ?>:</h3>
            <?php if ($reportType === 'weekly'): ?>
                <p><?php echo generateComparisonMessage($weights['paper'], $prevWeights['paper'], 'paper'); ?></p>
                <p><?php echo generateComparisonMessage($weights['plastic'], $prevWeights['plastic'], 'plastic'); ?></p>
                <p><?php echo generateComparisonMessage($weights['metal'], $prevWeights['metal'], 'metal'); ?></p>
                <p><?php echo generateComparisonMessage($weights['glass'], $prevWeights['glass'], 'glass'); ?></p>
            <?php elseif ($reportType === 'monthly'): ?>
                <p><?php echo generateComparisonMessage($weights['paper'], $prevWeights['paper'], 'paper'); ?></p>
                <p><?php echo generateComparisonMessage($weights['plastic'], $prevWeights['plastic'], 'plastic'); ?></p>
                <p><?php echo generateComparisonMessage($weights['metal'], $prevWeights['metal'], 'metal'); ?></p>
                <p><?php echo generateComparisonMessage($weights['glass'], $prevWeights['glass'], 'glass'); ?></p>
            <?php endif; ?>
        </div>
    </form>
</div>

</body>
</html>
