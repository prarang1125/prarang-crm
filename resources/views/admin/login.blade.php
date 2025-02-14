@include('layouts.partial.head')
@section('title', 'Login')
<style>
    /* Card body */
.d-flex .card .card-body{
 border-style:none;
 box-shadow:0px 0px 23px -11px #4c5258;
 border-top-left-radius:8px;
 border-width:0px;
 padding-left:48px !important;
 padding-right:49px !important;
 border-top-right-radius:13px;
 border-bottom-left-radius:15px;
 border-bottom-right-radius:17px;
}

/* Card */
.d-flex .card{
 border-style:none !important;
}

/* Card */
.d-flex .container .justify-content-center .col-sm-10 .card{
 border-width:18px !important;
}

/* Button */
.card-body .d-grid .btn-lg{
 background-color:#265ab4;
}

/* Input last enter email */
#inputLastEnterEmail{
 font-size:18px;
}

/* Column 12/12 */
.card-body .col-12{
 margin-top:9px;
}

/* Input last enter password */
#inputLastEnterPassword{
 padding-top:0px;
 padding-bottom:0px;
 transform:translatex(0px) translatey(0px);
 margin-left:-1px;
}

/* Button */
.card-body .d-grid .btn-lg{
 transform:translatex(0px) translatey(0px);
 padding-top:2px;
 padding-bottom:3px;
 margin-top:8px;
 font-size:18px;
}

/* Card body */
.d-flex .card .card-body{
 padding-top:3px !important;
 padding-bottom:33px !important;
 transform:translatex(0px) translatey(0px);
}

/* Italic Tag */
.card-body .card-title i{
 height:60px;
}

/* Text dark */
.card-body .card-title h5.text-dark{
 margin-top:0px !important;
 margin-bottom:32px !important;
}



</style>
<div class="d-flex align-items-center min-vh-100 bg-light-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card border-top border-0 border-4 border-dark" style="margin: 20px">
                    <div class="card-body p-4">
                        <div class="card-title text-center">
                            <i class="bx bxs-user-circle text-dark font-50"></i>
                            <h5 class="mb-5 mt-2 text-dark">Admin Login</h5>
                        </div>
                        <hr>
                        <form class="row g-3" action="{{ route('admin.authenticate') }}" method="POST">
                            @csrf
                            <div class="col-12">
                                <label for="inputLastEnterEmail" class="form-label">Enter Email</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="bx bxs-user"></i></span>
                                    <input type="text" value="{{ old('emailId') }}" name="email" class="form-control border-start-1 @error('email') is-invalid @enderror" id="inputLastEnterEmail" placeholder="Enter Email">
                                    @error('email')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <label for="inputLastEnterPassword" class="form-label">Enter Password</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="bx bxs-lock-open"></i></span>
                                    <input type="password" name="password" class="form-control border-start-1 @error('password') is-invalid @enderror" id="inputLastEnterPassword" placeholder="Enter Password">
                                    @error('password')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-12">
                                <label for="inputLanguage" class="form-label">Select Language</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-transparent"><i class="bx bx-search"></i></span>
                                    <select class="single-select form-control border-start-1 border-end-1 @error('language') is-invalid @enderror" id="languageSelect" name="language">
                                        <option value="">Select Default Language</option>
                                        <option value="english">English</option>
                                        <option value="hindi">Hindi</option>
                                    </select>
                                    @error('language')
                                        <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-dark btn-lg px-5"><i class="bx bxs-lock-open"></i> Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.partial.script')

