<?php

namespace Converters;

use CustomExceptions\ConverterException;
use DirectoryIterator;

class CsvSqlConverter
{
    /**
     * @var array
     */
    protected array $filesToConvert = [];

    /**
     * @param string $directory
     * @throws ConverterException
     */
    public function __construct(string $directory)
    {
        if (!is_dir($directory)) {
            throw new ConverterException("Directory \"$directory\" does not exist");
        }

        $this->loadCsvFiles($directory);
    }

    /**
     * @param string $outputDirectory
     * @return array
     * @throws ConverterException
     */
    public function convertFiles(string $outputDirectory): array
    {
        $result = [];

        foreach ($this->filesToConvert as $file) {
            $result[] = $this->convertFile($file, $outputDirectory);
        }

        return $result;
    }

    /**
     * @param \SplFileInfo $file
     * @param string $outputDirectory
     * @return string
     * @throws ConverterException
     */
    public function convertFile(\SplFileInfo $file, string $outputDirectory): string
    {
        $fileObject = new \SplFileObject($file->getRealPath());
        $fileObject->setFlags(\SplFileObject::READ_CSV);
        $columns = $fileObject->fgetcsv();

        $values = [];

        while (!$fileObject->eof()) {
            $values[] = $fileObject->fgetcsv();
        }

        $tableName = $file->getBasename('.csv');
        $sqlContent = $this->getSqlContent($tableName, $columns, $values);

        return $this->saveSqlContent($tableName, $outputDirectory, $sqlContent);
    }

    /**
     * @param string $tableName
     * @param array $columns
     * @param array $values
     * @return string
     */
    public function getSqlContent(string $tableName, array $columns, array $values): string
    {
        $columnsString = $this->getColumnsString($columns);

        $sql = "INSERT INTO $tableName ($columnsString) VALUES ";

        foreach ($values as $row) {
            array_walk($row, function (&$value) {
                $value = addslashes($value);
                $value = "'$value'";
            });
            $sql .= "( " . implode(',', $row) . "), ";
        }
        $sql = substr($sql, 0, -2);

        return $sql;
    }

    /**
     * Возвращает название колнок в виде строки и удаляет ZWNBSP
     * @param array $columns
     * @return string
     */
    private function getColumnsString(array $columns): string
    {
        $columnsString = implode(',', $columns);

        $columnsString = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $columnsString );
        $columnsString = trim($columnsString,chr(0xC2).chr(0xA0));

        return $columnsString;
    }

    /**
     * @param string $tableName
     * @param string $directory
     * @param string $content
     * @return string
     * @throws ConverterException
     */
    protected function saveSqlContent(string $tableName, string $directory, string $content): string
    {
        if(!is_dir($directory)){
            throw new ConverterException("Directory \"$directory\" does not exist");
        }

        $fileName = $directory . DIRECTORY_SEPARATOR . $tableName . '.sql';
        file_put_contents($fileName,$content);

        return $fileName;
    }

    /**
     * @param string $directory
     * @return void
     */
    protected function loadCsvFiles(string $directory): void
    {
        foreach (new DirectoryIterator($directory) as $file) {
            if($file->getExtension() === 'csv'){
                $this->filesToConvert[] = $file->getFileInfo();
            }
        }
    }

}