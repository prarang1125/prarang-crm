<?php

namespace App\Livewire;

use Livewire\Component;

class ExcelDataPaster extends Component
{
    public $pastedText = '';
    public $parsedData = [];
    public $inputName = 'data';
    public $jsonOutput = '{}';
    public $label = '';

    public function mount($label = 'Data to Json', $inputName = 'data', $initialData = null)
    {
        $this->inputName = $inputName;
        $this->label = $label;

        if (is_string($initialData)) {
            $initialData = json_decode($initialData, true);
        }

        $this->parsedData = is_array($initialData) ? $initialData : [];
        $this->updateJsonOutput();
    }

    public function updatedPastedText()
    {
        $this->parseExcelData();
    }

    public function parseExcelData()
    {
        if (empty(trim($this->pastedText))) {
            return;
        }

        $lines = explode("\n", str_replace("\r", "", $this->pastedText));

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $columns = explode("\t", $line);

            if (count($columns) >= 2) {
                $key = trim($columns[0]);

                // Allow dynamic structure based on number of columns
                if (count($columns) == 2) {
                    $value = trim($columns[1]);
                } else {
                    $value = [
                        'name' => trim($columns[1] ?? ''),
                        'url' => trim($columns[2] ?? '')
                    ];
                }

                if (!isset($this->parsedData[$key])) {
                    $this->parsedData[$key] = [];
                }

                // If it was previously a string, convert to array for grouping
                if (!is_array($this->parsedData[$key])) {
                    $this->parsedData[$key] = [$this->parsedData[$key]];
                }

                $this->parsedData[$key][] = $value;
            }
        }

        $this->pastedText = '';
        $this->updateJsonOutput();
    }

    public function removeCategory($key)
    {
        if (isset($this->parsedData[$key])) {
            unset($this->parsedData[$key]);
            $this->updateJsonOutput();
        }
    }

    public function removeItem($key, $index)
    {
        if (isset($this->parsedData[$key][$index])) {
            unset($this->parsedData[$key][$index]);
            $this->parsedData[$key] = array_values($this->parsedData[$key]);

            if (empty($this->parsedData[$key])) {
                unset($this->parsedData[$key]);
            }
            $this->updateJsonOutput();
        }
    }

    public function updateJsonOutput()
    {
        $this->jsonOutput = json_encode($this->parsedData, JSON_UNESCAPED_UNICODE);
    }

    public function render()
    {
        return view('livewire.excel-data-paster');
    }
}
