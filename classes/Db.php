<?php
class Db
{
    protected $dbh;


    public function __construct()
    {
        $config = include __DIR__ . '/../db/config.php';
        $dsn = 'mysql:dbname=' . $config['dbname'] . ';host=' . $config['host'];
        $this->dbh = new PDO($dsn, $config['user'], $config['password']);
    }


    function dbSelect($sql, $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);
        $res = ($sth->fetchAll(PDO::FETCH_ASSOC));
        return $res;
    }


    function dbGetId()
    {
        return $this->dbh->lastInsertId();
    }


    function dbExecute($sql, $params = [])
    {
        $sth = $this->dbh->prepare($sql);
        return $sth->execute($params);
    }



}
?>