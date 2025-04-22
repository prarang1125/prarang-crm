@extends('layouts.admin.admin')
@section('title', 'Auto Content')

@section('content')
<style>
    /* Contentx */
    #contentx {
        overflow-x: scroll;
        max-height: 80vh;
        /* transform:translatex(0px) translatey(0px); */
    }

    /* Images */
    #images {
        padding-right: 10px;
        padding-left: 7px;
        overflow-x: scroll;
        max-height: 80vh;
    }
    /* Bold */
.pt-3 tr .fw-bold{
 margin-bottom:0px;
}

/* Paragraph */
.pt-3 tr p{
 margin-bottom:1px !important;
}

table {
        width: 100%;
        border-collapse: collapse;
        font-family: sans-serif;
    }
    th {
        background-color: #f4f4f4;
        font-weight: bold;
        text-align: left;
    }
    td, th {
        border: 1px solid #ccc;
        padding: 12px;
        vertical-align: top;
    }
    p {
        margin: 4px 0;
    }
    /* Bold */
.pt-3 tr .fw-bold{
 font-weight:500 !important;
}

/* Strong Tag */
.pt-3 p strong{
 font-weight:600;
}

/* Paragraph */
.pt-3 tr p{
 font-size:13px;
}


</style>
<section class="p-3 pt-3">
    <h4 class="text-center">Filtered Posts</h4>
<div>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Post Info</th>
                <th>Tags</th>
                <th>Team</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $post)
                <tr>
                    {{-- Column 1: Post Info --}}
                    <td>
                        <p class="fw-bold">{{ $post->Title }}</p>
                        <p><strong>Geography:</strong> {{ $post->geography }}</p>
                        <p><strong>Emotion:</strong> {{ $post->emotionName }}</p>
                        <p><strong>Uploaded at:</strong> {{ $post->uploadDate }}</p>
                    </td>

                    {{-- Column 2: Tags --}}
                    <td>
                        <p><strong>English:</strong> {{ $post->tagEnglish }}</p>
                        <p><strong>Hindi:</strong> {{ $post->tagName }}</p>
                    </td>

                    {{-- Column 3: Team & Geography --}}
                    <td>
                        <p><strong>Maker:</strong> {{ $post->makerName }}</p>
                        <p><strong>Checker:</strong> {{ $post->checkerName }}</p>
                        <p><strong>Uploader:</strong> {{ $post->uploaderName }}</p>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<br><br>

    <div class="row">
        <div class="col-sm-7">
        <h4 class="text-center">Content</h4>
            <div id="contentx">
                @foreach ($contents as $content )
                {!! $content !!}
                @endforeach
            </div>
        </div>
        <div class="col-sm-5">
        <h4 class="text-center">Images</h4>
            <section id="images">
                <div class="row">
                    @foreach ($mainImg as $imgx )
                    <div class="col-6">
                        <img class="img-fluid w-20 m-2" src="{{$imgx}}" alt="">
                    </div>
                    @endforeach
                    @foreach ($images as $imgy )
                    <div class="col-6">
                        <img class="img-fluid w-20 m-2" src="{{$imgy}}" alt="">
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <div class="m-3">
        <h4>Links</h4>
        <ul>
            @foreach ($links as  $link)
            <li><a class="myLink" href="{!!$link!!}">{!!$link!!}</a></li>
            @endforeach
        </ul>
    </div>


</section>


<style>
    .link-status {
        font-size: 0.8em;
        margin-left: 8px;
    }
    .status-ok {
        color: green;
    }
    .status-dead {
        color: red;
    }
    .status-checking {
        color: orange;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const links = document.querySelectorAll('.myLink');
        const TIMEOUT = 7000;

        links.forEach(link => {
            const url = link.href;

            const statusSpan = document.createElement('span');
            statusSpan.className = 'link-status status-checking';
            statusSpan.innerText = '⏳ Checking...';
            link.insertAdjacentElement('afterend', statusSpan);

            checkRedirectedUrl(url, link, statusSpan);
        });

        function checkRedirectedUrl(url, linkElement, statusElement) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), TIMEOUT);

            fetch(url, {
                method: 'GET',
                redirect: 'follow',
                mode: 'no-cors', // changed to avoid blocking on short links
                signal: controller.signal
            }).then(response => {
                clearTimeout(timeoutId);

                const finalUrl = response.url || url;
                const status = response.status;

                if (status >= 200 && status < 400 || status === 0) {
                    // status 0 = opaque response from no-cors, assume it's okay
                    markAsAlive(statusElement, finalUrl, status || 'OK');
                } else {
                    markAsDead(statusElement, finalUrl, status);
                }
            }).catch(error => {
                clearTimeout(timeoutId);
                let message = (error.name === 'AbortError') ? 'Timeout' : error.message;
                markAsDead(statusElement, url, message);
            });
        }

        function markAsAlive(statusElement, resolvedUrl, status) {
            statusElement.className = 'link-status status-ok';
            statusElement.innerText = `✅ Live (${status})`;
        }

        function markAsDead(statusElement, resolvedUrl, reason) {
            statusElement.className = 'link-status status-dead';
            statusElement.innerText = `❌ Dead (${reason})`;
        }
    });
</script>

@endsection
