# SnowTricks

### Openclassrooms / Projet 6 : Développez de A à Z le site communautaire SnowTricks
[![Maintainability](https://api.codeclimate.com/v1/badges/adab98eec5399cb95d52/maintainability)](https://codeclimate.com/github/jucarre/SnowTricks/maintainability)

## Getting Started :metal:

1. copy repository `git clone git@github.com:jucarre/SnowTricks.git`
2. `composer install`
3. configure BDD connect on `.env` file
4. (local) start the PHP server `php bin/console server:start`
5. (optional) configure the SMTP if you want to activate the registration in `config/packages/swiftmailer.yaml`
6. Create database `php bin/console doctrine:database:create`
7. Migrate table on database `php bin/console doctrine:migrations:migrate`
8. (for test) load fixtures into the database `php bin/console doctrine:fixtures:load`
9. Account fixtures:
      * Role : Admin
        * Email : superadmin@admin.fr
        * Pass : root
     * Role : User
        * Email : user@user.fr
        * Pass : root
     * Role : User with no content
        * Email : bob@bob.fr
        * Pass : root
