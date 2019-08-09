# dPlanet

![Developers planet banner](src/assets/images/backgrounds/banner2.png 'banner')

A simple social media website to enhance your development skills.

## Prerequisites

In order to run this application in an isolated environment and to make installing it
easier, all you need is Docker. It's also advisable to have Make installed to
make use of the makefile.

## Getting started

Setting up the application is as easy as:
1. `git clone` the application and navigate to the project's directory
2. Run `make up` to start the application in development mode
3. Go to [localhost](https://localhost) to see the application, this might take some time 

Additionally you can also use `make fixtures` to generate test data and clear
the database.

Run the command `make` to see a list of make commands that you can use.

## Important to-do's:

-  ~~Discuss data model and make proper adjustements~~
-  ~~Apply final data model to the orm mappings~~
-  ~~Add proper initial migration~~
-  ~~Add fixtures for all data~~
-  ~~Add form types for all orm mappings~~
-  ~~Discuss deployment technique (Ansible)~~
-  ~~Discuss collaboration / code review process~~
-  ~~Create a dockerhub account for the project~~
-  Build login screen -> half done, layout needs to be fixed
-  Add style fixer
-  Add es linter
-  Add prettier configuration
-  Build layout
-  Add tests
-  Add more documentation
-  Discuss database management / backup management
-  Add translations for multinationality

## Known quirks

- Everything is still WIP

## Workflow

(WIP)

## Deployment

(WIP)

### Style fixer

All PHP code in this repository **must** be PSR compliant, to make this requirement
easy to achieve you can run the command `make php.fix` to fix all your php code. It is
also advisable to install php-cs-fixer in your IDE.

### PhpStan

To check if your php code is properly structured and does not contain easy-to-miss bugs
we have set up PhpStan for you. You can run this checker by using `make php.stan`

### PhpMyAdmin

In development phpmyadmin is running over at `http://localhost:8000`, you can
use this interface to directly view the data in the database.

**Credentials:**  
username: `dplanet`  
password: `development`  
