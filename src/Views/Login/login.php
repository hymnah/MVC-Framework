<?php extendPage('Globals/box-layout.php'); ?>

<?php blockStart('css') ?>
    <link rel="stylesheet" href="/assets/css/login/login.css">
<?php blockEnd() ?>

<?php blockStart('body') ?>
    <div class="body-main">
        <div class="center-div">
            <div class="form-wrap">
                <div class="sec">
                    <div class="sec-12">
                        <?= form_render($form['form_start']) ?>
                        <div class="field-wrap">
                            <?= form_render($form['username']) ?>
                        </div>
                        <div class="field-wrap">
                            <?= form_render($form['password']) ?>
                            <div id="show-pass" class="field-icon icon-right">
                                <i class="fi fi-rr-lock"></i>
                            </div>
                        </div>
                        <button type="submit">Login</button>
                        <?= form_render($form['form_end'])  ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php blockEnd() ?>


<?php blockStart('js') ?>
    <script src="/assets/js/login/login.js"></script>
<?php blockEnd() ?>
