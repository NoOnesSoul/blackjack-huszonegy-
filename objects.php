<?php
class Cardd {
    public $color; //kártya színe (káró, kőr, pikk, treff)
    public $value;  //kártya megjelenített értéke(2-10, A, J, Q, K)
    public $realvalue;  //kártya valós, számbeli értéke(pl.: J = 10), betűkkel mégsem számolhatok
    public $shown;  //kártya helyzete(hátulja van fent, vagy sem)

    function set_card($col, $val) { //kártya tulajdonságainak megadása
        $this->color = $col;
        $this->value = $val;
        $this->shown = "card";
        switch ($this->value) { //valós érték megadása
            case "A":
                $this->realvalue = array( //az ásznak két értéke is lehet, úgyhogy...
                    "few" => 1,
                    "much" => 11
                );
                break;
            case "J":
            case "Q":
            case "K":
                $this->realvalue = 10;
                break;
            default:
                $this->realvalue = $this->value; //ha egyszerű szám, akkor egyszerű szám
        }
    }
}
?>
