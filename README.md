# Vagrant Setup for CakePHP 3.x

This will generate the config files needed to get your vagrant environment setup.

## Prerequisites

- Virtualbox > 5.0
- Vagrant > 1.8.1
- Git
- Root access to your local machine

## Running the generator

To run the generator use the following command:

```
$ curl -sS https://raw.githubusercontent.com/cpierce/cakephp-vagrant-setup/master/scripts/vagrant_setup.php | php -- --ip-address 192.168.33.77 --hostname cakephp.dev | sudo tee -a /etc/hosts
```

After the Vagrant file is created you can simply use the `vagrant up` command to start provisioning your local environment!

This will install the Vagrantfile you'll need and then also modify your /etc/hosts unless you specify otherwise.  All available options are listed in the table below.

| Option           | Description                                                             | Example                      |
|------------------|-------------------------------------------------------------------------|------------------------------|
| `--ip-address`   | IP Address for your virtual machine to use.                             | `--ip-address 192.168.33.77` |
| `--hostname`     | Hostname to use for virtual environment.                                | `--hostname cakephp.dev`     |
| `--memory-limit` | Amount of memory in MB virtual environment should consume when running. | `--memory-limit 1024`        |
| `--cpu-limit`    | Number of virtual CPUs to give virtual environment.                     | `--cpu-limit 1`              |
| `--skip-host`    | Skip the echo of the hostfile information if you don't want to do the `sudo tee -a /etc/hosts` part or do the host setup manually.                          | `--skip-host` |

**Note:** While you can have multiple hosts with the same IP Address, you cannot run them at the same time so it is always recommended to change the host IP Address for each configuration you setup.
