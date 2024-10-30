<?php
session_start(); 

$conn = mysqli_connect('localhost:4306', 'root', '', 'stdinfo');
$showUpdateForm = false;
$updateSuccessful = false;


if (isset($_POST['btn'])) {
    $stdname = $_POST['stdname'];
    $stdreg = $_POST['stdreg'];

    if (!empty($stdname) && !empty($stdreg)) {
        $query = "INSERT INTO student(stdname, stdreg) VALUES ('$stdname', $stdreg)";
        $createQuery = mysqli_query($conn, $query);
        if ($createQuery) {
            $_SESSION['message'] = "Data successfully inserted.";
        }
    } else {
        $_SESSION['message'] = "Fields should not be empty.";
    }
    header("Location: index.php"); // Redirect to avoid re-submission
    exit;
}

if (isset($_GET['delete'])) {
    $stdid = $_GET['delete'];
    $query = "DELETE FROM student WHERE id={$stdid}";
    $deleteQuery = mysqli_query($conn, $query);
    if ($deleteQuery) {
        $_SESSION['message'] = "Data successfully deleted.";
    }
    header("Location: index.php"); 
    exit;
}


if (isset($_GET['update'])) {
    $stdid = $_GET['update'];
    $query = "SELECT * FROM student WHERE id={$stdid}";
    $getData = mysqli_query($conn, $query);

    if ($getData && mysqli_num_rows($getData) > 0) {
        $rx = mysqli_fetch_assoc($getData);
        $stdname = $rx['stdname'];
        $stdreg = $rx['stdreg'];
        $showUpdateForm = true;
    }
}


if (isset($_POST['update-btn'])) {
    $stdname = $_POST['stdname'];
    $stdreg = $_POST['stdreg'];
    $stdid = $_POST['stdid'];

    if (!empty($stdname) && !empty($stdreg)) {
        $query = "UPDATE student SET stdname='$stdname', stdreg=$stdreg WHERE id=$stdid";
        $updateQuery = mysqli_query($conn, $query);
        if ($updateQuery) {
            $_SESSION['message'] = "Data successfully updated.";
            header("Location: index.php"); // Redirect to clear update form
            exit;
        }
    } else {
        $_SESSION['message'] = "Fields should not be empty.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<title>CRUD</title>
</head>
<body>

<div class="container shadow m-5 p-4 mx-auto rounded">
    <?php
   
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-info'>{$_SESSION['message']}</div>";
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>
    
    <form method="post" class="d-flex justify-content-around">
        <input class="form-control me-3" type="text" name="stdname" placeholder="Enter Name" required>
        <input class="form-control me-3" type="number" name="stdreg" placeholder="Enter Reg Number" required>
        <input class="btn btn-success" type="submit" value="Submit" name="btn">
    </form>
</div>

<div class="container m-5 p-3 mx-auto" id="update-container" style="display: <?php echo $showUpdateForm ? 'block' : 'none'; ?>;">
    <form method="post" class="d-flex justify-content-around">
        <input type="hidden" name="stdid" value="<?php echo isset($stdid) ? $stdid : ''; ?>">
        <input class="form-control me-3" type="text" name="stdname" value="<?php echo isset($stdname) ? $stdname : ''; ?>" required>
        <input class="form-control me-3" type="number" name="stdreg" value="<?php echo isset($stdreg) ? $stdreg : ''; ?>" required>
        <input class="btn btn-primary" type="submit" value="Update" name="update-btn">
    </form>
</div>

<div class="container">
    <table class="table table-bordered">
        <tr>
            <th>STD ID</th>
            <th>STD NAME</th>
            <th>Reg No</th>
            <th></th>
            <th></th>
        </tr>
        
        <?php
        $query = "SELECT * FROM student";
        $readQuery = mysqli_query($conn, $query);

        if ($readQuery && mysqli_num_rows($readQuery) > 0) {
            while ($rd = mysqli_fetch_assoc($readQuery)) {
                $stdid = $rd['id'];
                $stdname = $rd['stdname'];
                $stdreg = $rd['stdreg'];
        ?>
        <tr>
            <td><?php echo $stdid; ?></td>
            <td><?php echo $stdname; ?></td>
            <td><?php echo $stdreg; ?></td>
            <td><a href="index.php?update=<?php echo $stdid; ?>" class="btn btn-info">Update</a></td>
            <td><a href="index.php?delete=<?php echo $stdid; ?>" class="btn btn-danger">Delete</a></td>
        </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='5'>No data to show</td></tr>";
        }
        ?>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
