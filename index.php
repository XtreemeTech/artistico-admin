<?php

include 'components/connect.php';


if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);
  $pass = $_POST['pass'];
  $pass = filter_var($pass, FILTER_SANITIZE_STRING);

  $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
  $select_admin->execute([$name]);
  $row = $select_admin->fetch(PDO::FETCH_ASSOC);


  if ($select_admin->rowCount() > 0) {
    if (password_verify($pass, $row['password'])){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
    }
    else {
      $message[] = 'invalid username or password!';
    }
  } else {
    $message[] = 'user not found!';
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="../css/base.css">
  <?php include 'components/stylesheets.php'; ?>
  <style>
  form * {
    font-style: normal;
  }
  </style>

</head>

<body>

  <?php
  if (isset($message)) {
    foreach ($message as $message) {
      echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
    }
  }
  ?>

  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <section class="form-container auth-form-light text-center p-5">
              <div class="brand-logo">
                <img src="components/assets/images/logo.png" alt="logo">
              </div>
              <h2>Login</h2>
              <p class="font-weight-light text-muted">Please enter your login and password</p>
              <form method="post" class="pt-3">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg box" name="name" placeholder="Username"
                    maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')" required>
                </div>
                <div class="form-group">
                  <input type="password" name="pass" class="form-control form-control-lg box" placeholder="Password"
                    maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')" required>
                </div>
                <div class="mt-3">
                  <input type="submit" value="Login" style="line-height: 16px;"
                    class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit">
                </div>
                <div class="text-center mt-2 font-weight-light">
                  <a href="#" class="text-primary text-muted">Forgot Password?</a>
                </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'components/scripts.php'; ?>
</body>

</html>
