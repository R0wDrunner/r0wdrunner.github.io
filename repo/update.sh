rm Packages Packages.bz2
dpkg-scanpackages -m debs > Packages
cp Packages Packages-copy
bzip2 Packages-copy
cp Packages-copy.bz2 Packages.bz2
rm Packages-copy.bz2