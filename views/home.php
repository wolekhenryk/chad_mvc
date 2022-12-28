<?php
use app\models\Post;
use app\core\Application;
use app\core\form\Form;
?>
<h1>HOME</h1>
<h3>WELCOME</h3>
<?php $formFav = Form::begin('/', 'post', 'homePageForm') ?>
<div class="d-flex flex-wrap justify-content-around">
    <?php foreach ($images as $img): ?>
        <?php if (!$img['private']): ?>
            <div class="card mb-3 m-3" style="width: 18rem;">
                <a href="<?php echo 'uploads/watermark/' . $img['imgFile']?>">
                    <img src="<?php echo 'uploads/mini/' . $img['imgFile']?>" class="card-img-top">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $img['imgTitle'] ?></h5>
                    <p class="card-text"><?php echo $img['author'] ?></p>
                    <p class="card-text"><?php echo date('d/m/Y H:i', $img['when']) ?></p>
                    <?php if(Application::$app->session->get('fav')): ?>
                        <?php echo $formFav->field($image, 'fav[]', $img['_id'])->checkBox(in_array($img['_id'], Application::$app->session->get('fav'))) ?>
                    <?php else: ?>
                        <?php echo $formFav->field($image, 'fav[]', $img['_id'])->checkBox(false) ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <?php if (Application::Auth() && Application::$app->user->login === $img['author']): ?>
                <div class="card" style="width: 18rem;">
                    <a href="<?php echo 'uploads/watermark/' . $img['imgFile']?>">
                        <img src="<?php echo 'uploads/mini/' . $img['imgFile']?>" class="card-img-top">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $img['imgTitle'] ?></h5>
                        <p class="card-text"><?php echo $img['author'] ?></p>
                        <p class="card-text"><?php echo date('d/m/Y H:i', $img['when']) ?></p>
                        <p> Private </p>
                        <?php echo $formFav->field($image, 'fav[]', $img['_id'])->checkBox(in_array($img['_id'], Application::$app->session->get('fav'))) ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<div class="d-flex justify-content-around">
    <button type="submit" class="btn btn-primary">Zapisz ulubione</button>
</div>
<?php Form::end(); ?>
<hr>
    <div class="d-flex">
        <div>
            <?php $formNext = Form::begin('prev', 'post') ?>
            <button type="submit" class="btn btn-primary">Poprzednia</button>
            <?php Form::end() ?>
            <span class="badge text-bg-primary"><?php echo Application::$app->session->get('page')?></span>
            <?php $formNext = Form::begin('next', 'post') ?>
            <button type="submit" class="btn btn-primary">Następna</button>
            <?php Form::end() ?>
        </div>
    </div>
<ul>
</ul>