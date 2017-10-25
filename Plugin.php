<?php

namespace Kanboard\Plugin\EmailToAssigned;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\EmailToAssigned\Action\TaskEmailToAssigned;

class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new TaskEmailToAssigned($this->container));
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }    
    
    public function getPluginName()
    {
        return 'Email to assigned user.';
    }

    public function getPluginDescription()
    {
        return t('Add automatic action: Send email to assigned user after change column.');
    }

    public function getPluginAuthor()
    {
        return 'Kamil Ściana';
    }

    public function getPluginVersion()
    {
        return '0.0.2';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/ksciana/kanboard-plugin-email-to-assigned';
    }

    public function getCompatibleVersion()
    {
        return '>= 1.0.48';
    }
}
