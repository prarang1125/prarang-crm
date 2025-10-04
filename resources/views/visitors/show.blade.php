@extends('layouts.admin.admin')
@section('title', 'Account Checker Edit')

@section('content')
    <style>
        /* Rounded */
        .page-wrapper .pt-3 .rounded {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: normal;
        }

        /* Col 6 */
        .pt-3 .col-sm-6 {
            display: flex;
            justify-content: normal;
            align-items: center;
        }

        /* Rounded */
        .page-wrapper .pt-3 .rounded {
            padding-bottom: 7px !important;
        }

        /* Button */
        .text-end p a {
            font-weight: 300;
            font-size: 12px;
            text-transform: capitalize;
            padding-left: 4px;
            padding-right: 4px;
            padding-top: 0px;
            padding-bottom: 0px;
            margin-right: -8px;
            margin-left: 9px;
        }

        /* Paragraph */
        .modal-dialog form p {
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 700;
        }

        /* Span Tag */
        .modal-body .ordate span {
            border-style: solid;
            border-top-left-radius: 50%;
            border-top-right-radius: 50%;
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #1462c1;
            color: #ffffff;
        }

        /* Paragraph */
        .modal-dialog form p {
            margin-top: 4px;
        }

        /* Table Data */
        .table-sm tr td {
            font-size: 12px;
        }

        /* Th */
        .table-sm tr th {
            font-size: 12px;
        }

        /* Post table */
        .pt-3 .post-table {
            overflow: scroll;
            max-height: 60vh;
        }

        /* Th */
        .table-sm tr th {
            position: sticky;
            top: 0px;
            background-color: #ffffff;
        }
        /* Heading */
.overflow-hidden div h6{
 font-size:14px;
}
/* For Webkit Browsers (Chrome, Safari) */
::-webkit-scrollbar {
    width: 2px; /* Scrollbar width */
}

::-webkit-scrollbar-thumb {
    background-color: #888; /* Scrollbar color */
    border-radius: 5px; /* Rounded corners */
}

::-webkit-scrollbar-track {
    background: transparent; /* Track background */
}

/* For Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: #888 transparent;
}
.custom-scroll {
    overflow-y: auto; /* Enable scrolling */
    max-height: 400px; /* Set height */
}

.custom-scroll::-webkit-scrollbar {
    width: 1px;
}

.custom-scroll::-webkit-scrollbar-thumb {
    background-color: #888;
}
/* Small Tag */
.overflow-hidden .card-body p{
 white-space:normal;
}

/* Heading */
.overflow-hidden .card-body h6{
 margin-bottom:5px !important;
}

/* Small Tag */
.overflow-hidden .card-body p{
 margin-bottom:7px;
 font-weight:600;
 color:#076bce;
 font-size:12px;
 /* padding-bottom: 5px; */
 min-height: 35px;

}
/* Post table */
.wrapper .page-wrapper .pt-3 span .p-2 .row .card .post-table{
 height:594px !important;
}

/* Post table */
.pt-3 span .post-table{
 max-height:540px;
}

@keyframes blurEffect {
    0% {
        filter: blur(0px);
    }
    25% {
        filter: blur(4px);
    }
    50% {
        filter: blur(8px);
    }
    100% {
        filter: blur(12.28px);
    }
}

@keyframes shimmer {
    0% {
        background-position: -200px 0;
    }
    100% {
        background-position: 200px 0;
    }
}

/* Blur effect on loading */
.loading-effect {
    animation: blurEffect 0.3s ease-in-out forwards;
    pointer-events: none; /* Disable interactions */
    position: relative;
}

/* Placeholder shimmer effect */
.loading-placeholder {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, #f6f7f8 0%, #edeef1 50%, #f6f7f8 100%);
    background-size: 400px 100%;
    animation: shimmer 1.5s infinite linear;
    opacity: 0.7;
    border-radius: 5px;
}

.post-frem{
 overflow:scroll;
 max-height:526px;
}
/* Post date */
.pt-3 span .post-date{
 display:flex;
 justify-content:flex-end;
 align-items:center;
}

/* Card */
.mt-2 .card{
 background-color:#ff2828;
 background-image:linear-gradient(to right, #ffeeee 0%, #ddefbb 100%);
}

/* Rounded */
.pt-3 span .rounded{
 background-image:linear-gradient(to right, #bbd2c5 0%, #536976 33%, #292e49 100%);
}

/* Bold */
.text-end p .fw-bold{
 color:#ffffff;
}

/* Paragraph */
.pt-3 .text-end p{
 color:#ffffff;
 font-family:Verdana,Geneva,sans-serif;
}

/* Bold */
.pt-3 p.fw-bold{
 color:#086dd2;
}



    </style>
    <section class="p-2 pt-3">
        @livewire('visitors.top-cards', ['city' => $city, 'startDate' => $startDate, 'endDate' => $endDate])
    </section>





@endsection
