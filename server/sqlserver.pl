BEGIN {
	unshift @INC, './lib', './t';
}
require 5.008;

use strict;
use warnings;
use Center;
use Msg;
use IO::Select;
use Getopt::Std;
use Encode;
use Time::HiRes qw/usleep time/;
use POSIX qw /setsid strftime/;
use POSIX ":sys_wait_h";

use constant {
	ONCE_BYTES     => 65535,
	GET_RESP_TIMES => 100,
	SELECTTM       => 0.1,
	ACTIONTM       => 100000,
	DEFAULTPORT	   => '26666',
	DEFAULTNAME	   => 'center',
	PBX_PORT	   => '26670',
	HEAD		   => 20,
	CHECKPBX	   => 60,
	CHECKMANAGER   => 60,
	MANAGERPING	   => 600,
	PINGDURATION   => 30,
	PINGTIMEOUT	   => 300,
	MAXCALLS	   => 30,
    CONFFILE       => '/salzh/mazalcenternew/dialer.conf'
};

my %SocketSpool = ();
my @SqlSpool    = ();
my $UNIX_PATH  = "/tmp/dialer.sock";
our $sock_file = "/tmp/sqlserver.sock";
$SIG{INT}		   = \&_stop_server;
$SIG{CHLD}		   = \&_reaper;
$SIG{'__DIE__'}	   = \&_die;
$SIG{'__WARN__'}   = \&_warn;



my %opts;
my $CONFFILE = CONFFILE;
getopts('vhdrs:l:p:c:', \%opts);

if ($opts{'d'}) {
	become_daemon();
}

my $dispatch = Center->new('logfile' => '/var/log/cccenter/sqlserver.pl');

my $conf_last_modtime = 0;
our %dispatch_conf	  = ();
parse_conf();

my $sdu		 = $dispatch->init_server_unix($UNIX_PATH) || die "Cannot create unix server sock\n";
warn "Success Create unix Server socket path=" . $UNIX_PATH . "\n";

my $isu = $dispatch->init_ioselect();
if (!$isu) {
	die "Cannnot create io select object for detect client event socket: $!";
}
$isu       ->add($sdu);

my %tmpstr = ();
while (1) {
    my @readyu = $isu->can_read(0.1);
    @SqlSpool  = ();
    for my $sock (@readyu) {
        my $fno = fileno $sock;

        if ($fno == fileno($sdu)) {
			my $eclient = $sdu->accept();
			log_debug(3, "new event client filneo=" . fileno($eclient));
			$isu->add($eclient);
            $SocketSpool{fileno($eclient)} = $eclient;
            next;
		} 
        die "internal error: not find fno=$fno" unless $SocketSpool{$fno};
        my $buffer = '';
        my $bytes  = sysread $sock, $buffer, ONCE_BYTES;
        if (defined $bytes && $bytes <=0 ) {
            $isu->remove($sock);
            clean_socket($sock);
            warn "fileno=$fno disconnected!!";
            delete $SocketSpool{$fno};
            delete $tmpstr{$fno};
        } else {
            warn "read $bytes string: $buffer!\n";
            push @SqlSpool, get_sql($fno, $buffer);
        }
    }
    
    next unless @SqlSpool > 0;
    die "Can't fork" unless defined (my $child = fork);
    if ($child == 0) {
        my $dbh      = $dispatch->dbh_dialer();
        for my $sql (@SqlSpool) {
            warn "run sql: $sql";
            my $sth = $dbh->prepare($sql);
            my $rv  = $sth->execute();
            
            if (!$rv) {
                warn "fail to run $sql: $sth->errstr\n";
            }
            usleep 100;
         }
        exit 0;
    } else {
        @SqlSpool = ();
    }
        
    usleep 500;
}

sub become_daemon {
	print "will become daemon...\n";
    die "Can't fork" unless defined (my $child = fork);
    exit 0 if $child;
    setsid();
    open( STDIN, "</dev/null" );
    open( STDOUT, ">/dev/null" );
    open( STDERR, ">&STDOUT" );
    system "echo $$ >> $sock_file";
    return $$;
}

sub _stop_server {
    log_debug(1, "** catch INT SIGNAL, server will quit!");
    for (keys %SocketSpool) {
        $isu->remove($SocketSpool{$_});
        clean_socket($SocketSpool{$_});
        delete $SocketSpool{$_};
    }
    
    if (-e $sock_file) {
		unlink $sock_file;
    }
    
	log_debug(1, "** Server stop!!![OK]");
    exit 0;
}

sub clean_socket {
    my $sock = shift;
	if (!$sock || !$sock->connected) {
		return;
	}

    $sock->close;
}
sub _reaper {
	my $pid;
	while (($pid = waitpid(-1, WNOHANG)) > 0) {
		print "success: reaper $pid\n";
	}
}

sub _die {
	log_debug(1, "DIE: pid=$$ and cause= @_");
	_exit("quit with die");
}

sub _warn {
	log_debug(5, "WARN: pid=$$ and cause=@_");
}

sub _exit {
	my $msg = shift || '';
	log_debug(3, "child $$ quit normally: $msg!\n");
	exit 0;
}

sub log_debug {
    my($l, $str) = @_;
	#return if $l > $dispatch_conf{LOGLEVEL};
    print "[". $dispatch->dispatch_time() . "]" . " [$$] level: $l - body: $str\n";
    if ($l == 0) {
        $dispatch->log_debug($l, $str);
        exit 0;
    } elsif ($l > 0 && $l < 7) {
        $dispatch->log_debug($l, $str);
    }
}

sub get_sql {
    my $fno    = shift;
    my $buffer = shift;
    if ($tmpstr{$fno} && length($tmpstr{$fno}) > 500) {
        log_debug(3, "$tmpstr{fno} is too long: INGORE!!");
        return;
    }
    
    $buffer    = $tmpstr{$fno}.$buffer;
    
    
    my @sqls   = split ';', $buffer;
    if (substr($buffer, -1,1) ne ';') {
        $tmpstr{$fno} = pop @sqls;
    }
    
    return @sqls;
}
sub parse_conf {
	my $pid = shift;
	my @s	= stat($CONFFILE);
	return unless $s[9] > $conf_last_modtime;
	$conf_last_modtime = $s[9];

	if (!(open FH, $CONFFILE)) {
		my $reason = "fail to read " . $CONFFILE . ":$!";
		if (!$pid) {
			die $reason;
		} else {
			log_debug(2, $reason);
		}
		return;
	}

	while(<FH>) {
		next if /^#/ || /^\s*$/;
		chomp;
		my ($key, $val) = split '=', $_;
		my $reason = sprintf("%-20s === %s\n",  $key, $val);
		if (!$pid) {
			print $reason;
		} else {
			log_debug(5, $reason);
		}

		$dispatch_conf{$key} = (defined $val ? $val : '');
	}

	return 1;
}