@extends('layouts.admin.admin')
@section('title', 'Account Checker Edit')

@section('content')
<section class="p-4">

    @php
        // Set default start and end dates if not provided
        $currentMonthStart = now()->startOfMonth()->format('d-m-Y H:i A');
        $currentMonthEnd = now()->endOfMonth()->format('d-m-Y H:i A');

        $startDate = $startDate ?? $currentMonthStart;
        $endDate = $endDate ?? $currentMonthEnd;
    @endphp

    <form action="" method="GET">
        <div class="row">
            <!-- City Dropdown -->

            <div class="col-sm-3">
                <label for="city">Select City</label>
                <select class="form-control" name="city" id="city">
                    <option value="">Select city</option>
                    @foreach ($cities as $cityo)
                        <option value="{{ $cityo }}" {{ request('city') == $cityo ? 'selected' : '' }}>{{ ucfirst($cityo) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date -->
            <div class="col-sm-2">
                <label for="s">Start Date & Time</label>
                <input id="s" name="s" value="{{ request('s', '') }}" class="form-control datetimepicker" type="text" placeholder="DD-MM-YYYY hh:mm A">
            </div>

            <!-- End Date -->
            <div class="col-sm-2">
                <label for="e">End Date & Time</label>
                <input id="e" name="e" class="form-control datetimepicker" value="{{ request('e','') }}" type="text" placeholder="DD-MM-YYYY hh:mm A">
            </div>

            <!-- Group By Toggle -->
            <div class="col-sm-2">
                <label for="group">Group By Post</label>
                <select class="form-control" name="group" id="group">
                    <option value="0" {{ request('group') == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ request('group') == '1' ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <!-- Submit Button -->
            <div class="col-sm-3">
                <input type="submit" class="btn btn-sm btn-success mt-4" value="Filter">
            </div>
        </div>
    </form>

@if(request('group'))
<div class="p-4 mt-3 shadow">
    <div class="row mb-3">
        <div class="col h5">Total Visit: {{ $totalVisit }}</div>
        <div class="col h5">Total Link Clicks/Hits: {{ $totalHits }}</div>
        <div class="col"></div>
    </div>
    <hr>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Post Title</th>
                <th>Post Date</th>
                <th>Total Hit/Click</th>
                <th>Total Visit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visitors as $visitor)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td><a href="https://prarang.in/post-summary/{{$visitor->city}}/{{$visitor->post_id}}/{{$visitor->Title}}" target='_blank'>{{ $visitor->Title }}</a></td>
                    <td>{{$visitor->dateOfApprove}}</td>
                    <td>{{ $visitor->record_count }}</td>
                    <td>{{ $visitor->total_visits }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination with query persistence -->
    {{ $visitors->appends(request()->query())->links('pagination::bootstrap-5') }}

</div>
@else
    <div class="p-4 mt-3 shadow">
        <div class="row mb-3">
            <div class="col h5">Total Visit: {{ $totalVisit }}</div>
            <div class="col h5">Total Link Clicks/Hits: {{ $totalHits }}</div>
            <div class="col"></div>
        </div>
        <hr>

        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Post ID</th>
                    <th>Post City</th>
                    <th>Visitor City</th>
                    <th>Visits</th>
                    <th>IP Address</th>
                    <th>Visitor Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($visitors as $visitor)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td><a href="https://prarang.in/post-summary/{{$visitor->city}}/{{$visitor->post_id}}/{{$visitor->Title}}" target='_blank'>{{ $visitor->post_id }}</a></td>
                        <td>{{ ucfirst($visitor->city) }}</td>
                        <td>{{ $visitor->visitor_city }}</td>
                        <td>{{ $visitor->visit_count }}</td>
                        <td>{{ $visitor->ip_address }}</td>
                        <td>{{ detectUserType($visitor->user_agent) }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#visModal{{ $visitor->post_id }}">
                                More
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination with query persistence -->
        {{ $visitors->appends(request()->query())->links('pagination::bootstrap-5') }}

        <!-- Visitor Modals -->
        @foreach ($visitors as $visitor)
            <div class="modal fade" id="visModal{{ $visitor->post_id }}" tabindex="-1" aria-labelledby="visModal{{ $visitor->post_id }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="visModal{{ $visitor->post_id }}Label">{{ $visitor->Title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $fields = [
                                    'City' => ucfirst($visitor->city),
                                    'Post ID' => $visitor->post_id,
                                    'IP Address' => $visitor->ip_address,
                                    'Visitor City' => $visitor->visitor_city,
                                    'Visit Count' => $visitor->visit_count,
                                    'Latitude' => $visitor->latitude,
                                    'Longitude' => $visitor->longitude,
                                    'Language' => $visitor->language,
                                    'Screen Width' => "{$visitor->screen_width} px",
                                    'Screen Height' => "{$visitor->screen_height} px",
                                    'Timestamp' => $visitor->timestamp,
                                    'Visitor Address' => $visitor->visitor_address,
                                ];
                            @endphp

                            @foreach ($fields as $label => $value)
                                <div class="row mb-2">
                                    <div class="col-4"><strong>{{ $label }}:</strong></div>
                                    <div class="col-8">{{ $value }}</div>
                                </div>
                            @endforeach

                            <div>{{ $visitor->user_agent }}</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</section>

<script>
    flatpickr(".datetimepicker", {
        enableTime: true,
        dateFormat: "d-m-Y h:i K", // Format: DD-MM-YYYY hh:mm AM/PM
        time_24hr: false, // Use 12-hour format with AM/PM
        minuteIncrement: 1,
        defaultHour: 12
    });
</script>
@endsection
