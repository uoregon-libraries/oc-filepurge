# File Purge

Adds the capability to purge files that haven't been touched for a while via a
special purge command run on the command line.

In order to avoid having unintended deletions, the purge command doesn't just
find and destroy random files:

- The command runs for a specified user, and only a single user at a time
- The command takes a number of days to consider a file "old"
- The files deleted must be under a user's top-level `auto-purge` folder

## Requirements

The only hard requirement is OwnCloud 7 (haven't tested on 8).  For CentOS 7,
this can be gotten via `yum install owncloud`.  Yum will handle the
dependencies for OwnCloud, resulting in a nearly ready server.

It's probably a good idea to install MySQL as well, but the built-in support
for SQLite may be sufficient for smaller installations.

## Plugin installation and setup

Note: All `command line examples` have been tested on a RHEL7 system.

- Copy the plugin to the apps directory:

```bash
cp -r [directory containing this plugin] /usr/share/owncloud/apps/filepurge
```

The destination directory MUST be named `filepurge`.

- Enable the plugin in owncloud by logging in as an admin, visiting the apps
  page, and enabling "File Purge".
- Create an owncloud user who will own the automatically purged content
- Login as the user who will share the purge-enabled directory and create a folder called `auto-purge`
  - It must be exactly like that, with a hyphen and all lowercase
- Share the folder to all users and/or groups who need to provide auto-expiring files
- Set up a system cron job / scheduled task

The task must be run from the OwnCloud directory, e.g., `/usr/share/owncloud`.
The cron job can be inline or a bash script such as this:

```bash
cd /usr/share/owncloud
php occ files:purge username 14
```

Just replace "username" with the user owning the auto-purge folder, and replace
"14" with the number of days before files/folders should be deleted.

Note that any user can have an `auto-purge` folder and have the content
regularly deleted so long as a cron job is run for that user.  This could allow
better content restriction if there are multiple groups who need independent
data exposing and purging.  (If it makes sense to do a lot of auto-purging, a
command will probably be warranted for just scouring all users for auto-purge
folders)

## Gotchas

Expiration is determined based on modification date.  This is somehow *not* the
same as what's displayed in the web UI in at least one situation: touching
`auto-purge/A/B/file.txt` will properly change `file.txt`'s mod date as well as
its directory, `B`.  But it will **not** affect `A`'s modification date.  If a
user needs to create a similar structure, they need to know that they have to
touch something in `A` in order to reset its countdown (say they add a pile of
files to `B` a day after creating it).
