<div class="<?php echo $_SESSION['comCards'][$i]->shown; ?>" style="color: <?php
switch ($_SESSION['comCards'][$i]->color) {
    case "diamonds":
    case "hearts":
        echo "red;";
        break;
    default:
        echo "black;";
}
?>">
    <?php
        if($_SESSION['comCards'][$i]->shown == "card") {
            echo "<b> ".$_SESSION['comCards'][$i]->value."</b>";
            echo "<br /><center><img src='assets/".$_SESSION['comCards'][$i]->color.".png' class='cardColor'></center>";
        } else {
            echo "​";
            echo "<br />​<br />​";
        } ?>
</div>