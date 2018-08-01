<!DOCTYPE html>

<?php
// Initialize the session
session_start();
if(!isset($_SESSION['username']) || empty($_SESSION['username'])
    || empty($_SESSION['lastName']) || !isset($_SESSION['lastName'])
    || empty($_SESSION['code']) || !isset($_SESSION['code'])
    || empty($_SESSION['firstName']) || !isset($_SESSION['firstName'])
    || empty($_SESSION['email']) || !isset($_SESSION['email'])
    || empty($_SESSION['companyName']) || !isset($_SESSION['companyName'])){
    header("location: index.php");
    exit;
}

require_once 'dbConfig.php';
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete User</title>
</head>
<body>
<h3>Users In <?php echo htmlspecialchars($_SESSION['companyName']); ?>'s System</h3>
<br>

<table id="userTable">
    <tr>
        <th>Username</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Delete?</th>
    </tr>

    <div>
        <?php

        $user = $_SESSION['username'];
        $code = $_SESSION['code'];
        $firstName = $_SESSION['firstName'];
        $lastName = $_SESSION['lastName'];

        $sql = "select Company.UserName, Employee.FirstName, Employee.LastName
            from Company 
            INNER JOIN Employee ON Company.UserName=Employee.UserName 
            where CompanyCode='$code'";
        $result = $conn->query($sql);

        if ($result->num_rows >= 1){
            while($row = $result->fetch_assoc()) {
                $username = $row['UserName'];
                $usernameURL = urlencode($username);

                //watch out for upper/lower case with this one...
                $firstname = $row['FirstName'];
                $firstnameURL = urlencode($firstname);

                $lastname = $row['LastName'];
                $lastnameURL = urlencode($lastname);

                echo "<tr>";
                echo "<td>$username</td>";
                echo "<td>$firstname</td>";
                echo "<td>$lastname</td>";
                $url = "DeleteUser.php?UserName=$usernameURL&FirstName=$firstnameURL&LastName=$lastnameURL";
                echo "<td><a href=$url><input type=\"button\" value=\"Delete\"></a></td>";
                echo "</tr>";
            }
        }
        ?>
</table>
<br>
</div>
<input type="button" value="Delete Selected User(s)" onclick="deleteUsers()">
<br>

<?php
$user = $_SESSION['username'];
$code = $_SESSION['code'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$email = $_SESSION['email'];
$companyName = $_SESSION['companyName'];

if (isset($_GET['UserName'])
    && isset($_GET['FirstName'])
    && isset($_GET['LastName'])
)
{
    $userToDelete = $_GET['UserName'];
    $sql = "DELETE a.*, b.* 
            FROM Employee a 
            LEFT JOIN Company b 
            ON b.UserName = a.UserName 
            WHERE a.UserName = '$userToDelete'";
    $result = $conn->query($sql);
    header("location: DeleteUser.php");
}
?>

</body>
</html>