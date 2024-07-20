<div class="mb-s">
    <?= $image->tag()->attr(name: 'src', value: $message->embed($image->src(), $image->mimeType())) ?>
</div>