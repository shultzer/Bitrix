<? if ( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== TRUE ) {
    die();
}
    session_start();
    $APPLICATION->SetPageProperty('hideWrapper', TRUE);
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    if ( $request->getRequestMethod() == 'POST' ) {
        $filter = $request->getPost("section_id");

        if ( $filter != NULL && count($filter) < 3 ) {
            unset($arResult[ 'ITEMS' ]);
            $arResult[ 'note' ] = 'Необходимо выбрать не менее трех диагнозов';
        }
        else {
            $_SESSION[ 'userchoose' ] = $filter;

            $arFilter = [
              'IBLOCK_ID'      => 28,
              "PROPERTY_49.ID" => $filter,
            ];

            $res = CIBlockElement::GetList(FALSE,
              $arFilter,
              [
                'IBLOCK_ID',
                'ID',
                'NAME',
                'DETAIL_PAGE_URL',
                'PREVIEW_TEXT',
              ]
            );

            while ( $arres = $res->GetNextElement() ) {
                $ar_res = $arres->GetFields();

                $arr_simptoms = $arres->GetProperties("PROPERTY_49.ID");
                $rel_simptoms = $arr_simptoms[ "rel_simptomi" ][ "VALUE" ];
                $intersect    = array_intersect($filter, $rel_simptoms);
                $cnt          = count($intersect);


                if ( $cnt >= 3 ) {
                    $r []                 = TRUE;
                    $diagnoz[ 'ITEMS' ][] = $ar_res;
                }
                else {

                }

            }

            if ( in_array(TRUE, $r) != 0 ) {
                $arResult[ 'note' ]  = '&#10035;Данный результат не является конечным утвержденным
              медицинским диагнозом. Если Вы обеспокоены состоянием Вашего здоровья –
              обязательно обратитесь к врачу!';
                $arResult[ 'ITEMS' ] = $diagnoz[ 'ITEMS' ];
            }
            else {
                $arResult[ 'note' ] = 'По вашему запросу ничего не найдено';
                unset($arResult[ 'ITEMS' ]);
            }
        }
    }
    else {

        unset($arResult[ 'ITEMS' ]);


    }
?>