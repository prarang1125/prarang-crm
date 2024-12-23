<?php

namespace App\Livewire\Localization;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class Portal extends Component
{
    public $jsonFiles;

    public $liveMessage = '';

    public $newLanguageCode = '';

    public $editingFile = null;

    public $fileContent = '';

    public function mount()
    {
        $path = resource_path('lang/portals/');

        if (! is_dir($path)) {
            throw new \Exception("Directory does not exist: {$path}");
        }

        $files = collect(scandir($path))
            ->filter(fn ($file) => pathinfo($file, PATHINFO_EXTENSION) === 'json')
            ->map(fn ($file) => [
                'name' => $file,
                'path' => str_replace('\\', '/', "{$path}/{$file}"),
                'content' => json_decode(file_get_contents("{$path}/{$file}"), true),
            ]);

        $this->jsonFiles = $files;
    }

    public function createFile()
    {
        $path = resource_path("lang/portals/{$this->newLanguageCode}.json");

        if (File::exists($path)) {
            $this->liveMessage = 'This language file already exists!';

            return;
        }

        File::put($path, json_encode(['data' => 'write Here!'], JSON_PRETTY_PRINT));
        $this->mount();
        $this->newLanguageCode = '';
        $this->liveMessage = 'New language file created successfully!';
    }

    public function editFile($filePath)
    {
        $filePath = str_replace('\\', '/', $filePath);
        $this->editingFile = $filePath;
        $this->fileContent = file_get_contents($filePath);
    }

    public function saveFile()
    {
        File::put($this->editingFile, $this->fileContent);
        $this->mount();
        $this->editingFile = null;
        $this->fileContent = '';
        $this->liveMessage = 'File saved successfully!';
    }

    public function deleteFile($filePath)
    {
        $filePath = str_replace('\\', '/', $filePath);

        if (File::exists($filePath)) {
            File::delete($filePath);
            $this->mount();
            $this->liveMessage = 'File deleted successfully!';
        } else {
            $this->liveMessage = 'File not found!';
        }
    }

    public function render()
    {
        return view('livewire.localization.portal');
    }
}
