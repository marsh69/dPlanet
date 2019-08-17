# dPlanet

[![Build Status](https://travis-ci.com/marsh69/dPlanet.svg?branch=master)](https://travis-ci.com/marsh69/dPlanet)

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
the database. The **frontend** is running on [localhost](https://localhost) and the backend is running on 
[localhost/api](https://localhost/api).

## Known quirks

- Everything is still WIP

## Workflow

(WIP)

### Deployment

The **master** branch automatically deploys to our production server, the **develop** branch to the test server. For
this reason both of these branches are locked down and can only be changed with pull requests.

### Https in development

When you first open the homepage your browser will most likely alarm you that you're visiting an insecure page, this is completely
normal and you can ignore the warning. 

### Style fixer

All PHP code in this repository **must** be PSR compliant, to make this requirement
easy to achieve you can run the command `make php.fix` to fix all your php code. It is
also advisable to install php-cs-fixer in your IDE.

Prettier is also installed and can be invoked with `make js.fix`.

### PhpStan

To check if your php code is properly structured and does not contain easy-to-miss bugs
we have set up PhpStan for you. You can run this checker by using `make php.stan`

### PhpMyAdmin

In development phpmyadmin is running over at `http://localhost:8000`, you can
use this interface to directly view the data in the database.

**Credentials:**  
username: `dplanet`  
password: `development`  
