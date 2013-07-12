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

Name                 | Installed  | Available  | Up to date
---------------------+------------+------------+-----------
symfony/console      | v2.3.1     | v2.3.1     | OK
composer/composer    | dev-master | dev-master | OK
guzzle/guzzle        | dev-master | dev-master | OK
symfony/filesystem   | v2.3.1     | v2.3.1     | OK
components/bootstrap | 2.3.2      | 2.3.2      | OK
twig/twig            | v1.13.1    | v1.13.1    | OK

6 of 6 packages are up to date.
```

TODO
----

* Build gemnasium-like html page