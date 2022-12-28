<?php
use app\models\Post;
use app\core\Application;
use app\core\form\Form;
?>
<h1>FAV</h1>
<h3>IMAGES</h3>
<div class="d-flex flex-wrap justify-content-around">
    <?php $formDelete = Form::begin('/fav', 'post') ?>
        <?php foreach ($images as $img): ?>
            <div class="card" style="width: 18rem;">
                <a href="<?php echo 'uploads/watermark/' . $img['imgFile']?>">
                    <img src="<?php echo 'uploads/mini/' . $img['imgFile']?>" class="card-img-top">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $img['imgTitle'] ?></h5>
                    <p class="card-text"><?php echo $img['author'] ?></p>
                    <p class="card-text"><?php echo date('d/m/Y H:i', $img['when']) ?></p>
                    <?php echo $formDelete->field($image, 'favDelete[]', $img['_id'])->checkBox(false) ?>
                </div>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Usuń zaznaczone z ulubionych</button>
    <?php Form::end() ?>
</div>