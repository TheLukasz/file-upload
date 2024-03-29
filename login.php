<?php
  session_start();
  if(isset($_POST["logout"])){
    unset($_SESSION["logged-in"],
          $_SESSION["username"],
          $_POST["logout"]);
  }
?>
<!DOCTYPE html>
<html>
   <head>
    <link rel="stylesheet" href="style.css">
   </head>
  <body>
    <p><b>Login</b></p>
    <form action="" method="post">
    <input type="text" name="username" id="username" placeholder="username" required
           value="<?php echo $_POST["username"] ?: "" ?>">
    <br>
    <input type="password" name="password" id="password" placeholder="password" required
           value="<?php echo $_POST["password"] ?: "" ?>">
    <button type="submit" name="clicked">OK</button>
    </form>
    <?php
      $lines = file('./users.txt');
      $lines = array_filter(array_map(function($n){
        return preg_match("/^[^#].*-.*/",$n) ? $n : null;
      },$lines));
      $lines = array_map(null,array_map(function($n){
        return explode("-",$n);
      },$lines));
      $usernames = array_map('trim',array_column($lines,0));
      $hashes = array_map('trim',array_column($lines,1));
      $passwords = array_combine($usernames,$hashes);

      if(isset($_POST['clicked'])){
        if(md5($_POST['password']) == $passwords[$_POST['username']]){
          $_SESSION['logged-in'] = true;
          $_SESSION['username'] = $_POST["username"];
          header("Location: ./file-upload.php");
        }else {
          echo "<p>Wrong username or password</p>";
        }
        }
        echo "<p>" . "logged-in: " .
          ($_SESSION['logged-in'] == true 
          ? (
            "true - " . $_SESSION["username"] .
            "<form action=\"./file-upload.php\" style=\"float:left;padding-right:5px;\">
              <input type=\"submit\" value=\"Go to upload\">
            </form>
            <form action=\"\" method=\"post\">
              <input type=\"submit\" name=\"logout\" value=\"Logout\">
            </form>"
          ) 
          : "false"
          ) . "</p>";
    ?>
  </body>
<html>
