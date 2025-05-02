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

        /* Selected month */
        #selectedMonth {
            transform: translatex(0px) translatey(0px);
        }

        /* Table Data */
        .page-wrapper tr td {
            font-weight: 500;
            font-size: 12px;
        }
    </style>
    <p class="text-center pt-2">
    <h4 class="text-center">Writer Report</h4>
    </p>
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <form wire:submit.prevent="submit" class="mb-3">
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
                    <select class="form-control w-25" wire:model="writerId">
                        <option value="">Select Writer</option>
                        @foreach ($allWriters as $writerz)
                        <option value="{{ $writerz->userId }}">{{ $writerz->firstName }} {{ $writerz->lastName }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary mt-2" type="Find">Submit</button>
            </form>
        </div>
        <div class="col-sm-3"></div>
    </div>

    @if ($writers)
    <p class="text-end me-3"><button class="btn btn-primary" onclick="printDiv('toPrint')">Print</button></p>
    <section class="m-2 shadow p-2  rounded" id="toPrint">

        <div class="card">
            <div class="card-header">
                Report of: {{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }}
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Writer Name</th>
                            <th>Post Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($writers as $key =>$writer)

                        <tr>
                            <td> {{$mUserName[$key]}} </td>
                            <td> {{$writer}} </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Title</th>
                    <th>Tags</th>
                    <th>Date of Maker</th>
                    <th>Date of Upload</th>
                    <th>Viwes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td> {{ $post['Title'] }} </td>
                    <td> {{ $post['tagInEnglish'] }} </td>
                    <td> {{ \Carbon\Carbon::parse($post['dateOfCreation'])->format('d-m-Y h:i A') }} </td>
                    <td> {{ \Carbon\Carbon::parse($post['dateOfApprove'])->format('d-m-Y h:i A') }} </td>
                    <td> {{ $post['totalViewerCount'] }} </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
    @endif
    <script>
        function printDiv(toPrint) {
            var printContents = document.getElementById(toPrint).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>

</div>
