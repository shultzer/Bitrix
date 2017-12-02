<?

  use Bitrix\Main\Security\Random;

  IncludeModuleLangFile(__FILE__);

  class GeneratePassword {

    public static function OnBeforeUserSendPasswordHandler($arFields) {
      //проверяем на наличие введенных данных в базе и определяем  ид пользователя по введенным данным
      if ($arFields[ 'LOGIN' ] != '') {
        $user        = CUser::GetList($by = "personal_country", $order = "desc", ['login' => $arFields[ 'LOGIN' ]]);
        $currentuser = $user->Fetch();
        $userid      = $currentuser[ 'ID' ];
        $usermail    = $currentuser[ 'EMAIL' ];
      }
      if ($arFields[ 'EMAIL' ] != '') {
        $user        = CUser::GetList($by = "personal_country", $order = "desc", ['email' => $arFields[ 'EMAIL' ]]);
        $currentuser = $user->Fetch();
        $userid      = $currentuser[ 'ID' ];
        $usermail    = $arFields[ 'EMAIL' ];
      }
      //если пользователь найден - генерируем пароль и обновляем запись в базе
      if ($currentuser != FALSE) {
        $new_pwd                    = '/9' . Random::getString(8, TRUE);
        $arfields                   = [
          "PASSWORD"         => $new_pwd,
          "CONFIRM_PASSWORD" => $new_pwd,
          "EMAIL_TO"         => $usermail,
        ];
        $upuser                     = new CUser;
        $arResult[ 'updateresult' ] = $upuser->Update($userid, $arfields);
      }
      //если пароль записался в базу - отсылаем пользователю сообщение на почту свои шаблоны
      if ($arResult[ 'updateresult' ]) {
        $arEventFields = [
          "EMAIL_TO" => $usermail,
          "NEWPWD"   => $new_pwd,
        ];
        $rsMess        = CEventMessage::GetList($by = "site_id", $order = "desc", ["TYPE_ID" => "GENERATE_PWD"]);
        while ($arMess = $rsMess->GetNext()) {
          CEvent::Send("USER_INFO", SITE_ID, $arEventFields, "N", $arMess[ "ID" ]);
        }
       
      }
      //запрещаем отсылку штатного сообщения с контрольной строкой
      return FALSE;
    }
  }
