<?php
class PackageSearch extends Packages
{
    public $options = array('offset' => 0,
                            'limit'  => -1);

    function __construct($options)
    {
        $this->options = $options + $this->options;
        $records       = array();
        $mysqli        = Record::getDB();

        $sql = 'SELECT packages.* FROM packages';

        if (isset($options['q']) && !empty($options['q'])) {
            $sql .= ' WHERE name LIKE "%';
            $sql .= $mysqli->escape_string($options['q']);
            $sql .= '%" OR description LIKE "%';
            $sql .= $mysqli->escape_string($options['q']);
            $sql .= '%"';
        }

        $records = array();
        if ($result = $mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $records[] = $row;
                }
                $result->free();
            }
        }
        $count = $this->options['limit'];
        if (count($records) == 0) {
            $count = 0;
        }
        parent::__construct(
            new ArrayIterator($records),
            $this->options['offset'],
            $count
        );
    }
}
