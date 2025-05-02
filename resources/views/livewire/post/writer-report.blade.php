<div wire:loading.class="loading" class="position-relative">
    <style>
        .loading {
            background-color: rgba(255, 255, 255, 0.7);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
        }
        .loading::before {
            content: "";
            position: absolute;
            border: 2px solid #3498db;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-6">
            <form wire:submit.prevent="submit" class="mb-3 w-50">
                <label for="selectedMonth" class="form-label">Select Month-Year</label>
                <div class="input-group">

                    <select class="form-control w-25" id="selectedMonth" wire:model="selectedMonth">
                        <option value="{{ date('m') }}">{{ date('F') }}</option>
                        @for ($i = 1; $i < 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                    <select class="form-control w-25" wire:model="selectedYear">
                        <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                        @for ($i = date('Y') - 1; $i >= 2025; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button class="btn btn-primary mt-2" type="Find">Submit</button>
            </form>
        </div>
        <div class="col-sm-4"></div>
    </div>

    @if ($writers)
    <section class="m-2 shadow p-2  rounded">
        <h6>Writer Report</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Writer Name</th>
                    <th>Post Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($writers as $key => $writer)
                <tr>
                    <td>{{ $mUserName[$key] }}</td>
                    <td>{{ $writer }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    @endif


</div>

