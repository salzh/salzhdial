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
	AUTODIALINT	   => 60,
    CONFFILE       => '/salzh/mazalcenternew/dialer.conf'
};

my %SocketSpool = ();
my @SqlSpool    = ();

$SIG{INT}		   = \&_stop_server;
$SIG{CHLD}		   = \&_reaper;
$SIG{'__DIE__'}	   = \&_die;
$SIG{'__WARN__'}   = \&_warn;

my $sock_file     = '/tmp/Dialer.sock';

my %opts;
my $CONFFILE = CONFFILE;
getopts('vhdrs:l:p:c:', \%opts);

if ($opts{'d'}) {
	become_daemon();
}

my $dispatch = Center->new('logfile' => '/var/log/cccenter/Dialer.pl');
my $c		 = $dispatch;

my $conf_last_modtime = 0;
our %dispatch_conf	  = ();
parse_conf();

my $manager       = '';
my $manager2	  = ''; #manager2 is for get real current calls
my $firststart    = 1;


my %tmpstr = ();
while (1) {
    my $dbh = $c->dbh_dialer;
    ###check pending job
    if (!$manager || !$manager->connect) {
		$manager = new CCCenter::Manager;
		$manager->user('dispatch');
		$manager->secret('dispatch123');	
		$manager->host('127.0.0.1');
		
		if (!$manager->connect) {
			log_debug(2, "Could not connect to " . $manager->host . "!\n");
			sleep 3;
			next;
		}
	}
	my $limit	  = 2*$main::dispatch_conf{MAXCALLS} || 60;
    my $sql       = "select t_work_detail.id id,telno,t_work_detail.userid,username,voicemoney,workid,workstate,sendtimetype,fixedtime,ifendtime,endtime,voicefile,VoiceTemplateId,workcount 
from t_work_detail,t_work,t_user where t_work_detail.workid=t_work.id and t_work_detail.userid=t_user.id and length(telno) > 6 and username != '' and
voicemoney > 2 and workstate in (0,1) and workcount > 0 and t_work_detail.sendtime is null and (voicefile is not null or VoiceTemplateId is not null) and 
(sendtimetype!=2 or (sendtimetype=2 and fixedtime < now())) and
(ifendtime is not null or (ifendtime is null and endtime > now())) order by rand() limit $limit";
	my $sql_count = "select count(*) total
from t_work_detail,t_work,t_user where t_work_detail.workid=t_work.id and t_work_detail.userid=t_user.id and length(telno) > 6 and username != '' and
voicemoney > 2 and workstate in (0,1) and workcount > 0 and t_work_detail.sendtime is null and (voicefile is not null or VoiceTemplateId is not null) and 
(sendtimetype!=2 or (sendtimetype=2 and fixedtime < now())) and
(ifendtime is not null or (ifendtime is null and endtime > now()))";	
	my $sql_update = "update t_work set workstate = 1 where id=?";
	my $sql_update_detail = "update t_work_detail set SendTime=CURRENT_TIMESTAMP where id=?";

	warn $sql;
	warn $sql_count;
	warn $sql_update;
	warn $sql_update_detail;
	
	my $sth = $dbh->prepare($sql);
	my $sth_count = $dbh->prepare($sql_count);
	my $sth_update = $dbh->prepare($sql_update);
	my $sth_update_detail = $dbh -> prepare($sql_update_detail);

	my %t_work = ();	
	my $start  = time();
	my $j	   = 1;
	my $total  = 0;
	while (1) {
		if ($j >= $total || time() - $start >= AUTODIALINT) {
			$start     = time();
			$sth_count-> execute();
			my $row    = $sth_count->fetchrow_hashref;
			$total     = $row->{total};
			$j		   = 1;
			warn "update total: $total";			
			
			if (!$total) {
				warn "total becomes 0, update t_work.workstate=3 - " . join(",", keys %t_work);
				$dbh->prepare("update t_livetable set livecalls=?,maxcalls=? where host=?")
					->execute(0, 0, 'sending');

				my $sth1 = $dbh->prepare("update t_work set workstate=3 where id=? and workstate=1 and overcount >= workcount");
				for my $w (keys %t_work) {
					$sth1   -> execute($w);
				}
				%t_work = ();
			}
		}
		
		if (!$total) {
			sleep AUTODIALINT;
			next;
		}
		
		$sth      -> execute();		
		my @spool  = ();
		
		while (my $row = $sth->fetchrow_hashref()) {
			push @spool, $row;
			$sth_update       ->execute($row->{workid}) if $row->{workstate} == 0;			
			$sth_update_detail->execute($row->{id});
			warn "mark t_work_detail with id=$row->{id}";
			$t_work{$row->{workid}} = 1  unless $t_work{$row->{workid}};
			usleep 100;
		}
			
		if (@spool) {
			for my $row (@spool) {
				my $Aid          = getid();
				my $dialednumber = $row->{telno};
				my $workid 		 = $row->{workid};
				my $userid		 = $row->{userid};
				my $username 	 = $row->{username};
				
				my $callerid     = get_callerid($workid, $userid, $dialednumber);
				log_debug(3, $j++ . "/$total ($workid|$username) Dial $dialednumber");
			
				$dbh->prepare("update t_livetable set livecalls=?,maxcalls=? where host=?")
					->execute($j, $total, 'sending');
				
				for (1..60) {				
					my $cnt    = getcurrentcalls();					
					my $ccalls = $cnt;
					
					warn "ccall: $ccalls";

					$dbh->prepare("update t_livetable set livecalls=?,maxcalls=? where host=?")
						->execute($ccalls, $main::dispatch_conf{MAXCALLS}, $main::dispatch_conf{BRANCHNAME});
						
					if ( $ccalls >= $main::dispatch_conf{MAXCALLS} ) {
						warn "current calls=$ccalls is more than " . $main::dispatch_conf{MAXCALLS}  . " sleep!!!";
						sleep 5;
					} else {
						last;				
					}
				}
				
				my %resp = $manager -> sendcommand(Action => 'Originate', Channel => _channel($dialednumber, 'l'),
						  Async => 1, ActionID => $Aid, Timeout => 25000,
						  Variable => "DETAILID=$row->{id}|DIALEDNUMBER=$dialednumber|workid=$workid",
						  Context => 'dialer', CallerID => "$callerid <$callerid>",
						  Exten	 => 'handler', Priority => 1);
				
				die %resp;
				if ($resp{Response} ne 'Success') {					
					$manager = new CCCenter::Manager;
					$manager->user('dispatch');
					$manager->secret('dispatch123');	
					$manager->host('127.0.0.1');
					warn "make call failed, reconnect manager!!!";
					if (!$manager->connect) {
						log_debug(2, "Could not connect to " . $manager->host . "!\n");
						return;
					}
					
					redo;
					sleep 3;
				}
				
				usleep 100;
			}
		} else {
			$total = 0; $j = 1;
		}
		
		usleep 100;
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
    exit 0;
}

sub get_callerid {
    return "02166778899";
}

sub _channel {
    my $number = shift;
    return "local/$number\@dialer/n";    
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

sub getid {
	return time . int rand 9999;
}

sub getcurrentcalls {
	my $start = time;
	my $cnt  = 0;
	my $host = '127.0.0.1';
	#$manager2 = $manager;
	if ($main::dispatch_conf{BRANCHNAME} eq 'zhonglian250') {
		$manager2  = '';
		$host	   = '192.168.5.250';
	}
	if (!$manager2 || !$manager2->connect) {
		$manager2 = new CCCenter::Manager;
		$manager2->user('dispatch');
		$manager2->secret('dispatch123');	
		$manager2->host($host);
		
		if (!$manager2->connect) {
			log_debug(2, "Could not connect to " . $manager2->host . "!\n");
			return 999;
		}
	}
	
	my $Aid  = getid();
	local $SIG{ALRM} = sub { warn "read manger timeout!!\n"; $manager2=''; }; # NB: \n
	alarm 3;
	my $l = $manager2->sendcommand(Action => 'Command', Command => 'core show channels', ActionID => $Aid, 1);
	alarm 0;
	return 999 if !$l;
	if ($main::dispatch_conf{BRANCHNAME} eq 'zhonglian250') {
		$cnt = $l =~ s{SS7/siuc/}{}g;
	} else {
		$cnt = $l =~ s{SIP/OUT1\-}{}g
	}
	warn "getcurrentchannels: " . (time-$start) . " seconds";
	return $cnt || 0;
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