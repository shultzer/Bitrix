<?

    use Bitrix\Main\Localization\Loc;
    use Bitrix\Main\ModuleManager;
    use \Bitrix\Main\IO\Directory;

    Loc::loadMessages(__FILE__);

    Class my_module extends CModule {

        var $MODULE_ID;

        var $MODULE_VERSION;

        var $MODULE_VERSION_DATE;

        var $MODULE_NAME;

        var $MODULE_DESCRIPTION;

        var $MODULE_CSS;

        var $MODULE_GROUP_RIGHTS = "Y";

        function __construct () {
            $arModuleVersion = [];

            $path = str_replace("\\", "/", __FILE__);
            $path = substr($path, 0, strlen($path) - strlen("/index.php"));
            include( $path . "/version.php" );


            $this->MODULE_ID = "my.module";
            if ( is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion) ) {
                $this->MODULE_VERSION      = $arModuleVersion[ "VERSION" ];
                $this->MODULE_VERSION_DATE = $arModuleVersion[ "VERSION_DATE" ];
            }
            $this->MODULE_NAME        = 'Отправка сгенерированного пароля на email';
            $this->MODULE_DESCRIPTION = "Модуль отправки сгенерированного пароля на email пользователя";
            $this->PARTNER_NAME       = "Скороход С.";
            $this->PARTNER_URI        = "Скороход С.";
        }

        function InstallEvents () {


            $obEventType = new CEventType;
            $obEventType->Add([
              "EVENT_NAME"  => "GENERATE_PWD",
              "NAME"        => "Сгенерирован новый пароль",
              "LID"         => "ru",
              "DESCRIPTION" => "#NEWPWD# - Сгенерированный пароль",
            ]);
            $arSites = [];
            $sites   = CSite::GetList($b = "", $o = "", [ LANGUAGE_ID => "ru" ]);
            while ( $site = $sites->Fetch() ) {
                $arSites[] = $site[ "LID" ];
            }
            if ( count($arSites) > 0 ) {
                $ar[ "ACTIVE" ]     = "Y";
                $ar[ "EVENT_NAME" ] = "GENERATE_PWD";
                $ar[ "EMAIL_FROM" ] = "#DEFAULT_EMAIL_FROM#";
                $ar[ "LID" ]        = $arSites;
                $ar[ "EMAIL_TO" ]   = "#EMAIL_TO#";
                $ar[ "SUBJECT" ]    = "Сгенерирован новый пароль";
                $ar[ "BODY_TYPE" ]  = "text";
                $ar[ "MESSAGE" ]    = "Вам сгенерирован новый пароль для входа. #NEWPWD#";
                $emess              = new CEventMessage;
                $emess->Add($ar);
            }
            return TRUE;
        }

        function UnInstallEvents () {
            $obEventType = new CEventType;
            $obEventType->Delete("GENERATE_PWD");
            $arFilter = [
              "TYPE_ID" => "GENERATE_PWD",
            ];
            $rsMess   = CEventMessage::GetList($by = "site_id", $order = "desc", $arFilter);

            while ( $arMess = $rsMess->GetNext() ) {
                $emess = new CEventMessage;
                $emess->Delete($arMess[ 'ID' ]);
            }
            return TRUE;
        }

        function InstallDB () {
            RegisterModuleDependences('main', 'OnBeforeUserSendPassword',
              'my.module', 'GeneratePassword', 'OnBeforeUserSendPasswordHandler');

            return TRUE;
        }

        function UnInstallDB () {

            UnRegisterModuleDependences('main', 'OnBeforeUserSendPassword',
              'my.module', 'GeneratePassword', 'OnBeforeUserSendPasswordHandler');

            return TRUE;

        }

        function DoInstall () {

            $this->InstallDB();
            $this->InstallEvents();
            ModuleManager::registerModule($this->MODULE_ID);
        }

        function DoUninstall () {
            ModuleManager::unregisterModule($this->MODULE_ID);
            $this->UnInstallEvents();
            $this->UninstallDB();
        }


        public function getpath ($notdocumentroot = FALSE) {
            if ( $notdocumentroot ) {
                return str_ireplace(\Bitrix\Main\Application::getDocumentRoot() . '' . dirname(__DIR__));
            }
            else {
                return dirname(__DIR__);
            }

        }

    }


?>


