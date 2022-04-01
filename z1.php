<?php
/* Skripta prima podatke iz baze podataka i sprema ih u txt datoteku
koja se nakon toga sažima pomoću biblioteke zlib */


//Naziv baze podataka, korištena je baza iz prve vježbe
$db_name = 'radovi';

//string konstante za backup
$str_insert = "INSERT INTO $db_name (";
$str_values = ")\nVALUES (";


//Direktorij za backup
$dir = "backup/$db_name";

//Ako direktorij ne postoji stvori ga 
if (!is_dir($dir)) {
    if (!@mkdir($dir)) {
        die("<p>Ne možemo stvoriti direktorij $dir.</p></body></html>");
    }
}

//Trenutno vrijeme
$time = time();

//Spoj na bazu podataka
$dbc = @mysqli_connect('localhost', 'root', '', $db_name) or die("<p>Ne možemo se spojiti na bazu $db_name.</p></body></html>");

//Pokaži sve tablice iz baze podataka
$r = mysqli_query($dbc, 'SHOW TABLES');

//Radimo backup ako postoji barem jedna tablica
if (mysqli_num_rows($r) > 0) {

    //Poruka da se radi backup
    echo "<p>Backup za bazu podataka '$db_name'.</p>";

    //Dohvati ime svake tablice
    while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {

        //Dohvati podatke iz tablice
        $q = "SELECT * FROM $table";
        $r2 = mysqli_query($dbc, $q);
        $field_info = $r2->fetch_fields();
        //Ako postoje podaci
        if (mysqli_num_rows($r2) > 0) {

            //Otvori datoteku
            if ($fp = fopen("$dir/{$table}_{$time}.txt", 'w9')) {

                //Dohvat podataka iz tablice
                while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {

                    //INSERT INTO dio zapisa
                    fwrite($fp, $str_insert);
                    //zapisivanje naziva stupaca u tablici
                    foreach ($field_info as $attribute) {
                        fwrite($fp, "$attribute->name");
                        if ($attribute != end($field_info)) {
                            fwrite($fp, ", ");
                        }
                    }
                    //VALUES dio zapisa
                    fwrite($fp, $str_values);
                    //zapisivanje podataka iz reda
                    foreach ($row as $value) {
                        $value = addslashes($value);
                        fwrite($fp, "'$value'");
                        if ($value != end($row)) {
                            fwrite($fp, ", ");
                        } else {
                            fwrite($fp, ")\";");
                        }
                    }
                    fwrite($fp, "\n");
                } //Kraj while petlje

                //Zatvori datoteku
                fclose($fp);

                //Ispiši da je backup uspješno izvršen
                echo "<p>Tablica '$table' je pohranjena.</p>";
            } else { //Ne možemo stvoriti datoteku
                echo "<p>Datoteka $dir/{$table}_{$time}.txt se ne može otvoriti.</p>";
                break; //Prekini while petlju
            } // Kraj gzopen() 

        } //Kraj mysqli_num_rows() 

    } //Kraj while petlje

} else {
    echo "<p>Baza $db_name ne sadrži tablice.</p>";
}
?>