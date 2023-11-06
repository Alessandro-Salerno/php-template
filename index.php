<?php
require_once("libsalerno.php");

function formatTableHeader($queryResult) {
    $header = $queryResult->fetch_assoc();
    $rheader = [];

    foreach ($header as $key => $value) {
        $column_hdr = str_replace("_", " ", $key);
        $column_hdr = strtoupper($column_hdr);
        $rheader[$column_hdr] = $value;
    }

    return $rheader;
}

db()->set(connectToDatabase("utenti"));
createTable(query()->select()->items("nome", "cognome")->from("persone")->issue(db()->get()), null, "formatTableHeader");
db()->get()->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esercizio 10</title>
</head>
<body>
    <!-- <select name="cars" id="cars">
    <option value="volvo">Volvo</option>
    <option value="saab">Saab</option>
    <option value="mercedes">Mercedes</option>
    <option value="audi">Audi</option>
    </select> -->

    <!-- <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
    <label for="vehicle1"> I have a bike</label><br>
    <input type="checkbox" id="vehicle2" name="vehicle2" value="Car">
    <label for="vehicle2"> I have a car</label><br>
    <input type="checkbox" id="vehicle3" name="vehicle3" value="Boat">
    <label for="vehicle3"> I have a boat</label><br>  -->

    
</body>
</html>