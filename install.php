<?php
echo "<!DOCTYPE html><HTML LANG='ES'><head><meta charset='utf-8' /><title>Formulari instal.lació DB</title></head><body>";

/*function generateRandomString($length = 3)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
} */


// Echo the random string.
// Optionally, you can give it a desired string length.
//echo generateRandomString();


if (!isset($_POST["action"])) {

    //venimos de nuevas. No hacer nada

} else {  //venimos de haber pulsado un boton

    if ($_POST["action"] == "create") {


        $userTable = 'users'; //herencia de cuando usaba prefijo

        $adminPassword = password_hash('admin', PASSWORD_DEFAULT);


        //Per paràmetre passem l'adreça on es troba guardat el fitxer de la base de dades
        $dataBase = new SQLite3("tasques_todo.db");

        if (!$dataBase) { //No s'ha pogut obrir la base de dades

            echo "La base de dades no s'ha pogut obrir.";
        } else { //S'ha pogut obrir la base de dades

            echo "La base de dades s'ha obert correctament.<BR>";

            // Creamos las tablas en la base de datos
            $dataBase->exec('BEGIN');
            $dataBase->exec("CREATE TABLE IF NOT EXISTS $userTable (nom TEXT PRIMARY KEY UNIQUE, password TEXT, admin BOOLEAN)");
            $dataBase->exec("CREATE TABLE IF NOT EXISTS TODO (id INTEGER PRIMARY KEY, creator TEXT, nom TEXT, descripcio TEXT, estat TEXT, comentaris TEXT, FOREIGN KEY(creator) REFERENCES $userTable(nom))");
            /*
            
            When you create a table that has an INTEGER PRIMARY KEY column, this column is the alias of the rowid column.

            rowid value or you use a NULL value when you insert a new row, SQLite automatically assigns the next sequential integer, 
            which is one larger than the largest rowid in the table. The rowid value starts at 1.*/
            $dataBase->exec("INSERT INTO $userTable (nom, password, admin) VALUES ('admin', '$adminPassword', '1')");
            $dataBase->exec('COMMIT');

            echo "Taules inserides correctament.<BR>";

            $dataBase->close(); //  Cerramos base de datos
        }
    } else if ($_POST["action"] == "delete") {

        $dataBase = new SQLite3("tasques_todo.db");

        /*
        $tablesquery = $dataBase->query("SELECT name FROM sqlite_master WHERE type='table';");

        while ($table = $tablesquery->fetchArray()) {
                if (str_ends_with($table['name'], '_user')) {
                $userTable = $table['name'];
                echo "tabla de usuarios era " . $userTable . "<BR>";
            }
        }
        */

        if (($dataBase->exec("DROP TABLE TODO")) & ($dataBase->exec("DROP TABLE $userTable"))) {
            echo "Les taules s'han eliminat.";
        } else {
            echo "Les taules no s'ha eliminat correctament";
        }
        $dataBase->close(); //Tanquem la base de dades*/
    }
}
?>

<FORM method="POST" action=install.php>
    <INPUT type="submit" value="Crear base de dades"> <!--boton para crear estructura de base de datos-->
    <INPUT type="hidden" name="action" value="create"> <!--formulario oculto que envia nombre de la acción-->
</FORM>
<BR>
<BR>
<BR>
<FORM method="POST" action=install.php>
    <INPUT type="submit" value="Esborrar base de dades"> <!--boton para borrar estructura de base de datos-->
    <INPUT type="hidden" name="action" value="delete"> <!--formulario oculto que envia nombre de la acción-->
</FORM>

<?php



echo "</body></html>";
?>