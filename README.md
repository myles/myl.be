# myl.be

My [URL shortening](https://en.wikipedia.org/wiki/URL_shortening) service based on the web based [YOURLS](http://yourls.org/) and OS X/iOS based [Dropshare](https://getdropsha.re/).

## Fabric Commands

* `fab deploy_www`: Deploy some stuff to <http://myl.be/>
* `fab deploy_files`: Deploy some stuff to <http://files.myl.be/>
* `fab update_landing_page`: Update the Landing Page for Droupshare drops.
* `fab backup_mysql`: Backup YOURLS' MySQL database.
* `fab backup_config`: Backup YOURLS' config file.
* `fab backup`: Backup and encrypt everything.