<?php

namespace DependenSees\Builder;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Florent Viel <luxifer666@gmail.com>
 */
class StatusBuilder
{
    protected $loader;
    protected $twig;
    protected $fs;
    protected $root;
    protected $outdated;

    public function __construct($root)
    {
        $this->root = $root.'/../build/dependensees';
        $this->loader = new \Twig_Loader_Filesystem(__DIR__.'/templates');
        $this->fs = new Filesystem();
        $this->twig = new \Twig_Environment($this->loader);
        $this->outdated = 0;
        $this->ensureDirectoryExist($this->root);
        $this->moveAssets($root.'/..');
    }

    public function render($package, $rows)
    {
        $rows = $this->prepareRows($rows);

        $output = $this->twig->render('index.html', array(
            'package'  => $package,
            'rows'     => $rows,
            'outdated' => $this->outdated,
            'count'    => count($rows)
        ));

        $handler = fopen($this->root.'/index.html', 'w+');
        fwrite($handler, $output);
        fclose($handler);
    }

    protected function prepareRows($rows)
    {
        foreach ($rows as &$row) {
            $row['color'] = $row['status'] === '-' ? 'success' : 'error';
            $this->outdated += $row['status'] === '-' ? 0 : 1;
            $row['href'] = sprintf('https://packagist.org/packages/%s', $row['name']);
        }

        return $rows;
    }

    protected function ensureDirectoryExist($dir)
    {
        if (!$this->fs->exists($dir)) {
            $this->fs->mkdir($dir);
        }
    }

    protected function moveAssets($dir)
    {
        if (!$this->fs->exists($dir.'/build/dependensees/assets')) {
            $this->fs->rename($dir.'/components', $dir.'/build/dependensees/assets');
        }
    }
}