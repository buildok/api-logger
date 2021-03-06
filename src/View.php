<?php
namespace buildok\logger;


/**
*  View Class
*/
class View
{
    /**
     * Path to templates
     * @var string
     */
    private $path;

    /**
     * Initialization
     */
    public function __construct()
    {
        $this->path = dirname(__FILE__) . '/views/';
    }
    /**
     * get html-markup from template view
     * @param string $template template name
     * @param mixed[] $params array of content data
     * @return string content
     */
    public function renderPartial($template, $params = [])
    {
        extract($params);

        ob_start();
        include $this->path . $template . '.php';

        return ob_get_clean();
    }

    /**
     * get html-markup from template view with layout
     * @param string $template template name
     * @param mixed[] $params array of content data
     * @return string content
     */
    public function render($params = [], $template = 'show')
    {
        $content = $this->renderPartial($template, $params);

        echo $this->renderPartial('layout', ['content' => $content]);
    }
}