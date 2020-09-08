<?php
session_start();
if(isset($_POST['status']) && isset($_POST['idurona'])){

  include("DBI.php");
  //Dohvati podatke
  $status = $_POST['status'];
  $idurona = $_POST['idurona'];
  $e_mail = $_SESSION['e_mail'];

  //Dohvati ID_korisnika
  $user_query = $mysqli->query("SELECT ID_korisnika FROM korisnici WHERE Email = '$e_mail'");
  $userID = $user_query -> fetch_assoc();
  $ID = $userID['ID_korisnika'];

  if($status == "join"){ //Radi se o prijavi na uron
    $opis = "Pridruzivanje uronu";
    $akcija = "Zahtjev_dodan";
    
    //Spremi zahtjev u bazu podataka
    $mysqli->query("INSERT INTO `zahtjevi` (`Opis`, `ID_podnositelja`, `ID_urona`)
    VALUES ('$opis', '$ID', '$idurona');");
    //Vrati ID zahtjeva
    $request_query = $mysqli->query("SELECT ID_zahtjeva FROM zahtjevi WHERE ID_podnositelja = $ID ORDER BY ID_zahtjeva DESC LIMIT 1");
    $request_arr = $request_query -> fetch_assoc();
    $request_ID = $request_arr['ID_zahtjeva'];

    $arr = array('akcija'=>$akcija,'idzahtjeva'=>$request_ID);
    echo json_encode($arr);
  }
  else if($status == "cancel"){ //Radi se o ponistavanju zahtjeva za uron
      $akcija = "Ponistavanje_zahtjeva";
      //Obrisi zahtjev iz baze podataka
      $mysqli->query("DELETE FROM zahtjevi WHERE ID_urona = $idurona AND ID_podnositelja = $ID;");
      $arr = array('akcija'=>$akcija);
      echo json_encode($arr);
  }
  exit;
}
?>
