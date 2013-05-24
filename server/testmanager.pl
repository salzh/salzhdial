BEGIN {
	unshift @INC, './lib', './t';
}
require 5.008;

use strict;
use warnings;
use YAML;

use CCCenter::Manager;

my $astman = new CCCenter::Manager;

$astman->user('dispatch');

$astman->secret('dispatch123');

$astman->host('localhost');

$astman->connect || die "Could not connect to " . $astman->host . "!\n";
my %resp = $astman->sendcommand(Action => 'Originate', Channel => 'SIP/OUT1/15901913696',
				  Async => 1,  Timeout => 300000,
				  Variable => "DETAILID=" . 177,
				  Context => 'dialer', CallerID => "123",
				  Exten	 => 'handler', Priority => 1);
print Dump(%resp);

my $l = $astman->sendcommand(Action => 'Command', Command => 'core show channels', 1);
print $l, "\n";	
my $cnt = $l =~ s{SIP/OUT1}{}g;
print $cnt;
$astman->disconnect;
