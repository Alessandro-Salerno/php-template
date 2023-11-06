<?php 
function connectToDatabase($db): mysqli {
  $connection = new mysqli("localhost", "root", "", $db);

  if ($connection -> connect_error)
    die("Connessione fallita: " . $connection -> connect_error);
  
  return $connection;
}

function createTable($result, $fetch = null, $fetch_hdr = null) {
  if (!$result) {
    echo "Errore nella query.";
    return;
  }

  if (!$fetch) {
    $fetch = function ($result) {
      return $result->fetch_assoc();
    };
  }

  if (!$fetch_hdr) {
    $fetch_hdr = $fetch;
  }
  
  if (!($header = $fetch_hdr($result))) {
    echo "Nessun risultato trovato.";
    return;
  }

  echo "<table border='1'>";
  
  echo "<tr>";
  foreach ($header as $key => $value) 
    echo "<th>" . $key . "</th>";
  echo "</tr>";

  // Fix: stampa la prima riga che altrimenti sarebbe ignorata
  echo "<tr>";
  foreach ($header as $key => $value) 
    echo "<td>" . $value . "</td>";
  echo "</tr>";

  while ($row = $fetch($result)) {
    echo "<tr>";
    foreach ($row as $value) 
      echo "<td>" . $value . "</td>";
    echo "</tr>";
  }

  echo "</table>";
}

function quoteString($string): string {
  return "\"" . $string . "\"";
}

class StringEchoer {
  private $string;

  function __construct() {
    $this->string = "";
  }

  function append($part) {
    $this->string .= $part;
    return $this;
  }

  function space() {
    $this->string .= " ";
    return $this;
  }

  function line() {
    $this->string .= "<br>";
    return $this;
  }

  function echo() {
    echo $this->string;
  }

  function get() {
    return $this->string;
  }

  function set($newstr) {
    $this->string = $newstr;
    return $this;
  }
}

class QueryBuilder {
  private $string;

  function __construct() {
    $this->string = "";
  }

  function insert($tableName) {
    $this->string .= "INSERT INTO " . $tableName;
    return $this;
  }

  function into(... $columns) {
    $this->string .= " (";
    foreach ($columns as $column) {
      $this->string .= $column;
      if ($columns[count($columns) - 1] == $column) {
        $this->string .= ")";
        break;
      }
      $this->string .= ", ";
    }

    return $this;
  }

  function values($autoquote = true, ... $values) {
    $this->string .= " VALUES (";
    foreach ($values as $value) {
      if ($autoquote) {
        $this->string .= quoteString($value);
      } else {
        $this->string .= $value;
      }

      if ($values[count($values) - 1] == $value) {
        $this->string .= ")";
        break;
      }

      $this->string .= ", ";
    }

    return $this;
  }

  function select() {
    $this->string .= "SELECT";
    return $this;
  }

  function items(... $items) {
    if (count($items) === 0) {
      $this->string .= " *";
      return $this;
    }

    $this->string .= " ";
    foreach ($items as $item) {
      $this->string .= $item;
      if ($items[count($items) - 1] == $item) {
        break;
      }
      $this->string .= ", ";
    }

    return $this;
  }

  function from($tableName) {
    $this->string .= " FROM " . $tableName;
    return $this;
  }

  function where($column, $cmp, $benchmark) {
    $this->string .= " WHERE " . $column . " " . $cmp . " " . $benchmark;
    return $this;
  }

  function asc($column) {
    $this->string .= " ORDER BY " . $column . " ASC";
    return $this;
  }

  function desc($column) {
    $this->string .= " ORDER BY " . $column . " DESC";
    return $this;
  }

  function sum($column, $decorator = "ALL") {
    $this->string .= " SUM (" . $decorator . " " . $column;
    return $this;
  }

  function avg($column, $decorator = "ALL") {
    $this->string .= " AVG (" . $decorator . " " . $column;
    return $this;
  }

  function table($tableName) {
    $this->string .= " TABLE " . $tableName;
    return $this;
  }

  function drop() {
    $this->string .= "DROP";
    return $this;
  }

  function build() {
    return str()->set($this->string);
  }

  function limit($lmt) {
    $this->string .= " LIMIT " . $lmt;
    return $this;
  }

  function set($queryString) {
    $this->string = $queryString;
    return $this;
  }

  function issue($conn) {
    return $conn->query($this->string);
  }
}

class Database {
    private static $instances = [];
    private $connection;

    protected function __construct() { }

    protected function __clone() { }

    public function __wakeup() {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): Database {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static();
        }

        return self::$instances[$cls];
    }

    function set($connection) {
      $this->connection = $connection;
    }

    function get(): mysqli {
      return $this->connection;
    }
}

function str(): StringEchoer {
  return new StringEchoer();
}

function query(): QueryBuilder {
  return new QueryBuilder();
}

function db(): Database {
  return Database::getInstance();
}
?>
