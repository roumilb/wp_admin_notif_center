#!/usr/bin/env bash
# Releases the committed HEAD to the wordpress.org SVN repository.
#
# Usage: bin/release-svn.sh            dry run: builds, prepares trunk and shows the SVN changes
#        bin/release-svn.sh --commit   same, then commits trunk and creates the tag after confirmation
#
# SVN_USERNAME=<wp.org username> to avoid the username prompt.
set -euo pipefail

SLUG='wp-admin-notification-center'
SVN_URL="${SVN_URL:-https://plugins.svn.wordpress.org/$SLUG}"
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
COMMIT=0
[ "${1:-}" = '--commit' ] && COMMIT=1

die() { echo "Error: $*" >&2; exit 1; }
step() { printf '\n\033[1m== %s\033[0m\n' "$*"; }
confirm() {
    local answer
    read -r -p "$1 [y/N] " answer
    [ "$answer" = 'y' ] || [ "$answer" = 'Y' ]
}

remote_svn() {
    if [ -n "${SVN_USERNAME:-}" ]; then
        svn --username "$SVN_USERNAME" "$@"
    else
        svn "$@"
    fi
}

command -v svn >/dev/null || die 'svn is not installed'
command -v rsync >/dev/null || die 'rsync is not installed'

step 'Checks'
cd "$ROOT"
[ -z "$(git status --porcelain --untracked-files=no)" ] || die 'uncommitted changes, the release is built from HEAD'

BRANCH="$(git rev-parse --abbrev-ref HEAD)"
if [ "$BRANCH" != 'master' ]; then
    echo "Warning: releasing from '$BRANCH', not master"
    confirm 'Continue?' || exit 1
fi

VERSION="$(sed -n 's/^Version: *//p' index.php | tr -d '[:space:]')"
[ -n "$VERSION" ] || die 'no Version header in index.php'
grep -q "const WANC_VERSION = '$VERSION';" index.php || die "WANC_VERSION in index.php does not match $VERSION"
grep -q "^Stable tag: $VERSION\$" readme.txt || die "readme.txt Stable tag does not match $VERSION"
grep -q "^= $VERSION =\$" readme.txt || die "no changelog entry for $VERSION in readme.txt"
if remote_svn ls "$SVN_URL/tags/$VERSION" >/dev/null 2>&1; then
    die "tags/$VERSION already exists on wordpress.org"
fi
echo "Version $VERSION from $BRANCH ($(git rev-parse --short HEAD))"

WORK="$(mktemp -d)"
trap 'rm -rf "$WORK"' EXIT

step 'Build'
mkdir -p "$WORK/build"
git archive HEAD | tar -x -C "$WORK/build"
find "$WORK/build" -mindepth 1 -name '.*' -prune -exec rm -rf {} +
rm -rf "$WORK/build/README.md" "$WORK/build/bin"
(cd "$WORK/build" && find . -mindepth 1 | sed 's|^\./||' | sort) > "$WORK/build.list"
sed 's/^/  /' "$WORK/build.list"

# Paths differing only by case cannot coexist on a case-insensitive file system and break the checkout:
# remove, on the server, the variants that are not part of the build
step 'Case collisions in trunk'
remote_svn ls -R "$SVN_URL/trunk" | sed 's|/$||' > "$WORK/trunk.list"
# Only the shallowest collisions are kept: removing a colliding folder also solves the collisions of its content
awk '{ key = tolower($0); count[key]++; paths[key] = paths[key] "\n" $0 }
     END {
         for (key in count) {
             if (count[key] < 2) continue
             nested = 0; ancestor = key
             while (sub(/\/[^\/]*$/, "", ancestor)) if (count[ancestor] > 1) nested = 1
             if (!nested) print substr(paths[key], 2)
         }
     }' "$WORK/trunk.list" \
    | { grep -vxF -f "$WORK/build.list" || true; } | sort > "$WORK/collisions.list"

COLLISIONS=()
while IFS= read -r path; do
    COLLISIONS+=("$path")
done < "$WORK/collisions.list"

if [ ${#COLLISIONS[@]} -eq 0 ]; then
    echo 'None'
else
    printf '  remove trunk/%s\n' "${COLLISIONS[@]}"
    if [ "$COMMIT" = 0 ]; then
        echo 'Dry run: these paths would be removed from the server before the checkout. Re-run with --commit.'
        exit 0
    fi
    confirm 'Remove these paths from trunk on wordpress.org?' || exit 1
    URLS=()
    for path in "${COLLISIONS[@]}"; do URLS+=("$SVN_URL/trunk/$path"); done
    remote_svn rm -m "Remove paths colliding by case" "${URLS[@]}"
fi

step 'Prepare trunk'
remote_svn co -q "$SVN_URL/trunk" "$WORK/trunk"
rsync -a --delete --exclude='.svn' "$WORK/build/" "$WORK/trunk/"
svn add -q --force --no-ignore "$WORK/trunk"
svn status "$WORK/trunk" | sed -n 's/^!       //p' | while IFS= read -r missing; do
    svn rm -q "$missing"
done

CHANGES="$(svn status "$WORK/trunk" | sed "s|$WORK/trunk/||")"
if [ -z "$CHANGES" ]; then
    echo 'No change in trunk'
else
    echo "$CHANGES"
fi

if [ "$COMMIT" = 0 ]; then
    step 'Dry run, nothing was sent to wordpress.org. Re-run with --commit to release.'
    exit 0
fi

step "Release $VERSION"
confirm "Commit trunk and create tags/$VERSION on wordpress.org?" || exit 1
if [ -n "$CHANGES" ]; then
    remote_svn ci -m "Release $VERSION" "$WORK/trunk"
fi
remote_svn cp -m "Tag $VERSION" "$SVN_URL/trunk" "$SVN_URL/tags/$VERSION"

step "Released $VERSION"
echo "https://wordpress.org/plugins/$SLUG/ (the page can take a few minutes to update)"
echo "Don't forget the git tag: git tag $VERSION && git push origin $VERSION"
