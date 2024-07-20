<!DOCTYPE html>
<html lang="<?= $view->esc($view->get('htmlLang', 'en')) ?>">
    <head>
        <?= $view->render('mail/inc/head') ?>
    </head>
    <body>
        <!--[if mso]><table><tr><td width="580"><![endif]-->
        <div class="container">
            <?= $view->render('mail/inc/header') ?>
            <div class="content">
                <?php foreach($blocks as $block) { ?>
                    <?= $block->render($view) ?>
                <?php } ?>
            </div>
            <?= $view->render('mail/inc/footer') ?>
        </div>
        <!--[if mso]></td></tr></table><![endif]-->
    </body>
</html>