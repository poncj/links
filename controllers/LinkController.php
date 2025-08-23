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
                    'validate' => ['POST'],
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
            Yii::$app->response->format = Response::FORMAT_JSON;

            $model->load(Yii::$app->request->post());
            if ($model->validate() && $model->save()) {
                $shortUrl = Yii::$app->request->hostInfo . '/s/' . $model->short_code;

                return [
                    'success'  => true,
                    'shortUrl' => $shortUrl,
                    'qr'       => $this->generateQr($shortUrl),
                ];
            }

            return ['success' => false, 'errors' => $model->errors];
        }

        return $this->render('index', ['model' => $model]);
    }

    /**
     * Ajax-валидация ссылки
     */
    public function actionValidate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Link();
        $model->load(Yii::$app->request->post());

        if ($model->validate()) {
            return ['valid' => true];
        }

        return ['valid' => false, 'errors' => $model->errors];
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

    /**
     * Генерация QR-кода (endroid/qr-code v6)
     */
    protected function generateQr(string $url): string
    {

        $builder = Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevel\ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeMode\RoundBlockSizeModeMargin())
            ->build();

        return $builder->getDataUri();
    }



}
