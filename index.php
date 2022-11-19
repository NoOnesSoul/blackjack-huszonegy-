<?php
require_once "objects.php";
require_once "functions.php";

//gombok kezelése
if(isset($_POST['hit'])) {
    $_SESSION['state'] = 'hit';
    action($_SESSION['state']);
} elseif (isset($_POST['stand'])) {
    $_SESSION['state'] = 'stand';
    action($_SESSION['state']);
} elseif (isset($_POST['new'])) {
    action('new');
}

//ha már elindult a játék, ne indítsa újra gombnyomás után
if(!isset($_SESSION['state'])) {
    $_SESSION['state'] = 'start';
    action($_SESSION['state']);
}

//ha a jatékos eléri a 21-et, akkor már ne tudjon, csak új játékot kezdeni
if(getPoint('usr') >= 21) {
    $_SESSION['state'] = "stand";
    action($_SESSION['state']);
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Black Jack</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <center>
            <div class="table">
                <?php if($_SESSION['state'] == "end") {echo action('end');} //ha vége a játéknak, jelenítse meg a kapott eredményt ?>
                <div class="tab">
                <h2>Dealer(com): <?php if ($_SESSION['state'] == "end") {echo getPoint('com');} else {echo "?";} //ha vége a játéknak, jelenítse meg az osztó lapjainak értékét ?></h2>
                <?php for($i = 0; $i <= sizeof($_SESSION['comCards'])-1; $i++) {
                    include "comCard.php"; //minden kártya egy külön fájl
                } ?> 
            </div>
            <br />
            <div class="tab">
                <h2>You: <?php echo getPoint('usr'); //írja ki a játékos lapjainak értékét ?></h2>
                <?php for($i = 0; $i <= sizeof($_SESSION['usrCards'])-1; $i++) {
                    include "usrCard.php"; //minden kártya egy külön fájl
                } ?>
            </div>
            <br />
            <form method="POST">
                <?php if(getPoint('usr') < 21 && !isset($_SESSION['winner'])) { //ha nincs vége a játéknak, jelenítse meg a "játszó gombokat"?>
                    <input type="submit" name="hit" value="Hit"> <!-- új lap kérés -->
                    <input type="submit" name="stand" value="Stand"> <!-- nem kér több lapot -->
                <?php } ?>
                    <input type="submit" name="new" value="Start new game"> <!-- Összes kártya eldobása és játék újrakezdése -->
                </form>
                <br />
                <?php include "rules.html" ?> <!-- A blackjack játékról információk(egyenlőre hiányos, csak erre a verzióra érvényes infók vannak benne) -->
            </div>
        </center>
    </body>
</html>
