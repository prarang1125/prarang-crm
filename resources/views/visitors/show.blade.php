@extends('layouts.admin.admin')
@section('title', 'Account Checker Edit')

@section('content')
<section class="p-4">

    <form action="" method="GET">
        <div class="row">
            <!-- City Dropdown -->
            <div class="col-sm-3">
                <label for="city">Select City</label>
                <select class="form-control" name="city" id="city">
                    <option value="null">Select city</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}">{{ ucfirst($city) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date Input -->
            <div class="col-sm-3">
                <label for="s">Start Date</label>
                <input id="s" name="s" class="form-control" type="text" placeholder="DD/MM/YYYY">
            </div>

            <!-- End Date Input -->
            <div class="col-sm-3">
                <label for="e">End Date</label>
                <input id="e" name="e" class="form-control" type="text" placeholder="DD/MM/YYYY">
            </div>

            <!-- Submit Button -->
            <div class="col-sm-3">

                <input type="submit" id="dede" class="btn btn-sm btn-success mt-4" value="Filter">
            </div>
        </div>
    </form>

<div class="p-4 mt-3 shadow">
<div class="row mb-3">
        <div class="col h5">Total Visit: {{$totalVisit}}</div>
        <div class="col h5">Total Link Click/Hits: {{$totalHits}}</div>
        <div class="col"></div>
    </div>
    <hr>
    <table class="table table-sm">
        <thead>
            <tr>
                <th>Post ID</th>
                <th>Post City</th>
                <th>Visitor City</th>
                <th>Address</th>
                <th>Visits</th>
                <th>IP Address</th>
                <th>Visitor Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($visitors as $visitor)
                <tr>

                    <td>{{ $visitor->post_id }}</td>
                    <td>{{ ucfirst($visitor->city) }}</td>
                    <td>{{ $visitor->visitor_city }}</td>
                    <td>{{ $visitor->visitor_address }}</td>
                    <td>{{ $visitor->visit_count }}</td>
                    <td>{{ $visitor->ip_address }}</td>
                    <td>{{detectUserType($visitor->user_agent)}}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#visModal{{ $visitor->post_id }}">
                          More
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modals for each visitor -->
    @foreach ($visitors as $visitor)
        <div class="modal fade" id="visModal{{ $visitor->post_id }}" tabindex="-1" aria-labelledby="visModal{{ $visitor->post_id }}Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visModal{{ $visitor->post_id }}Label">Visitor Details for Post ID: {{ $visitor->post_id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-4"><strong>City:</strong></div>
                            <div class="col-8">{{ ucfirst($visitor->city) }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Post ID:</strong></div>
                            <div class="col-8">{{ $visitor->post_id }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>IP Address:</strong></div>
                            <div class="col-8">{{ $visitor->ip_address }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Visitor City:</strong></div>
                            <div class="col-8">{{ $visitor->visitor_city }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Visit Count:</strong></div>
                            <div class="col-8">{{ $visitor->visit_count }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Latitude:</strong></div>
                            <div class="col-8">{{ $visitor->latitude }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Longitude:</strong></div>
                            <div class="col-8">{{ $visitor->longitude }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Language:</strong></div>
                            <div class="col-8">{{ $visitor->language }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Screen Width:</strong></div>
                            <div class="col-8">{{ $visitor->screen_width }} px</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Screen Height:</strong></div>
                            <div class="col-8">{{ $visitor->screen_height }} px</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Timestamp:</strong></div>
                            <div class="col-8">{{ $visitor->timestamp }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Visi Count:</strong></div>
                            <div class="col-8">{{ $visitor->visit_count }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4"><strong>Visitor Address:</strong></div>
                            <div class="col-8">{{ $visitor->visitor_address }}</div>
                        </div>
                        <div>{{ $visitor->user_agent }} </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}}

</section>
@endsection
