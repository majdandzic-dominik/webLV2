<?php
/*Expat parser*/

//Funkcija koja upravlja oznakom za početak
function handle_open_element($p, $element, $attributes)
{

    //Ovisno o oznaci radi sljedeće
    switch ($element) {
            //Oznake su record: id, ime, prezime, email, spol, slika, zivotopis    
        case 'RECORD': //Za record stvori div
            echo '<div style="border: 1px solid #000">';
            break;
        case 'ID':
            echo $element . ": ";
            break;
        case 'IME':
            echo $element . ": ";
            break;
        case 'PREZIME':
            echo $element . ": ";
            break;
        case 'SPOL':
            echo $element . ": ";
            break;
        case 'EMAIL':
            echo $element . ": ";
            break;
        case 'SLIKA':
            echo "<img src=\"";
            break;
        case 'ZIVOTOPIS':
            echo $element . ": ";
            break;
    } //Kraj switch

}

//Funkcija za rukovanje oznakom za kraj
function handle_close_element($p, $element)
{

    //Ovisno o oznaci radi sljedeće
    switch ($element) {
            //Zatvori HTML oznake        
        case 'RECORD':
            echo '</div>';
            break;
        case 'ID':
        case 'IME':
        case 'PREZIME':
        case 'SPOL':
        case 'EMAIL':
        case 'SLIKA':
            echo "\"><br>";
            break;
        case 'ZIVOTOPIS':
            echo "<br>";
            break;
    } //Kraj switch

}

//Ispiši sadržaj
function handle_character_data($p, $cdata)
{
    echo $cdata;
}


//Stvori parser   korak 1.
$p = xml_parser_create();

//Postavi funkcije za rukovanje korak 2.
//Funkcije koje se pokreću na početak i kraj XML oznake
xml_set_element_handler($p, 'handle_open_element', 'handle_close_element');
xml_set_character_data_handler($p, 'handle_character_data');

//Pročitaj datoteku korak 3.
$file = 'LV2.xml';
$fp = @fopen($file, 'r') or die("<p>Ne možemo otvoriti datoteku '$file'.</p></body></html>");
while ($data = fread($fp, 4096)) {
    xml_parse($p, $data, feof($fp));
}

//Zatvori parser korak 4.
xml_parser_free($p);
?>