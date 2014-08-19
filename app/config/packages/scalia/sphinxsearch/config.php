<?php

return array (
    'host'    => '127.0.0.1',
    'port'    => 3312,
    'indexes' => array (
        'channelsIndexInfinity' => array('table'=>'channels','column'=>'id','modelname'=>'Channel'),
        'productsIndexInfinity' => array('table'=>'products_meta','column'=>'product_id','modelname'=>'ProductsMeta'),
        'productsAccessibilityIndexInfinity' => array('table'=>'products_accessories','column'=>'id','modelname'=>'ProductAccessory'),
        'newsIndexInfinity' => array('table'=>'i18n_news','column'=>'id','modelname'=>'I18nNews'),
        'pagesIndexInfinity' => array('table'=>'i18n_pages','column'=>'id','modelname'=>'I18nPage')
    )
);
