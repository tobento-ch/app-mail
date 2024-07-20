<?php if (!$list->empty()) { ?>
<ul>
    <?php foreach($list->items() as $value) { ?>
        <li><?= $list->renderValue($view, $value) ?></li>
    <?php } ?>
</ul>
<?php } ?>