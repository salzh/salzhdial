#!/usr/bin/perl
use IO::Socket::INET;
my ($host, $port) = @ARGV;
$host ||= '127.0.0.1';
$port ||= 3306;

my %dispatch_conf = ();
parse_conf();

if ($dispatch_conf{BRANCHNAME} eq 'zhonglian253') {
    $sock = IO::Socket::INET->new(PeerPort  => $port,
                                PeerAddr  => $host,
                                Proto     => 'tcp',
                                LocalAddr => '127.0.1',
                                Timeout => 10);
    if (!$sock) {
        warn "fail to connect to $host:$port\n";
        system ("ssh -oPort=2022 3306:127.0.0.1:3306 61.152.175.238");
    } else {
        print "check $host:$port OK!\n";
    }
    
}

$port = 26668;
$sock = IO::Socket::INET->new(PeerPort  => $port,
                            PeerAddr  => $host,
                            Proto     => 'tcp',
                            LocalAddr => '127.0.1',
                            Timeout => 10);
if (!$sock) {
    warn "fail to connect to $host:$port\n";
    system ("echo \"update t_livetable set livecalls=0,maxcalls='$dispatch_conf{MAXCALLS}' where host='$dispatch_conf{BRANCHNAME}'\"" .
            " | mysql evoice -u cccenteruser --password=amp109");
} else {
    print "check $host:$port OK!\n";
}

sub parse_conf {
	my $CONFFILE = '/salzh/mazalcenternew/dialer.conf';

	if (!(open FH, $CONFFILE)) {
		warn"fail to read " . $CONFFILE . ":$!";
		return;
	}

	while(<FH>) {
		next if /^#/ || /^\s*$/;
		chomp;
		my ($key, $val) = split '=', $_;
		#warn sprintf("%-20s === %s\n",  $key, $val);
		

		$dispatch_conf{$key} = (defined $val ? $val : '');
	}

	return 1;
}