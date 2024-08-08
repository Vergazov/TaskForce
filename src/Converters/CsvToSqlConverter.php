<?php

namespace Converters;

class CsvToSqlConverter{

    public \SplFileObject $fileObject;
    public \SplFileObject $sqlFileObject;

    public string $columns;

    public $csvFileContent = [];

    public function __construct($csvFile, $sqlFile)
    {
        $this->fileObject = new \SplFileObject($csvFile,'r');
        $this->sqlFileObject = new \SplFileObject($sqlFile,'r+');
    }

    public function iterateCsvFile()
    {
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
    }

    public function write()
    {
        foreach ($this->iterateCsvFile() as $row) {
            $this->csvFileContent[] = $row;
        }
    }

    public function convert()
    {
        foreach ($this->iterateCsvFile() as $value){
            if($value[0] === 'name'){
                $this->columns = implode(',',$value);
            }
            $this->sqlFileObject->fwrite($this->getSqlScript($value, $this->columns));
        }
    }

    public function getSqlScript($values,$columns)
    {
        if($values[0] === 'name'){
            return;
        }
        $body = "INSERT INTO categories($columns) VALUES(\"$values[0]\", \"$values[1]\");" . PHP_EOL ;
        return $body;
    }

}
