<?php

include 'components/connect.php';



$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
  header('location:index.php');
}

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $name = filter_var($name, FILTER_SANITIZE_STRING);

  $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
  $update_profile_name->execute([$name, $admin_id]);

  $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
  $prev_pass = $_POST['prev_pass'];
  $old_pass = sha1($_POST['old_pass']);
  $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
  $new_pass = sha1($_POST['new_pass']);
  $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
  $confirm_pass = sha1($_POST['confirm_pass']);
  $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

  if ($old_pass == $empty_pass) {
    $message[] = 'please enter old password!';
  } elseif ($old_pass != $prev_pass) {
    $message[] = 'old password not matched!';
  } elseif ($new_pass != $confirm_pass) {
    $message[] = 'confirm password not matched!';
  } else {
    if ($new_pass != $empty_pass) {
      $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
      $update_admin_pass->execute([$confirm_pass, $admin_id]);
      $message[] = 'password updated successfully!';
    } else {
      $message[] = 'please enter a new password!';
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
  <title>Update profile</title>
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
                <h4 class="card-title">Update Profile</h4>
                <p class="card-description">
                  Update your details
                </p>
                <form action="" method="post">
                  <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
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
                    <label for="InputPassword1">Current Password</label>
                    <input type="password" class="form-control" id="InputPassword1" placeholder="Enter current password"
                      name="old_pass" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="form-group">
                    <label for="InputPassword2">New Password</label>
                    <input type="password" class="form-control" id="InputPassword2" placeholder="Enter new password"
                      name="new_pass" maxlength="20" oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <div class="form-group">
                    <label for="InputConfirmPassword1">Confirm Password</label>
                    <input type="password" class="form-control" id="InputConfirmPassword1"
                      placeholder="Confirm new password" name="confirm_pass" maxlength="20"
                      oninput="this.value = this.value.replace(/\s/g, '')">
                  </div>
                  <input type="submit" value="Update Now" class="btn btn-primary mr-2" name="submit">
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
