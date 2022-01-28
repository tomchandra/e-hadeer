<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url("/") ?>">e-Hadeer</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">EP</a>
        </div>
        <ul class="sidebar-menu">
            <?php if (count($menu) > 0) : ?>
                <li class="menu-header">Menu</li>
                <?php foreach ($menu as $key => $parent) : ?>
                    <?php if (count($parent['menu_child']) > 0) : ?>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-folder-open"></i> <span><?= $parent['menu_name'] ?></span></a>
                            <ul class="dropdown-menu">
                                <?php foreach ($parent['menu_child'] as $key => $child) : ?>
                                    <li><a class="nav-link" href="<?= base_url("app/" . $child['menu_url']) ?>"><?= $child['menu_name'] ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li><a class="nav-link" href="<?= base_url("app/" . $parent['menu_url']) ?>"><i class="fa fa-folder-open"></i> <span><?= $parent['menu_name'] ?></span></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </aside>
</div>