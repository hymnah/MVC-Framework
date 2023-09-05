<?php extendPage('Globals/box-layout.php'); ?>

<?php blockStart('css') ?>
    <link rel="stylesheet" href="/assets/css/test/test.css">
<?php blockEnd() ?>

<?php blockStart('body') ?>
    <div class="header">
        <h1>HELLO <?= $name ?></h1>
        <a href="<?= $testlink ?>">test link</a>
    </div>
    <?= form_render($form['form_start']) ?>

        <?= form_render($form['test_field']) ?>
        <div class="error">
            <?= form_render($form['test_field:error']) ?>
        </div>

        <?= form_render($form['test_field_2']) ?>
        <div class="error">
            <?= form_render($form['test_field_2:error']) ?>
        </div>
        <button>Save</button>
    <?= form_render($form['form_end'])?>

<?php blockEnd() ?>