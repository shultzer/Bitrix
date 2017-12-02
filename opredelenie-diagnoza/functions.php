<?

    function select () {

        echo "<select  class=\"custom-select input\" required id='sel' name=\"section_id[]\">
            <option value=\"\" disabled selected hidden>Выберите симптом</option>";
        $arFilter  = [
          'IBLOCK_ID'   => 27,
          'ACTIVE'      => 'Y',
          "DEPTH_LEVEL" => "1",
        ];
        $arSelect  = [ 'ID', 'NAME' ];
        $rsSection = CIBlockElement::GetList(FALSE, $arFilter, $arSelect);
        while ( $arSection = $rsSection->Fetch() ) {

            echo "<option value=\"{$arSection[ 'ID' ]}\">{$arSection[ 'NAME' ]}</option>";
        }
        echo '</select>';

    }

?>