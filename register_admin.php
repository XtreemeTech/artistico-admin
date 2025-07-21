<?php

include 'components/connect.php';



$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:index.php');
}

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $pass = $_POST['pass'];
  $pass = filter_var($pass, FILTER_SANITIZE_STRING);
  $cpass = $_POST['cpass'];
  $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

  $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
  $select_admin->execute([$name]);

  if ($select_admin->rowCount() > 0) {
    $message[] = 'username already exist!';
  } else {
    if ($pass != $cpass) {
      $message[] = 'confirm password not matched!';
    } else {
      $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
      $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password) VALUES(?,?)");
      $insert_admin->execute([$name, $hashedPassword]);
      $message[] = 'new admin registered successfully!';
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register New Admin</title>
  <?php include 'components/stylesheets.php'; ?>
</head>

<body>
  <?php include 'components/header.php'; ?>
  <div class="container-fluid page-body-wrapper">
    <?php include 'components/sidebar.php'; ?>
    <div class="main-panel">
      <div class="content-wrapper">
        <div class="row">
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Register Admin</h4>
                <p class="card-description">
                  Add a new admin to the backend
                </p>
                <form action="" method="post">
                  <div class="form-group">
                    <label for="InputUsername1">Username</label>
                    <input type="text" class="form-control" id="InputUsername1" value="<?= $fetch_profile['name']; ?>"
                      placeholder="Username" name="name" maxlength=" 20"
                      oninput="this.value = this.value.replace(/\s/g, '')" required>
                  </div>
                  <!-- <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                  </div> -->
                  <div class="form-group">
                    <label for="InputPassword1">Password</label>
                    <input type="password" class="form-control" id="InputPassword1" placeholder="Enter your password"
                      name="pass" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="form-group">
                    <label for="InputConfirmPassword1">Confirm Password</label>
                    <input type="password" class="form-control" id="InputConfirmPassword1"
                      placeholder="Confirm new password" name="cpass" maxlength="20"
                      oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <input type="submit" value="Register Now" class="btn btn-primary mr-2" name="submit">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include 'components/footer.php'; ?>
    </div>
  </div>
  <?php include 'components/scripts.php'; ?>
</body>

</html>
