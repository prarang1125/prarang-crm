@extends('layouts.admin.admin')
@section('title', 'Post Edit')

@section('content')
    <br><br>
    <section class="container p-4 m-2">
        <h6>Post Preview</h6>

        <h2>{{ $chitti->Title }}</h2>
        <div class="row">
            <div class="col">
                {{ \Carbon\Carbon::parse($chitti->dateOfApprove)->format('d-m-Y H:i A') }}
                <br>
                {{ $chitti->tagInEnglish ?? 'N/A' }}


            </div>
            <div class="col">
                {{ $chitti->geography ?? 'N/A' }}
            </div>
        </div>
        <hr>
        <br>
        <img class="img-fluid w-100" src="https://{{ $chitti->imageUrl }}">
        <br>
        <br><br>
        {!! $chitti->description ?? 'N/A' !!}
    </section>

@endsection
