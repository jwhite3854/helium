<nav class="uk-navbar uk-navbar-container uk-margin">
    <div class="uk-navbar-left uk-margin-left">
        <ul class="uk-navbar-nav">
            <li><a href="<?php echo $this->url('') ?>">HOME</a></li>
        </ul>
    </div>
    <div class="uk-navbar-right uk-margin-right">
        <a class="uk-navbar-toggle uk-icon uk-navbar-toggle-icon" href="#" uk-navbar-toggle-icon="" aria-expanded="false">
            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <rect y="9" width="20" height="2"></rect>
                <rect y="3" width="20" height="2"></rect>
                <rect y="15" width="20" height="2"></rect>
            </svg>
        </a>
        <div class="uk-navbar-dropdown" uk-drop="mode: hover; cls-drop: uk-navbar-dropdown;" >
            <ul class="uk-nav uk-nav-default">
                <?php foreach ($this->menu('default') as $menuItem): ?>
                    <li><a href="<?php echo $this->url($menuItem) ?>"><?php echo ucfirst($menuItem) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>