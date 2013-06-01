IonPHP
======

Lightweight PHP API Framework

Database Functions
```php
Database::load("Database", "Classes");

//Connect to your Database
$db = array(
    "user" => "user",
    "host" => "127.0.0.1",
    "pass" => "",
    "table" => "test"
);
Database::connect($db);

//Querying
Database::query("SELECT * FROM users WHERE access_level = 1");
$result = Database::get();
print_r($result);

//Changing Fetch type
Database::setFetchType(1);
```