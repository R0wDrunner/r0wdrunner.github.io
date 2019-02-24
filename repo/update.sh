#!/bin/bash
rm "Packages"
rm "Packages.bz2"
dpkg-scanpackages ./debs > "Packages"
cp "Packages" "Packages-copy"
bzip2 "Packages-copy"
mv "Packages-copy.bz2" "Packages.bz2"