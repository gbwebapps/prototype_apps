<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php if((isset($beforeStyle)) && ( ! empty($beforeStyle))): 
            foreach($beforeStyle as $comment => $style):
                echo '<!-- ' . $comment . ' -->' . "\n\t\t";
                echo '<link rel="stylesheet" href="' . base_url($style . '.css') . '">' . "\n\t\t";
            endforeach;
        endif; ?>

        <!-- App CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/backend/style.css'); ?>">

        <?php if((isset($afterStyle)) && ( ! empty($afterStyle))): 
            foreach($afterStyle as $comment => $style):
                echo '<!-- ' . $comment . ' -->' . "\n\t\t";
                echo '<link rel="stylesheet" href="' . base_url($style . '.css') . '">' . "\n\t\t";
            endforeach;
        endif; ?>

        <title>Hello, world!</title>
    </head>
<body>
    <div id="menuTop">
        <?= $this->include('backend/template/menuTopView'); ?>
    </div>
    
    <?= $this->include('backend/template/sectionView'); ?>

    <?= $this->include('backend/template/messagesView'); ?>

    <?= $this->include('backend/template/linksBarView'); ?>
        