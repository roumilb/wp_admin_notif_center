# wp_admin_notif_center

## Release SVN

1. Change the version number in `index.php` (header and `WANC_VERSION`) and `readme.txt` (`Stable tag`, changelog)
2. Copy the files in the svn `trunk` folder, without hidden files (`.git`, `.gitignore`, `.php-version`, `.idea`, `.DS_Store`...), `vendor/` and `README.md`:
   `rsync -a --delete --exclude='.*' --exclude=vendor --exclude=README.md ./ <svn>/trunk/`
3. `svn add trunk/* --force`
4. `svn cp trunk tags/<version>`
5. `svn ci -m "Release <version>"`
