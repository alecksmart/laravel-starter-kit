<ul class="dropdown-menu" role="menu">
    <li><a href="/home">Dashboard</a></li>
    <li><a href="/myaccount">My Account</a></li>
    <li><a href="/posts">List of Posts</a></li>
    <li><a href="/comments">List of Comments</a></li>
    <li>
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>
</ul>