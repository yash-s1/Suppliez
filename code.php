<?php

include('config.php'); 

if(isset($_GET["name"]))
$n = $_GET['name'];
else
echo "Field not available.";

if(isset($_GET["username"]))
$n1 = $_GET['username'];
else 
echo "Field not available.";

if(isset($_GET["password"]))
$n2 = $_GET['password'];
else 
echo "Field not available.";

if(isset($_GET["email"]))
$n4 = $_GET['email'];
else 
echo "Field not available.";

if(isset($_GET["mobile"]))
$n5 = $_GET['mobile'];
else 
echo "Field not available.";

if(isset($_GET["address"]))
$n6 = $_GET['address'];
else 
echo "Field not available.";

$hash = hash('sha256', '$n4, $n2');
$hash1 = hash('sha256', '$n5, $n2');
$hash2 = hash('sha256', '$n6, $n2');
$hash3 = hash('sha256', '$n2, $n1');
$hash4 = hash('sha256', '$n1, $n2');

include('Crypt/RSA.php');
 
class myRSA
{
    public static $privateKey = '';
    public static $publicKey = '';
    public static $keyPhrase = '';
     
    public static function createKeyPair()
    {
        $rsa = new Crypt_RSA();
        $password = base64_encode(sha1(time().rand(100000,999999)));
        $rsa->setPassword($password );
        $keys=$rsa->createKey(2048);     
        myRSA::$privateKey=$keys['privatekey'];
        myRSA::$publicKey=$keys['publickey'];
        myRSA::$keyPhrase=$password;
    }
 
    public static function encryptText($text)
    {
        $rsa = new Crypt_RSA();
        $rsa->loadKey(myRSA::$publicKey);
        $encryptedText = $rsa->encrypt($text);
        return $encryptedText;
    }
 
    public static function decryptText($encryText)
    {
        $rsa = new Crypt_RSA();
        $rsa->setPassword(myRSA::$keyPhrase);
        $rsa->loadKey(myRSA::$privateKey);
        $plaintext = $rsa->decrypt($encryText);
        return $plaintext;
    }
}
 
//create keys
myRSA::createKeyPair(1024);

//Text to encrypt

$text =  "$n";
$text1 = "$n1";
$text2 = "$n2";
 
$secureText = myRSA::encryptText($text);


$secureText1 = myRSA::encryptText($text1);


 
$secureText2 = myRSA::encryptText($text2);


$decrypted_text1 =  myRSA::decryptText($secureText1);

$decrypted_text2 =  myRSA::decryptText($secureText2);

$query = "INSERT INTO dd VALUE('".$secureText."','".$secureText1."','".$secureText2."')";
$res = mysqli_query($con,$query) or die("error");
$query1 = "INSERT INTO dd5 VALUE(AES_ENCRYPT('$hash','$hash3'),AES_ENCRYPT('$hash1','$hash3'),AES_ENCRYPT('$hash2','$hash3'),AES_ENCRYPT('$n1','$n2'),AES_ENCRYPT('$n2','$n1'))";
$res1 = mysqli_query($con,$query1) or die("error");
header("location:login.html");
?>

