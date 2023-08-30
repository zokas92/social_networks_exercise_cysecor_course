<?php
// Glavne funckionalnosti sajta - ostvarivanje konekcije sa bazom i provera 

// konekcija sa bazom
$con = mysqli_connect('localhost', 'root', '', 'social_network');

//funkcionalnosti koje ce nam pomoci kasnije kada budemo izvrsavali upite u bazi podataka
function escape($string) {
    global $con;
    return mysqli_real_escape_string($con, $string);
}

function query($query) {
    global $con;
    return mysqli_query($con, $query);
}

function confim($result) {
    global $con;
    if(!$result) {
        die("QUERY FAILED" . mysqli_error($con));
    }
}