<?php

function encryptFile($data)
{
    //Ključ za enkripciju
    $encryption_key = md5('jed4n j4k0 v3l1k1 kljuc');

    //Podaci za enkripciju
    $data = 'Ovo su podaci koje želimo kriptirati i korisnik je dodao...';

    //Odaber cipher metodu AES
    $cipher = "AES-128-CTR";

    //Stvori IV sa ispravnom dužinom
    $iv_length = openssl_cipher_iv_length($cipher);
    $options = 0;

    // Non-NULL inicijalizacijski vektor za enkripciju 
    //Random dužine 16 byte
    $encryption_iv = random_bytes($iv_length);

    // Kriptiraj podatke sa openssl 
    $data = openssl_encrypt(
        $data,
        $cipher,
        $encryption_key,
        $options,
        $encryption_iv
    );

    return $data;
}












//server settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webLV2";

//konekcija na server
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//putanja do datoteke
$path = "files/cat.jpg";
$fileName = basename($path);
// tip datoteke
$fileType = pathinfo($path, PATHINFO_EXTENSION);

//kriptiraj podataka
$encryptedData = encryptFile($path);

echo $fileType . "<br>" . $encryptedData . "<br><br>";

//provjera tipa podatakay
$allowedFileTypes = array('jpg', 'png', 'jpeg', 'pdf');
if (in_array($fileType, $allowedFileTypes)) {
    //ubaci u tablicu
    $sql = "INSERT INTO `files` (`name`) VALUES ('$encryptedData')";
    if ($conn->query($sql) === true) {
        echo "Upload successful!";
    } else {
        echo "Upload failed!";
    };
} else {
    echo "Error, wrong file type";
}


//close connection
$conn->close();
