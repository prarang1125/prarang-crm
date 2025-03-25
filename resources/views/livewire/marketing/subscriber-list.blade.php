<div>
    @section('title', 'Marketing Subscriber List')
    @section('content')
    <style>
        /* Row */
    section form .row{
    justify-content:center;
    align-items:center;
    }
/* Column 4/12 */
section form .col-4{
 display:flex;
 justify-content:center;
 align-items:center;
}

/* Italic Tag */
/* Italic Tag */
section form i{
 position:relative;
 top:3px !important;
}

/* Italic Tag */
.wrapper .page-wrapper section .row .col-4 form .row .col-4 .text-warning i{
 bottom:auto !important;
}





    </style>
    <br>
        <section class="mt-4  p-4 m-2 bg-white shadow rounded">
            <div>
                <h4 class="text-center">Prarang Subscribers</h4>
            </div>
            <hr>

            <div class="row">
                <div class="col-8 h6">
                    @php
                        $totalS = $subscriberCounts->sum('count');
                    @endphp
                    <strong>Subscriber Counts:</strong> <br>
                    @foreach ($subscriberCounts as $count)
                        <span class="text-primary">{{ $count->role == 2 ? 'vCard' : 'Post Subscriber' }}:</span>
                        <strong>{{ $count->count }} </strong>
                    @endforeach
                    | <strong>Total: {{ $totalS }}</strong>
                </div>

                <div class="col-4">
                    <form action="{{ request()->url() }}" method="GET">
                        <div class="row">
                            <div class="col-8 mb-4">
                                <label for="date" class="form-label">Filter by Date:</label>
                                <input type="date" value="{{ $date ?? '' }}" name="date" class="form-control">
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a class="text-warning h2" href="/subscribers">
                                    <i class="bx bx-refresh"
                                       onmouseover="this.classList.add('bx-spin', 'bx-rotate-90')"
                                       onmouseleave="this.classList.remove('bx-spin', 'bx-rotate-90')">
                                    </i>
                                </a>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <hr>

            <div class="table-responsive">
                <h5>Subscriber List:</h5>
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone No.</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscribers as $subscriber)
                            <tr class="{{ $loop->even ? 'table-light' : '' }}">
                                <td>{{ $subscriber->id }}</td>
                                <td>{{ $subscriber->name }}</td>
                                <td>{{ $subscriber->phone }}</td>
                                <td>{{ $subscriber->email }}</td>
                                <td>{{ $subscriber->role == 2 ? 'vCard' : 'Post Subscriber' }}</td>
                                <td>{{ $subscriber->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $subscribers->links('pagination::bootstrap-5') }}
        </section>
    @endsection
</div>
