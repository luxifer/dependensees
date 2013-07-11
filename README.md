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

+-------------------+------------+------------+------------+
| Name              | Installed  | Available  | Up to date |
+-------------------+------------+------------+------------+
| symfony/console   | 2.3.x-dev  | dev-master | KO         |
| composer/composer | dev-master | dev-master | OK         |
| guzzle/guzzle     | dev-master | dev-master | OK         |
+-------------------+------------+------------+------------+

2 of 3 packages are up to date.
```

TODO
----

* Build gemnasium-like html page