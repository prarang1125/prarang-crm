<div class="col">

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#title{{ $chittiId }}"><i
            class="bx bx-edit-alt"></i></button>
    <!-- Modal -->
    <div class="modal fade" id="title{{ $chittiId }}" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Post Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (Auth::guard('admin')->check())
                        <form method="POST" action="{{ route('update.title') }}">
                            @csrf
                            <input type="hidden" value="{{ $chittiId }}" name="chittiId">
                            <input class="form-control mb-3" name="Title" type="text" placeholder="New post title"
                                aria-label="New post title">
                            <input class="form-control mb-3" name="subTitle" type="text"
                                placeholder="New post Subtitle (English)" aria-label="New post Subtitle (English)">
                            <button class="form-control mb-3 btn btn-success" type="submit">Update</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('accupdate.title') }}">
                            @csrf
                            <input type="hidden" value="{{ $chittiId }}" name="chittiId">
                            <input class="form-control mb-3" name="Title" type="text" placeholder="New post title"
                                aria-label="New post title">
                            <input class="form-control mb-3" name="subTitle" type="text"
                                placeholder="New post Subtitle (English)" aria-label="New post Subtitle (English)">
                            <button class="form-control mb-3 btn btn-success" type="submit">Update</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
