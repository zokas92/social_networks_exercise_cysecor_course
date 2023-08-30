<?php
// Ovaj fajl ce se pozivati u svakom fajlu - login, register, index, profile - kako bi svaki fajl imao mogucnost upravljanja sesijom i konekcijom nad bazom
session_start();

include "db.php";
include "functions.php";