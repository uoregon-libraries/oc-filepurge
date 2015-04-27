# File Purge

- Place this in the apps directory (`/usr/share/owncloud/apps/` on CentOS 7)
- Create a user who will own the automatically purged content
- It's probably also a good idea to create a group for easier sharing
- Create a folder called "auto-purge".  It MUST be exactly like that, with a hyphen and all lowercase!
- Share the folder to all users who need to provide magic-expiring files
- Set up a cron job:
  - Change to the owncloud root directory (`/usr/share/owncloud`)
  - Run `php occ files:purge username 14`
    - "username" is the user owning the auto-purge folder
    - Replace "14" with auto-expire duration in days

# Gotchas

Expiration is determined based on modification date.  This is somehow *not* the
same as what's displayed in the web UI in at least one situation: touching
`auto-purge/A/B/file.txt` will properly change `file.txt`'s mod date as well as
its directory, `B`.  But it will **not** affect `A`'s modification date.  If a
user needs to create a similar structure, they need to know that they have to
touch something in `A` in order to reset its countdown (say they add a pile of
files to `B` a day after creating it).
