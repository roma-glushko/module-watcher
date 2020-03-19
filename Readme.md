# Module Version Watcher 🔎

This is a system of reporting outdated composer dependencies that are used in a project.
Originally, it was designed to be used on Magento 2 based projects 
where custom modules are frequently installed via Composer.
However, the idea is platform-agnostic.

## Requirements

- PHP 7.1
- CRON/CI system
- Sendmail
- Git

## Configurations

File [robo.yml.dist](https://github.com/roma-glushko/project-update-watcher/blob/master/robo.yml.dist) should be copied to `robo.yml` file where all configuration changes should happen. Things can be configured:
- project repository configurations
- watcher directory configurations
- report email configurations
- report dependency blacklist configurations 

## Workflow

The system is based on [Robo](https://robo.li) task framework. 
[RoboFile.php](https://github.com/roma-glushko/project-update-watcher/blob/master/RoboFile.php) is an extensible point that adds two commands (described in Commands section). 
After installing the system (clonning the repository and running `composer install`), it's needed to adjust configurations and install the project via `watcher:install` command. 

Finally, the second command `watcher:check-outdated-dependency` should be run by scheduler or CI system.

## Commands

### watcher:install

This one helps to install everything that is needed to watch for project dependency updates. 
Configurations from `robo.yml` and `robo.yml.dist` in the root of project is used while installing. 

### watcher:check-outdated-dependency

This one is used for checking whenever project has any dependency that is outdated. 
Collected project update report is being sent by email.
Dependencies can be blacklisted to keep project update report clean and useful.

There are two types of reporting:
- email: `./vendor/bin/robo watcher:check-outdated-dependency email`
- CLI: `./vendor/bin/robo watcher:check-outdated-dependency`
