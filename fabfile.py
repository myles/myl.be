#!/usr/bin/env python

import os
import datetime
import ConfigParser

from fabric.api import env, task, roles, put, cd, hosts, local, run, get

from boto.s3.key import Key
from boto.s3.connection import S3Connection

env.user = 'myles'
env.use_ssh_config = True
env.cwd = '/srv/www/myl.be/www/'
env.hosts = ['bear.mylesbraithwaite.com']

env.roledefs = {
    'yourls': ['bear.mylesbraithwaite.com']
}

env.database_name = 'myl_be_www'
env.database_username = 'myl_be_www'
env.database_password = 'myl_be_www'


@task
@roles('yourls')
def deploy_www():
    with cd('/srv/www/myl.be/www/html/'):
        put('./www/index.php', 'index.php')
        put('./www/json.php', 'json.php')
        put('./www/rss.php', 'rss.php')
        put('./www/csv.php', 'csv.php')
        put('./www/sitemap.php', 'sitemap.php')
        put('./www/htaccess', '.htaccess')


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


@task
@hosts('localhost')
def update_landing_page():
    dropshare_landing_page = os.path.expanduser(('~/Library/'
                                                 'Application Support/'
                                                 'Dropshare 4/'
                                                 'CustomLandingPage.html'))

    local('cp ./files/landing-page.html "{0}"'.format(dropshare_landing_page))


@task
@roles('yourls')
def backup_mysql():
    timestamp = datetime.datetime.now().isoformat().replace(':', '-')

    file_name = '/tmp/%(database)s-backup-%(timestamp)s.sql.gz' % {
        'database': env.database_name,
        'timestamp': timestamp
    }

    run('mysqldump -u %(username)s -p%(password)s %(database)s | '
        'gzip > %(file_name)s' % {'username': env.database_username,
                                  'password': env.database_password,
                                  'database': env.database_name,
                                  'file_name': file_name})

    get(file_name, os.path.join('./backups/', os.path.basename(file_name)))

    run('rm "%s"' % file_name)


@task
@roles('yourls')
def backup_config():
    timestamp = datetime.datetime.now().isoformat().replace(':', '-')

    get('html/user/config.php',
        './backups/config-backup-%(timestamp)s.php' % {'timestamp': timestamp})


@task
@roles('yourls')
def backup():
    backup_mysql()
    backup_config()
