<?php

class CsvDataReader
{
    private $persons;

    public function __construct()
    {
        $this->persons = [];
    }

    public function readCsvData($csvFilePath)
    {
        $this->validateCsvFile($csvFilePath);

        $csvFile = fopen($csvFilePath, 'r');
        if ($csvFile !== false) {
            fgetcsv($csvFile);

            $personHandler = new PersonHandler();

            while (($row = fgetcsv($csvFile)) !== false) {
                $cleanedRow = $this->cleanRow($row);

                if ($this->isSinglePerson($cleanedRow)) {
                    $this->persons[] = $personHandler->setPerson($cleanedRow);
                } else {
                    $couples = $personHandler->handleCouple($cleanedRow);
                    foreach ($couples as $couple) {
                        $this->persons[] = $personHandler->setPerson($couple);
                    }
                }
            }

            fclose($csvFile);
            return $this->persons;
        } else {
            throw new RuntimeException("Error opening the CSV file: " . error_get_last()['message']);
        }
    }

    private function validateCsvFile($csvFilePath)
    {
        if (!file_exists($csvFilePath)) {
            throw new InvalidArgumentException("The specified CSV file does not exist.");
        }
    }

    private function cleanRow($row)
    {
        return str_replace(['.', ','], '', implode(', ', $row));
    }

    private function isSinglePerson($cleanedRow)
    {
        return strpos($cleanedRow, 'and') === false && strpos($cleanedRow, '&') === false;
    }
}

class PersonHandler
{
    public function setPerson($cleanedRow)
    {
        $item = explode(' ', trim($cleanedRow));
        if (count($item) == 2) {
            $person = [
                'title'      => $item[0],
                'first_name' => null,
                'initial'    => null,
                'last_name'  => $item[1],
            ];
        } else {
            $person = [
                'title'      => $item[0],
                'first_name' => strlen($item[1]) > 1 ? $item[1] : null,
                'initial'    => strlen($item[1]) > 1 ? null : $item[1],
                'last_name'  => isset($item[2]) ? $item[2] : null,
            ];
        }

        return $person;
    }

    public function handleCouple($cleanedRow)
    {
        $names = str_replace(['and', '&'], '', $cleanedRow);
        $words = explode(' ', trim($names));
        $couples = [];

        $filteredWords = array_filter($words);
        $count = count($filteredWords);

        if ($count == 3) {
            $lastname = $filteredWords[3];
            $couples[] = $filteredWords[0] . ' ' . $lastname;
            $couples[] = $filteredWords[2] . ' ' . $lastname;
        } elseif ($count == 4) {
            $lastname = $filteredWords[4];
            $couples[] = $filteredWords[0] . ' ' . $lastname;
            $couples[] = $filteredWords[2] . ' ' . $filteredWords[3] . ' ' . $lastname;
        } else {
            $couples[] = $filteredWords[0] . ' ' . $filteredWords[1] . ' ' . $filteredWords[2];
            $couples[] = $filteredWords[4] . ' ' . $filteredWords[5] . ' ' . $filteredWords[6];
        }

        return $couples;
    }
}
$csvReader = new CsvDataReader();
$csvData = $csvReader->readCsvData('csv/examples-4-.csv');

// echo '<pre>';
// var_dump(json_encode($csvData, JSON_PRETTY_PRINT));
// echo '</pre>';
// die();