#!/usr/bin/php -w
<?php
$vagrant_ip = readline('Vagrant IP Address [192.168.33.77]:  ');
if (empty($vagrant_ip)) {
    $vagrant_ip = '192.168.33.77';
}
$vagrant_file = <<<VAGRANT_FILE_CONTENTS
# -*- mode: ruby -*-
# vi: set ft=ruby :
Vagrant.require_version ">= 1.7.4"

VAGRANT_API_VERSION = "2"
GUEST_NETWORK_IP = "$vagrant_ip"
GUEST_MEMORY_LIMIT = "1024"
GUEST_CPU_LIMIT = "1"

#########################################################
# You shouldn't have to modify anything below this line #
#########################################################

Vagrant.configure(VAGRANT_API_VERSION) do |config|

    config.vm.box = "ubuntu/vivid64"

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
        apt-get install -y -qq python-dev python-pip
        pip install ansible
        ssh -T git@bitbucket.org -o StrictHostKeyChecking=no
        PYTHONUNBUFFERED=1 ansible-pull \
            --url=git@bitbucket.org:csdurant/server-playbook.git \
            --inventory-file inventories/localhost \
            dev-standalone.yml
    SHELL

end

VAGRANT_FILE_CONTENTS;
file_put_contents('Vagrantfile', $vagrant_file);
