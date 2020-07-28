<?php 
    use kartik\mpdf\Pdf;

    return [
        'class' => Pdf::class,
        'format' => Pdf::FORMAT_LETTER,
        'orientation' => Pdf::ORIENT_PORTRAIT,
        'destination' => Pdf::DEST_BROWSER,
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
    ];
?>