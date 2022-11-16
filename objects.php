<?php
class Cardd {
    public $color;
    public $value;
    public $realvalue;
    public $shown;

    function set_card($col, $val) {
        $this->color = $col;
        $this->value = $val;
        $this->shown = "card";
        switch ($this->value) {
            case "A":
                $this->realvalue = array(
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
                $this->realvalue = $this->value;
        }
    }
}
?>