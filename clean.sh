#!/bin/sh
# bash script to clean (delete) Finder .DS_Store, .Trashes and ._resources
# Use cleandsstores.sh 
# juanfc 2010-03-06

if [ $# != 1 ]
then
  echo "ERROR:  use\n\t`basename $0` dirtoclean"
  exit 1
fi

res=`find "$@" \( -name ".DS_Store" -or -name ".Trashes" -or -name ".project" -or -name "._*" -or -name "CVS" -or -name "_notes" -or -name "*~" \) -print`


if [[ -z $res ]]; then
  echo "nothing to delete"
  exit 0
else
  echo "Going to delete:"
  echo $res
fi
read -p "Ok (yYsS1)?" ok

case $ok in
  [yYsS1] )
    find "$@" \( -name ".DS_Store" -or -name ".Trashes" -or -name "._*" -or -name "CVS" -or -name "_notes" -or -name "*~" \) -exec rm -rf "{}" \; -prune ;;
  * )
    echo "aborted."
esac

exit 0
