<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use \Bitrix\Iblock\TypeTable as IblockType;
use \Bitrix\Iblock\IblockTable as Iblock;

Loc::loadMessages(__FILE__);
Class itua_dmbuilder extends CModule
{
	const MODULE_ID = 'itua.dmbuilder';
	var $MODULE_ID = 'itua.dmbuilder';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("itua.dmbuilder_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("itua.dmbuilder_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("itua.dmbuilder_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("itua.dmbuilder_PARTNER_URI");
	}
	function InstallFiles($arParams = array())
	{
        CopyDirFiles(__DIR__.'/wizards',
            $_SERVER['DOCUMENT_ROOT'].BX_ROOT."/wizards",
            true,
            true
        );
		return true;
	}

	function UnInstallFiles()
	{
        DeleteDirFilesEx(BX_ROOT."/wizards/itua/itua.dmbuilder");
	}
        
    function InstallEvent()
    {
        RegisterModuleDependences("main", "OnPanelCreate", self::MODULE_ID, "CItuaDbuilder", "AddPanelButton");
    }
    function UnInstallEvent()
    {
        UnRegisterModuleDependences("main", "OnPanelCreate", self::MODULE_ID, "CItuaDbuilder", "AddPanelButton");
    }
        
	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
        $this->InstallEvent();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallFiles();
        $this->UnInstallEvent();
	}
}

