<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
<style>
    * { font-family: 'Inter', sans-serif; }
    body { margin: 0; padding: 0; background-color: #f0f2f5; }

    .sidebar {
        height: 100vh;
        width: 220px;
        position: fixed;
        top: 0;
        left: 0;
        background: linear-gradient(180deg, #1a1d23 0%, #2d3748 100%);
        padding-top: 0;
        overflow-y: auto;
        z-index: 1000;
        border-right: 1px solid rgba(255,255,255,0.06);
        display: flex;
        flex-direction: column;
    }

    .sidebar-brand {
        padding: 16px 14px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        margin-bottom: 4px;
        flex-shrink: 0;
    }

    .sidebar-brand h4 {
        margin: 0;
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: -0.5px;
        text-align: left;
        background: none;
    }

    .sidebar-brand span {
        color: #60a5fa;
    }

    .sidebar-section {
        padding: 0 8px;
        margin-bottom: 4px;
    }

    .sidebar-section-label {
        padding: 6px 10px 2px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: rgba(255,255,255,0.35);
    }

    .sidebar a, .sidebar .dropdown-link-custom {
        padding: 7px 12px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        color: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        border-radius: 6px;
        margin: 1px 0;
        cursor: pointer;
    }

    .sidebar a:hover, .sidebar .dropdown-link-custom:hover {
        background: rgba(255,255,255,0.08);
        color: #fff;
        text-decoration: none;
    }

    .sidebar a.active {
        background: rgba(96, 165, 250, 0.15);
        color: #60a5fa;
    }

    .sidebar a i, .sidebar .dropdown-link-custom i {
        margin-right: 10px;
        width: 18px;
        text-align: center;
        font-size: 13px;
    }

    .sublinks {
        display: none;
        padding-left: 8px;
    }

    .sublinks a {
        font-size: 12px;
        padding: 5px 12px 5px 28px;
        color: rgba(255,255,255,0.55);
    }

    .sublinks a:hover {
        color: #60a5fa;
    }

    .sidebar-scrollable {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }

    .sidebar-footer {
        flex-shrink: 0;
        width: 100%;
        padding: 8px;
        border-top: 1px solid rgba(255,255,255,0.08);
        background: rgba(0,0,0,0.2);
    }

    .sidebar-footer a, .sidebar-footer .dropdown-link-custom {
        font-size: 12px;
        padding: 6px 10px;
    }

    .lang-switcher {
        display: flex;
        justify-content: center;
        gap: 6px;
        padding: 6px 0 2px;
    }

    .lang-switcher a {
        padding: 3px 10px !important;
        font-size: 11px !important;
        border-radius: 6px;
        background: rgba(255,255,255,0.06);
        justify-content: center;
    }

    .lang-switcher a:hover {
        background: rgba(96, 165, 250, 0.2);
    }

    .content {
        margin-left: 220px;
        min-height: 100vh;
    }

    /* Modern table styling */
    .table { border-collapse: separate; border-spacing: 0; }
    .table thead th {
        background: #1a1d23;
        color: #fff;
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 8px 12px;
        border: none;
    }
    .table tbody td {
        padding: 8px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #e5e7eb;
        font-size: 13px;
    }
    .table tbody tr:hover { background-color: #f8fafc; }
    .table-striped tbody tr:nth-of-type(odd) { background-color: #fafbfc; }
    .table-striped tbody tr:nth-of-type(odd):hover { background-color: #f0f4f8; }

    /* Modern button styling */
    .btn { border-radius: 6px; font-weight: 500; font-size: 12px; padding: 4px 10px; transition: all 0.2s; }
    .btn-primary { background: #3b82f6; border-color: #3b82f6; }
    .btn-primary:hover { background: #2563eb; border-color: #2563eb; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
    .btn-danger { background: #ef4444; border-color: #ef4444; }
    .btn-danger:hover { background: #dc2626; border-color: #dc2626; }
    .btn-warning { background: #f59e0b; border-color: #f59e0b; color: #fff; }
    .btn-warning:hover { background: #d97706; border-color: #d97706; color: #fff; }
    .btn-secondary { background: #6b7280; border-color: #6b7280; }
    .btn-secondary:hover { background: #4b5563; border-color: #4b5563; }
    .btn-info { background: #06b6d4; border-color: #06b6d4; color: #fff; }
    .btn-info:hover { background: #0891b2; border-color: #0891b2; color: #fff; }
    .btn-dark { background: #374151; border-color: #374151; }
    .btn-dark:hover { background: #1f2937; border-color: #1f2937; }

    /* Modern card styling */
    .card { border: none; box-shadow: 0 1px 8px rgba(0,0,0,0.08); border-radius: 10px; }
    .card-body { padding: 16px; }

    /* Modern form styling */
    .form-control {
        border-radius: 6px;
        border: 1.5px solid #d1d5db;
        padding: 6px 10px;
        font-size: 13px;
        transition: all 0.2s;
    }
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
    }

    /* Section headers */
    h6 {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        padding: 8px 14px;
        color: #fff;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 12px;
    }

    /* Search styling */
    .search-form .input-group { max-width: 280px; }
    .search-form .form-control { border-radius: 6px 0 0 6px; }
    .search-form .btn { border-radius: 0 6px 6px 0; }

    /* Pagination styling */
    .pagination { margin-top: 12px; }
    .page-link { border-radius: 6px; margin: 0 2px; border: none; color: #3b82f6; font-size: 13px; }
    .page-item.active .page-link { background: #3b82f6; border-color: #3b82f6; }

    /* Responsive sidebar */
    @media (max-width: 992px) {
        .sidebar {
            width: 180px;
        }
        .content {
            margin-left: 180px;
        }
        .sidebar-brand h4 { font-size: 16px; }
        .sidebar a, .sidebar .dropdown-link-custom { font-size: 12px; padding: 6px 10px; }
    }
    @media (max-width: 768px) {
        .sidebar {
            width: 60px;
            overflow: visible;
        }
        .content {
            margin-left: 60px;
        }
        .sidebar-brand h4 span { display: none; }
        .sidebar-brand h4::after { content: 'S'; }
        .sidebar-brand h4 { font-size: 0; }
        .sidebar-brand h4::after { font-size: 18px; color: #60a5fa; }
        .sidebar-section-label { display: none; }
        .sidebar a span, .sidebar .dropdown-link-custom span { display: none; }
        .sidebar a, .sidebar .dropdown-link-custom {
            justify-content: center;
            padding: 10px;
        }
        .sidebar a i, .sidebar .dropdown-link-custom i {
            margin-right: 0;
            font-size: 16px;
        }
        .sublinks { padding-left: 0; }
        .sublinks a { padding: 8px 10px; justify-content: center; }
        .lang-switcher a { padding: 3px 6px !important; font-size: 10px !important; }
        .sidebar-footer a span, .sidebar-footer .dropdown-link-custom span { display: none; }
    }
</style>

<div class="sidebar">
    <div class="sidebar-brand">
        <h4>Sanivor <span>Offers</span></h4>
    </div>

    <div class="sidebar-scrollable">
        <div class="sidebar-section">
            <div class="sidebar-section-label">Main</div>
            <a href="{{ url('/offert') }}" class="{{ request()->is('offert*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice"></i><span>@lang('public.offert')</span>
            </a>
        </div>

        @if (Route::has('login'))
            @auth
                @if (auth()->user()->role === 'admin')
                @php
                    $showSettingsManagementNav = request()->routeIs(
                        'dashboard',
                        'offert.index',
                        'profile.edit',
                        'material_piece.*',
                        'material.*',
                        'element.*',
                        'group_element.*',
                        'organigram.*',
                        'coefficient.*',
                        'client.*',
                        'user.index',
                        'register',
                    );
                @endphp
                @if ($showSettingsManagementNav)
                <div class="sidebar-section">
                    <div class="sidebar-section-label">@lang('public.settings')</div>
                    <a href="javascript:void(0);" class="toggle-sublinks dropdown-link-custom" data-target="home">
                        <i class="fa-solid fa-gear"></i><span>@lang('public.settings')</span>
                        <i class="fa-solid fa-chevron-down" style="margin-left:auto; font-size:10px;"></i>
                    </a>
                    <div class="sublinks" id="home-sublinks">
                        <a href="{{ url('/material_piece') }}"><i class="fa-solid fa-puzzle-piece"></i><span>@lang('public.material_pieces')s</span></a>
                        <a href="{{ url('/material') }}"><i class="fa-solid fa-cubes"></i><span>@lang('public.materials')</span></a>
                        <a href="{{ url('/element') }}"><i class="fa-solid fa-layer-group"></i><span>@lang('public.elements')</span></a>
                        <a href="{{ url('/group_element') }}"><i class="fa-solid fa-object-group"></i><span>@lang('public.group_elements')</span></a>
                        <a href="{{ url('/organigram') }}"><i class="fa-solid fa-sitemap"></i><span>@lang('public.organigram')</span></a>
                        <a href="{{ url('/coefficient') }}"><i class="fa-solid fa-calculator"></i><span>@lang('public.coefficient')</span></a>
                    </div>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-label">Management</div>
                    <a href="{{ url('/users') }}" class="{{ request()->is('users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i><span>@lang('public.users')</span>
                    </a>
                    <a href="{{ url('/client') }}" class="{{ request()->is('client*') ? 'active' : '' }}">
                        <i class="fa fa-address-card"></i><span>@lang('public.clients')</span>
                    </a>
                </div>
                @endif
                @else
                {{-- Seller: show only Coefficient --}}
                <div class="sidebar-section">
                    <div class="sidebar-section-label">@lang('public.settings')</div>
                    <a href="{{ url('/coefficient') }}" class="{{ request()->is('coefficient*') ? 'active' : '' }}">
                        <i class="fa-solid fa-calculator"></i><span>@lang('public.coefficient')</span>
                    </a>
                </div>
                @endif
            @endauth
        @endif
    </div>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}">
            <i class="fa-solid fa-user-circle"></i><span>@lang('public.profile')</span>
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket"></i><span>@lang('public.logout')</span>
            </a>
        </form>
        <div class="lang-switcher">
            <a href="/locale/en"><i class="fa-solid fa-globe" style="margin-right:4px;"></i>EN</a>
            <a href="/locale/de"><i class="fa-solid fa-globe" style="margin-right:4px;"></i>DE</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-sublinks').forEach(link => {
            link.addEventListener('click', () => {
                const targetId = link.getAttribute('data-target');
                const targetSublinks = document.getElementById(`${targetId}-sublinks`);
                const chevron = link.querySelector('.fa-chevron-down');
                if (targetSublinks) {
                    const isHidden = targetSublinks.style.display === 'none' || targetSublinks.style.display === '';
                    targetSublinks.style.display = isHidden ? 'block' : 'none';
                    if (chevron) {
                        chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0)';
                        chevron.style.transition = 'transform 0.2s ease';
                    }
                }
            });
        });
    });
</script>
