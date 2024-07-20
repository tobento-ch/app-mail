<?php if (!$list->empty()) { ?>
<div class="my-m">
    <?php foreach($list->items() as $key => $value) { ?>
        <div><?= $list->renderValue($view, $key) ?></div>
        <div class="text-700 mt-xxs mb-s"><?= $list->renderValue($view, $value) ?></div>
    <?php } ?>
</div>
<?php } ?>