<?php

namespace Mazba\Services;

use Exception;

class CsvFileParser
{
    /**
     * Date rows
     * */
    public $rows;


    /**
     * Parse data from csv file
     * @param $file_path string
     * @return array
     *
     * *@throws Exception
     */
    public function __construct($file_path){
        $this->rows = $this->parseCsvFile($file_path);
    }

    /**
     * Parse data from csv file
     * @param $file_path
     * @return array
     * @throws Exception
     */
    public function parseCsvFile($file_path) : array{
        try{
            $file = fopen($file_path, 'r');
            $csv = [];
            while (($row = fgetcsv($file)) !== false) {
                $csv[] = $row;
            }
        }
        catch (Exception $e){
            throw new Exception('Unable to parse the file: '.$file_path);
        }
        return $csv;
    }
}