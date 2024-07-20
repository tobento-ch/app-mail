<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?= $view->esc($message->subject()) ?></title>
<?php
// render assets only if inline styles are not used:
if (!$withInlineCssStyles) {
    echo $view->assets()->render();
}
// assets can be included in every subview too.
$view->asset('assets/mail/mail.css');
?>