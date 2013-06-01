IonPHP
======
IonPHP is a Lightweight PHP API Framework which is currently being developed to make our new website, Volt Gaming.

Developers
======
- Graham Newton
- Lewis Scally

Functions
======

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

Configuration Functions
```php
//Store data
Config::rw("test", "Hello World");

//Reading out the stored information
echo Config::rw("test");
```