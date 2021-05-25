<?php
session_start();//
if(!isset($_SESSION['user'])){
	header("Location:index.php");
    die();
  }
include "file.php";
if (isset( $_POST["send"] )) (new File($_POST))->addFile() ;
if (isset( $_POST["sendDelete"] )) (new File($_POST))->deleteFile($_POST['id']) ;

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
<h3><a href="index.php" >Strona Główna</a></h3>
 <br><br>

<form enctype="multipart/form-data" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="512000000" />
    <label for="radio">Typ urlopu:</label><br>
    <input type="radio" id="ordinary" name="vac" value="ordinary" checked>
    <label for="ordinary">Urlop zwykły</label><br>
    <input type="radio" id="on_request" name="vac" value="on_request" >
    <label for="on_request">Urlop na żądanie</label><br>
    <input type="radio" id="unpaid" name="vac" value="unpaid">
    <label for="unpaid">Urlop bezpłatny</label><br>
    <label for="startDate" >Data rozpoczęcia urlopu:</label>
    <input type="date" id="startDate" name="startDate" required><br>
    <label for="endDate">Data zakończenia urlopu:</label>
    <input type="date" id="endDate" name="endDate" required><br>
    <label for="myfile">Wybierz wniosek (PDF/Image):</label>
    <input type="file" id="myfile" name="myfile" accept=" .jpg, .pdf" required><br>
    <label for="text">Dodatkowy komentarz do wniosku – pole opcjonalne:</label><br>
    <textarea id="text" name="text" rows="4" cols="50"></textarea>
    <input type="submit" value="Wyślij wniosek" id="send" name="send"><br><br>
    
</form>
<table style="width:100%">
  <tr>
    <th>Data Przesłania</th>
    <th>Typ Urlopu</th> 
    <th>Od</th>
    <th>Do</th>
    <th>Plik</th>
    <th>Komentarz</th>
    <th>Opcje</th>
  </tr>
<?php 

try{
    $sql = "SELECT * FROM Docs WHERE iduser=?";
    $results =  (new DbConn())->sql($sql,[$_SESSION['id']]);
    $results = $results->fetchAll();
    foreach ($results as $result){
        echo '  
        <tr>
        <td>'.$result['dateset'].'</td>
        <td>'.$result['vactype'].'</td>
        <td>'.$result['datefrom'].'</td>
        <td>'.$result['dateto'].'</td>
        <td><a href="'.$result['fileurl'].'" download>Przesłany Plik</a> </td>
        <td>'.$result['comment'].'</td>
        <td><form method="post">
        <input type="hidden" id="id" name="id" value="'.$result['id'].'" />
        <input type="submit" value="Usuń" id="sendDelete" name="sendDelete" >
    </form></td>
      </tr>
        ';
    }
}catch (PDOException $e){

}
?>
</table>

</body>
</html>