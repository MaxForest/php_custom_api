<?php
    $autoload = [
        'ServeurController' => './src/Controllers/ServeurController.php',
        'AuthController' => './src/Controllers/AuthController.php',
        'Serveur' => './src/Models/Serveur.php',
        'MySqlConnect' => './src/DbContext/MySqlConnect.php',
        'API_extension' => 'API_extension.php',
        'App_Response' => 'App_Response.php'
     ];

    define("CONST_DB_HOST", "localhost");
    define("CONST_DB_USERNAME", "root");
    define("CONST_DB_PASSWORD", "");
    define("CONST_DB", "simple_db");

    define("CONST_SECRETKEY", "lemessagesecret");
    define("CONST_CIPHER", "AES-128-CBC");
    define("CONST_KEY", "hashkey");

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);

    //----------------------------------------------------------------------------------------------------------------------
    spl_autoload_register(function ($class) use ($autoload) {
        if (isset($autoload[$class])) {
            require_once $autoload[$class];
        }
    }, true);
?>
