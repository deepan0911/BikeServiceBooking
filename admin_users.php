
<?php 
require_once 'config.php'; 
session_start();

// Check if admin is logged in 
if (!isset($_SESSION['admin_logged_in'])) { 
    header('Location: admin_login.php'); 
    exit; 
}

// Retrieve users from database 
$query = "SELECT * FROM users";
$result = $conn->query($query);
$users = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Users</title>
    <link rel="stylesheet" href="styles1.css">
    <style>
        .search-container {
            display: flex;
            justify-content: right;
            margin-bottom: 20px;
        }
        
        .search-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
        }
        
        .search-button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #4CAF50;
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin.php">Bookings</a></li>
                <li><a href="admin_users.php">Users</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main class="dashboard">
        <h1>Admin Users</h1>
        <div class="search-container">
            <input type="text" class="search-input" id="search-input" placeholder="Search by name or email">
            <button class="search-button" onclick="searchUsers()">Search</button>
        </div>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="user-table-body">
                <?php foreach ($users as $user) { ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['name']; ?></td>
                        <td><?php echo $user['mobile']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <button class="action-button">
                                <a href="admin_edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                            </button>
                            <button class="action-button">
                                <a href="admin_delete_user.php?id=<?php echo $user['id']; ?>">Delete</a>
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>
</html>

<script>
    function searchUsers() {
        const searchInput = document.getElementById('search-input').value.toLowerCase();
        const tableRows = document.querySelectorAll('#user-table-body tr');

        tableRows.forEach((row) => {
            const rowText = row.textContent.toLowerCase();
            if (!rowText.includes(searchInput)) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
            }
        });
    }
</script>



