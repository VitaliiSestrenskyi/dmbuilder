<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\IO\File;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CreateBaseModule extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("create_base_module");//ID шага

        $this->SetTitle("Создание модуля D7");//Заголовок
        $this->SetSubTitle("Введите данные разработчика и нового модуля"); //Подзаголовок

        //Навигация
        $this->SetNextStep("success");
        $this->SetCancelStep("cancel");

        $wizard =& $this->GetWizard(); // Получаем ссылку на объект мастера
        $wizard->SetDefaultVar("partner_name", "Информационные Технологии Украины");
        $wizard->SetDefaultVar("partner_uri", "http://itua.com.ua/");
        $wizard->SetDefaultVar("partner_code", "itua");
        $wizard->SetDefaultVar("module_name", "D7-заготовка");
        $wizard->SetDefaultVar("module_desc", "Модуль, D7-заготовка");
        $wizard->SetDefaultVar("version", "0.1.1");
        $wizard->SetDefaultVar("version_date", date('Y-m-d'));
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();

        if ($wizard->IsCancelButtonClick())
            return;

        $arrFormValues = [
            'partner_name'=>$wizard->GetVar("partner_name"),
            'partner_uri'=> $wizard->GetVar("partner_uri"),
            'partner_code'=>$wizard->GetVar("partner_code"),
            'module_id'=>   $wizard->GetVar("module_id"),
            'module_name'=> $wizard->GetVar("module_name"),
            'version'=>     $wizard->GetVar('version'),
            'version_date'=>$wizard->GetVar('version_date'),
            'module_desc'=> $wizard->GetVar("module_desc"),
        ];

        if(empty($arrFormValues['partner_name'])
            || empty($arrFormValues['partner_uri'])
            || empty($arrFormValues['partner_code'])
            || empty($arrFormValues['module_id'])
            || empty($arrFormValues['module_name'])
            || empty($arrFormValues['version'])
            || empty($arrFormValues['version_date'])
        )
        {
            $this->SetError("Заполните корректно данные для создание модуля");
        }
        else
        {
            Directory::createDirectory(
                Application::getDocumentRoot() . "/bitrix/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/"
            );
            CopyDirFiles(__DIR__.'/itua.base',
                Application::getDocumentRoot() . BX_ROOT."/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}",
                true,
                true
            );

            $fileInstallIndex = File::getFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/install/index.php"
            );
            $fileInstallIndex = str_replace('{{partner_code}}', $arrFormValues['partner_code'], $fileInstallIndex);
            $fileInstallIndex = str_replace('{{module_id}}', $arrFormValues['module_id'], $fileInstallIndex);
            $fileInstallIndex = str_replace('{{partner_uri}}', $arrFormValues['partner_uri'], $fileInstallIndex);
            File::putFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/install/index.php",
                $fileInstallIndex
            );


            $fileInstallVersion = File::getFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/install/version.php"
            );
            $fileInstallVersion = str_replace('{{version}}', $arrFormValues['version'], $fileInstallVersion);
            $fileInstallVersion = str_replace('{{version_date}}', $arrFormValues['version_date'], $fileInstallVersion);
            File::putFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/install/version.php",
                $fileInstallVersion
            );


            $fileLangRuInstallIndex = File::getFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/lang/ru/install/index.php"
            );
            $fileLangRuInstallIndex = str_replace('{{module_name}}', $arrFormValues['module_name'], $fileLangRuInstallIndex);
            $fileLangRuInstallIndex = str_replace('{{module_desc}}', $arrFormValues['module_desc'], $fileLangRuInstallIndex);
            $fileLangRuInstallIndex = str_replace('{{partner_name}}', $arrFormValues['partner_name'], $fileLangRuInstallIndex);
            File::putFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/lang/ru/install/index.php",
                $fileLangRuInstallIndex
            );


            $fileDefault_Option = File::getFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/default_option.php"
            );
            $fileDefault_Option = str_replace('{{partner_code}}', $arrFormValues['partner_code'], $fileDefault_Option);
            $fileDefault_Option = str_replace('{{module_id}}', $arrFormValues['module_id'], $fileDefault_Option);
            File::putFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/default_option.php",
                $fileDefault_Option
            );


            $fileInclude = File::getFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/include.php"
            );
            $fileInclude = str_replace('{{partner_code}}', $arrFormValues['partner_code'], $fileInclude);
            $fileInclude = str_replace('{{module_id}}', $arrFormValues['module_id'], $fileInclude);
            File::putFileContents(
                Application::getDocumentRoot().
                BX_ROOT.
                "/modules/{$arrFormValues['partner_code']}.{$arrFormValues['module_id']}/include.php",
                $fileInclude
            );

            $wizard->SetVar("d7module", $arrFormValues['partner_code'].$arrFormValues['module_id'] );
        }
    }

    function ShowStep()
    {
        $this->content .= '<table class="wizard-data-table">';

        $this->content .= '<tr><th align="right"><span class="wizard-required">*</span>Название вашей компании:</th><td>';
        $this->content .= $this->ShowInputField("text", "partner_name", Array("size" => 60));
        $this->content .= '</td></tr>';

        $this->content .= '<tr><th align="right"><span class="wizard-required">*</span>Адрес вашего сайта:</th><td>';
        $this->content .= $this->ShowInputField("text", "partner_uri", Array("size" => 60));
        $this->content .= '</td></tr>';

        $this->content .= '<tr><th align="right"><span class="wizard-required">*</span>Код партнера:</th><td>';
        $this->content .= $this->ShowInputField("text", "partner_code", Array("size" => 60));
        $this->content .= '</td></tr>';

        $this->content .= '<tr><th align="right"><span class="wizard-required">*</span>Код модуля:</th><td>';
        $this->content .= $this->ShowInputField("text", "module_id", Array("size" => 60));
        $this->content .= '</td></tr>';

        $this->content .= '<tr><th align="right"><span class="wizard-required">*</span>Название модуля:</th><td>';
        $this->content .= $this->ShowInputField("text", "module_name", Array("size" => 60));
        $this->content .= '</td></tr>';

        $this->content .= '<tr><th align="right"><span class="wizard-required">*</span>Версия модуля:</th><td>';
        $this->content .= $this->ShowInputField("text", "version", Array("size" => 60));
        $this->content .= '</td></tr>';

        $this->content .= '<tr><th align="right"><span class="wizard-required">*</span>Дата создания модуля:</th><td>';
        $this->content .= $this->ShowInputField("text", "version_date", Array("size" => 60));
        $this->content .= '</td></tr>';

        $this->content .= '<tr><th align="right">Описание модуля:</th><td>';
        $this->content .= $this->ShowInputField("textarea", "module_desc", Array("col" => 120, 'rows'=>4));
        $this->content .= '</td></tr>';

        $this->content .= '</table>';
        $this->content .= '<br /><div class="wizard-note-box"><span class="wizard-required">*</span> Поля, обязательные для заполнения.</div>';
    }
}

class SuccessStep extends CWizardStep
{
    function InitStep()
    {
        $this->SetStepID("success");//ID шага
        $this->SetTitle("Работа мастера успешно завершена");//Заголовок
        //Навигация
        $this->SetCancelStep("success");
        $this->SetCancelCaption("Готово");
    }

    function ShowStep()
    {
        $this->content .= "Мастер успешно добавил новый модуль.";

        $wizard =& $this->GetWizard();
        $d7module = intval($wizard->GetVar("d7module"));
        if ($d7module > 0)
            $this->content .= 'Создан новый модуль - '.$d7module;
    }
}

class CancelStep extends CWizardStep
{
    function InitStep()
    {
        $this->SetTitle("Мастер прерван");
        $this->SetStepID("cancel");
        $this->SetCancelStep("cancel");
        $this->SetCancelCaption("Закрыть");
    }

    function ShowStep()
    {
        $this->content .= "Мастер создания нового модуля прерван.";
    }
}

