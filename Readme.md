# Project Update Watcher 🔎

This is a system of reporting about outdated composer dependencies that are used in a project.
Originally, it was designed to be used on Magento 2 based projects 
where custom modules are frequently installed via Composer.
However, the idea is platform-agnostic.

## Requirements

- PHP 7.1
- CRON
- Sendmail
- GIT
- Composer

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
- CLI `./vendor/bin/robo watcher:check-outdated-dependency`


