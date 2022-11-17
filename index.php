<?php
require_once "objects.php";
require_once "functions.php";

if(isset($_POST['hit'])) {
    $_SESSION['state'] = 'hit';
    action($_SESSION['state']);
} elseif (isset($_POST['stand'])) {
    $_SESSION['state'] = 'stand';
    action($_SESSION['state']);
} elseif (isset($_POST['new'])) {
    action('new');
}

if(!isset($_SESSION['state'])) {
    $_SESSION['state'] = 'start';
    action($_SESSION['state']);
}

if(getPoint('usr') >= 21) {
    $_SESSION['state'] = "stand";
    action($_SESSION['state']);
}


//print_r($_SESSION);
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
            <div class="tab">
                <div class="tab">
                <h2>Dealer(com): <?php if ($_SESSION['state'] == "end") {echo getPoint('com');} else {echo "?";} ?></h2>
                <?php for($i = 0; $i <= sizeof($_SESSION['comCards'])-1; $i++) {
                    include "comCard.php";
                } ?>
            </div>
            <br />
            <div class="tab">
                <h2>You: <?php echo getPoint('usr'); ?></h2>
                <?php for($i = 0; $i <= sizeof($_SESSION['usrCards'])-1; $i++) {
                    include "usrCard.php";
                } ?>
            </div>
            <br />
            <form method="POST">
                <?php if(getPoint('usr') < 21 && !isset($_SESSION['winner'])) { ?>
                    <input type="submit" name="hit" value="Hit">
                    <input type="submit" name="stand" value="Stand">
                <?php } ?>
                    <input type="submit" name="new" value="Start new game">
                </form>
            </div>
        </center>
    </body>
</html>
