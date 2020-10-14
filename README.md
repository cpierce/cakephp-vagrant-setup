# Vagrant Setup for CakePHP 3.x

This will generate the config files needed to get your vagrant environment setup for cakePHP running on Ubuntu 20.04 with PHP 7.4 on nginx.

## Prerequisites

- Virtualbox >= 6.0
- Vagrant >= 2.2.2
- Git
- Root access to your local machine

## Running the generator

To run the generator use the following command:

```
curl -sS https://raw.githubusercontent.com/cpierce/cakephp-vagrant-setup/master/scripts/vagrant_setup.php | php -- --ip 192.168.33.77 --hostname dev.cpierce.org
```

After the Vagrant file is created you can simply use the `vagrant up` command to start provisioning your local environment!

This will install the Vagrantfile you'll need. All available options are listed in the table below.

| Option           | Description                                                             | Example                      |
|------------------|-------------------------------------------------------------------------|------------------------------|
| `ip`           | IP Address for your virtual machine to use.                             | `--ip 192.168.33.77` |
| `hostname`     | Hostname to use for virtual environment.                                | `--hostname dev.cpierce.org`     |
| `memory-limit` | Amount of memory in MB virtual environment should consume when running. | `--memory-limit 1024`        |
| `cpu-limit`    | Number of virtual CPUs to give virtual environment.                     | `--cpu-limit 1`              |
| `skip-host`    | Skip the echo of the hostfile information if you don't want to do the `sudo tee -a /etc/hosts` part or do the host setup manually.                          | `--skip-host` |

**Note:** While you can have multiple hosts with the same IP Address, you cannot run them at the same time so it is always recommended to change the host IP Address for each configuration you setup.
**Note:** Also thanks to Google making .dev (TLD) a require https on the HSTS you'll now have to either use another tld that isn't real or just do what I've been doing and update the dns for your domain such
as `dev.cpierce.org`.  One benefit of this is that you don't have to make changes to your host file and multiple people can use the same dev hostname. If you don't know how to do this you are welcome to use
the default http://dev.cpierce.org/ to develop with.
