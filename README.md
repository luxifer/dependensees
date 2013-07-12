Composer Dependencies Command-line checker
==========================================

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
version     : 1.0.0-dev
Description : Command-line tool to check wether composer dependencies are up to date

Processing...

Name                 | Installed  | Available  | Outdated
---------------------+------------+------------+---------
symfony/console      | v2.3.1     | v2.3.1     | -
composer/composer    | dev-master | dev-master | -
guzzle/guzzle        | v3.7.1     | v3.7.1     | -
symfony/filesystem   | v2.3.1     | v2.3.1     | -
components/bootstrap | 2.3.2      | 2.3.2      | -
twig/twig            | v1.13.1    | v1.13.1    | -

0 of 6 packages are outdated.
```

TODO
----

* Build gemnasium-like html page