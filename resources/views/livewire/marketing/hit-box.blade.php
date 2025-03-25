<div>
    @section('title','Marketing Hit Box')

    @section('content')
    <main class="m-2">
    <section class="shadow p-2 text-center rounded">
        <h6>Prarang Daily Marketing (hitBox)</h6>
    </section>
    <section class="work-box">
        <div class="row">
            <div class="col-sm-7"><section class="mt-4 p-2 shadow rounded border">hello</section></div>
            <div class="col-sm-5"><section class="mt-4 p-2 shadow rounded border">
                <form wire:prevent>
                    <div class="mb-3">
                        <label for="template" class="form-label">Template</label>
                        <select id="template" class="form-select shadow-none border-0" wire:model="type">
                            <option value="default">Default</option>
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Message Box</label>
                        <textarea id="content" style="height:300px" class="form-control shadow-none border-0" wire:model="content"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-10">
                            {{-- <label for="channel" class="form-label">Options</label> --}}
                            <select id="channel" class="form-select shadow-none border-0" wire:model="option">
                                <option value="1">Whatsapp</option>
                                <option value="2">Email</option>
                                <option value="3">SMS</option>
                            </select>
                        </div>
                        <div class="col-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary shadow-none border-0 w-100">Send</button>
                        </div>
                    </div>

                </form>
            </section></div>
        </div>
    </section>

    </main>
    @endsection




</div>
