# Module Watcher 🔎

Module Watcher makes sure that you won't miss any update from your third-party modules 
installed on the Magento2-based projects.

The project is on MVP stage and has a lot of var_dumps() 😄

## Requirements

- PHP 7.2
- Cron/CI system
- Git
- php mail()/Swift Mail/Slack

## Workflow

### 1. Add a new project to the config file

Module Watcher has a common config files (possible to have a couple of them) 
that helps to declare and share watcher configurations. Example of the config file can be found
under `module-watcher.yaml.sample`.

### 2. Install your project

Module Watcher uses Git to access your project branch you want to track dependencies on.
Before watching you need to run `project:install-projects` command to make sure all projects and their configs are
installed and ready to be watched.

It's good to run this command after adding a new project, changing actual branch or changing list of branches.

### 3. Watch Your Modules

After your config file is installed, you are ready to run `project:watch` command and 
get notifications about module updates.

It's convenient to run the command from Cron or CLI to constantly get notifications and don't forget about this action.

The command makes project branches up to date and then performs checks for
Magento2 modules installed in all possible ways.

## Magento2 Modules

Module Watcher is capable to track the following types of third-party module installations:

- ✅ Third-party modules installed via Composer from Vendor Packagists
- ⚠️ Third-party modules installed via Composer from SI Packagists
- 🚨 Third-party modules committed to the Codebase

## Notification Channels

We are going to support the following notification channels:

- Email via PHP mail()
- Email via SwiftMail
- Slack
