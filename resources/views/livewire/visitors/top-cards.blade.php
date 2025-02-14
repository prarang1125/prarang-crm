<span>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Division */
        .modal-body .mb-3 {
            display: block !important;
        }

        /* Col 6 */
        .border .col-sm-6 {
            font-size: 12px;
        }

        /* Span Tag */
        .mt-2 h4 span {
            font-size: 20px;
        }


        /* Heading */
        .mt-2 div h4 {
            font-size: 14px;
        }

        /* Text muted */
        .mt-2 h4 .text-muted {
            font-size: 10px;
            padding-left: 5px;
        }

        /* Text muted */
        .col-sm-4 h4 .text-muted {
            font-size: 13px !important;
            padding-left: 8px;
        }

        /* Span Tag */
        .col-sm-4 h4 span {
            font-size: 20px !important;
        }

        /* Heading */
        .col-sm-4 div h4 {
            padding-left: 23px;
        }

        /* Division */
        .col-sm-4>div>div>div {
            margin-bottom: 11px;
            border-style: solid;
            border-width: 1px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
            box-shadow: 0px 0px 6px 1px #c6c6cc;
        }

        /* Reff chart */
        #reffChart {
            border-width: 0px;
        }

        @media (min-width:1200px) {

            /* Heading */
            .col-sm-4 div h4 {
                font-size: 16px;
            }

        }
    </style>

    <div class="shadow p-3 rounded border">
        <div class="row">
            <div class="col-sm-6">
                <p class="fw-bold h6">Prarang Visitor Analytics </p>
</span>
</div>
<div class="col-sm-6">
    <div class="text-end">
        <p>
            <span class="fw-bold fs-10">{{ ucfirst($city) }}</span> | Between
            <span class="fw-bold">{{ date('d M, Y h:i A', strtotime($startDate)) }}</span> to
            <span class="fw-bold">{{ date('d M, Y h:i A', strtotime($endDate)) }}</span>
            &nbsp;
            <a class="btn btn-sm btn-primary" href="javascript:void(0)" data-bs-toggle="modal"
                data-bs-target="#dateChangeModal">Change</a>
        </p>
    </div>
</div>
</div>
</div>
<section class="mt-2">
    <div class="row">
        @foreach (['facebook' => 'bxl-facebook', 'google' => 'bxl-google', 'prarang' => 'bx-user', 'others' => 'bx-time'] as $key => $icon)
            <div class="col">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">{{ ucfirst($key) }}</p>
                                <h4 class="my-1">
                                    <span>{{ number_format($reffData[$key][0] ?? 0) }}</span><span>/</span>{{ number_format($reffData[$key][1] ?? 0) }}<span
                                        class="text-muted">Clicks</span>
                                </h4>
                            </div>
                            <div class="text-primary ms-auto font-35"><i class="bx {{ $icon }}"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <!-- Pagination Links -->


</section>
<section>
    {{-- @livewire('visitors.posts') --}}
</section>

<section class=" p-2">
    <div class="row">
        <div class="col-sm-8 card radius-10 p-2">
            <div class="post-date mb-2">
                <div>Post Date: &nbsp; &nbsp;</div>
                <div>
                    <input id="from" wire:model="postStartDate" wire:change="changePostData"  class="form-control datetimepicker" type="text"
                        placeholder="DD-MM-YYYY hh:mm A">
                    @error('startDate')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                </div>
                <div class="ps-2 pe-2">To</div>
                <div>
                    <input id="to" wire:model="postEndDate" wire:change="changePostData" class="form-control datetimepicker" type="text"
                        placeholder="DD-MM-YYYY hh:mm A">
                    @error('startDate')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="post-table" wire:loading.class="loading-effect">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Sr.</th>
                            <th scope="col">Title</th>
                            <th scope="col">Clicks</th>
                            <th scope="col">Visits</th>
                            <th scope="col">Other</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($visitors as $visitor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="javascript:void(0)"
                                        wire:click="getPostAnalytics({{ $visitor->post_id }}, '{{ addslashes($visitor->Title) }}')">
                                        {{ $visitor->Title }}
                                    </a>
                                </td>

                                <td>{{ $visitor->record_count }}</td>
                                <td>{{ $visitor->visit_count }}</td>
                                <td>{{ Carbon\Carbon::parse($visitor->dateOfApprove)->format('d-M-Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Data Found!</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>
            <div class="mt-3 ">
                {{ $visitors->links() }}
            </div>
        </div>
        <div class="col-12 col-lg-4 col-xl-4 d-flex">
            <div class="card radius-10 overflow-hidden w-100">
                <div class="card-body">
                    <div class=" align-items-center">
                        <h6 class="mb-0"> <span wire:loading wire:target="getPostAnalytics">
                                <span class="spinner-border text-primary spinner-border-sm" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                                &nbsp; &nbsp;</span>{{ ucfirst($city) }} Post Analytics
                        </h6>
                        <p>{{ $postTitle }}</p>
                        <div class="post-frem">
                            <div class="table-responsive" wire:loading.class="loading-effect">
                                {{-- get5th31th --}}
                                @isset($get5th31th)
                                    <table class="table align-items-center table-sm mb-0">
                                        <tbody>
                                            <tr>
                                                <th>Days</th>
                                                <td>Clicks</td>
                                                <td>Views</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-4">5<sup>th</sup></th>
                                                <td>{{ $get5th31th->view_5th }}</td>
                                                <td>{{ $get5th31th->view_5th }}</td>
                                            </tr>
                                            <tr>
                                                <th class="ps-4"> 31 <sup>st</sup></th>
                                                <td>{{ $get5th31th->view_31st }}</td>
                                                <td>{{ $get5th31th->view_31st }}</td>
                                            </tr>
                                        </tbody>
                                    </table> <br>
                                @endisset
                                <table class="table align-items-center table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th>Reference</th>
                                            <td>Clicks</td>
                                            <td>Views</td>
                                        </tr>
                                        @php
                                            $totalh = 0;
                                            $totalv = 0;
                                        @endphp
                                        @foreach (['facebook' => 'bxl-facebook', 'google' => 'bxl-google', 'prarang' => 'bx-user', 'others' => 'bxs-circle'] as $key => $icon)
                                            @isset($postReffData[$key][1])
                                                @php
                                                    $totalh = $totalh + $postReffData[$key][1];
                                                    $totalv = $totalv + $postReffData[$key][0];
                                                @endphp
                                            @endisset
                                            <tr>
                                                <td><i class="bx {{ $icon }} me-2" "></i>
                                                {{ ucfirst($key) }}</td>
                                            <td>{{ number_format($postReffData[$key][1] ?? 0) }}</td>
                                            <td>{{ number_format($postReffData[$key][0] ?? 0) }}</td>
                                        </tr>
 @endforeach
                                            <tr>
                                                <th class="ps-4">Total</th>
                                                <th>{{ $totalh }}</th>
                                                <th>{{ $totalv }}</th>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="table-responsive" wire:loading.class="loading-effect">
                                <table class="table align-items-center table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th>Visitor Type</th>

                                            <td></td>
                                        </tr>
                                        @foreach (['user' => 'bx-user', 'facebook_bot' => 'bxl-facebook', 'google_bot' => 'bxl-google', 'bing_bot' => 'bxl-bing', 'others' => 'bxs-circle'] as $key => $icon)
                                            <tr>
                                                <td><i class="bx {{ $icon }} me-2""></i>
                                                    {{ ucfirst($key) }}</td>
                                                <td>{{ number_format($userType[$key] ?? 0) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div> <br>
                            <div class="table-responsive" wire:loading.class="loading-effect">
                                <table class="table align-items-center table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <th>Behaviour</th>

                                            <td></td>
                                        </tr>
                                        @foreach (['scroll' => 'bx-mobile', 'duration' => 'bx-time', 'mobile' => 'bx-phone', 'tablet' => 'bx-tab', 'desktop' => 'bx-desktop'] as $key => $icon)
                                            <tr>
                                                <td><i class="bx {{ $icon }} me-2"></i>
                                                    {{ ucfirst($key) }}</td>
                                                <td>{{ number_format($scroll->{$key} ?? 0) }}{{ $key == 'scroll' ? '% (agv)' : '' }}{{ $key == 'duration' ? ' min' : '' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

</section>

<section class=" p-2 mt-4 card radius-10 w-100 ">

    {{-- <canvas id="reffChart"></canvas> --}}
</section>


<!-- Date Change Modal -->
<div class="modal fade" id="dateChangeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">Change Analytic Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form wire:submit.prevent="applyFilters">
                <div class="modal-body">
                    <input type="hidden" name="city" value="{{ $city }}">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="from">From</label>
                            <input id="from" wire:model="startDate" class="form-control datetimepicker"
                                type="text" placeholder="DD-MM-YYYY hh:mm A">
                            @error('startDate')
                                <small class="text-danger ps-1">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="to">To</label>
                            <input id="to" wire:model="endDate" class="form-control datetimepicker"
                                type="text" placeholder="DD-MM-YYYY hh:mm A">
                            @error('endDate')
                                <small class="text-danger ps-1">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <p class="ordate"><span>OR</span></p>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="fromMonthYear">From (Month)</label>
                            <input disabled id="fromMonthYear" value="" wire:model="startDate"
                                class="form-control" type="month">
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="toMonthYear">To (Month)</label>
                            <input disabled id="toMonthYear" value="" wire:model="endDate"
                                class="form-control" type="month">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        Apply <span wire:loading wire:target="applyFilters">
                            <div class="spinner-border text-light spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </span>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('hideDateChangeModal', () => {
            let myModal = document.getElementById('dateChangeModal');
            let modalInstance = bootstrap.Modal.getInstance(myModal) || new bootstrap.Modal(myModal);
            modalInstance.hide();
        });
    });
    document.addEventListener('DOMContentLoaded', function() {

        window.addEventListener('updateChart', () => {
            var data = @json($reffData);

            var ctx = document.getElementById("reffChart").getContext("2d");
            var reffChart = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: [],
                    datasets: [{
                            data: [],
                            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4CAF50"]
                        },
                        {
                            data: [],
                            backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56", "#4CAF50"]
                        }

                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true // Hide the legend (labels)
                        },
                        tooltip: {
                            enabled: true // Hide tooltips on hover
                        },
                        datalabels: {
                            display: true // Remove labels on the pie chart itself
                        }
                    }
                }

            });
            // console.log("Chart Data:", data);/
            let labels = Object.keys(data);
            let values = Object.values(data);
            // reffChart.data.labels = labels.map(label => label.toUpperCase());
            reffChart.data.datasets[0].data = Object.values(data).map(d => d[0])
            reffChart.data.datasets[1].data = Object.values(data).map(d => d[1])

            reffChart.update();

        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".datetimepicker", {
            enableTime: true,
            dateFormat: "d-m-Y h:i K",
            time_24hr: false,
        });
    });
</script>
</span>
