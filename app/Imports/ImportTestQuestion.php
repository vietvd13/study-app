<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class ImportTestQuestion implements ToModel, WithChunkReading
{
    /**
    * @param Collection $collection
    */
    use RemembersRowNumber;

    public $data;
    public function __construct($data)
    {

    }

    public function model(array $row)
    {
        $rowIndex = $this->getRowNumber();
    }

    public function chunkSize(): int
    {
        return 1000;
    }

}
