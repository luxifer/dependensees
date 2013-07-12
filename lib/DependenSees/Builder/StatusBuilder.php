<?php

namespace DependenSees\Builder;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class StatusBuilder
{
    protected $loader;
    protected $twig;
    protected $fs;
    protected $root;

    public function __construct()
    {
        $this->root = __DIR__.'/../../../public';
        $this->loader = new \Twig_Loader_Filesystem(__DIR__.'/templates');
        $this->fs = new Filesystem();
        $this->twig = new \Twig_Environment($this->loader);
    }

    public function render($package, $rows)
    {
        $rows = $this->prepareRows($rows);

        $output = $this->twig->render('index.html', array(
            'package' => $package,
            'rows'    => $rows
        ));

        $handler = fopen($this->root.'/index.html', 'w+');
        fwrite($handler, $output);
        fclose($handler);
    }

    protected function prepareRows($rows)
    {
        foreach (&$rows as $row) {
            $status = end($row);
            $row['color'] = $status === '-' ? 'success' : 'error';
        }
    }
}