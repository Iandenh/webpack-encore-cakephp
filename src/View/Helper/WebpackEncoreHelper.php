<?php

namespace Iandenh\WebpackEncore\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Iandenh\WebpackEncore\Lib\EntrypointLookup;
use Iandenh\WebpackEncore\Lib\ManifestLookup;

/**
 * Class WebpackEncoreHelper
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @package Iandenh\WebpackEncore\View\Helper
 */
class WebpackEncoreHelper extends Helper
{
    use StringTemplateTrait;

    public $helpers = ['Html'];
    protected $_defaultConfig = [
        'templates' => [
            'css' => '<link rel="{{rel}}" href="{{url}}"/>',
            'javascript' => '<script src="{{url}}"</script>',
        ],
        'manifestPath' => WWW_ROOT . 'dist' . DS . 'manifest.json',
        'entrypointPath' => WWW_ROOT . 'dist' . DS . 'entrypoints.json'
    ];

    /**
     * @var \Iandenh\WebpackEncore\Lib\EntrypointLookup
     */
    protected $entrypointLookup;

    /**
     * @var \Iandenh\WebpackEncore\Lib\ManifestLookup
     */
    protected $manifestLookup;

    /**
     * Initialize Helper
     *
     * @param array $config The configuration settings provided to this helper.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->entrypointLookup = new EntrypointLookup($this->config('manifestPath'));
        $this->manifestLookup = new ManifestLookup($this->config('entrypointPath'));
    }

    public function entryScripts($entryName)
    {
        $scripts = [];
        foreach ($this->entrypointLookup->getScripts($entryName) as $script) {
            $scripts[] = $this->formatTemplate('javascript', ['url' => $script]);
        }

        return implode('', $scripts);
    }

    public function entryCss($entryName)
    {
        $cssLinks = [];
        foreach ($this->entrypointLookup->getCss($entryName) as $css) {
            $cssLinks[] = $this->formatTemplate('css', ['url' => $css, 'rel' => 'stylesheet']);
        }

        return implode('', $cssLinks);
    }

    public function script($url, array $options = [])
    {
        $url = $this->manifestLookup->getFile($url);

        return $this->Html->script($url, $options);
    }

    public function css($url, array $options = [])
    {
        $url = $this->manifestLookup->getFile($url);

        return $this->Html->css($url, $options);
    }
}
