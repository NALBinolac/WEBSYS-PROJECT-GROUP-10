<?php
include 'config.php';

$result =
mysqli_query($conn,
"SELECT * FROM memberships");
?>

<!DOCTYPE html>
<html>
<head>
<title>Members</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Membership Applications</h2>

<table class="admin-table">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['membership_id']; ?></td>

<td><?= $row['fullname']; ?></td>

<td><?= $row['email']; ?></td>

<td><?= $row['status']; ?></td>

</tr>

<?php } ?>

</table>

</body>
</html>