# EasyAdminPlusBundle

### About

EasyAdminPlusBundle is a Symfony 4 wrapper for the amazing [EasyCorp/EasyAdminBundle](https://github.com/EasyCorp/EasyAdminBundle/tree/master) which includes some extra features. 

### Requirements

* PHP >= 7.1
* Symfony 4
* EasyAdminBundle ^2.0

### Install

```shell
$ composer require cisse/easyadmin-plus-bundle
```

### Replace EasyAdmin controller

Load routes from our `AdminController` or yours but make sure it extends `CisseEasyAdminPlusBundle` Controller

```yaml
# config/routes/easy_admin.yaml
easy_admin_bundle:
    resource: '@CisseEasyAdminPlusBundle/Controller/AdminController.php'
    prefix: /admin
    type: annotation
```
