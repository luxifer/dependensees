<?php

namespace DependenSees\Builder;

use Symfony\Component\Filesystem\Filesystem;
use DependenSees\DependenSees;

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
        $root = $this->normalizePath($root);
        $this->root = $root.'/build/dependensees';
        $this->loader = new \Twig_Loader_Filesystem(__DIR__.'/templates');
        $this->fs = new Filesystem();
        $this->twig = new \Twig_Environment($this->loader);
        $this->outdated = 0;
        $this->ensureDirectoryExist($this->root);
        $this->moveAssets($root);
    }

    public function render($package, array $prod, array $dev = array())
    {
        $prod = $this->prepareRows($prod);

        if (count($dev)) {
            $dev = $this->prepareRows($dev);
        }

        $output = $this->twig->render('index.html.twig', array(
            'package'  => $package,
            'prod'     => $prod,
            'dev'      => $dev,
            'outdated' => $this->outdated,
            'count'    => count(array_merge($prod, $dev)),
            'version'  => DependenSees::VERSION
        ));

        $this->dump($this->root.'/index.html', $output);
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
        $base = $dir.'/components';
        $dest = $dir.'/build/dependensees/assets';

        if (!$this->fs->exists($dest)) {
            $this->fs->mkdir($dest);
            $this->walk($base, $dest);
        }
    }

    protected function walk($base, $dest)
    {
        $dir = opendir($base);

        while(false !== ($file = readdir($dir))) {
            if (is_dir($base.'/'.$file) && $file != '.' && $file != '..') {
                $this->fs->mkdir($dest.'/'.$file);
                $this->walk($base.'/'.$file, $dest.'/'.$file);
            } elseif (is_file($base.'/'.$file)) {
                $this->fs->copy($base.'/'.$file, $dest.'/'.$file);
            }
        }

        closedir($dir);
    }

    protected function dump($file, $content)
    {
        $handler = fopen($file, 'w+');
        fwrite($handler, $content);
        fclose($handler);
    }

    protected function normalizePath($dir)
    {
        return is_link($dir) ? readlink($dir) : $dir;
    }
}