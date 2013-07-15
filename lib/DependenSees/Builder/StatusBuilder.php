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
    protected $outdated;

    public function __construct()
    {
        $this->root = __DIR__.'/../../../build';
        $this->loader = new \Twig_Loader_Filesystem(__DIR__.'/templates');
        $this->fs = new Filesystem();
        $this->twig = new \Twig_Environment($this->loader);
        $this->outdated = 0;
        $this->ensureDirectoryExist($this->root);
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
            $status = $row[3];
            unset($row[3]);
            $row['color'] = $status === '-' ? 'success' : 'error';
            $this->outdated += $status === '-' ? 0 : 1;
            $row['href'] = sprintf('https://packagist.org/packages/%s', $row[0]);
            $row['name'] = $row[0];
            unset($row[0]);
            $row['current'] = $row[1];
            unset($row[1]);
            $row['latest'] = $row[2];
            unset($row[2]);
        }

        return $rows;
    }

    protected function ensureDirectoryExist($dir)
    {
        if (!$this->fs->exists($dir)) {
            $this->fs->mkdir($dir);
        }
    }
}