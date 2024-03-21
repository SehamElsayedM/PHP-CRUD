<?php

$host = "localhost";
$userName = "root";
$password = "";
$DBName = "company";
$con = mysqli_connect($host, $userName, $password, $DBName);

//create query
if (isset($_POST['send'])) {
    print_r($_FILES);
    $Name = $_POST['name'];
    $Email = $_POST['email'];
    $Position = $_POST['position'];
    $Password = $_POST['password'];
    $Gender = $_POST['gender'];
    print_r($_FILES);
    $ImageName = time() . rand(0, 255) . $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $location = 'uploaded/' . $ImageName;

    move_uploaded_file($tmpName, $location);

    $insert = "INSERT INTO `employee` VALUES (NULL, '$Name' , '$Email' , '$Position' , '$Password' , '$Gender' , '$ImageName')";
    $insertQuery = mysqli_query($con, $insert);
}

//empty variables
$Mood = "create";
$Name = '';
$Email = '';
$Position = '';
$Password = '';
$Gender = '';
$Image = null;
$userId = null;
//update query
if (isset($_GET['edit'])) {
    $Mood = "update";
    $id = $_GET['edit'];
    $userId = $_GET['edit'];
    $selectOne = "SELECT * FROM `employee` WHERE ID = $id";
    $getOne = mysqli_query($con, $selectOne);
    $row = mysqli_fetch_assoc($getOne);

    $Name = $row['name'];
    $Email = $row['email'];
    $Position = $row['position'];
    $Password = $row['password'];
    $Gender = $row['gender'];
    $Image = $row['image'];
}
if (isset($_POST['update'])) {
    $Name = $_POST['name'];
    $Email = $_POST['email'];
    $Position = $_POST['position'];
    $Password = $_POST['password'];
    $Gender = $_POST['gender'];

    if ($_FILES['image']['name'] == null) {
        $imageName = $Image;
    } else {
        $ImageName = time() . rand(0, 255) . $_FILES['image']['name'];
        $tmpName = $_FILES['image']['tmp_name'];
        $location = 'uploaded/' . $ImageName;
        move_uploaded_file($tmpName, $location);

        unlink("./uploaded/$Image");
    }

    $update = " UPDATE `employee` SET `name`='$Name' , `email`='$Email' , `position`='$Position' , `password`='$Password' , `gender`='$Gender', `image`='$ImageName' WHERE `ID`=$userId ";
    $updateQuery = mysqli_query($con, $update);
    header("Location: index.php");
}


//delete query
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    //get old image from DB
    $selectOne = "SELECT * FROM `employee` WHERE ID = $id";
    $Query = mysqli_query($con, $selectOne);
    $row = mysqli_fetch_assoc($Query);
    $oldImage = $row['image'];

    //delete the old image 
    unlink("./uploaded/$oldImage");

    $delete = "DELETE FROM `employee` WHERE ID = $id";
    $deleteQuery = mysqli_query($con, $delete);
    header("Location: index.php");
}

//read query
$select = "SELECT * FROM `employee`";
$selectQuery = mysqli_query($con, $select);
$xx = mysqli_fetch_assoc($selectQuery);

$selectTheme = "SELECT * FROM theem WHERE id=1";
$theem = mysqli_query($con, $selectTheme);
$x = mysqli_fetch_assoc($theem);
if(isset($_GET['color'])) {
    $color = $_GET['color'];

    $update = "UPDATE theem SET `color`='$color' WHERE id=1";
    $Query = mysqli_query($con, $update);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

        <?php if ($x['color'] == "dark") : ?>
            <link rel="stylesheet" href="./css/dark.css">
        <?php elseif($x['color'] == "light") : ?>
            <link rel="stylesheet" href="./css/light.css">
        <?php endif; ?>
    
</head>

<body>
    <?php if ($x['color'] == "dark") : ?>
        <a href="?color=light" name="mood" class="btn btn-light">Light Mood</a>

    <?php elseif ($x['color'] == "light") : ?>
        <a href="?color=dark" name="mood" class="btn btn-dark">Dark Mood</a>

    <?php endif; ?>
    <div class="col-4 m-auto">
        <div class="card bg-dark text-light">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?= $Name ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" value="<?= $Email ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="position" class="form-label">Position</label>
                        <select class="form-select mt-3" name="position" id="position">
                            <?php if ($Position == 'Junior') : ?>
                                <option selected value="Junior">Junior Web Developer</option>
                                <option value="Senior">Senior Web Developer</option>
                                <option value="Manager">Project Manager</option>

                            <?php elseif ($Position == 'Senior') : ?>
                                <option value="Junior">Junior Web Developer</option>
                                <option selected value="Senior">Senior Web Developer</option>
                                <option value="Manager">Project Manager</option>

                            <?php elseif ($Position == 'Manager') : ?>
                                <option value="Junior">Junior Web Developer</option>
                                <option value="Senior">Senior Web Developer</option>
                                <option selected value="Manager">Project Manager</option>

                            <?php else : ?>
                                <option selected disabled value="">position</option>
                                <option value="Junior">Junior Web Developer</option>
                                <option value="Senior">Senior Web Developer</option>
                                <option value="Manager">Project Manager</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="<?= $Password ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="image" class="form-label">uploade image</label>
                        <input type="file" accept="image/*" class="form-control" name="image" id="image" value="<?= $image ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select name="gender" id="gender" class="form-select">
                            <?php if ($Gender == 'Male') : ?>
                                <option selected value="Male">Male</option>
                                <option value="Female">Female</option>

                            <?php elseif ($Gender == 'Female') : ?>
                                <option selected value="Female">Female</option>
                                <option value="Male">Male</option>

                            <?php else : ?>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            <?php endif; ?>
                        </select>

                    </div>

                    <div class="form-button mt-3">
                        <?php if ($Mood == "create") : ?>
                            <button name="send" class="btn btn-primary">ADD</button>
                        <?php elseif ($Mood == "update") : ?>
                            <button name="update" class="btn btn-warning">Update</button>
                            <a href="./index.php" class="btn btn-secondary">cancel</a>
                        <?php endif; ?>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-4  m-auto mt-5">
        <table class="table table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Password</th>
                <th>Gender</th>
                <th>Iimage</th>
                <th colspan="2">Action</th>
            </tr>

            <?php foreach ($selectQuery as $employee) : ?>
                <tr>
                    <td><?= $employee['ID'] ?></td>
                    <td><?= $employee['name'] ?></td>
                    <td><?= $employee['email'] ?></td>
                    <td><?= $employee['position'] ?></td>
                    <td><?= $employee['password'] ?></td>
                    <td><?= $employee['gender'] ?></td>
                    <td><img src="./uploaded/<?= $employee['image'] ?>" width="70" alt=""></td>

                    <td>
                        <a href="?edit=<?= $employee['ID'] ?>" class="btn btn-warning">Edit</a>
                    </td>
                    <td><a href="?delete=<?= $employee['ID'] ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>



    <script src="./script.js"></script>
</body>

</html>