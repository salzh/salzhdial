#!/usr/bin/perl

#$Id: clients.pl salzh $
use strict;
use warnings;
use File::Spec;
use CGI::Simple (-upload);
$CGI::Simple::POST_MAX = 4_048_576;

my $cgi    = CGI::Simple->new();
print $cgi->header(-charset => 'utf-8');

my $file   = $cgi->param('rawfile');
if (!$file) {
	print "ERROR:: file is null\n";
	exit 0;
}

$file        =~ m{([^\\\/]+)$};
my $filename = $1 || $file;


my $des = File::Spec->catfile("/opt/lampp/htdocs/files" , $filename);
$des   =~ s{\\}{/}g;
my $ok  = $cgi->upload($file, $des);

if ($ok) {
	print "OK:: $filename saved\n";
} else {
	print "ERROR:: saved error - " . $cgi->cgi_error() . "\n";
}


exit 0;

