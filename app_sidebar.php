<?php 
    ob_start();

    if (!isset($_SESSION['sess_user'])){
        $_SESSION['login_reject'] = "We need to verify your account information to allow access this resource.";

        header("Location: index.php");
        exit();
    }
    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_PATH);
    $components = explode('/', $path);
    $route_arr = explode('.', $components[2]);
    $route = $route_arr[0];

    $categoryNav = ["category_list", "category_add","category_edit"];
    $idealistNav = ["idea_category_list", "idea_latest_list", "idea_trending_list", "idea", "idea_list"];
    $staffNav = ["staff_list", "staff"];
    $roleNav = ["role_list", "role_add","role_edit"];

    $sess_user_pname = $_SESSION['sess_user']['pname'];
    $sess_user_type = $_SESSION['sess_user']['type'];
    
?>
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo text-center">
                <img src="assets/img/logo.png" class="img-fluid logo">
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">

        <li class="menu-item <?php if ($route=="dashboard") {echo "active"; } else  {echo "";}?>">
            <a href="dashboard.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-alt"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Brainstrom</span>
        </li>
        <li class="menu-item <?php if (in_array($route,$idealistNav)) {echo "active"; } else  {echo "";}?>">
            <a href="idea_category_list.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-extension"></i>
                <div data-i18n="Idea List">Idea Lists</div>
            </a>
        </li>

        <li class="menu-item <?php if ($route=="idea_add") {echo "active"; } else  {echo "";}?>">
            <a href="idea_add.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-plus"></i>
                <div data-i18n="New Idea">New Idea</div>
            </a>
        </li>
        <?php if($sess_user_pname == 'QA Manager'): ?>
        <li class="menu-item <?php if (in_array($route,$categoryNav)) {echo "active"; } else  {echo "";}?>">
            <a href="category_list.php" class="menu-link ">
                <i class="menu-icon tf-icons bx bx-category-alt"></i>
                <div data-i18n="New Idea">Category</div>
            </a>
        </li>
        <?php endif; ?>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Personal</span>
        </li>
        <li class="menu-item <?php if ($route=="own_idea_list") {echo "active"; } else  {echo "";}?>">
            <a href="own_idea_list.php" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-quote-alt-left"></i>
                <div data-i18n="Own Idea">Own Idea</div>
            </a>
        </li>

        <li class="menu-item <?php if ($route=="own_comment_list") {echo "active"; } else  {echo "";}?>">
            <a href="own_comment_list.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-comment-dots"></i>
                <div data-i18n="Own Comment">Own Comment</div>
            </a>
        </li>

        <?php 
            if($sess_user_pname == 'QA Manager' || $sess_user_type == "QAC"): 
        ?>


        
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Staff</span>
        </li>
        <li class="menu-item <?php if (in_array($route,$staffNav)) {echo "active"; } else  {echo "";}?>">
            <a href="staff_list.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div data-i18n="Staff">Staff</div>
            </a>
        </li>

        <?php 
            endif;
            if($sess_user_pname == 'QA Manager'): 
        ?>


        <li class="menu-item <?php if ($route=="staff_add") {echo "active"; } else  {echo "";}?>">
            <a href="staff_add.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                <div data-i18n="New Staff">New Staff</div>
            </a>
        </li>
        <li class="menu-item <?php if (in_array($route,$roleNav)) {echo "active"; } else  {echo "";}?>">
            <a href="role_list.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-alt-2"></i>
                <div data-i18n="Roles"> Roles </div>
            </a>
        </li>

        <li class="menu-item <?php if ($route=="department_list") {echo "active"; } else  {echo "";}?>">
            <a href="department_list.php" class="menu-link">
                <i class="menu-icon tf-icons bx bx-sitemap"></i>
                <div data-i18n="Departments"> Departments </div>
            </a>
        </li>

        <?php endif; ?>

        
    </ul>
</aside>