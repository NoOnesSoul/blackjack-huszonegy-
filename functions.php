<?php
require_once "objects.php";
if(session_id() == '') {
    session_start();
}

function genCard() {
    $colors = array(
        1 => "spades",
        2 => "hearts",
        3 => "clubs",
        4 => "diamonds"
    );
    $values = array(
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

function action($state) {
    switch ($state) {
        case "start":
            $_SESSION['comCards'][0] = genCard();
            $_SESSION['comCards'][1] = genCard();
            $_SESSION['comCards'][0]->shown = "hiddencard";
            $_SESSION['usrCards'][0] = genCard();
            $_SESSION['usrCards'][1] = genCard();
            $_SESSION['state'] = "started";
            break;
        case "hit":
            if(getPoint('usr') < 21) {
                $_SESSION['usrCards'][] = genCard();
            } else {
                $_SESSION['state'] = 'stand';
                action($_SESSION['state']);
            }
            if(getPoint('com') <= 16) {
                $_SESSION['comCards'][] = genCard();
            }
            header("Refresh:0");
            break;
        case 'stand':
            while (getPoint('com') <= 16) {
                $_SESSION['comCards'][] = genCard();
            }
            $_SESSION['comCards'][0]->shown = 'card';
            $_SESSION['state'] = 'end';
            action($_SESSION['state']);
            break;
        case 'end':
            return "<div class='tab message'><h1>".getWinner(getPoint('com'), getPoint('usr'))."</h1></div><br />";
            break;
        case 'new':
            session_unset();
            header('Refresh:0');
            break;
    }
}

function getPoint($player) {
    $points = 0;
    switch ($player) {
        case 'com':
            for($i=0; $i <= sizeof($_SESSION['comCards'])-1; $i++) {
                    if($_SESSION['comCards'][$i]->value == "A") {
                        if($points <= 10) {
                            $points += $_SESSION['comCards'][$i]->realvalue['much'];
                        } else {
                            $points += $_SESSION['comCards'][$i]->realvalue['few'];
                        }
                    } else {
                        $points += $_SESSION['comCards'][$i]->realvalue;
                    }
            }
            break;
        case 'usr':
            for($i=0;$i <= sizeof($_SESSION['usrCards'])-1; $i++) {
                if($_SESSION['usrCards'][$i]->value == "A") {
                    if($points <= 10) {
                        $points += $_SESSION['usrCards'][$i]->realvalue['much'];
                    } else {
                        $points += $_SESSION['usrCards'][$i]->realvalue['few'];
                    }
                } else {
                    $points += $_SESSION['usrCards'][$i]->realvalue;
                }
            }
            break;
    }
    return $points;
}

function getWinner($com, $usr) {
    $message = "";
    switch ($usr) {
        case 21:
            if(sizeof($_SESSION['usrCards']) == 2) {
                if($usr == $com) {
                    if(sizeof($_SESSION['comCards']) == 2) {
                        $message = "Draw";
                    } else {
                        $message = "You win";
                    }
                } else {
                    $message = "You win";
                }
            } else {
                if($com == $usr) {
                    if(sizeof($_SESSION['comCards']) == 2) {
                        $message = "You lose";
                    } else {
                        $message = "Draw";
                    }
                } else {
                    $message = "You win";
                }
            }
            break;
        default:
            if($usr > 21) {
                $message = "You lose";
            } else {
                if($usr < 22 && sizeof($_SESSION['usrCards']) == 5) {
                    $message = 'You win';
                } else {
                if($com > 21) {
                    $message = "You win";
                } else {
                    if($com == $usr) {
                        $message = "Draw";
                    } else {
                            if($com > $usr) {
                                $message = "You lose";
                            } else {
                                $message = "You win";
                            }
                        }
                    }
                }
            }
    }
    $_SESSION['winner'] = 1;
    return $message;
}
?>
