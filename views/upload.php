<?php
use \app\core\Application;
use \app\core\form\Form;
?>
<h2>Upload file to the server</h2>
<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($model,'imgTitle') ?>
    <?php echo $form->field($model,'watermark') ?>
    <?php if (Application::Auth()): ?>
        <?php echo $form->field($model, 'author', Application::$app->user->login) ?>
    <?php else: ?>
        <?php echo $form->field($model, 'author') ?>
    <?php endif; ?>
    <?php echo $form->field($model,'imgFile') ->fileField() ?>
    <?php if (Application::Auth()): ?>
        <?php echo $form->field($model,'private', 'priv') ->checkBox(false) ?>
    <?php endif ?>
    <button type="submit" class="btn btn-primary">Submit</button>
    <?php Form::end(); ?>
<!--<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Image title</label>
        <input type="text" class="form-control" name="imgTitle" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Image description</label>
        <textarea class="form-control" name="imgDescription" id="exampleInputPassword1"></textarea>
    </div>
    <div class="mb-3">
        <label class="form-check-label" for="exampleCheck1">Actual image file</label>
        <input type="file" name="imgFile" class="form-control" id="exampleCheck1">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>-->