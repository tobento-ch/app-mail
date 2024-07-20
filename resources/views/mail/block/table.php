<?php if (!$table->empty()) { ?>
<table>
    <?php if ($table->headers()) { ?>
    <tr>
    <?php foreach($table->headers() as $heading) { ?>
        <th><?= $table->renderValue($view, $heading, $heading) ?></th>
    <?php } ?>
    </tr>
    <?php } ?>
    <?php if ($table->rows()) { ?>
        <?php foreach($table->rows() as $row) { ?>
            <tr>
            <?php foreach($table->verifyRow($row) as $name => $value) { ?>
                <td><?= $table->renderValue($view, $value, $name) ?></td>
            <?php } ?>
            </tr>
        <?php } ?>
    <?php } ?>
</table>
<?php } ?>