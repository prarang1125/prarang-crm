@extends('layouts.admin.admin')
@section('title', 'Prarang analytics ')

@section('content')
<section class="p-4">
    <br>
    <section class="d-flex align-items-center justify-content-center row">
        <div class="shadow p-3 rounded border col-sm-8 pt-4">
            <h5 class="text-center">
                <b>Prarang</b> Visitor Analytics
             </h5>
             <br>
             <form action="{{ route('visitor.show') }}" method="GET">

                <div class="row">
                    <div class="col-sm-4 b-3">
                        <label for="from" class="ps-1">Chose City</label>
                       <select name="city" class="form-control " id="city">
                        <option value="" >Select City</option>
                        @foreach ($cities as $city)
                        <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>{{ ucfirst($city) }}</option>
                        @endforeach
                       </select>
                       {{-- show error --}}
                       @error('city')
                           <small class="text-danger ps-1">{{ $message }}</small>
                       @enderror
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label for="from">From</label>
                        <input id="from" name="s" value="{{ old('s') }}" class="form-control datetimepicker" type="text" placeholder="DD-MM-YYYY hh:mm A">
                        @error('s')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label for="from">From</label>
                        <input id="from" name="e" value="{{ old('e') }}" class="form-control datetimepicker" type="text" placeholder="DD-MM-YYYY hh:mm A">
                        @error('e')
                        <small class="text-danger ps-1">{{ $message }}</small>
                    @enderror
                    </div>
                </div>
            <p class="text-end pe-2"> <input type="submit" class="btn btn-sm btn-success" value="Show Analytics"></p>
            </form>
            <p class="text-center  muted small">Prarang Visitor Analytics City Wise posts visitors matrics</p>
        </div>
        {{-- @livewire('marketing.hit-box') --}}

        {{-- <section class="shadow p-2 rounded border">
de
        </section> --}}

    </section>


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
