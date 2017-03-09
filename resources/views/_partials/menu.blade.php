<ul class="dropdown-menu" role="menu">
  <li><a href="/dashboard">Dashboard</a></li>
  @can('manage-users-list')
    <li><a href="/manage/users/list">Manage Users</a></li>
  @endcan
  @can('manage-posts')
    <li><a href="/manage/posts/list">Manage Posts</a></li>
  @endcan
  @can('manage-comments')
    <li><a href="/manage/comments/list">Manage Comments</a></li>
  @endcan
  <li><a href="/myaccount">My Account</a></li>
  <li> <a href="{{ route('logout') }}" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
            Logout
        </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> {{ csrf_field() }} </form>
  </li>
</ul>
