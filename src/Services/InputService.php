<?php

namespace Mazba\Services;

use Exception;

class InputService
{

    /**
     * Input date
     * */
    public $data;

    /**
     * Parse data from input file
     * @param $input
     * @throws Exception
     */
    public function __construct($input){
        return is_array($input)
            ? false
            : $this->parseInputFile($input);

    }
    /**
     * Parse csv file to PHP array
     * @return object
     *
     * @throws Exception
     */
    private function parseInputFile($file_path){
        $ext = pathinfo($file_path, PATHINFO_EXTENSION);
        $this->isPathReadable($file_path);
        switch ($ext){
            case 'csv':
                return $this->data = new CsvFileParser($file_path);
            default:
                break;
        }
    }

    /**
     * Is the path readable from the string.
     *
     * @param string $string
     * @return void
     *
     * @throws Exception
     */
    private function isPathReadable($string): void
    {
        if (is_readable($string))
        {
            return;
        }
        throw new Exception('Unable to read the file');
    }
}