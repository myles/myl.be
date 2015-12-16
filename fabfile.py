#!/usr/bin/env python

from fabric.api import env, task, roles, put, cd

env.user = 'myles_mylbe'
env.hosts = ['ssh.phx.nearlyfreespeech.net']
env.use_ssh_config = True

env.roledefs = {
    'yourls': ['ssh.phx.nearlyfreespeech.net']
}


@task
@roles('yourls')
def upload_www():
    with cd('/home/public/'):
        put('./www/index.php', 'index.php')
