<?php
define('DEV', 'dev');

if(isset($argv[1]) && $argv[1] === 'dev') {
    define("ENV", DEV);
    define("ENV_FILE", '.env.local');
}
else {
    define("ENV", 'prod');
    define("ENV_FILE", '.env');
}

/********************************/
/*     Checking requirements    */
/********************************/
// Check if the directory is writeable.
if(!is_writable(__DIR__)) {
    echo "\e[1;31m -> The current location on your server is not writable \e[0m" . PHP_EOL;
    die();
}
echo "\e[0;32m -> The location is writable \e[0m" . PHP_EOL;

// Check php version >= 7.4
if(version_compare(phpversion(), '7.4', '<')) {
    echo "\e[1;31m -> The current PHP version must be equal or greater then 7.4 \e[0m" . PHP_EOL;
    die();
}
echo "\e[0;32m -> The PHP version is >= 7.4 \e[0m" . PHP_EOL;


/****************************************************/
/*     Getting composer.phar and php dependencies   */
/****************************************************/
if(!file_exists("composer.phar")) {
    echo "...Composer not found, getting composer.phar" . PHP_EOL;
    $hash = file_get_contents("https://composer.github.io/installer.sig");

    copy('https://getcomposer.org/installer', 'composer-setup.php');
    if (hash_file('sha384', 'composer-setup.php') === $hash) {
        echo "\e[0;32m -> Installer verified, starting composer installation \e[0m" . PHP_EOL;
        exec("php composer-setup.php");
    } 
    else {
        echo "\e[1;31m -> Installer corrupt \e[0m" . PHP_EOL;
        die();
    }

    unlink('composer-setup.php');    
}
else {
    echo "\e[0;32m -> Composer was found \e[0m" . PHP_EOL;
}

echo "...Installing PHP dependencies" . PHP_EOL;
exec(__DIR__ . "/composer.phar install");

if(ENV !== DEV)
    unlink('composer.phar');


/****************************************************/
/*     Getting nodejs binaries and js dependencies  */
/****************************************************/
// Writing initial .env file with only 'prod' env var used to compile assets without watcher.
file_put_contents(ENV_FILE, "APP_ENV=prod" . PHP_EOL);

// Installing nodejs only for windows users, do not force node installation on linux to d not break devs environnement.
$npm = 'npm';
if(strpos(strtolower(PHP_OS), 'win' ) !== false) {
    $npm = __DIR__ . "/node/npm";
    echo "...Getting NodeJs Windows binaries" . PHP_EOL;
    copy('https://nodejs.org/dist/v12.18.3/node-v12.18.3-win-x86.zip', 'node.zip');
    // Extract zip archive contents.
    $zip = new ZipArchive;
    $res = $zip->open('node.zip');
    if ($res === true) {
        $zip->extractTo('./');
        $node = trim($zip->getNameIndex(0), '/');
        rename($node, 'node');
        $zip->close();
        echo "\e[0;32m -> Node was downloaded ans extracted \e[0m" . PHP_EOL;
    }
    else {
        echo "\e[1;31m -> Error occured while downloading and unzipping NodeJs \e[0m" . PHP_EOL;
        unlink('node.zip');
    }

    unlink('node.zip');
}

echo "...Getting NodeJs dependencies" . PHP_EOL;
echo PHP_EOL . $npm . PHP_EOL;
if(! $result = exec($npm . ' install', $result) ) {
    echo "\e[1;31m -> Error ocured while installing node dependencies \e[0m" . PHP_EOL;
    if(ENV !== DEV && is_dir('node'))
        rrmdir('node');
    die();
}

echo "\e[0;32m -> Node dependencies installed \e[0m" . PHP_EOL;
echo "...Building JS, CSS and moving images" . PHP_EOL;
if( ! $result = exec($npm . ' run build', $result) ) {
    echo "\e[1;31m -> Error occurred while building JS, CSS and moving files \e[0m" . PHP_EOL;
    if(ENV !== DEV && is_dir('node'))
        rrmdir('node');
    die();
}
if(ENV !== DEV && is_dir('node'))
    rrmdir('node');


/***************************************************/
/*** Getting database information by user inputs ***/
/***************************************************/

// Getting host.
$handle = fopen ("php://stdin","r");
recurseFgets($handle, "-> Your database hostname. Type 'localhost' for a local or dedicated/vps server: ", $dbHostname);

// Getting port.
echo "-> What is your database port ? (3306): ";
$dbPort = intval(trim(fgets($handle)));
if($dbPort === 0) {
    $dbPort = 3306;
}

// Getting username.
recurseFgets($handle, "-> Your database user: ", $dbUser);

// Getting password.
echo "-> Your database user password: ";
if(strpos(strtolower(PHP_OS), 'win' ) !== false) {
    // // TODO target 0.0.2 => find a way to obfuscate password in Windows cli...
    $dbPassword = trim(fgets($handle));
}
else {
    system('stty -echo');
    $dbPassword = trim(fgets($handle));
    system('stty echo');
}

// Getting database name.
recurseFgets($handle, "-> Your database name ( eg: evalbook ) ", $dbName);

// Database information resume.
echo "\e[0;32m -> Review below information and press enter. \e[0m" . PHP_EOL;
$review = "\e[1m Hostname \e[0m: %s\n\r" .
          "\e[1m Port \e[0m: %d\n\r".
          "\e[1m Database user \e[0m: %s\n\r" .
          "\e[1m Database password \e[0m: %s\n\r" .
          "\e[1m Database name \e[0m: %s\n\r";

echo sprintf($review, $dbHostname, $dbPort, $dbUser, str_pad('', strlen($dbPassword), '*'), $dbName);
echo "Press enter to continue...  If the database does not exists, an attempt to create it will be made...";
fgets($handle);

// Testing database connection.
list($test, $databaseExists) = testDbConnection($dbHostname, $dbPort, $dbUser, $dbPassword, $dbName, true);

if(!$test) {
    echo "\e[1;31m -> It sounds like the database information you gave are incorrect, please, review them and try again. Also make sure the SQL server is running \e[0m" . PHP_EOL;
    die();
}


/*************************************************************/
/*     Getting mailer DSN for mails sent by the application  */
/*************************************************************/
echo "-> What is your smtp mailer hostname ? ( Leave empty to use sendmail ): ";
$smtpHost = trim(fgets($handle));

echo "-> What is your smtp mailer port ? (465): ";
$smtpPort = intval(trim(fgets($handle)));
if(empty($smtpPort)) {
    $smtpPort = 465;
}

echo "-> What is your smtp username ? ( Leave empty to use sendmail ): ";
$smtpUsername = trim(fgets($handle));

echo "-> What is your smtp password ? ( Leave empty to use sendmail ): ";
$smtpPassword = trim(fgets($handle));

// SMTP mailer information resume.
echo "\e[0;32m -> Review below information and press enter. \e[0m" . PHP_EOL;
$review = "\e[1m SMTP hostname \e[0m: %s\n\r" .
          "\e[1m SMTP port \e[0m: %d\n\r".
          "\e[1m SMTP user \e[0m: %s\n\r" .
          "\e[1m SMTP password \e[0m: %s\n";

echo sprintf($review, $smtpHost, $smtpPort, $smtpUsername, str_pad('', strlen($smtpPassword), '*'));
echo "Press enter to continue...  If the database does not exists, an attempt to create it will be made...";
fgets($handle);


/***************************************/
/*   Writing .env configuration file   */
/***************************************/
// Writing the right env.
file_put_contents(ENV_FILE, "APP_ENV=" . ENV . PHP_EOL);
ob_start();
$sep = (strlen($dbPassword) > 0) ? ":" : "";
echo "DATABASE_URL=mysql://$dbUser$sep$dbPassword@$dbHostname:$dbPort/$dbName" . PHP_EOL;
if(strlen($smtpHost) === 0)
    echo "MAILER_DSN=smtp://localhost" . PHP_EOL;
else
    echo "MAILER_DSN=smtp://$smtpUsername:$smtpPassword@$smtpHost:$smtpPort" . PHP_EOL;
try {
    echo "APP_SECRET=" . bin2hex(random_bytes(16)) . PHP_EOL;
}
catch (Exception $e) {
    echo "APP_SECRET=" . bin2hex(substr("appeb_" . uniqid(), 0, 16)) . PHP_EOL;
}

$env = ob_get_contents();
ob_end_clean();
file_put_contents(ENV_FILE, $env, FILE_APPEND);


/*********************************/
/*   Database and data migration */
/*********************************/
migrateDatabase(!$databaseExists);
list($conn, $db) = testDbConnection($dbHostname, $dbPort, $dbUser, $dbPassword, $dbName, true);
if(!$conn || !$db) {
    echo "\e[1;31m -> Something went wrong with your database, please try again. \e[0m" . PHP_EOL;
    die();
}

echo "\e[0;32m -> Database ok, tables were created... \e[0m" . PHP_EOL;

// Import default data.



echo "\e[0;32m -> Installation complete, please, delete the install.php file from your server \e[0m" . PHP_EOL;


/**
 * Test the database connection.
 * @param $host
 * @param $port
 * @param $user
 * @param $passwd
 * @param $db
 * @param bool $testdb
 * @return bool|mixed
 */
function testDbConnection($host, $port, $user, $passwd, $db, $testdb = false) {
    // Testing database connection.
    try {
        $dbh = new PDO("mysql:host=$host;port=$port", $user, $passwd);
        // Trying to make use of the provided database.
        if($testdb) {
            if (false !== $dbh->exec("use $db")) {
                return [true, true];
            }
            return [true, false];
        }
        return true;
    }
    catch (PDOException $e) {}
    return false;
}


/**
 * Create the database and migrate tables.
 * @param $createDatabase
 */
function migrateDatabase($createDatabase) {
    $commands = [];
    if($createDatabase) {
        $commands[] = "php bin/console doctrine:database:create";
    }

    $commands = array_merge($commands, [
        "php bin/console doctrine:migrations:migrate",
        "php bin/console cache:clear",
    ]);

    array_map(function($cmd){ exec($cmd . " --no-interaction"); }, $commands);
}


/**
 * Fetch stdin for user input, while no input detected.
 * @param $handle
 * @param $message
 * @param $result
 */
function recurseFgets($handle, $message, &$result) {
    while(empty($result)) {
        echo $message;
        $result = trim(fgets($handle));
    }
}


/**
 * Recursive deletion of directpry.
 * @param $dir
 */
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                    rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                else
                    unlink($dir. DIRECTORY_SEPARATOR .$object);
            }
        }
        rmdir($dir);
    }
}
