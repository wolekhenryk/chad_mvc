<?php
use app\models\Post;
use app\core\Application;
use app\core\form\Form;
/** @var $post Post */
?>
<div class="m-3">
    <?php $ajaxForm = Form::begin('/search/show', 'post', 'searchForm') ?>
        <?php echo $ajaxForm->field($post, 'searchImgTitle', $_POST['searchImgTitle'] ?? '')?>
    <?php Form::end(); ?>
    <script>
        $('#searchImgTitle').bind('keyup', function () {
            $('#searchForm').submit();
        });

        $('#searchForm').submit(function (event) {
            event.preventDefault();
            var postData = {
                imgTitle: $('#searchImgTitle').val()
            };
            $.ajax({
                type: 'POST',
                url: '/search/show',
                data: postData,
                success: function (readyData) {
                    $('#replaceable').html(readyData);
                }
            });
        });
    </script>
    <pre>
    <?php /*var_dump($posts->toArray()); */?>
    </pre>
    <div class="d-flex flex-wrap justify-content-around" id="replaceable">
        <?php foreach ($posts as $post): ?>
            <div class="card mb-4" style="width: 18rem;">
                <a href="<?php echo 'uploads/watermark/' . $post['imgFile']?>">
                    <img src="<?php echo 'uploads/mini/' . $post['imgFile']?>" class="card-img-top">
                </a>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $post['imgTitle'] ?></h5>
                    <p class="card-text"><?php echo $post['author'] ?></p>
                    <p class="card-text"><?php echo date('d/m/Y H:i', $post['when']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>