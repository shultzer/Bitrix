<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== TRUE) {
  die();
}
  /** @var array $arParams */
  /** @var array $arResult */
  /** @global CMain $APPLICATION */
  /** @global CUser $USER */
  /** @global CDatabase $DB */
  /** @var CBitrixComponentTemplate $this */
  /** @var string $templateName */
  /** @var string $templateFile */
  /** @var string $templateFolder */
  /** @var string $componentPath */
  /** @var CBitrixComponent $component */
  $this->setFrameMode(TRUE);
?>
<? if ($arParams[ "DISPLAY_NAME" ] != "N" && $arResult[ "NAME" ]): ?>
    <h1><?= $arResult[ "NAME" ] ?></h1>
<? endif; ?>
<div class="news-detail diagnoz-detail">
  <? if ($arParams[ "DISPLAY_PICTURE" ] != "N" && is_array($arResult[ "DETAIL_PICTURE" ])): ?>
      <img
              class="detail_picture"
              border="0"
              src="<?= $arResult[ "DETAIL_PICTURE" ][ "SRC" ] ?>"
              width="<?= $arResult[ "DETAIL_PICTURE" ][ "WIDTH" ] ?>"
              height="<?= $arResult[ "DETAIL_PICTURE" ][ "HEIGHT" ] ?>"
              alt="<?= $arResult[ "DETAIL_PICTURE" ][ "ALT" ] ?>"
              title="<?= $arResult[ "DETAIL_PICTURE" ][ "TITLE" ] ?>"
      />
  <? endif ?>
    <div class="container symptoms">
        <h5>Cимптомы из заболевания</h5>
        <ul>
          <?
            if (isset($arResult[ 'relsimptom_cons' ]) && !empty($arResult[ 'relsimptom_cons' ])):
              foreach ($arResult[ 'relsimptom_cons' ] as $relsimptom_cons):
                ?>
                  <li><b><?= $relsimptom_cons ?></b></li>
              <? endforeach;
            endif; ?>
          <?
            if (isset($arResult[ 'relsimptom' ]) && !empty($arResult[ 'relsimptom' ])):
              foreach ($arResult[ 'relsimptom' ] as $relsimptom):
                ?>
                  <li><?= $relsimptom ?></li>
              <? endforeach;
            endif;
          ?>
        </ul>
    </div>
    <div class="diagnoz-text">
      <? if ($arParams[ "DISPLAY_DATE" ] != "N" && $arResult[ "DISPLAY_ACTIVE_FROM" ]): ?>
          <span class="news-date-time"><?= $arResult[ "DISPLAY_ACTIVE_FROM" ] ?></span>
      <? endif; ?>
      <? if ($arParams[ "DISPLAY_PREVIEW_TEXT" ] != "N" && $arResult[ "FIELDS" ][ "PREVIEW_TEXT" ]): ?>
          <p><?= $arResult[ "FIELDS" ][ "PREVIEW_TEXT" ];
              unset($arResult[ "FIELDS" ][ "PREVIEW_TEXT" ]); ?></p>
      <? endif; ?>
      <? if ($arResult[ "NAV_RESULT" ]): ?>
        <? if ($arParams[ "DISPLAY_TOP_PAGER" ]): ?><?= $arResult[ "NAV_STRING" ] ?>
              <br/><? endif; ?>
        <? echo $arResult[ "NAV_TEXT" ]; ?>
        <? if ($arParams[ "DISPLAY_BOTTOM_PAGER" ]): ?>
              <br/><?= $arResult[ "NAV_STRING" ] ?><? endif; ?>
      <? elseif (strlen($arResult[ "DETAIL_TEXT" ]) > 0): ?>
        <? echo $arResult[ "DETAIL_TEXT" ]; ?>
      <? else: ?>
          <p><? echo $arResult[ "PREVIEW_TEXT" ]; ?></p>
      <? endif ?>
        <div style="clear:both"></div>
        <br/>
      <? foreach ($arResult[ "FIELDS" ] as $code => $value):
        if ('PREVIEW_PICTURE' == $code || 'DETAIL_PICTURE' == $code) {
          ?><?= GetMessage("IBLOCK_FIELD_" . $code) ?>:&nbsp;<?
          if (!empty($value) && is_array($value)) {
            ?><img border="0" src="<?= $value[ "SRC" ] ?>"
                   width="<?= $value[ "WIDTH" ] ?>"
                   height="<?= $value[ "HEIGHT" ] ?>"><?
          }
        }
        else {
          ?><?= GetMessage("IBLOCK_FIELD_" . $code) ?>:&nbsp;<?= $value; ?><?
        }
        ?><br/>
      <?endforeach;
        foreach ($arResult[ "DISPLAY_PROPERTIES" ] as $pid => $arProperty):?>

          <?= $arProperty[ "NAME" ] ?>:&nbsp;
          <?
          if (is_array($arProperty[ "DISPLAY_VALUE" ])):?>
            <?= implode("&nbsp;/&nbsp;", $arProperty[ "DISPLAY_VALUE" ]); ?>
          <? else: ?>
            <?= $arProperty[ "DISPLAY_VALUE" ]; ?>
          <? endif ?>
            <br/>
        <?endforeach;
        if (array_key_exists("USE_SHARE", $arParams) && $arParams[ "USE_SHARE" ] == "Y") {
          ?>
            <div class="news-detail-share">
                <noindex>
                  <?
                    $APPLICATION->IncludeComponent("bitrix:main.share", "", [
                      "HANDLERS"          => $arParams[ "SHARE_HANDLERS" ],
                      "PAGE_URL"          => $arResult[ "~DETAIL_PAGE_URL" ],
                      "PAGE_TITLE"        => $arResult[ "~NAME" ],
                      "SHORTEN_URL_LOGIN" => $arParams[ "SHARE_SHORTEN_URL_LOGIN" ],
                      "SHORTEN_URL_KEY"   => $arParams[ "SHARE_SHORTEN_URL_KEY" ],
                      "HIDE"              => $arParams[ "SHARE_HIDE" ],
                    ],
                      $component,
                      ["HIDE_ICONS" => "Y"]
                    );
                  ?>
                </noindex>
            </div>
          <?
        }
      ?>
    </div>
    <div class="actions-wrap">
        <h5>Необходимые действия</h5>
        <ul>
          <? if (isset($arResult[ 'relserv' ]) && !empty($arResult[ 'relserv' ])):
            foreach ($arResult[ 'relserv' ] as $relserv_code => $relserv_name):?>
                <li>
                    <a href="/services/serv/<?= $relserv_code ?>/"><?= $relserv_name ?></a>
                </li>
            <? endforeach;
          endif; ?>
        </ul>
    </div>
    <h3>Остались вопросы?</h3>
  <? $APPLICATION->IncludeComponent(
    "bitrix:iblock.element.add.form",
    "feedback",
    [
      "COMPONENT_TEMPLATE"            => "feedback",
      "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
      "CUSTOM_TITLE_DATE_ACTIVE_TO"   => "",
      "CUSTOM_TITLE_DETAIL_PICTURE"   => "",
      "CUSTOM_TITLE_DETAIL_TEXT"      => "",
      "CUSTOM_TITLE_IBLOCK_SECTION"   => "",
      "CUSTOM_TITLE_NAME"             => "Имя",
      "CUSTOM_TITLE_PREVIEW_PICTURE"  => "",
      "CUSTOM_TITLE_PREVIEW_TEXT"     => "Ваш вопрос",
      "CUSTOM_TITLE_TAGS"             => "",
      "DEFAULT_INPUT_SIZE"            => "50",
      "DETAIL_TEXT_USE_HTML_EDITOR"   => "N",
      "ELEMENT_ASSOC"                 => "CREATED_BY",
      "FIELDS_ORDER"                  => [
        0 => "PREVIEW_TEXT",
        1 => "NAME",
        2 => "42",
        3 => "",
      ],
      "FORM_NAME"                     => "",
      "GROUPS"                        => [0 => "2",],
      "IBLOCK_ID"                     => "1",
      "IBLOCK_ID_DEPARTMENT"          => "",
      "IBLOCK_TYPE"                   => "s1",
      "LEVEL_LAST"                    => "Y",
      "LIST_URL"                      => "",
      "MAX_FILE_SIZE"                 => "0",
      "MAX_LEVELS"                    => "100000",
      "MAX_USER_ENTRIES"              => "100000",
      "PREVIEW_TEXT_USE_HTML_EDITOR"  => "N",
      "PROPERTY_CODES"                => [
        0 => "2",
        1 => "NAME",
        2 => "PREVIEW_TEXT",
      ],
      "PROPERTY_CODES_REQUIRED"       => [
        0 => "2",
        1 => "NAME",
        2 => "PREVIEW_TEXT",
      ],
      "RESIZE_IMAGES"                 => "N",
      "SEF_MODE"                      => "N",
      "STATUS"                        => "ANY",
      "STATUS_NEW"                    => "NEW",
      "USER_MESSAGE_ADD"              => "Спасибо, ваш вопрос добавлен",
      "USER_MESSAGE_EDIT"             => "",
      "USE_CAPTCHA"                   => "N",
    ]
  ); ?>
    <div class="recommendations-wrap">
        <h5>Рекомендации</h5>
        <ul>
          <? if (isset($arResult[ 'relart' ]) && !empty($arResult[ 'relart' ])):

            foreach ($arResult[ 'relart' ] as $relart_code => $relart_name):?>
                <li>
                    <a href="/personal/library/<?= $relart_code ?>/"><?= $relart_name ?></a>
                </li>
            <? endforeach;
          endif; ?>
        </ul>
    </div>
</div>