<?php
session_start();  // Start the session to access session variables
if ($_SESSION['profileName'] !== 'User Admin') {
    header('Location: ../login.php');
    exit();
}
// Include necessary files
require_once(__DIR__ . '/adminNavbar.php');
require_once(__DIR__ . '/../../controllers/UserAdmin/ViewUPController.php');
require_once(__DIR__ . '/../../controllers/UserAdmin/SearchUPController.php');

// Get the search query from GET request
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

// Instantiate the controllers
$viewController = new ViewUserProfileController();
$searchController = new SearchUserProfileController();

// Check if there's a search query
if ($searchQuery) {
    // Fetch user profiles based on the search query
    $userProfiles = $searchController->searchUserProfiles($searchQuery);
} else {
    // Fetch all user profiles if no search query is provided
    $userProfiles = $viewController->viewUserProfiles();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Profiles</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 40px; }
        .container { background: white; padding: 30px; max-width: 1000px; margin: auto; margin-top: 80px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; }
        .detail { margin-bottom: 15px; }
        .label { font-weight: bold; }
        .search-container { margin-bottom: 20px; text-align: center; }
        .search-container h2 { margin-top: 0; }
        .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 4px; width: 60%; margin-bottom: 10px; }
        .search-button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .search-button:hover { background-color: #0056b3; }
        .reset-button {padding: 10px 20px;background-color: #808080; color: white; border: none;border-radius: 4px;cursor: pointer;}
        .reset-button:hover {background-color: #565656; /* Darker Gray */}
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .actions-buttons button { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 4px; cursor: pointer; }
        .actions-buttons .update-button { background-color: #28a745; color: white; text-decoration: none; }
        .actions-buttons .suspend-button { background-color: #dc3545; color: white; }
        .actions-buttons button:hover { opacity: 0.8; }
        .no-results { text-align: center; font-style: italic; color: #777; }
    </style>
</head>
<body>

    <div class="container">

        <!-- Search Form -->
        <div class="search-container">
        <h2>Search by Profile Name or ID</h2>
            <form action="" method="GET">
                <input type="text" class="search-input" name="search" placeholder="Enter profile name or ID" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="search-button">Search</button>
                <button type="reset" class="reset-button" onclick="window.location.href = window.location.pathname;">Reset</button>
            </form>
        </div>


        <h2>User Profile List</h2>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Profile Name</th>
                    <th>Is Suspended</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    // Check if user profiles are available
                    if (empty($userProfiles)) {
                        echo "<tr><td colspan='4' class='no-results'>No results found.</td></tr>";
                    } else {
                        foreach ($userProfiles as $profile) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($profile->getProfileId()) . "</td>";
                            echo "<td>" . htmlspecialchars($profile->getName()) . "</td>";
                            echo "<td>" . htmlspecialchars($profile->getIsSuspended() ? 'Yes' : 'No') . "</td>";
                            echo "<td class='actions-buttons'>
                                    <button onclick=\"window.location.href='updateUP.php?userid=" . $profile->getProfileId() . "';\" class='update-button'>Update</button>
                                    <button onclick=\"return confirm('Are you sure you want to suspend this user?') ? window.location.href='suspendUP.php?userid=" . $profile->getProfileId() . "' : false;\" class='suspend-button'>Suspend</button>
                                  </td>";
                            echo "</tr>";
                        }
                    }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
