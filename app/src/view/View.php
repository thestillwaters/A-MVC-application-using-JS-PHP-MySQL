<?php


namespace yh\a3\View;

use yh\a3\exception\TemplatesNotFoundException;

use const yh\a3\APP_ROOT;

class View
{
    /**
     * @var string Path to template being rendered
     */
    protected $template = null;
    /**
     * @var array data to be made available to the template
     */
    protected $data = array();

    /**
     * View constructor.
     *
     * @param string $template Path to template being rendered
     */
    public function __construct($template)
    {
        try {
            $file =  APP_ROOT . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR .
                'templates' . DIRECTORY_SEPARATOR . $template . '.phtml';
            if (file_exists($file)) {
                $this->template = $file;
            } else {
                throw new \yh\a3\Exception\TemplatesNotFoundException('Template ' . $template . ' not found!');
            }
        } catch (TemplatesNotFoundException $e) {
            echo $e;
        }
    }
    /**
     * Adds a key/value pair to be available to phtml template
     * @param string $key name of the data to be available
     * @param object $val value of the data to be available
     * @return $this View
     */
    public function addData($key, $val)
    {
        $this->data[$key] = $val;
        return $this;
    }

    /**
     * Render the template, returning it's content.
     * @return string The rendered template.
     */
    public function render()
    {
        //View->data
        extract($this->data);

        ob_start();
        include($this->template);
        /**  Save content in string into $content */
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
