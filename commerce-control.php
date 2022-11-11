<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Framework\Flex\FlexDirectory;
use RocketTheme\Toolbox\File\File;
use RocketTheme\Toolbox\Event\Event;
use Grav\Common\Plugin;
use Grav\Common\Uri;
use Grav\Common\Grav;
use Grav\Common\Yaml;

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
            'onFlexInit' => ['onFlexInit', 0],
            'onPluginsInitialized' => [ ['onPluginsInitialized', 0] ],
            'onAdminTwigTemplatePaths' => ['onAdminTwigTemplatePaths', 0],
            'onGetPageTemplates' => ['onGetPageTemplates', 0]
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
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0],

            ]);
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onFormProcessed' => ['onFormProcessed', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            // Put your main events here
        ]);
    }

    public function onGetPageTemplates(Event $event)
    {
        /** @var Types $types */
        $types = $event->types;
        $types->scanTemplates('plugins://commerce-control/templates');
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
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
    public function onTwigSiteVariables() {
        $quotations = $this->getQuotations();
        $this->grav['twig']->get_quotations = $quotations;
        $this->grav['twig']->twig_vars['get_quotations'] = $quotations;

    }

    public function onFlexInit($event){
        $directory = new FlexDirectory('products', 'plugins://commerce-control/blueprints/flex-objects/customers.yaml', ['enabled'=>true]);

        $flex = $event['flex'];
        $flex->addDirectory($directory);
    }

    public static function pagesProducts(): array
    {
      /** @var Grav */
      $grav = Grav::instance();
      $grav['admin']->enablePages();

      /** @var Page */
      $teamsPage = $grav['pages']->find('/shop');

      $children = [];
      foreach ($teamsPage->children() as $team) {
        $children[] = $team->title();
      }

      return $children;
    }

    private function getQuotations(): array
    {
        $locator = $this->grav['locator'];
        $path = $locator->findResource('user-data://', true);
        $quotationFiles = [];

        if ($quotations = opendir($path.'/sales/quotations')) {
            /* This is the correct way to loop over the directory. */
            while (false !== ($q = readdir($quotations))) {
                if ($q != "." && $q != "..") {
                    $data = $this->getDataFromFilename($path.'/sales/quotations/'.$q);
                    $quotationFiles[] = [
                        'file' => $q,
                        'data' => $data
                    ];
                }
            }
        }
        return $quotationFiles;
    }


    /**
     * Given a data file route, return the YAML content already parsed
     */
    private function getDataFromFilename($fileRoute) {

        //Single item details
        $fileInstance = File::instance($fileRoute);

        if (!$fileInstance->content()) {
            //Item not found
            return;
        }

        return Yaml::parse($fileInstance->content());
    }


    public function onFormProcessed(Event $event)
    {
        $form = $event['form'];
        $action = $event['action'];
        $params = $event['params'];

        if (!$this->active) {
            return;
        }

        switch ($action) {
            case 'addCustomer':
                $data =  $form->getData();

                $filename = DATA_DIR . 'comments';
                $filename .= ($lang ? '/' . $lang : '');
                $filename .= $path . '.yaml';
                $filename = DATA_DIR . '/flex-objects/customers.json';
                $file = File::instance($filename);
                $file->save(json_encode($data));

                /*if (file_exists($filename)) {
                    $data = Yaml::parse($file->content());

                    $data['comments'][] = [
                        'text' => $text,
                        'date' => date('D, d M Y H:i:s', time()),
                        'author' => $name,
                        'email' => $email
                    ];
                } else {
                    $data = array(
                        'title' => $title,
                        'lang' => $lang,
                        'comments' => array([
                            'text' => $text,
                            'date' => date('D, d M Y H:i:s', time()),
                            'author' => $name,
                            'email' => $email
                        ])
                    );
                }

                $file->save(Yaml::dump($data));*/


                break;
        }
    }

}
