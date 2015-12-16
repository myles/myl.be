#!/usr/bin/env python

import os
import ConfigParser

from fabric.api import env, task, roles, put, cd, local, hosts

from boto.s3.key import Key
from boto.s3.connection import S3Connection

env.user = 'myles_mylbe'
env.hosts = ['ssh.phx.nearlyfreespeech.net']
env.use_ssh_config = True

env.roledefs = {
    'yourls': ['ssh.phx.nearlyfreespeech.net']
}


@task
@roles('yourls')
def deploy_www():
    with cd('/home/public/'):
        put('./www/index.php', 'index.php')


@task
@hosts('localhost')
def deploy_files():
    aws_config = ConfigParser.ConfigParser()
    aws_config.read(['credentials', os.path.expanduser('~/.aws/credentials')])

    conn = S3Connection(aws_config.get('default', 'aws_access_key_id'),
                        aws_config.get('default', 'aws_secret_access_key'))

    bucket = conn.get_bucket('files.myl.be')

    index = Key(bucket)
    index.key = 'index.html'
    index.set_contents_from_filename('./files/index.html')
    index.set_acl('public-read')
