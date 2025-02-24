<div class="theme-setting-wrapper">
  <div id="settings-trigger"><i class="fas fa-fill-drip"></i></div>
  <div id="theme-settings" class="settings-panel">
    <i class="settings-close fa fa-times"></i>
    <p class="settings-heading">SIDEBAR SKINS</p>
    <div class="sidebar-bg-options selected" id="sidebar-light-theme">
      <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
    </div>
    <div class="sidebar-bg-options" id="sidebar-dark-theme">
      <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
    </div>
    <p class="settings-heading mt-2">HEADER SKINS</p>
    <div class="color-tiles mx-0 px-4">
      <div class="tiles primary"></div>
      <div class="tiles success"></div>
      <div class="tiles warning"></div>
      <div class="tiles danger"></div>
      <div class="tiles info"></div>
      <div class="tiles dark"></div>
      <div class="tiles default"></div>
    </div>
  </div>
</div>
<!-- partial -->
<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <div class="nav-link">
        <div class="profile-image">
          <img src="components/assets/images/samples/300x300/6.jpg" alt="image" />
        </div>
        <div class="profile-name">
          <p class="name">
            Welcome <?= ucfirst($fetch_profile['name']); ?>
          </p>
          <p class="designation">
            Super Admin
          </p>
        </div>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="dashboard.php">
        <i class="fa fa-home menu-icon"></i>
        <span class="menu-title">Dashboard</span>
      </a>
    </li>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="orders.php">
        <i class="fa fa-file-invoice-dollar menu-icon"></i>
        <span class="menu-title">Orders</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="users.php">
        <i class="fa fa-user menu-icon"></i>
        <span class="menu-title">Users</span>
      </a>
    </li>
  </ul>
</nav>
