<?php

/**
 * Sealed class Item, do some bussiness logic
 * 
 * @property-read int $id
 * @property string $name
 * @property int $status
 * @property bool $changed
 * @property-read bool $isInited
 */
final class Item 
{
    /** 
     * @var int $id 
     */
    private $id;

    /** 
     * @var string $name 
     */
    private $name;

    /** 
     * @var int status 
     */
    private $status;

    /** 
     * @var bool $changed 
     */
    private $changed;

    /** 
     * @var bool $isInited 
     */
    private $isInited;
    
    public function __construct($id)
    {  
        $this->id = (int) $id;
        $this->changed = false;
        $this->isInited = false;

        $this->init();
    }

    /**
     * Init object. Fetch data from DB to properties.
     * 
     * @return void
     */
    private function init()
    {
        if ($this->isInited) 
            return;

        $dbh = new PDO('mysql:host=127.0.0.1;dbname=test_model', 'root', 'root', [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        $stmt = $dbh->prepare("SELECT name, status FROM `objects` WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
 
        $result = $stmt->fetch();

        $this->name = $result['name'] ?? '';
        $this->status = (int) $result['status'] ?? 0;
        $this->isInited = true;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        print_r('lol');
        if ($property === 'id')
            throw new Exception("Property `id` cannot be overwriten.");

        if ($property === 'isInited')
            throw new Exception("Propery `isInited` cannot be changed");

        if (!isset($value) && $value !== '')
            throw new Exception("Value cannot be empty");

        if (gettype($this->$property) === gettype($value))
            $this->$property = $value;
        else 
            throw new Exception("Value must be type of " . gettype($this->$property));

        $this->changed = true;
    }

    /**
     * Save object data to DB if changed.
     * 
     * @return void
     */
    public function save()
    {
        if (!$this->changed)
            return;

        $dbh = new PDO('', [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        $stmt = $dbh->prepare("INSERT INTO `objects` (id, name, status) VALUES (:id, :name, :status)
            ON DUPLICATE KEY UPDATE name=:name, status=:status");
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':status', $this->status);
        $stmt->execute();
    }
}