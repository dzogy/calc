<?php/* @var $this yii\web\View */$this->title = 'Калькулятор';use yii\helpers\Html;?><div class="body-content">        <div class="row">            <div class="col-lg-4"></div>            <div class="col-lg-4">                <br>                        <h3 style="font-weight:bold">РЕЗУЛЬТАТ РАССЧЕТА</h3>        <ul>            <li><label>Город доставки</label>: <?= $sityarray[$model->sity] ?></li>            <li><label>Вес товара</label>: <?= Html::encode($model->weight).' грамм' ?></li>            <li><label>Общая стоимость доставки</label>: <?= $pricearr[price].' рублей' ?></li>            <li><label>Стоимость базовая</label>: <?= $pricearr[price_base].' рублей' ?></li>            <li><label>Стоимость обслуживания</label>: <?= $pricearr[price_service].' рублей' ?></li>            <li><label>Срок доставки</label>: <?= $pricearr[delivery_period].' дней' ?></li>        </ul>                    </div>        </div></div>