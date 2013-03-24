deleteExtraCopies
=================

This is a helper script to replace duplicate smaller mp3s with larger ones.

Here's a concrete example:

```$ ls -l music/
total 8
-rw-r--r-- 1 jmullan jmullan  5 Mar 23 21:41 foo (1).mp3
-rw-r--r-- 1 jmullan jmullan 13 Mar 23 21:41 foo.mp3```

Run this:

```$ ./cleanTree.php music/
foo (1).mp3
delete foo (1).mp3 in favor of foo.mp3 in music/```

It's pretty straightforward. Make your own backups!
