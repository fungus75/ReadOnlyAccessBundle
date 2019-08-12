# ReadOnlyAccessBundle

A Kimai 2 plugin, which allows to give users a readonly access to limited Companies defined in Kimai.

The Developer of this plugin wishes to thank the creator of the CustomCSS Plugin because it was very helpfull. - https://github.com/Keleo/CustomCSSBundle

## Requirement

Requires Kamai 2, V1.1 or higher

## Installation

First clone it to your Kimai installation `plugins` directory:
```
cd /kimai/var/plugins/
git clone https://github.com/fungus75/ReadOnlyAccessBundle.git
```

And then rebuild the cache: 
```
cd /kimai/
bin/console cache:clear
bin/console cache:warmup
```

## Permissions

This bundle ships a new administration screen, which will be available for the following users:

- `ROLE_SUPER_ADMIN` - every super administrator
- `edit_readonly_user` - every use that owns this permission 

This bundle also ships a new user-screen, which is available for users that have configured a client
In the new administration screen, this configuration can be made
 
## Screenshot

![Screenshot](https://raw.githubusercontent.com/fungus75/ReadOnlyAccessBundle/master/screenshot.jpg)


