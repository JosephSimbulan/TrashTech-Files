<?php
include 'header.php';
include 'sidebar.php';
include 'db_connection.php'; 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// CSRF Token Generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Retrieve company_name based on the username
$query = "SELECT company_name FROM users WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();

if (!$user_info) {
    echo "User not found.";
    exit();
}

$_SESSION['company_name'] = $user_info['company_name'];

// Fetch users by company
function fetchUsers($conn, $company_name) {
    $query = "SELECT * FROM users WHERE company_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $company_name);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$errors = [];
$success_message = ''; // Variable to store success messages

// Password Validation Function
function validatePassword($password) {
    return strlen($password) >= 8 && strlen($password) <= 16 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password);
}

// Handle Create, Update, and Delete Actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('Invalid CSRF token.');
    }

    $action = $_POST['action'] ?? null;
    $userId = $_POST['user_id'] ?? null;
    $full_name = $_POST['full_name'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $company_name = $_POST['company_name'] ?? $_SESSION['company_name'];
    $company_address = $_POST['company_address'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validation Logic Based on Action
    if ($action === 'create_user') {
        // Full validation for creating a user
        if (empty($full_name)) {
            $errors['full_name'] = "Full name is required.";
        }
        if (!preg_match('/^\d{10}$/', $contact_number)) {
            $errors['contact_number'] = "Contact number must be exactly 10 digits.";
        }
        if (empty($username)) {
            $errors['username'] = "Username is required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }
        if ($role != 'admin' && !validatePassword($password)) {
            $errors['password'] = "Password must be 8-16 characters and include uppercase, lowercase, and a number.";
        }
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
    } elseif ($action === 'update') {
        // Minimal validation for updating a user
        if (empty($full_name)) {
            $errors['full_name'] = "Full name is required.";
        }
        if (!preg_match('/^\d{10}$/', $contact_number)) {
            $errors['contact_number'] = "Contact number must be exactly 10 digits.";
        }
        if (empty($username)) {
            $errors['username'] = "Username is required.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }
        // Only validate password if it's being updated
        if (!empty($password) && !validatePassword($password)) {
            $errors['password'] = "Password must be 8-16 characters and include uppercase, lowercase, and a number.";
        }
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
    }

    // Process actions if no errors
    if (empty($errors)) {
        if ($action === 'create_user') {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (full_name, contact_number, username, email, password, company_name, company_address, role) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssss", $full_name, $contact_number, $username, $email, 
                              $hashed_password, $company_name, $company_address, $role);
            $stmt->execute();
            $success_message = "New user created successfully.";
        } elseif ($action === 'update') {
            // Only hash the password if it's being updated
            $hashed_password = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;
            $sql = "UPDATE users SET full_name=?, contact_number=?, username=?, email=?, company_name=?, 
                    company_address=?, role=?". (!empty($hashed_password) ? ", password=?" : "") ." WHERE id=?";
            $stmt = $conn->prepare($sql);
            if (!empty($hashed_password)) {
                $stmt->bind_param("ssssssssi", $full_name, $contact_number, $username, $email, 
                                  $company_name, $company_address, $role, $hashed_password, $userId);
            } else {
                $stmt->bind_param("sssssssi", $full_name, $contact_number, $username, $email, 
                                  $company_name, $company_address, $role, $userId);
            }
            $stmt->execute();
            $success_message = "User updated successfully.";
        } elseif ($action === 'delete') {
            $sql = "DELETE FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $success_message = "User deleted successfully.";
        }
    }
}

$users = fetchUsers($conn, $_SESSION['company_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .toggle-password {
            cursor: pointer;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
    <script>
        function toggleEdit(userId) {
            const row = document.getElementById('user-' + userId);
            const editRow = document.getElementById('edit-' + userId);
            row.style.display = (row.style.display === 'none') ? '' : 'none';
            editRow.style.display = (editRow.style.display === 'none') ? '' : 'none';
        }
    </script>
</head>
<body>
    <div id="content">
        <h1>Manage Users</h1>
        
        <?php if ($success_message): ?>
            <div class="success"><?= $success_message ?></div>
        <?php endif; ?>

        <!-- User Creation Form -->
        <form method="POST" action="manage_users.php">
            <h2>Create User</h2>
            <input type="hidden" name="action" value="create_user">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <!-- Form Fields -->
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" placeholder="Enter the user's full name" required>
            <div class="error"><?= $errors['full_name'] ?? '' ?></div>

            <label for="contact_number">Contact Number:</label>
            <div class="contact-number-wrapper">
                <span class="country-code">+63</span>
                <input type="text" name="contact_number" maxlength="10" placeholder="Enter the last 10-digits of your contact number" required>
            </div>
            <div class="error"><?= $errors['contact_number'] ?? '' ?></div>

            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Enter a username" required>
            <div class="error"><?= $errors['username'] ?? '' ?></div>

            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Enter the user's email address" required>
            <div class="error"><?= $errors['email'] ?? '' ?></div>

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Enter a password" required>
            <div class="error"><?= $errors['password'] ?? '' ?></div>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" placeholder="Confirm the password" required>
            <div class="error"><?= $errors['confirm_password'] ?? '' ?></div>

            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" value="<?= $_SESSION['company_name'] ?>" readonly>

            <label for="company_address">Company Address:</label>
            <input type="text" name="company_address" placeholder="Enter the company address">

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Create User</button>
        </form>

        <hr>

        <!-- User List Table -->
        <h2>Existing Users</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Company Name</th>
                    <th>Company Address</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr id="user-<?= $user['id'] ?>">
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
                        <td><?= htmlspecialchars($user['contact_number']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['company_name']) ?></td>
                        <td><?= htmlspecialchars($user['company_address']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <button onclick="toggleEdit(<?= $user['id'] ?>)">Edit</button>
                            <form method="POST" action="manage_users.php" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="edit-<?= $user['id'] ?>" style="display:none;">
                        <form method="POST" action="manage_users.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <td><?= $user['id'] ?></td>
                            <td><input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required></td>
                            <td><input type="text" name="contact_number" value="<?= htmlspecialchars($user['contact_number']) ?>" required></td>
                            <td><input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required></td>
                            <td><input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required></td>
                            <td><input type="text" name="company_name" value="<?= htmlspecialchars($user['company_name']) ?>" readonly></td>
                            <td><input type="text" name="company_address" value="<?= htmlspecialchars($user['company_address']) ?>"></td>
                            <td>
                                <select name="role" required>
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit">Save</button>
                                <button type="button" onclick="toggleEdit(<?= $user['id'] ?>)">Cancel</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
