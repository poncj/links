<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Link;
use app\models\LinkClickLog;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

class LinkController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index'    => ['GET', 'POST'],
                    'click'    => ['GET'],
                ],
            ],
        ];
    }

    /**
     * Главная страница + форма добавления ссылки
     */
    public function actionIndex()
    {
        $model = new Link();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                $shortUrl = Yii::$app->request->hostInfo . '/s/' . $model->short_code;

                return $this->asJson([
                    'success'  => true,
                    'shortUrl' => $shortUrl,
                    'qr'       => $this->generateQr($shortUrl),
                ]);
            }

            return $this->asJson([
                    'success' => false,
                    'errors' => $model->errors
            ]);
        }

        return $this->render('index', ['model' => $model]);
    }

    /**
     * Редирект по короткому коду
     */
    public function actionClick($short_code)
    {
        $link = Link::findOne(['short_code' => $short_code]);

        if (!$link) {
            throw new \yii\web\NotFoundHttpException("Ссылка не найдена");
        }

        $link->updateCounters(['clicks' => 1]);

        $log = new LinkClickLog([
            'link_id'   => $link->id,
            'ip_address'=> Yii::$app->request->userIP,
        ]);
        $log->save(false);

        return $this->redirect($link->original_url);
    }

    protected function generateQr(string $url): string
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: '',
            logoResizeToWidth: 50,
            logoPunchoutBackground: true,
            labelText: '',
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );

        $result = $builder->build();

        return $result->getDataUri();
    }


    public function actionTest()
    {
        throw new \yii\web\NotFoundHttpException("Ссылка не найдена");

        $model = new Link();
        $original_url = 'http://google.com/';

        $model->original_url = $original_url;
        $model->validateUrl('original_url', []);

        var_dump($model->getErrors());
    }
}