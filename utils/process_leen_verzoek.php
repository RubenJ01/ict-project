<?php

/**
 * @file process_leen_verzoek.php
 *
 * @brief Er staan hier een paar functies voor het accepeteren of voor het weigeren van een leenverzoek
 *
 * Dit bestand maakt gebruiker van database_connection.php
 */

 // Voeg de database_connection.php toe. Als we process_leen_verzoek includen in een van de root bestanden dan moeten we in de utils folder kijken en anders niet
 $db_conn_file = "database_connection.php";
 if (file_exists("database_connection.php") == false) {
   $db_conn_file = ("utils/".$db_conn_file);
 }
 require_once $db_conn_file;

 function UpdateBicycles($eigenaarId) {
   $query = "UPDATE leen_verzoek l
             SET l.status_ = 'verlopen'
             WHERE (l.fiets_id IN (
               SELECT f.id
               FROM fietsen f
               WHERE f.gebruiker_id = ?
             ) OR l.lener_id = ?) AND l.status_ = 'in_afwachting' AND l.ophaal_moment < NOW()
             ";
   $stmt = $GLOBALS['mysqli']->prepare($query);
   if (!$stmt) {
     trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
   }
   else {
     $stmt->bind_param('ii', $eigenaarId, $eigenaarId);
     if (!$stmt->execute()) {
       trigger_error($stmt->error, E_USER_ERROR);
     }
     $stmt->close();
     return true;
   }
   return false;
 }

 function AcceptRequest($eigenaarId, $vezoekId) {
   $query = "UPDATE leen_verzoek l
             SET l.status_ = 'gereserveerd'
             WHERE l.id = ? AND l.status_ = 'in_afwachting' AND ? IN (
               SELECT f.gebruiker_id
               FROM fietsen f
               WHERE l.id = ? AND l.fiets_id = f.id AND f.gebruiker_id = ?
             )";

   $stmt = $GLOBALS['mysqli']->prepare($query);
   if (!$stmt) {
     trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
   }
   else {
     $stmt->bind_param('iiii', $vezoekId, $eigenaarId, $vezoekId, $eigenaarId);
     if (!$stmt->execute()) {
       trigger_error($stmt->error, E_USER_ERROR);
     }
     $stmt->close();
     return true;
   }
   return false;
 }

 function DenyRequest($gebruikerId, $vezoekId) {
   $query = "UPDATE leen_verzoek l
             SET l.status_ = 'geannuleerd'
             WHERE l.id = ? AND (l.status_ = 'in_afwachting' OR l.status_ = 'gereserveerd') AND (? IN (
               SELECT f.gebruiker_id
               FROM fietsen f
               WHERE l.id = ? AND l.fiets_id = f.id AND f.gebruiker_id = ?
             ) OR l.lener_id = ?)";

   $stmt = $GLOBALS['mysqli']->prepare($query);
   if (!$stmt) {
     trigger_error($GLOBALS['mysqli']->error, E_USER_ERROR);
   }
   else {
     $stmt->bind_param('iiiii', $vezoekId, $gebruikerId, $vezoekId, $gebruikerId, $gebruikerId);
     if (!$stmt->execute()) {
       trigger_error($stmt->error, E_USER_ERROR);
     }
     $stmt->close();
     return true;
   }
   return false;
 }

  // Check of er op geaccepteerd is geklikt
  if(isset($_POST['geaccepteerd'])) {
   // Start de sessie mocht dat nog niet gedaan zijn
   if (isset($_SESSION) === false) {
       session_start();
   }
   // Kijk of de gebruiker is ingelogt anders ga terug naar de inlog pagina
   if (isset($_SESSION['id']) === false) {
     RedirectToPage("inloggen.php");
   }
   AcceptRequest($_SESSION['id'], $_POST['id']);
   header("Location: ../leen_verzoeken.php");
  }

  // Check of er op geannuleerd is geklikt
  if(isset($_POST['geannuleerd'])) {
   // Start de sessie mocht dat nog niet gedaan zijn
   if (isset($_SESSION) === false) {
       session_start();
   }
   // Kijk of de gebruiker is ingelogt anders ga terug naar de inlog pagina
   if (isset($_SESSION['id']) === false) {
     RedirectToPage("inloggen.php");
   }
   DenyRequest($_SESSION['id'], $_POST['id']);
   header("Location: ../leen_verzoeken.php");
  }

?>
