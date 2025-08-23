<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Сервис коротких ссылок';
?>

<div class="link-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите длинный URL, чтобы получить короткую ссылку:</p>

    <div id="link-form">
        <?php $form = ActiveForm::begin([
            'id' => 'create-link-form',
            'action' => Url::to(['link/index']),
            'enableAjaxValidation' => false,
            'options' => ['onsubmit' => 'return false;'], // отключаем стандартный сабмит
        ]); ?>

        <?= $form->field($model, 'original_url')->textInput(['placeholder' => 'https://example.com'])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Сократить', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <div id="result" style="display:none; margin-top:20px;">
        <h3>Короткая ссылка:</h3>
        <p><a href="#" id="short-url" target="_blank"></a></p>
        <h3>QR-код:</h3>
        <img id="qr-image" src="" alt="QR code"/>
    </div>
</div>

<?php
$ajaxUrl = Url::to(['link/index']);
$js = <<<JS
$('#create-link-form').on('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: '$ajaxUrl',
        type: 'POST',
        data: $(this).serialize(),
        success: function(res)
        {
            if(res.success) {
                $('#short-url').text(res.shortUrl).attr('href', res.shortUrl);
                $('#qr-image').attr('src', res.qr);
                $('#result').show();
            } else {
                alert('Ошибка: ' + JSON.stringify(res.errors));
            }
        },
        error: function() 
        {
            alert('Произошла ошибка при запросе');
        }
    });
});
JS;

$this->registerJs($js);
?>
