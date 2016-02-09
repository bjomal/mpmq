# MPMQ - My Precious Message Queue
This is a simple implementation of a Message Queue in PHP/Slim/SQLite3. It's supposed to be accessed using RESTful methods to the server.

## Installation notes
### SELinux
When installing on a linux distro using SELinux, you need to enable write access to det folder where the database is supposed to be located.
`chcon -t httpd_sys_rw_content_t "/var/www/html/mpmq/app/data"`
You have to replace the path with the proper path on your webserver

Another command that might help, but is unverified:
`semanage fcontext --add -t httpd_sys_rw_content_t "/var/www/html/mpmq/app/data(/.*)?"`
Also here you need to replace path for your webserver.
