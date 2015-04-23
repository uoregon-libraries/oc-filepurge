# File Purge

- Place this app in **owncloud/apps/**
- Create a user who will own the automatically purged content
- (Maybe create a group for easier sharing)
- Create a folder called "auto-purge".  It MUST be exactly like that, with a hyphen and all lowercase!
- Share the folder to all users who need to provide magic-expiring files
- Set up a cron job:
  - Change to the owncloud dir (`/usr/share/owncloud` here)
  - Run `php occ files:purge username 14`
    - "username" is the user owning the auto-purge folder
    - Replace "14" with auto-expire duration in days

# Gotchas

Expiration is determined based on modification date.  This is somehow *not* the
same as what's displayed in the web UI.  And unlike in the UI, touching
`auto-purge/A/B/file.txt` will **not** affect `A`'s modification date.  It will
change `B`'s date, but that change just doesn't propagate up for some reason.
If a user needs to create a similar structure, they need to know that they have
to touch something in `A` in order to reset its countdown (say they add a pile
of files to `B` a day after creating it).
