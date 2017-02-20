<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


Class CItuaDbuilder
{
    function AddPanelButton()
    {
        if ($GLOBALS["USER"]->IsAdmin())
        {
            $GLOBALS["APPLICATION"]->AddPanelButton(array(
                "HREF" => "javascript:WizardWindow.Open('itua:d7mbuilder','".bitrix_sessid()."')",
                "ID" => "itua.d7mbuilder",
                "SRC" => "/bitrix/wizards/itua/d7mbuilder/images/973-red-ruby-vector.png",
                "MAIN_SORT" => 400,
                "SORT" => 100,
                "ALT" => Loc::getMessage("BUTTON_DESCRIPTION"),
                "TEXT" => Loc::getMessage("BUTTON_NAME"),
                "MENU" => array(),
            ));
        }
    }
}