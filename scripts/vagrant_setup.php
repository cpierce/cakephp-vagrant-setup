<?php

/*
 * Vagrant Setup Script
 *
 * (c) 2016 Chris Pierce
 * Chris Pierce <cpierce@csdurant.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$options = cmd_line(is_array($argv) ? $argv : []);
/**
 * Hostname check
 *
 * @param string $hostname
 *
 * @return boolean
 */
function hostname_check($hostname = null)
{
    if (empty($hostname))
        return false;

    $host_file = file_get_contents('/etc/hosts');
    if (strpos($host_file, $hostname))
        return true;

    return false;
}

/**
 * Process command line arguments
 */
function cmd_line($argv)
{
    $options['ip_address'] = '192.168.33.77';
    $options['cpu_limit'] = 1;
    $options['memory_limit'] = 1024;
    $options['hostname'] = 'cakephp.dev';
    $options['append_host_file'] = true;

    foreach ($argv as $key => $val) {
        if (0 === strpos($val, '--ip-address')) {
            if (12 === strlen($val) && isset($argv[$key+1])) {
                $options['ip_address'] = trim($argv[$key+1]);
            } else {
                $options['ip_address'] = trim(substr($val, 13));
            }
        }

        if (0 === strpos($val, '--cpu-limit')) {
            if (11 === strlen($val) && isset($argv[$key+1])) {
                $options['cpu_limit'] = trim($argv[$key+1]);
            } else {
                $options['cpu_limit'] = trim(substr($val, 12));
            }
        }

        if (0 === strpos($val, '--memory-limit')) {
            if (14 === strlen($val) && isset($argv[$key+1])) {
                $options['memory_limit'] = trim($argv[$key+1]);
            } else {
                $options['memory_limit'] = trim(substr($val, 15));
            }
        }

        if (0 === strpos($val, '--hostname')) {
            if (10 === strlen($val) && isset($argv[$key+1])) {
                $options['hostname'] = trim($argv[$key+1]);
            } else {
                $options['hostname'] = trim(substr($val, 11));
            }
        }

        if (0 === strpos($val, '--skip-host')) {
            if (11 === strlen($val)) {
                $options['append_host_file'] = false;
            }
        }
    }

    return $options;
}

$ip_address = $options['ip_address'];
$memory_limit = $options['memory_limit'];
$cpu_limit = $options['cpu_limit'];

$vagrant_file = <<<VAGRANT_FILE_CONTENTS
# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.require_version ">= 1.8.1"

VAGRANT_API_VERSION = "2"
GUEST_NETWORK_IP = "$ip_address"
GUEST_MEMORY_LIMIT = "$memory_limit"
GUEST_CPU_LIMIT = "$cpu_limit"

#########################################################
# You shouldn't have to modify anything below this line #
#########################################################

Vagrant.configure(VAGRANT_API_VERSION) do |config|

    config.vm.box = "boxcutter/ubuntu1504"

    config.vm.network "private_network", ip: GUEST_NETWORK_IP

    # Allow more memory usage for the VM
    config.vm.provider :virtualbox do |v|
        v.memory = GUEST_MEMORY_LIMIT
        v.cpus = GUEST_CPU_LIMIT
    end

    # forward agent for ansible access
    config.ssh.forward_agent = true

    config.vm.synced_folder "./", "/vagrant", type: "nfs"

    config.vm.provision "shell", inline: <<-SHELL
        apt-get update
        apt-get install -y -qq git python-dev python-pip
        pip install markupsafe
        pip install ansible
        ssh -T git@bitbucket.org -o StrictHostKeyChecking=no
        PYTHONUNBUFFERED=1 ansible-pull \
            --url=git@github.com:cpierce/cakephp-vagrant-setup.git \
            --inventory-file inventories/localhost \
            dev-standalone.yml
    SHELL

end

VAGRANT_FILE_CONTENTS;
file_put_contents('Vagrantfile', $vagrant_file);

if ($options['append_host_file'] === true) {
    $hostname_check = hostname_check($options['hostname']);

    if (!$hostname_check) {
        echo $options['ip_address'] . '  ' . $options['hostname'] . ' www.' . $options['hostname'] . "\n";
    }
}
