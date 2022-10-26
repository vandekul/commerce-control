<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Common\Uri;

/**
 * Class CommerceControlPlugin
 * @package Grav\Plugin
 */
class CommerceControlPlugin extends Plugin
{

    /** @var Uri */
    protected $uri;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['onPluginsInitialized', 0]
            ],
            'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0],
        ];
    }
    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            $this->enable([
                'onAdminDashboard' => ['onAdminDashboard', 1000],
            ]);
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            // Put your main events here
        ]);
    }

    public function onAdminTwigTemplatePaths($event): void
    {
        $paths = $event['paths'];
        $paths[] = __DIR__ . '/admin/themes/grav/templates';
        $event['paths'] = $paths;
    }

    public function onAdminDashboard()
    {
        $twig = $this->grav['twig'];

        // Dashboard
        $twig->plugins_hooked_nav['PLUGIN_PAGE_COMMERCE_CONTROL.PAGE_CENTRAL'] = [
            'route' => 'commerce-control',
            'icon' => 'fa-bullseye',
            'authorize' => ['admin.login', 'admin.super'],
            'priority' => 900
        ];
    }

}
