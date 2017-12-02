<?
    //1 извлекаем симптомы и выделяем совпавшие
    CModule::IncludeModule('iblock');
    $arFilter       = [
      'IBLOCK_ID' => 28,//ID инфоблока
      'ID'        => $arResult[ 'ID' ],
    ];
    $VISITOR_choose = $_SESSION[ 'userchoose' ];
    $res            = CIBlockElement::GetList(FALSE, $arFilter, FALSE, FALSE, [
      'PROPERTY_49.NAME',
      'PROPERTY_49.ID',
    ]);
    while ( $relsim = $res->Fetch() ) {
        if ( in_array($relsim[ 'PROPERTY_49_ID' ], $_SESSION[ 'userchoose' ]) ) {
            $arResult[ 'relsimptom_cons' ][] = $relsim[ 'PROPERTY_49_NAME' ];
        }
        else {
            $arResult[ 'relsimptom' ][] = $relsim[ 'PROPERTY_49_NAME' ];
        }
    };
    //извлекаем связанные услуги
    $arFilter = [
      'IBLOCK_ID' => 28,//ID инфоблока
      'ID'        => $arResult[ 'ID' ],
    ];

    $res = CIBlockElement::GetList(FALSE, $arFilter, FALSE, FALSE, [
      'PROPERTY_50.NAME',
      'PROPERTY_50.CODE',
    ]);
    while ( $relserv = $res->Fetch() ) {
        $arResult[ 'relserv' ][ $relserv[ 'PROPERTY_50_CODE' ] ] = $relserv[ 'PROPERTY_50_NAME' ];

    }

    //извлекаем связанные статьи библиотеки

    $arFilter = [
      'IBLOCK_ID' => 28,//ID инфоблока
      'ID'        => $arResult[ 'ID' ],
    ];

    $res = CIBlockElement::GetList(FALSE, $arFilter, FALSE, FALSE, [
      'PROPERTY_51.NAME',
      'PROPERTY_51.CODE',
    ]);
    while ( $relart = $res->Fetch() ) {
        $arResult[ 'relart' ][ $relart[ 'PROPERTY_51_CODE' ] ] = $relart[ 'PROPERTY_51_NAME' ];
    }
?>

