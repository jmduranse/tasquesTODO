<?php session_start();



if (isset($_SESSION['user']) & $_SESSION['rol'] == '1') {  //entramos estando logueados como admins



?>
    <HTML>

    <HEAD>
        <meta charset="UTF-8" />
        <TITLE>
            TODO LIST - llista de usuaris
        </TITLE>
        <style>
            table {
                border-width: 2px;
                border-style: solid;
            }

            td,
            th,
            tr {
                border-width: 2px;
                border-style: solid;
                padding: 2px;
            }
        </style>
    </HEAD>

    <BODY>

        <?php

        //generamos banner con nombre usario y rol
        if ($_SESSION['rol'] == '1') {
            $rol = 'admin';
        } else {
            $rol = 'user';
        }

        $user = $_SESSION['user'];

        echo "<h2>Usuari $user ($rol)</h2>"; ?>

        <table>
            <thead>
                <th>Nom Usuari</th>
                <th>Admin</th>
            </thead>

            <?php
            //obtenemos informacion de la lista de usuarios usando una query

            $user = $_SESSION["user"];
            $dataBase = new SQLite3("tasques_todo.db");
            $query = $dataBase->query("SELECT nom,admin FROM users;");


            while ($arrayquery = $query->fetchArray()) {  //generamos una tabla solo con nombre de usuarios y si son admin o no

            ?>
                <tr>
                    <td><?php echo $arrayquery["nom"]; ?></td>
                    <td><?php if ($arrayquery["admin"] == '1') {  //convertimos el valor boolean de admin a texto
                            echo "SI";
                        } else {
                            echo "NO";
                        }; ?></td>
                    <td> <!--en esta posición de la tabla ponemos un boton llama al formulario para modificar, pasando como post la id de la tasca-->
                        <FORM method="POST" action=modificaUsuari.php>
                            <INPUT type="submit" value="Modificar usuari"> <!--boton para enviar-->
                            <INPUT type="hidden" name="user" value="<?php echo $arrayquery["nom"]; ?>"> <!--formulario oculto que envia el nombre de usuari-->
                        </FORM>
                    </td>
                    <td> <!--en esta posición de la tabla ponemos un boton llama al formulario para borrar, pasando como post la id de la tasca-->
                        <FORM method="POST" action=esborraUsuari.php>
                            <INPUT type="submit" value="Esborrar usuari"> <!--boton para enviar-->
                            <INPUT type="hidden" name="user" value="<?php echo $arrayquery["nom"]; ?>"> <!--formulario oculto que envia el nombre de usuari-->
                        </FORM>
                    </td>
                </tr>



            <?php

            }
            $dataBase->close(); //  Cerramos base de datos
            ?>



        </table>
        <BR>
        <HR>
        <TABLE>
            <TR>
                <TD>
                    <!--formulario oculto para crear usuarios-->
                    <FORM method="POST" action=creaUsuari.php>
                        <INPUT type="submit" value="Creació de usuaris">
                    </FORM>
                </TD>

                <TD>
                    <!--formulario oculto para volver a llistat-->
                    <FORM method="POST" action=llistat.php>
                        <INPUT type="submit" value="Tornar a llistat de tasques">
                </TD>
            </TR>
        </TABLE>




    </BODY>

    </HTML>

<?php

} else { //no estamos logueados

    echo "<h3>ERROR: no hi ha usuari o no es admin</h3>";
    header('refresh:5;login.php'); //Tornem a login


}
