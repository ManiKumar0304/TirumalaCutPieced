<?
session_start();

if ($_SESSION['user_id'] != '') {
  header("location:/index");
  die();
}

include "includes/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['email'] != '') {

  $email = mysqli_real_escape_string($con, $_POST['email']);

  $password = mysqli_real_escape_string($con, $_POST['password']);

  $rs = mysqli_query($con, "select * from users where email='$email' and password = '$password'");
  if (mysqli_num_rows($rs) > 0) {

    $row = mysqli_fetch_assoc($rs);

    if ($row['status'] == 1) {

      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['station_id'] = $row['station_id'];
      $_SESSION['own_station'] = $row['station_id'];
      $_SESSION['role'] = $row['role'];
      $a_stations[] = $_SESSION['own_station'];


      if ($_SESSION['role'] == 0) {
        $sql = "SELECT * FROM `stations`";
      } else {
        $sql = "SELECT * FROM `stations` where sdpo_station_id='" . $_SESSION['own_station'] . "' OR ci_station_id='" . $_SESSION['own_station'] . "'";
      }

      $acc = mysqli_query($con, $sql);


      while ($acc_stations = mysqli_fetch_assoc($acc)) {
        $a_stations[] = $acc_stations['station_id'];
      }

      $_SESSION['access_stations'] = implode(',', $a_stations);




      if ($_POST['redirect'] != '') {
        header("location:/cases/" . $_POST['redirect'] . "?id=" . $_POST['redirect']."");
      } else {
        header("location:/index");
      }

      die();

    } else
      $err = "Account Not active. Please contact administrator";

  } else
    $err = "Invalid Username/Password";

}

?>
<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Anakapalli District e Malkhana | Dashboard</title>
  <meta name="description" content="Anakapalli District e Malkhana" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <link rel="shortcut icon" href="images/favicon.ico" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body {
      display: flex;
      align-items: center;
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: #f5f5f5;
    }
  </style>
</head>

<body class="text-center">
  <main class="form-signin">
    <form id="frm_login" action="" method="post">
      <img class="mb-4" src="images/logo.png" alt="">
      <h1 class="frontpg">Anakapalli District e Malkhana</h1>
      <div class="checkbox mb-3">
        <div class="input-group"> <span class="input-group-text"><i class="fa fa-envelope"></i></span>
          <input type="hidden" name="redirect" value="<?= $_GET['id'] ?>" />
          <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Email address"
            autocomplete="off" data-parsley-required-message="Please enter email" required autofocus>
        </div>
      </div>
      <div class="checkbox mb-3">
        <div class="input-group"> <span class="input-group-text"><i class="fa fa-key"></i></span>
          <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password"
            minlength="8" maxlength="16" required>
        </div>
      </div>
      <div class="checkbox mb-3">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
      </div>
      <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
      <div class="checkbox mb-3"> <a class="form-check-label for-pass" href="/forgotpage.php">Forgot Password</a> </div>
      <? if ($err != '') { ?>
        <div class="alert alert-danger err" role="alert">
          <?= $err ?>
        </div>
      <? } ?>
    </form>
    <footer class="my-5 pt-5 text-muted text-center text-small print_hide">
      <p class="mb-1">&copy;
        <?= date('Y') ?>
        Receptum E-Logic Software Solutions
      </p>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/parsley.min.js"></script>
    <script src="js/scripts.js"></script>
  </main>

</html>