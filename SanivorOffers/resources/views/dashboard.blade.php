@include('layouts.sidebar')
<div class="content">
    <div class="container">
        <h1>Welcome {{ Auth::user()->username }}</h1>

    </div>
</div>