<?php
ini_set( "display_errors", 0); 
session_start();
include "user.php";
if ( $_POST["register"] )(new User())->registerUser($_POST);

if ( $_POST["login"] )   (new User())->loginUser($_POST["username"],$_POST["pass"]);

if ( $_POST["logout"] )  (new User())->logoutUser();

?>

<!DOCTYPE html>
<html>
<head>
<title>Zadanie Rekrutacyjne na DEV PHP 2021</title>
<meta charset="UTF-8">
<meta name="description" content="Zadanie Rekrutacyjne na DEV PHP 2021">
<meta name="keywords" content="HTML, CSS, PHP, Login, Register, ">
<meta name="author" content="Dawid Kaliszewski">


</head>
<body>

<?php

if (isset($_SESSION['logout'])){
    unset($_SESSION['logout']);
    echo '
    <h1>Wylogowano</h1>
    <form method="post">
    <input type="submit" value="Strona Logowania/Rejestracji" id="refresh" name="refresh" >
  </form>
    <br>';
}else 
if (isset($_SESSION['user'])==false){
    echo '<h1>Rejestracja</h1><br>

    <form method="post">
      <label for="username">Username:</label><br>
      <input type="text" id="username" name="username" required><br>
      <label for="pass">Password:</label><br>
      <input type="password" id="pass" name="pass" required><br>
      <label for="pass2">Confirm Password:</label><br>
      <input type="password" id="pass2" name="pass2" required><br>
      <label for="firstname">Firstname:</label><br>
      <input type="text" id="firstname" name="firstname" required><br>
      <label for="lastname">Lastname:</label><br>
      <input type="text" id="lastname" name="lastname" required><br>
      <input type="radio" id="male" name="gender" value="male" checked required>
      <label for="male">Male</label><br>
      <input type="radio" id="female" name="gender" value="female">
      <label for="female">Female</label><br>
      <input type="radio" id="other" name="gender" value="other">
      <label for="other">Other</label><br>
      <input type="submit" value="Rejestracja" id="register" name="register">
    </form>
    
    <h1>Logowanie</h1><br>
    
    <form method="post">
      <label for="username">Username:</label><br>
      <input type="text" id="username" name="username" required><br>
      <label for="pass">Password:</label><br>
      <input type="password" id="pass" name="pass" required><br>
      <input type="submit" value="Logowanie" id="login" name="login">
    </form>';
    
}
  else {
    echo '<h3><a href="vacation.php" >Wniosek Urlopowy</a></h3><br><br>Masz na imię '.$_SESSION['firstname'].' '.$_SESSION['lastname'].' i jesteś ';
    if ($_SESSION['gender']=="male") echo 'mężczyzną';
    if ($_SESSION['gender']=="female") echo 'kobietą';
    if ($_SESSION['gender']=="other") echo 'osobą niebinarną';
    echo '
    <br>
    <br>
    <form method="post">
      <input type="submit" value="Wylogowanie" id="logout" name="logout" >
    </form><br><br>
    ';
}
?>
</body>
</html>