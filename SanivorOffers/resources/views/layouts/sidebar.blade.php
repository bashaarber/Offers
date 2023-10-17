<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #454d55;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .content {
            margin-left: 250px;
            padding: 15px;
        }

        .sublinks {
            display: none;
            padding-left: 30px;
        }

        h4 {
            text-align: center;
            color:burlywood;
            margin-bottom: 30px;
        }
        .dropdown-link {
        padding: 15px 25px;
        text-decoration: none;
        font-size: 18px;
        color: white;
        display: block;
        transition: 0.3s;
    }

    .dropdown-link:hover {
        background-color: #454d55;
    }

    .dropdown-link i {
        margin-right: 10px;
    }
    </style>
</head>

<body>
    <div class="sidebar">
        <h4>Welcome {{ Auth::user()->username }}</h4>
        <a href="{{ url('/') }}"><i class="fas fa-home"></i> Home</a>
        @if (Route::has('login'))
        @auth
        @if (auth()->user()->role === 'admin')
        <a href="javascript:void(0);" class="toggle-sublinks" data-target="home"><i class="fa-solid fa-bars"></i> Settings</a>
        <div class="sublinks" id="home-sublinks">
        <a href="{{ url('/material') }}">Materials</a>
        <a href="{{ url('/element') }}">Elements</a>
        <a href="{{ url('/group_element') }}">GroupElements</a>
        <a href="{{ url('/organigram') }}">Organigram</a>
        <a href="{{ url('/coefficient') }}">Coefficient</a>
        </div>

        <a href="{{ url('/users') }}"><i class="fas fa-user"></i>All Users</a>
        <!-- <a href="{{ route('register') }}"><i class="fas fa-plus"></i>Register User</a> -->
        @endif
        @endif
        @endauth

        <x-dropdown-link :href="route('profile.edit')"><i class="fa-solid fa-pen"></i>
            {{ __('Profile') }}
        </x-dropdown-link>

        <!-- Authentication -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault();
             this.closest('form').submit();"><i class="fa-sharp fa-solid fa-arrow-right-from-bracket"></i>
                {{ __('Log Out') }}
            </x-dropdown-link>
        </form>
    </div>

    <div class="content">

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        // Add an event listener to toggle sublinks
        document.querySelectorAll('.toggle-sublinks').forEach(link => {
            link.addEventListener('click', () => {
                const targetId = link.getAttribute('data-target');
                const targetSublinks = document.getElementById(`${targetId}-sublinks`);
                if (targetSublinks) {
                    targetSublinks.style.display = (targetSublinks.style.display === 'none' || targetSublinks.style.display === '') ? 'block' : 'none';
                }
            });
        });
    </script>
</body>

</html>
