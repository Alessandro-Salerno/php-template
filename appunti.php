<?php

function generaGend() {
    foreach ($GLOBALS["generi"] as $k => $v) {
        echo "<input type=\"radio\" name=\"gend\" value=\"{$k}\">";
        echo "<label>{$v}</label>";
    }
}

function generaHobby() {
    foreach ($GLOBALS["hobby"] as $k => $v) {
        echo "<input type=\"checkbox\" name=\"hob[]\" value=\"{$k}\">";
        echo "<label>{$v}</label>";
    }
}

function generaRegioni() {
    foreach ($GLOBALS["regioni"] as $k => $v) {
        echo "<option value=\"{$k}\">{$v}</option>";
    }
}
?>