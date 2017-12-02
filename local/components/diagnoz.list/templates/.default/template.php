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
<?
  include 'functions.php';
?>

<div id="block">
    <form class="diagnoz" action="" method="POST">
        <!-- <h2>Выбор симптома: </h2> -->
        <div class="container-fluid">
            <div id="select-sync" class="select-sync-container">
                <div class="jq-selectbox jqselect opacity-one">
                  <?
                    select();
                    select();
                    select();?><button type="button" id="select-sync-button">+Добавить
                        симптом</button>
                </div>
            </div>
        </div>
        <input class="btn btn-success" type="submit"
               value="Определить диагноз">
    </form>
</div>

<p class="note"><span>*</span> Данный инструмент представлен в информационных
    целях и не заменяет врачебную консультацию.</p>

<br>
<? if (isset($arResult[ "ITEMS" ])): ?>
    <div class="white-content-box col-margin-top news-list">
        <h3>Результат</h3>
      <?= $arParams[ '~RSS_ICON' ];
      ?>
      <? $i = 1; ?>
      <? foreach ($arResult[ "ITEMS" ] as $arItem): ?>
         <!--  <h4>Диагноз <?= $i ?>:</h4> -->
        <?
        $this->AddEditAction($arItem[ 'ID' ], $arItem[ 'EDIT_LINK' ], CIBlock::GetArrayByID($arItem[ "IBLOCK_ID" ], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem[ 'ID' ], $arItem[ 'DELETE_LINK' ], CIBlock::GetArrayByID($arItem[ "IBLOCK_ID" ], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
        ?>
          <div class="news-item diagnoz-list"
               id="<?= $this->GetEditAreaId($arItem[ 'ID' ]); ?>">
            <? if ($arParams[ "DISPLAY_PICTURE" ] != "N" && is_array($arItem[ "PREVIEW_PICTURE" ])): ?>
                <div class="news-item-image">
                  <? if (!$arParams[ "HIDE_LINK_WHEN_NO_DETAIL" ] || ($arItem[ "DETAIL_TEXT" ] && $arResult[ "USER_HAVE_ACCESS" ])): ?>
                      <a href="/opredelenie-diagnoza/diagnoz-detalno.php?ELEMENT_ID=<?= $arItem[ "DETAIL_PAGE_URL" ] ?>"><img
                                  src="<?= imageResize([
                                    "WIDTH"  => 305,
                                    "HEIGHT" => 186,
                                    "MODE"   => "in",
                                  ], $arItem[ "PREVIEW_PICTURE" ][ "SRC" ]) ?>"
                                  alt="<?= $arItem[ "NAME" ] ?>"
                                  title="<?= $arItem[ "NAME" ] ?>"
                          /></a>
                  <? else: ?>
                      <img
                              src="<?= imageResize([
                                "WIDTH"  => 305,
                                "HEIGHT" => 186,
                                "MODE"   => "in",
                              ], $arItem[ "PREVIEW_PICTURE" ][ "SRC" ]) ?>"
                              alt="<?= $arItem[ "NAME" ] ?>"
                              title="<?= $arItem[ "NAME" ] ?>"
                      />
                  <? endif; ?>
                </div>
            <? endif ?>
              <div class="news-item-content">
                <? if ($arParams[ "DISPLAY_NAME" ] != "N" && $arItem[ "NAME" ]): ?>
                  <? if (!$arParams[ "HIDE_LINK_WHEN_NO_DETAIL" ] || ($arItem[ "DETAIL_TEXT" ] && $arResult[ "USER_HAVE_ACCESS" ])): ?>
                        <h2 class="news-item-header"><a
                                    href="/opredelenie-diagnoza/diagnoz-detalno.php?ELEMENT_ID=<? echo $arItem[ "DETAIL_PAGE_URL" ] ?>"><? echo $arItem[ "NAME" ] ?></a>
                        </h2>
                  <? else: ?>
                        <h2 class="news-item-header"><? echo $arItem[ "NAME" ] ?></h2>
                  <? endif; ?>
                <? endif; ?>
                <? if ($arParams[ "DISPLAY_DATE" ] != "N" && $arItem[ "DISPLAY_ACTIVE_FROM" ]): ?>
                    <div class="news-item-date"><? echo $arItem[ "DISPLAY_ACTIVE_FROM" ] ?></div>
                <? endif ?>
                <? if ($arParams[ "DISPLAY_PREVIEW_TEXT" ] != "N" && $arItem[ "PREVIEW_TEXT" ]): ?>
                    <div class="news-item-text"><? echo $arItem[ "PREVIEW_TEXT" ]; ?></div>
                <? endif; ?>
                <? foreach ($arItem[ "DISPLAY_PROPERTIES" ] as $pid => $arProperty): ?>

                  <?= $arProperty[ "NAME" ] ?>:&nbsp;
                  <? if (is_array($arProperty[ "DISPLAY_VALUE" ])): ?>
                    <?= implode("&nbsp;/&nbsp;", $arProperty[ "DISPLAY_VALUE" ]); ?>
                  <? else: ?>
                    <?= $arProperty[ "DISPLAY_VALUE" ]; ?>
                  <? endif ?>
                    <br/>
                <? endforeach; ?>
                  <a href="/opredelenie-diagnoza/diagnoz-detalno.php?ELEMENT_ID=<? echo $arItem[ "DETAIL_PAGE_URL" ] ?>"
                     class="n-readmore">Подробнее</a>
              </div>
          </div>
        <?$i++?>
      <? endforeach; ?>
    </div>
<? endif; ?>
<p class="note"><?= $arResult[ 'note' ] ?></p>
<? if ($arParams[ "DISPLAY_BOTTOM_PAGER" ]): ?>
  <?= $arResult[ "NAV_STRING" ] ?>
<? endif; ?>
<script type="application/javascript">

    ((container, maxItemNumber) => {
        if (container) {

            let _ = container;
            let __ = console.log;

            let selectListChecker = e => {
                let selects = _.querySelectorAll('select');
                let selected = [];

                selects.forEach(select => {
                    /* let currentWidth = ((100 - selects.length) / selects.length) + '%;'
                     select.setAttribute('style', 'width: ' + currentWidth + ' min-width: ' + currentWidth);*/
                    if (selected.indexOf(select.value) !== -1) {
                        select.value = "";
                    }
                    select.querySelectorAll('option').forEach(op => {
                        if (op.selected && op.value) {
                            selected.push(op.value);
                        }
                        if (selected.indexOf(op.value) !== -1) {
                            op.setAttribute('hidden', '');
                        } else {
                            op.removeAttribute('hidden');
                        }
                    });
                });
            };

            document.addEventListener("DOMContentLoaded", e => {
                let firstSelect = _.querySelector('select');

                firstSelect.addEventListener('change', selectListChecker);
                firstSelect.nextElementSibling.addEventListener('change', selectListChecker);
                firstSelect.nextElementSibling.nextElementSibling.addEventListener('change', selectListChecker);
                let selectButton = _.querySelector('#select-sync-button');
                selectButton.addEventListener('click', e => {

                    if (_.querySelectorAll('select').length < maxItemNumber) {
                        let clonedElement = firstSelect.cloneNode(true);
                        clonedElement.removeAttribute('required');
                        /*firstSelect.parentElement.appendChild(clonedElement);*/
                        firstSelect.parentElement.insertBefore(clonedElement, selectButton);
                        clonedElement.addEventListener('change', selectListChecker);
                        selectListChecker();
                    } else {
                        __('Max number of items : ' + maxItemNumber + ' are reached!');
                    }
                });
            });

        } else {
            console.error('Container with id="#select-sync" not found. Try to correct or html or given id.');
        }

    })(document.querySelector('#select-sync'), 6);

</script>
