<div>
    @section('title', 'Marketing Hit Box')
    <style>
        /* Grpx */
        .work-box .grpx {
            display: flex;
            justify-content: center;
        }

        .grpx .form-check label {
            margin-right: 45px;
        }

        /* Section */
        .work-box .mt-2 {
            background-color: rgba(215, 207, 207, 0.3);
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        /* Rounded */
        .work-box .col-sm-7 .rounded {
            border-top-left-radius: 8px !important;
            border-top-right-radius: 8px !important;
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
        }
    </style>

    <main class="m-2">
        <section class="shadow p-2 text-center rounded">
            <h6>Prarang Daily Marketing (HitBox)</h6>
        </section>

        <section class="work-box">
            <div class="row">
                <!-- Left Side: City Selection -->
                <div class="col-sm-7 ">
                    <div class="mt-4 p-2 shadow rounded border">
                        <section class="">
                            <div class="select-cities">
                                <div class="dropdown">
                                    <button class=" btn btn-sm btn-success dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown">
                                        <i class="bx bx-plus"></i> City
                                    </button>
                                    <div class="dropdown-menu">
                                        @foreach ($cities as $citySelect)
                                            <label class="dropdown-item">
                                                <input type="checkbox" value="{{ $citySelect->id }}"
                                                    wire:model="cityIds" wire:change="cityUpdate">
                                                {{ $citySelect->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="selected-cities mt-3">
                                @foreach ($city as $cityData)
                                    <i class='bx bx-current-location bx-tada'></i> {{ $cityData->name }} &nbsp;&nbsp;
                                @endforeach

                            </div>
                        </section>
                        @empty($city)
                            <div class="text-danger p-2  mt-2">Please select a city</div>
                            @else

                        <section class="mt-2 p-2 ">
                            <h6>User Groups</h6>
                            <div class="grpx">
                                <div class="form-check">
                                    <input id="vc" class="form-check-input" type="checkbox" value="2"
                                        wire:model="userGroup">
                                    <label for="vc" class="form-check-label">
                                        VCard
                                    </label>
                                </div>


                                <div class="form-check">
                                    <input id="sub" class="form-check-input" type="checkbox" value="4"
                                        wire:model="userGroup">
                                    <label for="sub" class="form-check-label">
                                        Subscriber
                                    </label>
                                </div>
                            </div>
                            <h6>Channel</h6>
                            <div class="grpx">
                                @foreach ($channels as $key => $channelData)
                                    <div class="form-check">
                                        <div class="form-check">
                                            <input wire:model="channel" id="{{ $channelData }}"
                                                class="form-check-input" type="radio" value="{{ $key }}"
                                                name="channel">
                                            <label for="{{ $channelData }}" class="form-check-label">
                                                {{ $channelData }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach


                            </div>

                        </section>
                        <section class="mt-2 p-2 ">
                            <div class="mb-3">
                                <label for="template" class="form-label">Template</label>
                                <select id="template" class="form-select" wire:change='setTemplates()'
                                    wire:model="templateId">
                                    @foreach ($templates as $templateData)
                                        <option value="{{ $templateData['id'] }}">{{ $templateData['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </section>
                        <section class="mt-2 p-2 ">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <p class="text-danger">
                                        @error('customUsers')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </p>
                                    <label for="customUser" class="form-label">Custom User</label>
                                    <textarea id="customUser" style="height:100px" class="form-control" wire:model.defer="customUsers"
                                        wire:change="handaleCustomUsers()"></textarea>
                                </div>
                            </div>
                        </section>
                        @endempty

                    </div>
                </div>

                <!-- Right Side: Message Sending -->
                <div class="col-sm-5">
                    <section class="mt-4 p-2 shadow rounded border">
                        <form wire:submit.prevent="sendMessage">
                            <!-- Template Selection -->
                            @if ($showPost)
                                <div class="mb-3">
                                    <label for="template" class="form-label">Select Post</label>
                                    <select id="template" class="form-select" wire:change="setTemplates()"
                                        wire:model="postId">
                                        <option>Select Post</option>
                                        @foreach ($posts as $postData)
                                            <option value="{{ $postData->chittiId }}">
                                                {{ $postData->Title }}
                                            </option>
                                        @endforeach

                                    </select>

                            @endif
                            <!-- Message Box -->
                            <div class="mb-3">
                                <label for="content" class="form-label">Message Box</label>
                                <textarea id="content" style="height:300px" class="form-control" wire:model.defer="content">

                                </textarea>
                            </div>

                            <!-- Channel Selection -->
                            <div class="row">
                                <div class="col-10">
                                    <select id="channel" class="form-select" wire:model="option">
                                        <option value="1">WhatsApp</option>
                                        <option value="2">Email</option>
                                        <option value="3">SMS</option>
                                    </select>
                                </div>
                                <div class="col-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Send</button>
                                </div>
                            </div>
                        </form>

                        <!-- Success & Error Messages -->
                        @if (session()->has('success'))
                            <div class="alert alert-success mt-2">{{ session('success') }}</div>
                        @endif
                        @if (session()->has('error'))
                            <div class="alert alert-danger mt-2">{{ session('error') }}</div>
                        @endif
                    </section>
                </div>
            </div>
        </section>
    </main>

</div>
