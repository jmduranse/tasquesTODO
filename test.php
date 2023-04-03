<?php
//Obrim la base de dades passada per paràmetre. Si no existeix es crearà.
//Per paràmetre passem l'adreça on es troba guardat el fitxer de la base de dades

if (class_exists('SQLite3')) {
    echo 'SQLite3 extension loaded';
}


$baseDades = new SQLite3("RRHH.db");

if (!$baseDades) { //No s'ha pogut obrir la base de dades

    echo "La base de dades no s'ha pogut obrir.";
} else { //S'ha pogut obrir la base de dades

    echo "La base de dades s'ha obert correctament.";
}

$baseDades->close(); //Tanquem la base de dades*/
