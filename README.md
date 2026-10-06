# wp_admin_notif_center

## Release SVN

1. Change the version number in `index.php` (header and `WANC_VERSION`) and `readme.txt` (`Stable tag`, changelog), commit
2. `bin/release-svn.sh` (dry run) to check the files and the SVN changes
3. `bin/release-svn.sh --commit` to commit trunk and create the tag on wordpress.org (`SVN_USERNAME=<username>` to skip the prompt)

The script builds from the committed `HEAD` (without hidden files, `bin/` and `README.md`) in a temporary checkout of `trunk` only, so no local SVN working copy is needed.
