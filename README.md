Composer Dependencies Command-line checker
==========================================

[![Latest Stable Version](https://poser.pugx.org/luxifer/dependensees/v/stable.png)](https://packagist.org/packages/luxifer/dependensees) [![Total Downloads](https://poser.pugx.org/luxifer/dependensees/downloads.png)](https://packagist.org/packages/luxifer/dependensees)

Installation
------------

```json
{
    "require": {
        "luxifer/dependensees": "dev-master"
    },
    "config": {
        "bin-dir": "bin/"
    }
}
```

Usage
-----

```bash
$ php bin/dependensees
```

Command output
--------------

```bash
Name        : luxifer/dependensees
version     : 1.1.0
Description : Command-line tool to check wether composer dependencies are up to date
Stability   : stable

Processing.......

Name                    | Installed  | Required       | Available  | Outdated
------------------------+------------+----------------+------------+---------
symfony/console         | v2.3.1     | >=2.1,<2.4-dev | v2.3.1     | -
composer/composer       | dev-master | 1.0.*@dev      | dev-master | -
guzzle/guzzle           | v3.7.1     | *              | v3.7.1     | -
symfony/filesystem      | v2.3.1     | >=2.1,<2.4-dev | v2.3.1     | -
components/bootstrap    | 2.3.2      | 2.3.2          | 2.3.2      | -
twig/twig               | v1.13.1    | 1.*            | v1.13.1    | -
components/font-awesome | 3.2.0      | 3.2.0          | 3.2.0      | -

0 of 7 packages are outdated.

Building HTML status page... Done!
```