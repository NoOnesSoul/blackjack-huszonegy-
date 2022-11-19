<?php
require_once "objects.php";

if(session_id() == '') { //csak az jöjjön, ami még nem volt(akkor kezdődjön, ha még nem kezdődött el)
    session_start();
}

function genCard() { //új kártya legenerálása
    $colors = array( //lehetséges színek
        1 => "spades",
        2 => "hearts",
        3 => "clubs",
        4 => "diamonds"
    );
    $values = array( //lehetséges értékek
        1 => "A",
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => "J",
        12 => "Q",
        13 => "K"
    );
    $newCard = new Cardd();
    $color = random_int(1,4);
    $value = random_int(1,13);
    $newCard->set_card($colors[$color], $values[$value]);
    return $newCard;
}

function action($state) { //különféle helyzetek kezelése
    switch ($state) {
        case "start": //játék elkezdése, kezdő lapok kiosztása
            $_SESSION['comCards'][0] = genCard();
            $_SESSION['comCards'][1] = genCard();
            $_SESSION['comCards'][0]->shown = "hiddencard";
            $_SESSION['usrCards'][0] = genCard();
            $_SESSION['usrCards'][1] = genCard();
            $_SESSION['state'] = "started";
            break;
        case "hit": //kártyát kért, kapjon kártyát(kivéve, ha elérte a 21-et, akkor leállhat)
            if(getPoint('usr') < 21) {
                $_SESSION['usrCards'][] = genCard();
            } else {
                $_SESSION['state'] = 'stand';
                action($_SESSION['state']);
            }
            if(getPoint('com') <= 16) {
                $_SESSION['comCards'][] = genCard();
            }
            header("Refresh:0"); //szuperglobális POST változó kiürítése
            break;
        case 'stand': //nem kér több lapot, az osztó kapjon, ha kell neki
            while (getPoint('com') <= 16) {
                $_SESSION['comCards'][] = genCard();
            }
            $_SESSION['comCards'][0]->shown = 'card';
            $_SESSION['state'] = 'end'; //játék végének kihírdetése
            action($_SESSION['state']);
            break;
        case 'end': //vége, kérjük a végeredményt
            return "<div class='tab message'><h1>".getWinner(getPoint('com'), getPoint('usr'))."</h1></div><br />";
            break;
        case 'new': //új játékot
            session_unset(); //szuperglobális SESSION teljes kiürítése
            header('Refresh:0'); //ezt azért, hogy ne dobjon hibát
            break;
    }
}

function getPoint($player) { //kártyák értékeinek összeadása
    $points = 0;
    switch ($player) {
        case 'com': //ha az osztóét keressük
            for($i=0; $i <= sizeof($_SESSION['comCards'])-1; $i++) {
                    if($_SESSION['comCards'][$i]->value == "A") { //az ász értékének meghatározása
                        if($points <= 10) {
                            $points += $_SESSION['comCards'][$i]->realvalue['much'];
                        } else {
                            $points += $_SESSION['comCards'][$i]->realvalue['few'];
                        }
                    } else {
                        $points += $_SESSION['comCards'][$i]->realvalue; //ha nem ász, akkor egyértelmű a valós érték
                    }
            }
            break;
        case 'usr': //ha a játékosét keressük
            for($i=0;$i <= sizeof($_SESSION['usrCards'])-1; $i++) {
                if($_SESSION['usrCards'][$i]->value == "A") { //ász értékének meghatározása
                    if($points <= 10) {
                        $points += $_SESSION['usrCards'][$i]->realvalue['much'];
                    } else {
                        $points += $_SESSION['usrCards'][$i]->realvalue['few'];
                    }
                } else {
                    $points += $_SESSION['usrCards'][$i]->realvalue; //nem ász, valós érték
                }
            }
            break;
    }
    return $points;
}

function getWinner($com, $usr) { //győztes meghatározása
    $message = ""; //üzenet, amit majd játék végén kiír
    switch ($usr) {
        case 21: //ha 21-ünk van
            if(sizeof($_SESSION['usrCards']) == 2) { //ha két lapunk van és az összeg 21
                if($usr == $com) { //ha az osztónak is 21-e van
                    if(sizeof($_SESSION['comCards']) == 2) { //és az osztónak is két lapból van meg
                        $message = "Draw"; //akkor döntetlen
                    } else {
                        $message = "You win"; //ha csak a játékosnak, akkor nyert
                    }
                } else {
                    $message = "You win"; //ha csak a játékosnak van meg a 21, akkor nyert
                }
            } else {
                if($com == $usr) { //ha nem két lapból van meg a 21, de az osztónak is megvan
                    if(sizeof($_SESSION['comCards']) == 2) { //ha az osztónak 2 lapból van meg
                        $message = "You lose"; //akkor az osztó nyer
                    } else {
                        $message = "Draw"; //de ha az osztónak sem két lapból, akkor döntetlen
                    }
                } else {
                    $message = "You win"; //ha csak a játékosnak van meg a 21, akkor nyer
                }
            }
            break;
        default:
            if($usr > 21) { //ha a játékos túlmegy a 21-en, akkor veszít, független az osztótól
                $message = "You lose";
            } else {
                if($usr < 22 && sizeof($_SESSION['usrCards']) == 5) { //ha 5 lapból nem megy túl 21-en, akkor nyer
                    $message = 'You win';
                } else {
                if($com > 21) { //ha az osztó túlmegy 21-en, akkor a játékos nyer
                    $message = "You win";
                } else {
                    if($com == $usr) { //ha az osztó és a játékos lapjainak összege megegyezik, akkor döntetlne
                        $message = "Draw";
                    } else {
                            if($com > $usr) { //ha senki nem megy túl 21-en és az osztó közelebb van hozzá, akkor az osztó nyer
                                $message = "You lose";
                            } else {
                                $message = "You win"; //ha a játékos van közelebb 21-hez, akkor ő nyer
                            }
                        }
                    }
                }
            }
    }
    $_SESSION['winner'] = 1; //megvan a győztes
    return $message; //visszatér az üzenettel
}
?>
