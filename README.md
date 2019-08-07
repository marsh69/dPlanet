# dPlanet
A simple social media website to enhance your development skills.

## Prerequisites

In order to run this application in an isolated environment and to make installing it
easier, all you need is Docker. It's also advisable to have Make installed to
make use of the makefile.

## Getting started

Setting up the application is as easy as:
1. `git clone` the application and navigate to the project's directory
2. Run `sudo make up` to start the application in development mode
3. Run `make composer.install` to install the dependencies in the php containers
4. Go to [localhost](https://localhost) to see the application, this might take some time 

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
-  Build login screen -> half done, layout needs to be fixed
-  Build layout (and discuss front- and backend integration)
-  Add tests
-  Add more documentation
-  Create a dockerhub account for the project
-  Discuss database management / backup management
-  Add translations for multinationality

## Known quirks

- Everything is still WIP

## Workflow

(WIP)

## Deployment

(WIP)
