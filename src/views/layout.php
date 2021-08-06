<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $data['meta'] ?>
    <link rel="stylesheet" href=" <?php echo $this->assets('css/uikit.min.css') ?>" />
    <?php if ( count( $data['more_stylesheets'] ) ): ?>
        <?php foreach ( $data['more_stylesheets'] as $stylesheet ): ?>
            <link rel="stylesheet" href="<?php echo $this->assets('css/'.$stylesheet['href']) ?>" media="<?php echo $stylesheet['media'] ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href=" <?php echo $this->assets('css/style.css') ?>" />
    <script src="<?php echo $this->assets('js/uikit.min.js') ?>"></script>
    <script src="<?php echo $this->assets('js/uikit-icons.min.js') ?>"></script>
</head>
    <body>
    <?php echo $data['nav'] ?>
    <div class="uk-container uk-container-xsmall uk-margin-medium">
        <?php echo $data['content'] ?>
    </div>
    <?php if ( count( $data['more_scripts'] ) ): ?>
        <?php foreach ( $data['more_scripts'] as $script ): ?>
            <script src="<?php echo $this->assets('js/'.$script['src']) ?>" <?php echo $script['async'] ?> <?php echo $script['defer'] ?> ></script>
        <?php endforeach; ?>
    <?php endif; ?>
    </body>
</html>
