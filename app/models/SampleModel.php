<?php
/**
* A damn simple demo model!
*
*/
class SampleModel
{

    public static function addPerson($name, $age)
    {
        // Get the database instance from DbFactory
        $db = DbFactory::getFactory()->getConn();
        // See? Pure PDO, nothing else!
        $sql = "INSERT INTO `saika_demo` (name, age) VALUES (:name, :age)";
        $query = $db->prepare($sql);
        $params = array(':name' => $name, ':age' => $age);
        return $query->execute($params);
    }
}
