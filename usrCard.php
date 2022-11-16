<div class="<?php echo $_SESSION['usrCards'][$i]->shown; ?>" style="color: <?php
switch ($_SESSION['usrCards'][$i]->color) {
    case "diamonds":
    case "hearts":
        echo "red;";
        break;
    default:
        echo "black;";
}
?>">
    <?php
        if($_SESSION['usrCards'][$i]->shown == "card") {
            echo "<b> ".$_SESSION['usrCards'][$i]->value."</b>";
            echo "<br /><center><img src='assets/".$_SESSION['usrCards'][$i]->color.".png' class='cardColor'></center>";
        } else {
            echo "​";
            echo "<br />​<br />​";
        } ?>
</div>