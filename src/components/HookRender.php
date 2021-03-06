<?php

namespace zacksleo\yii\plugin\components;

use yii;
use zacksleo\yii\plugin\libs\PluginAbstract;
use zacksleo\yii\plugin\models\Plugins;

$moduleDir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';
Yii::setPathOfAlias('pluginModule', $moduleDir);

class HookRender
{
    protected $hooks = array();

    public function init()
    {
        $this->getHooks();
    }

    public function render($pos)
    {
        if (empty($this->hooks)) {
            return;
        }
        $hooks = $this->hooks[$pos];
        if (!$hooks) {
            return;
        }

        foreach ($hooks as $hook) {
            @include_once($hook['path'] . DIRECTORY_SEPARATOR . $hook['identify'] . 'Plugin.php');
            $class = $hook['identify'] . 'Plugin';
            if (!class_exists($class)) {
                continue;
            }
            $plugin = new $class();
            $act = explode('.', $hook['hook']);
            if ($act[1]) {
                $h = $plugin->LoadHook($hook['hook']);
                if (!$h) {
                    continue;
                }
                $h->run();
            } else {
                $render = $act[0];
                $plugin->$render();
            }
        }
    }

    protected function getHooks()
    {
        $plugins = Plugins::model()->findAllByAttributes(array('enable' => 1));
        if (!$plugins) {
            return;
        }
        foreach ($plugins as $plugin) {
            foreach (unserialize($plugin->hooks) as $pos => $hook) {
                $this->hooks[$pos][] = array('identify' => $plugin->identify, 'path' => $plugin->path, 'hook' => $hook);
            }
        }
        return true;
    }
}
