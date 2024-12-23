<div>
    <h4>Portal Localization Files</h4>
    <!-- {{portalLocaLang('culture')}} -->
    <section class="p-2 m-3">
        <p class="text-end">
            <button class="btn btn-sm btn-primary" wire:click="$toggle('newLanguageCode')">
                <i class="fa fa-plus"></i> Add Language File
            </button>
        </p>

        @if ($newLanguageCode !== '')
        <div class="form-group">
            <label for="newLanguageCodeInput">Enter Language Code:</label>
            <input type="text" class="form-control" wire:model="newLanguageCode" id="newLanguageCodeInput"
                placeholder="e.g., en">
            <button class="btn btn-success mt-2" wire:click="createFile">Create File</button>
        </div>
        
        @endif
        @if ($liveMessage)
        <p class="text-warning">{{ $liveMessage }}</p>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jsonFiles as $file)
                <tr>
                    <td>{{ $file['name'] }}</td>
                    <td>
                        <button class="btn btn-sm btn-primary"
                            wire:click="editFile('{{ $file['path'] }}')">Edit</button>
                        <button disabled
                            class="btn btn-sm btn-danger"
                            wire:click="deleteFile('{{ addslashes($file['path']) }}')">
                            Delete
                        </button>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if ($editingFile)
        <div class="form-group mt-4">
            <label for="fileContent">Edit Content</label>
            <textarea id="fileContent" class="form-control" rows="10" wire:model="fileContent"></textarea>
            <button class="btn btn-success mt-2" wire:click="saveFile">Save</button>
        </div>
        @endif
    </section>
</div>