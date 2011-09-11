#!/usr/bin/perl -w

for $ARGV (@ARGV) 
{
        $new = $ARGV;
        $new =~ s/ /_/g;
        rename $ARGV,$new;
}

## Remove any spaces in the file name and replace with _ 
## Usage
##
## find -type f -print0 | xargs -0 /home/inz/audio/underscore_file.pl