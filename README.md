# SoTAdOff
[![Build Status](https://travis-ci.org/EugenGanshorn/SoTAdOff.svg?branch=master)](https://travis-ci.org/EugenGanshorn/SoTAdOff)
[![works badge](https://cdn.rawgit.com/nikku/works-on-my-machine/v0.2.0/badge.svg)](https://github.com/nikku/works-on-my-machine)

###### Installation
0. `git clone https://github.com/EugenGanshorn/SoTAdOff.git`
1. `composer install`
2. `bin/console doctrine:migrations:migrate`
3. `bin/console app:find-tasmota-devices 10.0.10.1 10.0.10.255`
4. `bin/console fos:user:create`
5. `bin/console fos:user:activate`
6. `bin/console fos:user:promote` (add ROLE_ADMIN)
7. Open Webinterface http://10.0.10.1/admin (backend) or http://10.0.10.1/ (frontend)
