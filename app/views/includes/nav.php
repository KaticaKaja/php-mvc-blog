<nav class="navbar navbar-expand-md  navbar-light bg-light mb-4">
    <div class="container">
      <a href="<?=URLROOT.$data['pages']; ?>" class="navbar-brand <?php echo (LOCATION == '' || LOCATION == 'pages' || LOCATION == 'pages/index') ? 'text-primary' : ''; ?>">TechTalk</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav">
          <?php if(isset($_SESSION['user_id'])): ?>
            <li class="nav-item px-2">
              <a class="nav-link <?php echo (LOCATION == 'posts') ? 'text-primary' : ''; ?>" href="<?=URLROOT.$data['posts']; ?>">My Posts</a>
            </li>
          <?php endif; ?>
          <?php if(isset($_SESSION['user_id']) && ($_SESSION['user_type'] == 1)): ?>
              <li class="nav-item">
                  <a class="nav-link <?php echo (LOCATION == 'admin') ? 'text-primary' : ''; ?>" href="<?=URLROOT.$data['admin']; ?>">Admin</a>
              </li>
          <?php endif; ?>
        </ul>

        <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION['user_id'])):?>
          <li class="nav-item dropdown mr-3">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              <i class="fas fa-user"></i> Welcome <?=$_SESSION['user_firstName']; ?>
            </a>
            <div class="dropdown-menu">
              <a href="<?=URLROOT; ?>" class="dropdown-item <?php echo (LOCATION == '' || LOCATION == 'pages' || LOCATION == 'pages/index') ? 'text-primary' : ''; ?>">
                <i class="fas fa-home"></i> Home
              </a>
              <a href="<?=URLROOT.$data['users/settings'].'/'.$_SESSION['user_id']; ?>" class="dropdown-item <?php echo (LOCATION == 'users/settings/'.$_SESSION['user_id']) ? 'text-primary' : ''; ?>">
                <i class="fas fa-cog"></i> Settings
              </a>
            </div>
          </li>
          <li class="nav-item">
            <a href="<?=URLROOT.$data['users/logout']; ?>" class="nav-link">
              <i class="fas fa-user-times"></i> Logout
            </a>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a href="<?=URLROOT; ?>" class="nav-link <?php echo (LOCATION == '' || LOCATION == 'pages' || LOCATION == 'pages/index') ? 'text-primary' : ''; ?>">
              <i class="fas fa-home"></i></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=URLROOT.$data['users/register']; ?>" class="nav-link <?php echo (LOCATION == 'users/register') ? 'text-primary' : ''; ?>">
              <i class="fas fa-user-plus"></i> Register
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=URLROOT.$data['users/login']; ?>" class="nav-link <?php echo (LOCATION == 'users/login') ? 'text-primary' : ''; ?>">
              <i class="fas fa-user-check"></i> Login
            </a>
          </li>
        <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>