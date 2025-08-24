<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Сервис коротких ссылок';


$this->registerJsVar('ajaxLinkTo', Url::to(['link/index']));
?>

<div class="link-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Введите длинный URL, чтобы получить короткую ссылку:</p>

    <div id="link-form">
        <?php $form = ActiveForm::begin([
            'id' => 'create-link-form',
            'action' => Url::to(['link/index']),
            'enableAjaxValidation' => false,
            'enableClientValidation' => false,
            'validateOnSubmit' => false,
            'validateOnBlur' => false,
            'validateOnChange' => false,
        ]); ?>

        <?= $form->field($model, 'original_url', [
            'template' => "
                <div class=\"input-group\">
                    {input}
                    <button class=\"btn btn-primary\" type=\"submit\">
                        OK
                    </button>
                </div>
                {error}
                ",
        ])->textInput([
            'placeholder' => 'https://example.com',
            'class' => 'form-control'
        ])->label(false) ?>

        <?php ActiveForm::end(); ?>
    </div>

    <div id="result" style="display:none; margin-top:20px;">
        <h3>Короткая ссылка:</h3>
        <p><a href="#" id="short-url" target="_blank"></a></p>
        <h3>QR-код:</h3>
        <img id="qr-image" src="" alt="QR code"/>
    </div>
</div>
