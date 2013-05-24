#!/opt/lampp/bin/perl
#mazal.pl
#Since 2010-6-15
#Author by Salzh
#LASTMODTIME: 2010-09-21 14:01:03
#$Id: mazal.pl 64 2010-07-15 07:18:49Z salzh $

BEGIN {
	unshift @INC, './lib', './t';
}
require 5.008;

use strict;
use warnings;
use YAML;
use Smart::Comments '######';
use Center;
use Msg;
use IO::Select;
use Getopt::Std;
use Encode;
use Time::HiRes qw/usleep time/;
use POSIX qw /setsid strftime/;
use POSIX ":sys_wait_h";
use T;

our $VERSION = '$Rev: 119 $';

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
	MAXCALLS	   => 30
};

my $conf_last_modtime = 0;
my $FMT				  = "%0" . (HEAD-13) . "d";
our $EOL	   = "\015\012";
our $BLANK	   = $EOL;

our %GroupInfor					= ();
our %ExtenSpool					= ();
our %ChannelTable				= ();
our %ivr_cordinates				= ();
our %ClientSpool				= ();
our $uptime						= '';
our %ManagerSockSpool			= ();
our $dispatch_username			= '';
our $dispatch_privillege		= '';
our $dispatch_callerid			= '';
our $command_index				= -1;
our $dispatch_admin				= '';

my $is;
my $MdFno;
my $MdeFno;
my $sdno;
my $pbxsockno;
my $dispatch_sock;
my $dispatch_fdno;
my $dispatch_IsLogin;
my @PLUGINS;
my @sockpool			= ();
my %ActionSpool			= ();
my @EventSpool			= ();
my @ClientMsgSpool		= ();
my $MdBufferTmp			= '';
my $MdBufferTmpEvent	= '';
my $ClientMsgTmp		= '';
my %ClientSocks			= ();
my %EventSockSpool      = ();
my $lastsize			= '';
my $lastpingtime		= '';
my $lastpongtime		= '';


our $md;
our $mde;

my %opts;
my $CONFFILE = DEFAULTNAME	. ".conf.default";
getopts('vhdrs:l:p:c:', \%opts);

if ($opts{'h'}) {
	usage();
}

if ($opts{'s'}) {
	shutdown_center($opts{'s'});
	exit 0;
}

if ($opts{'v'}) {
	show_version();
	exit 0;
}

warn __FILE__;

if ($opts{'c'}) {
	$CONFFILE = $opts{'c'};
	die "$opts{'c'} not exists" unless -e $opts{'c'};
}

our %dispatch_conf	  = ();
parse_conf();

$dispatch_conf{SERVERPORT}	||= DEFAULTPORT;
$dispatch_conf{NAME}		||= DEFAULTNAME;
$dispatch_conf{LOGFILE}		||= "/var/log/cccenter/dispatch_conf{NAME}.log";
$dispatch_conf{SCRIPTNAME}  ||= DEFAULTNAME;
$dispatch_conf{MAXCALLS}    ||= MAXCALLS;

if ($opts{'p'}) {
	$dispatch_conf{SERVERPORT} = $opts{'p'};
	warn "Server port set to: $dispatch_conf{SERVERPORT}\n";
}


if ($opts{'l'}) {
	set_log($opts{'l'});
	warn "Log Level set to $opts{'l'}\n";
	exit 0;
}

my $UNIX_PATH  = "/tmp/$dispatch_conf{NAME}-$$.sock";
our $sock_file = "$dispatch_conf{NAME}.sock";
our $SDIR	   = "/var/lib/cccenter/sounds";
if ($dispatch_conf{PLUGINS}) {
	@PLUGINS = split /\s+/, $dispatch_conf{PLUGINS};
	for (@PLUGINS) {
		warn "loading $_...\n";
		require "$_.pm"
	};
} else {
	warn "PLUGINS definition is null, No module will be loaded\n";
}

$SIG{INT}		   = \&_stop_server;
$SIG{CHLD}		   = \&_reaper;
$SIG{'__DIE__'}	   = \&_die;
$SIG{'__WARN__'}   = \&_warn;

unlink $sock_file;
my $dispatch = Center->new('logfile' => $dispatch_conf{LOGFILE});
$dispatch	-> server_port($dispatch_conf{SERVERPORT} || 26667);
my $sd		 = $dispatch->init_server('', 0) || die "Cannot create server sock port=" .
													$dispatch->server_port . "!\n";
$sdno		 = fileno $sd;
warn "Success Create Server Socket Port=" . $dispatch->server_port . " serverno=$sdno\n";

my $sdu		 = $dispatch->init_server_unix($UNIX_PATH) || die "Cannot create unix server sock\n";
warn "Success Create unix Server socket path=" . $UNIX_PATH . "\n";

my $sdm_port = $dispatch->server_port - 100;
my $sdm      = $dispatch->init_server($sdm_port, 0) || die "Cannot create server sock port=$sdm_port!\n";
my $sdmno	 = fileno $sdm;
warn "Success Create Server Manager Socket Port=$sdm_port serverno=$sdmno\n";


if ($opts{'d'}) {
	become_daemon();
}

die "fail to fork\n" unless defined (my $child = fork);
create_flash_daemon() if $child == 0;

if ($dispatch_conf{CLIENTD}) {
	die "fail to for echild" unless defined (my $echild = fork);
	create_client_daemon() if $echild == 0;
}

$dispatch->clear_status();
log_debug(3, "Parent process start!!!\n");

$is = $dispatch->init_ioselect();
if (!$is) {
	die "Cannnot create io select object: $!";
}

$is->add($sd);
$is->add($sdu);
$is->add($sdm);

my $isu = $dispatch->init_ioselect();
if (!$isu) {
	die "Cannnot create io select object for detect client event socket: $!";
}

my $last_connect_manager = time;
$mde = $dispatch->init_manager_event();
if (!$mde) {
	warn "Cannot create manager event socket";
} else {
	$MdeFno = fileno $mde->connfd;
	$is->add($mde->connfd);
}


my $msg    = Msg->new();
my $sdp	   = $dispatch->init_sock('127.0.0.1', PBX_PORT, 'tcp', 0);
our $sdpfno;
my $last_connect_pbx = time;;
if (!$sdp) {
	warn "Cannot create sock to pbx server";
} else {
	$sdpfno = fileno $sdp;
	send_response($sdp, justify_head("<action>Login</action><type>listen</type>"));
	$is->add($sdp);
}

our $ism = $dispatch->init_ioselect();
if (!$ism) {
	die "Cannnot create io select object for client manager socket: $!";
}

if (!$opts{'d'}) {
	warn "add STDIN in ism";
	$ism   -> add(\*STDIN);
	$ManagerSockSpool{'0'} = { msg   => Msg->new(),
							   st	 => time(),
							   lping => time(),
							   infor => 'STDIN'
							  };
}

$uptime = time;
log_debug(3, "Server Uptime: " . $dispatch->center_time2str($uptime));
log_debug(1, "Server Started Successfully");

exit 0 if $ENV{MAKEPP};  #for pp

while (1) {
	my @ready = $is->can_read(0.1);
	for my $sock (@ready) {
		my $fno = fileno $sock;
		if ($fno == $sdno) {
			my $dclient = $sd->accept();
			if ($dclient) {
				my $cinfor = $dclient->peerhost . ":" . $dclient->peerport;
				log_debug(3, "new dispatch client  - $cinfor");
				send_response($dclient, "Welcome to Dispatch System $BLANK$BLANK") ||
							log_debug(2, "fail to welcome to client: $cinfor\n");
				my $c = fork;
				if (!defined $c) {
					log_debug(2, "fail to fork for $cinfor");
				} elsif ($c == 0) {
					process_client($dclient);
				} else {
					$ClientSpool{$c} = {sock => $dclient, starttime => time};
				}
			}
		} elsif ($fno == fileno($sdu)) {
			my $eclient = $sdu->accept();
			log_debug(3, "new event client filneo=" . fileno($eclient));
			$EventSockSpool{fileno($eclient)} = $eclient;
			$isu->add($eclient);
		} elsif ($fno == $MdeFno) {
			my $buffer = '';
			my $bytes  = sysread($sock, $buffer, ONCE_BYTES);
			if (not defined $bytes) {
				next;
			} elsif ($bytes <=0 ) {
				log_debug(1, "CCCenter event socket stopped!");
				$is->remove($sock);
				clean_socket($sock);
				undef $mde;
			} else {
				for (keys %EventSockSpool) {
					my $sock  = $EventSockSpool{$_};
					my $bytes = send_unix_response($sock, $buffer);
				}
			}
		} elsif ($sdpfno && $fno == $sdpfno) {
			my $buffer = '';
			my $bytes  = sysread $sock, $buffer, ONCE_BYTES;
			log_debug(5, "pbx: read bytes=$bytes buffer=$buffer");
			if (not defined $bytes) {
				next;
			} elsif ($bytes <=0 ) {
				$is->remove($sock);
				clean_socket($sock);
				undef $sdp;
			} else {
				$msg->store($buffer, HEAD);
			}
		} elsif ($fno == $sdmno) {
			my $mclient = $sdm->accept();
			log_debug(3, "new manager client from " . $mclient->peerhost . ":" . $mclient->peerport);
			send_response($mclient, "Welcome to Dispatch Manager System $BLANK");
			$ManagerSockSpool{fileno($mclient)} = {
												   sock  => $mclient,
												   msg   => Msg->new(),
												   st	 => time(),
												   lping => time(),
												   infor => $mclient->peerhost . ":" . $mclient->peerport
												  };
			$ism->add($mclient);
		}
	}

	my @readyu = $isu->can_read(0.1);
	for my $sock (@readyu) {
		my $fno = fileno $sock;
		die "internal error: not find fno=$fno" unless $EventSockSpool{$fno};
		my $buffer = '';
		my $bytes  = sysread $sock, $buffer, ONCE_BYTES;
		if (defined $bytes && $bytes <=0 ) {
			$isu->remove($sock);
			clean_socket($sock);
			warn "fileno=$fno disconnected!!";
			delete $EventSockSpool{$fno};
		}
	}

	my @readym = $ism->can_read(0.1);
	for my $sock (@readym) {
		my $fno = fileno $sock;
		die "internal error: not find fno=$fno" unless $ManagerSockSpool{$fno};
		my $buffer = '';
		my $bytes  = sysread $sock, $buffer, ONCE_BYTES;
		if (defined $bytes && $bytes <=0 ) {
			$ism->remove($sock);
			clean_socket($sock);
			warn "manager sock fileno=$fno disconnected!!";
			delete $ManagerSockSpool{$fno};
		} else {
			my $dumpbin = $dispatch->dumpbin($buffer);
			warn "read from manager: $buffer " . $dumpbin . "\n";
			my $v = $ManagerSockSpool{fileno($sock)};

			if ($dumpbin eq '27 91 65 ') { #up arrow
				$command_index++;
				my $size   = int @{$v->{history}};
				next unless $size;
				my $index  = ($command_index < $size ? $size-$command_index-1 : 0);
				my $oldcmd = $v->{history}[$index];
				next unless $oldcmd =~ /\w/;
				log_debug(5, "send " . $dispatch->dumpbin($oldcmd) . "\n");
				$oldcmd = "\b" x 80 . $oldcmd;
				syswrite $sock, $oldcmd, length($oldcmd);

			} elsif ($dumpbin eq '27 91 66 ') { #down arrow
				$command_index--;
				my $size   = int @{$v->{history}};
				next unless $size;
				my $index  = ($command_index > -1 ? $size-$command_index-1 : $size - 1);
				my $oldcmd = $v->{history}[$index];
				next unless $oldcmd =~ /\w/;
				$oldcmd = "\r\n" . $oldcmd;

				syswrite $sock, $oldcmd, length($oldcmd);

			} else {
				$command_index = -1;
				if (length($buffer) == 1 && ord($buffer) == 8) {
					syswrite $sock, ' ' . $buffer, 2;
				}
				$v   -> {msg}->store2($buffer);
			}
		}
	}

	if ($sdpfno) {
		while (1) {
			my $buffer = $msg->fetch();
			last if !$buffer;
			my $event  = hash2line($dispatch->parse_string($buffer));
			for (values %EventSockSpool) {
				send_unix_response($_, $event);
			}
		}
	}

	if ((time - $last_connect_pbx > CHECKPBX) && (!$sdp || !$sdp->connected)) {
		$sdp	  = $dispatch->init_sock('127.0.0.1', PBX_PORT, 'tcp', 0);
		$last_connect_pbx = time;
		if ($sdp) {
			$sdpfno = fileno $sdp;
			send_response($sdp, justify_head("<action>Login</action><type>listen</type>"));
			$is->add($sdp);
			log_debug(3, "reconnect to pbx socket: ok");
		}
	}

	if ((time - $last_connect_manager > CHECKMANAGER) && (!$mde || !$mde->connfd || !$mde->connfd->connected)) {
		$last_connect_manager = time;
		$mde = $dispatch->init_manager_event();
		if ($mde) {
			$MdeFno = fileno $mde->connfd;
			$is->add($mde->connfd);
			log_debug(3, "reconnect to cccenter evevent socket: ok");
		}
	}

	process_manager_msg();
	parse_conf();
	usleep 500;
}


### daemon for monitoring 843 port and deal reload request
sub create_flash_daemon {
	log_debug(3, "flash daemon started pid=$$!\n");
	my $dispatch = Center->new();
	$dispatch   -> server_port(843);
	$dispatch	-> server_blocking(0);
	my $sd		 = $dispatch->init_server();
	if (!$sd) {
		log_debug(5, "WARNING: port=843 may used by other process!!!");
	}

	while (1) {
		parse_conf();
		if ($dispatch->is_reload()) {
			$dispatch->apply_cccenter_conf();
		}
		usleep 500*1000;

		if ($sd) {
			my $client = $sd->accept || next;
			log_debug(3, "Flash New client: " . $client->peerhost . ":" . $client->peerport);
			$client   -> autoflush(1);
			my $buffer = '';
			sysread($client, $buffer, 255);
			my $xml = `cat crossdomain.xml`;
			syswrite $client, $xml;
			syswrite $client, "\0";
		}
	}
	exit 0;
}

### deamon for monitoring cccenter event
sub create_event_daemon {
	log_debug(3, "event daemon started pid=$$!");

	$dispatch_privillege = "event_daemon";

	$mde = $dispatch->init_event($UNIX_PATH);

	if (!$mde) {
		_exit("Error: Cannot create mde for event daemon");
	}
	$MdeFno = fileno $mde;
	warn "mde: " . "host=" . $mde->hostpath . "peer=" . $mde->peerpath() . "\n";
	$is = $dispatch->init_ioselect();
	if (!$is) {
		_exit("Error: Cannot create io::select obj for event_daemon!\n");
	}

	$is->add($mde);

	while (1) {
		#warn "in event_daemon\n";
		check_loop_main();
		my @ready = $is->can_read(0.1);
		for my $sock  (@ready) {
			if (is_from_mde($sock)) {
				##### try to read from Manager sock
				read_manager();
			}
		}

		my $lastindex = $#EventSpool;
		for my $eindex (0..$#EventSpool) {
			deal_manager($eindex, 'daemon');
		}
		delete @EventSpool[0..$lastindex];
		parse_conf();
		usleep 500*100;
	}
	exit 0;
}

sub create_client_daemon {
	my $t = T->new('', $dispatch_conf{'SERVERPORT'});

	my $s = $t->login($dispatch_conf{USERNAME} || '100',
				  $dispatch_conf{PASSWORD} || '100',
				  $dispatch_conf{PRIVILLEGE} || 'queue_admin_daemon');
	if (!$s) {
		log_debug(1, "client daemon fail to login in broadcast system");
		die "client daemon fail to login in broadcast system";
	}

	while (1) {
		$t->read();
		usleep 1000;
	}
	$t->close;
	exit 0;
}

sub process_client {
	my $client = shift;
	log_debug(3, "enter child process for " . $client->peerhost . ":" . $client->peerport);
	_exit("client is not define or client is disconnected") unless $client && $client->connected;

	_release(\%ClientSpool);
	($dispatch_sock, $dispatch_fdno, $dispatch_IsLogin, $dispatch_privillege, $dispatch_callerid, $pbxsockno) = ();
	$dispatch_sock = $client;
	$dispatch_fdno = fileno $client;
	$lastpingtime	  = time;
	$lastpongtime	  = time;
	$dispatch->init_dbh();
	$msg = Msg->new();

	$is = $dispatch->init_ioselect();
	if (!$is) {
		send_response($client,
			justify_head("<event>systemerror</event><message>cannot create io select</message>"));
		clean_socket($client);
		_exit("Cannot create io::select obj!\n");
	}
	$is->add($client);

	$md = $dispatch->init_manager();
	if (!$md) {
		send_response($client,
			justify_head("<event>systemerror</event><message>cannot create cccenter manager sock</message>"));
		clean_socket($client);
		_exit("Cannot create manager obj!\n");
	}

	$mde = $dispatch->init_event($UNIX_PATH);
	if (!$mde) {
		send_response($client,
			justify_head("<event>systemerror</event><message>cannot create cccenter manager sock</message>"));
		clean_socket($client);
		_exit("Cannot create manager obj!\n");
	}
	$MdFno  = fileno $md->connfd;
	$MdeFno = fileno $mde;

	log_debug(6, "MdFno: $MdFno\nMdeFno:$MdeFno\n");
	$dispatch->add_child($$, fileno($client), $client->peerhost . ":" . $client->peerport);

	%ActionSpool      = ();
	@EventSpool       = ();
	@ClientMsgSpool   = ();
	$MdBufferTmp      = '';
	$MdBufferTmpEvent = '';
	$ClientMsgTmp  = '';
	%ClientSocks	  = ();
	%GroupInfor		  = ();
	%ExtenSpool		  = ();
	%ChannelTable	  = ();
	#%Channel_Spool    = ();
	#%Channel_Spool_Reverse      = ();
	#%Dial_Channel_Spool         = ();
	#%Dial_Channel_Spool_Reverse = ();
	#%Local_Channel_Spool        = ();
	#%Hangup_Channel_CauseSpool  = ();
	#%Local_ChannelCid_Spool		= ();
	$is->add($md->connfd);
	$is->add($mde);

	intial_channelspool();
	warn Dump(\%ChannelTable);
	while (1) {
		if (!$client || !$client->connected) {
			clean_socket($client);
			_exit("client state is disconnected");
		}


		if ($lastpingtime - $lastpongtime > PINGTIMEOUT && $dispatch_privillege && $dispatch_privillege ne 'queue_admin_daemon') {
			clean_socket($client);
			_exit("client socket closed caused by pong timeout!");
		}

		if (time - $lastpingtime > PINGDURATION) {
			send_response($client, justify_head("<event>ping</event>"));
			$lastpingtime = time;
		}

		receive_msg();
		process_msg();

		no strict "refs";
		for (@PLUGINS) {
			my $f = "$_" . "::CHECK_TIMER";
			if (defined &$f) {
				&$f($dispatch);
			}
		}

		parse_conf();
		usleep 500;
	}
}

sub process_manager_msg {
	while (my ($k, $v) = each %ManagerSockSpool) {
		if (time - $v->{lping} > MANAGERPING) {
			my $ping = "ping$BLANK$BLANK";
			if (!syswrite $v->{sock}, $ping, length($ping)) {
				log_debug(3, $v->{infor} . " offline");
				if (!$k) {
					$ism->remove(\*STDIN);
				} else {
					$ism->remove($v->{sock});
				}

				clean_socket($v->{sock});
				warn "manager sock fileno=$k disconnected!!";
				delete $ManagerSockSpool{$k};
				next;
			}
			$v->{lping} = time;
		}

		my $cmd = $v->{msg}->fetch();
		if ($cmd) {
			if ($k) {
				log_debug(4, "deal manger command '$cmd' for " .
					$v->{sock}->peerhost . ":" . $v->{sock}->peerport);
			} else {
				log_debug(4, "deal manger command '$cmd' for STDIN");
			}

			if (!$v->{history}) {
				$v->{history} = [];
			}
			push @{$v->{history}}, $cmd;

			my $return;
			my $func = "";
			my @cmds = split /\s+/, $cmd;
			$func = shift @cmds;
			$func = "Monitor::$func";

			no strict "refs";

			if (!defined &$func) {
				#$return = {status => 0, message => "$cmd not defined"};
				unshift @cmds, $func;
				$return = Monitor::send($dispatch, $k, '', $cmd);
			} else {
				$return = &$func($dispatch, $k, @cmds);
			}

			$return->{status}  ||= 0;
			if ($return->{status} eq '10') {
				log_debug(6, "status=10 caused by the manager socket be closed");
				return;
			}
			if ($return->{status} eq '8') {
				log_debug(6, "status=8 needn't reply");
				return;
			}

			if ($return->{status} eq '7') {
				send_response($v->{sock}, "$BLANK$return->{body}$BLANK$BLANK");
				return;
			}
			$return->{message} ||= '';
			$return->{body}    ||= '';

			if ($v->{logtype} && $v->{logtype} eq 'w')
			{
				$return->{message} = encode("gbk", decode "utf8", $return->{message});
				$return->{body}    = encode("gbk", decode "utf8", $return->{body});
			}

			send_response($v->{sock}, "STATUS: $return->{status}$BLANK" . "MESSAGE: $return->{message}$BLANK" .
									  "BODY:$BLANK$return->{body}$BLANK$BLANK");
		}
		#warn $v->{log};
		if (is_true($v->{log})) {
			my ($size, $str) = $dispatch->sendtail($dispatch_conf{LOGFILE}, $lastsize, $v->{log}, $v->{logtype});
			$lastsize	 = $size;
			#warn "$lastsize, $str";
			syswrite $v->{sock}, $str, length($str) if $str;
		}
	}
}

sub process_msg {
	#print "enter process_msg\n";
	while (1) {
		my $buffer = $msg->fetch();
		last if !$buffer;
		deal_client2($buffer);
	}

	my $lastindex = $#EventSpool;
	for my $eindex (0..$#EventSpool) {
		deal_manager($eindex);
	}
	delete @EventSpool[0..$lastindex];
	#print "end process_msg\n";
}

sub receive_msg {
	if (!$md->connfd || !$md->connfd->connected) {
		#####  "CCCenter connection is fail, try to reconnect!\n";
		##### waiting for 10 seconds
		$is->remove($md->connfd);
		$md->connfd->close;
		#die "CCCenter Closed";
		sleep 2;
		#$md->disconnect;
		$md = $dispatch->init_manager() || _exit("Cannot create connection to CCCenter!\n");
		$is->add($md->connfd);
	}

	my @ready =  $is->can_read(0.1);
	for my $sock (@ready) {
		#warn "sock: " . fileno($sock) . "sd: " . fileno($sd) . "\n";
		my $sockfn = fileno $sock || next;

		if (is_from_mde($sock)) {
			##### try to read from Manager sock
			read_manager();
		} elsif (is_from_md($sock)) {
			my $buffer = '';
			my $bytes  = sysread($sock, $buffer, ONCE_BYTES);
			if (defined $bytes && $bytes <=0) {
				send_response($dispatch_sock,
							justify_head("<event>systemerror</event><message>cccenter stopped</message>"));
				clean_socket($dispatch_sock);
				_exit("CCCenter stopped!\n");
			}
		} else {
			##### try to read from client sock

			my $buffer = '';
			my $bytes = sysread $sock, $buffer, ONCE_BYTES;
			log_debug(5, "read bytes=$bytes buffer=$buffer");
			if (not defined $bytes) {
				next;
			} elsif ($bytes <=0 ) {
				$is->remove($sock);
				clean_socket($sock);
				_exit("child socket closed: pid=$$");
			} else {
				$msg->store($buffer, HEAD);
			}
		}
	}
}

sub deal_manager {
	my $index	  = shift;
	my $privilege = shift || '';
	my $event	  = $EventSpool[$index];
	my $ename	  = $event->{Event};
	my $cevent    = '';
	my $group     = '';
	my $isdaemon  = ($privilege eq 'daemon' ? '_daemon' : '');
	my $body = '';
	no strict "refs";

	my $func = lc $ename . $isdaemon;
	if (defined &$func) {
		$event = &$func($event);
	}
	return unless $event;

	for (@PLUGINS) {
		my $f = $_ . "::" . "$func" . "_" . "event" . $isdaemon; #Notice::notice_hangup_event
		if (defined &$f) {
			log_debug(5, "deal_manager: $ename");
			#print Dump($event);

			$body = &$f($dispatch, $event);
			last;
		}
	}

	send_reg_socket($group, $body) if $body;
}

sub hangup_daemon {
	my $event = shift;
	return  if $event->{Channel} =~ /^Local/i; ##ignore Local pesudo channel
	my $cid2  = get_channeltable("ChannelSpool", $event->{Channel});

	log_debug(5, "Hangup: " . $event->{Channel} . " ===> " . $cid2 . " ===> " . $event->{Cause});

	del_channeltable("ChannelSpool", $event->{Channel});
	return {callerid => $cid2, cause => $event->{Cause}};
}

sub newchannel_daemon {
	my $event = shift;
	#print Dump($event);
	log_debug(5, "new channel " . $event->{Channel} . " ===> " . $event->{CallerIDNum});
	return if $event->{Channel} =~ /^Local/i; ##ignore Local pesudo channel
	set_channeltable("ChannelSpool", $event->{Channel}, $event->{CallerIDNum});
}

sub newcallerid_daemon {
	my $event = shift;
	log_debug(5, "new callerid " . $event->{Channel} . " ===> " . $event->{CallerID});

	if (!$event->{CallerID} || $event->{CallerID} !~ /^\d+$/) {
		return;
	} else {
		set_channeltable("ChannelSpool", $event->{Channel}, $event->{CallerID});
	}
}


sub deal_client2 {
	my $buffer = shift || return;
	my $sock   = $dispatch_sock;

	return if !$sock || !$sock->connected;

	log_debug(4, "Enter deal_client for " . $sock->peerhost . ":" . $sock->peerport . " content: " . $buffer);
	#send_response($sock, justify_head($buffer));
	#return;


	my $action   = getvalue('action', $buffer) || '';
	$action      = getvalue('msg', $buffer) if !$action;
	$action    ||= 'Unkown';
	my $ActionID = getvalue('actionid', $buffer) || '';
	$ActionID    = getvalue('seq', $buffer) if !$ActionID;
	$ActionID  ||= '';

	my $return;
	if ($action eq 'Login') {
		$return = login($buffer, $sock);
	} elsif ($action eq 'pong') {
		$return = pong($buffer, $sock);
	} else {
		if (defined $dispatch_IsLogin && $dispatch_IsLogin != 1) {
			send_response($sock, justify_head($buffer . "<status>0</status><message>not login yet</message>"));
			return -1;
		}
		if ($action eq 'Logout') {
			my $return = logout($sock);
			send_response($sock, justify_head($buffer .
						"<status>$return->{status}</status><message>$return->{message}"));
			clean_socket($sock);
			return 1;
		} else {
			$return			= _deal(lc($action), $buffer, $sock);
			$lastpongtime   = time;
		}
	}

	my $status  = (defined $return->{status} ? $return->{status} : '0');
	my $message = (defined $return->{message} ? $return->{message} : '');
	my $retstr  = (defined $return->{retstr} ? $return->{retstr} : '');

	my $bytes   = send_response($sock,
					justify_head("<action>$action</action><actionid>$ActionID</actionid>" .
							"<status>$status</status><message>$message</message>$retstr")
				);


	return 1;
}

sub login {
	my $buffer    = shift || return {status => 0, message => 'buffer is null'};
	my $sock	  = shift || return {status => 0, message => 'sock is null'};
	my $username  = getvalue('username', $buffer);
	my $callerid  = getvalue('callerid', $buffer) || $username;
	my $privilege = getvalue('privilege', $buffer) || 'action';

	if (!$username) {
		return {status => 0, message => 'username is null'}
	}

	my $password = getvalue('password', $buffer) || '';

	my $infor  = {};
	if ($dispatch_conf{NAME} eq 'autodial') {
		$infor = AutoDial2::do_login($dispatch, $buffer);
	} elsif ($dispatch_conf{NAME} eq 'broadcast') {
		$infor = Broadcast::do_login($dispatch, $buffer);
	} elsif ($dispatch_conf{NAME} eq 'autodial3') {
		$infor = AutoDial3::do_login($dispatch, $buffer);
	} elsif ($dispatch_conf{NAME} eq 'tvsale') {
		$infor = Tvsale::do_login($dispatch, $buffer);
	} elsif ($dispatch_conf{NAME} eq 'panda') {
		$infor = Panda::do_login($dispatch, $buffer);
	} elsif ($dispatch_conf{NAME} eq 'groupnotice') {
		$infor = GroupNotice::do_login($dispatch, $buffer);
	} elsif ($dispatch_conf{NAME} eq 'dialer') {
		$infor = Dialer::do_login($dispatch, $buffer);
	} else {
		$infor = $dispatch->do_login($username, $password, $privilege);
	}

	if (!$infor->{status}) {
		return {status => 0, message => $infor->{message}};
	}
	my $fno					  = fileno $sock;
	$GroupInfor{$fno}         = $infor;
	$GroupInfor{$fno}->{sock} = $sock;

	$ClientSocks{fileno($sock)}->{is_login}  = 1;
	$ClientSocks{fileno($sock)}->{callerid}  = $callerid;
	$ClientSocks{fileno($sock)}->{privilege} = $privilege;
	$dispatch_IsLogin    = 1;
	$dispatch_privillege = $privilege;
	$dispatch_username	 = $username;
	$dispatch_admin		 = $dispatch->get_admin($username);
	$dispatch_callerid   = getvalue('callerid', $buffer) || $username;

	$dispatch            ->set_privilege($$, $privilege, $username, $callerid);
	$dispatch            ->log_event("$username 登陆");
	return {status => 1, message => "Login Success and treated as sock for $privilege"};
}

sub logout {
	my $sock = shift || return {status => 0, message => "sock is null"};
	delete $GroupInfor{fileno($sock)};
	delete $ClientSocks{fileno($sock)};
	$dispatch->log_event("$dispatch_callerid 调度员退出");
	return {status => 1, message => "Success Logout"};
}

sub pong {
	$lastpongtime = time;
	return {status => 1, message => "pong ok"};
}

sub _deal {
	my ($func, $buffer, $sock) = @_;
	for my $pg (@PLUGINS) {
		my $fn = "$pg" . "::" . $func;
		next unless defined &$fn;
		no strict "refs";
		return &$fn($dispatch, $buffer, $sock);
	}

	return {status => 0, message => 'no action defined'};
}

sub check_loop_main {
	no strict "refs";
	for (@PLUGINS) {
		my $fn = $_ . "::" . "loop_main";
		&$fn($dispatch) if defined &$fn;
	}
}

sub getid {
	return time . int rand 9999;
}


sub getres {
	my $aid	   = shift || return;
	my $action = shift || 'uname';
	my $times  = shift || GET_RESP_TIMES;
	my $start  = time();

	#clean MdBufferTmp for each action

	$MdBufferTmp = '';

	for my $t (1..$times) {
		log_debug(6, "$t: try read response for $aid - $action: " . time . "\n");
		my $selecttm = SELECTTM * $t;
		if (check_socket($md->connfd, '') ) {
			read_manager(1);

			if (exists $ActionSpool{$aid}) {
				my $ret = $ActionSpool{$aid};
				delete $ActionSpool{$aid};
				return %$ret;
			}
			log_debug(6, "fail to get action response for actionid=$aid wait for next time\n");
		}

		usleep ACTIONTM;
	}

	$ActionSpool{$aid} = 1;
	log_debug(2, "Fail to get response with ActionID=$aid Action=$action with $times tries: " .
				 (time - $start) . "s");
	return (Response => 'Error');
}


sub read_manager {
	my $which = shift;

	my $sock;
	my $MdBufferTmp_; #$ActionSpool_, $EventSpool_;

	if ($which) {
		$sock = $md->connfd;
		$MdBufferTmp_ = \$MdBufferTmp;
	} else {
		$sock = $mde;
		$MdBufferTmp_ = \$MdBufferTmpEvent;
	}

	return if !$sock;
	my $buffer = '';

	if (!$sock || !$sock->connected) {
		clean_socket($sock);
		return;
	}


	if (!check_socket($sock)) {
		return;
	}


	my $bytes = sysread($sock, $buffer, ONCE_BYTES);

	if (!$bytes) {
		log_debug(2, "read nothing from sock in read_manager!!!");
		clean_socket($sock);
		return;
	}


	if ($$MdBufferTmp_) {
		$buffer = $$MdBufferTmp_ . $buffer;
		log_debug(6, "****Compound msg: $buffer");
		$$MdBufferTmp_ = '';
	}
	my @dialogs = split /$EOL$EOL/, $buffer;
	if ($buffer !~ /$EOL$EOL$/) {

		$$MdBufferTmp_ = pop @dialogs || '';
		log_debug(6, "**partial msg: $$MdBufferTmp_\n");
	}

	for my $d (@dialogs) {
		log_debug(6, "--d: $d\n");
		my %h = get_h($d);
		#print Dump(\%h);

		if (exists $h{'Event'}) {
			push @EventSpool, \%h; #only for mde;
		} else {
			if (exists $h{'ActionID'}) {
				$ActionSpool{$h{'ActionID'}} = \%h unless exists $ActionSpool{$h{'ActionID'}};
			} else {
				log_debug(2, "Action RESPONSE without No ActionID");
			}
		}
	}
}

sub justify_head {
	my $rawstr = shift || return;
	$rawstr .= "<timestamp>" . time . "</timestamp>";
	###print $rawstr, "\n";

	my $len = sprintf $FMT, length(decode "utf8", $rawstr);

	return "<head>$len</head>$rawstr";
}


sub justify_head_byte {
	my $rawstr = shift || return;
	$rawstr .= "<timestamp>" . time . "</timestamp>";
	###print $rawstr, "\n";

	my $len = sprintf $FMT, $rawstr;

	return "<head>$len</head>$rawstr";
}

sub send_response {
	my $sock = shift || \*STDOUT;
	my $body = shift;

	my $fno  = fileno $sock;
	my $bytes;

	if ($fno == 0 || $fno == 1) {
		log_debug(4, " SEND RESPONSE to STDOUT - $body");
		$bytes = syswrite \*STDOUT, $body, length($body);
		return $bytes;
	}

	if (!$sock || !$sock->connected) {
		warn "send_response fail: check sock fail\n";
		return;
	}

	log_debug(4, " SEND RESPONSE to " . $sock->peerhost . ":" . $sock->peerport . " - $body");

	if (check_socket($sock, 1)) {
		$bytes = syswrite $sock, $body, length($body);
		#warn "write $bytes bytes\n";
		if (!$bytes) {
			log_debug(2, "send to " . $sock->peerhost . ":" . $sock->peerport . " fail: $!\n");
			return;
		}
		return $bytes;
	} else {
		log_debug(4, "send to " . $sock->peerhost . ":" . $sock->peerport . " fail: sock not ready!!!\n");
	}
	return;
}


sub send_unix_response {
	my $sock = shift || return;
	my $body = shift;

	my $bytes;
	if (!$sock || !$sock->connected) {
		warn "send_response fail: check sock fail\n";
		return;
	}

	log_debug(6, " SEND RESPONSE fileno=" . $sock->hostpath . " - $body");


	$bytes = syswrite $sock, $body, length($body);
	#warn "write $bytes bytes\n";
	if (!$bytes) {
		log_debug(2, "send to sock fail fileno=" . fileno($sock) .  " send nothing!!!\n");
		return;
	}
	return $bytes;
}

sub sendcommand {
	my $p	= shift || return (Response => 'error: p is null');
	my $s	= shift;
	my $Aid = getid();
	$p->{ActionID} = $Aid;
	if (!$md) {
		$md = $dispatch->init_manager();
	}
	#print Dump($p);
    $md->sendcommand($p, $s);
    return getres($Aid);
}

sub getvalue {
	my $key = shift || return;
	my $buffer = shift;
	my($value) =  $buffer =~ m{<$key>(.*?)</$key>}s;

	return defined $value ? $value : '';
}

sub get_callerid {
	return "$dispatch_callerid <$dispatch_callerid>";
}

sub get_h {
	my $str = shift || return;

	my @return;
	my @lines = split /$EOL/, $str;
	my $output = '';
	my $is_command = 0;
	for my $l (@lines) {
		my($key, $val) = $l =~ /^(.+?):\s(.+?|)$/;

		if (defined $key && defined $val && $key eq 'Response' && $val eq 'Follows') {
			$is_command = 1;
		} elsif ($key) {
			$val = (defined $val ? $val : '');
			push @return, $key, $val;
		} else {
			$output .= $l . "\n";
		}
	}

	push @return, 'Response', $output if $is_command;
	return @return;
}

sub clean_socket {
    my $sock = shift;
	if (!$sock || !$sock->connected) {
		return;
	}

    $sock->close;
}

sub log_debug {
    my($l, $str) = @_;
	return if $l > $dispatch_conf{LOGLEVEL};
    print "[". $dispatch->dispatch_time() . "]" . " [$$] level: $l - body: $str\n";
    if ($l == 0) {
        $dispatch->log_debug($l, $str);
        exit 0;
    } elsif ($l > 0 && $l < 7) {
        $dispatch->log_debug($l, $str);
    }
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

sub set_log {
	my $l = shift || return;
	warn "set log: " . $dispatch_conf{LOGLEVEL} . " to " . $l . "\n";
	$dispatch_conf{LOGLEVEL} = $l;
	open FH, ">", $CONFFILE || die "fail to open " . $CONFFILE . "for writing: $!\n";
	while (my ($k, $v) = each %dispatch_conf) {
		print FH "$k=$v\n";
	}
	close FH;
}

sub is_from_mde {
	my $sock = shift || return;
	return if !$sock->connected;
	return fileno($sock) == $MdeFno ? 1 : 0;
}

sub is_from_md {
	my $sock = shift || return;
	return if !$sock->connected;
	return fileno($sock) == $MdFno ? 1 : 0;
}

sub is_from_pbx {
	my $sock = shift || return;
	return if !$pbxsockno || !$sock->connected;
	return fileno($sock) == $pbxsockno ? 1 : 0;
}

sub read_client {
	my $sock = shift || return;
	my $len  = shift || ONCE_BYTES;
	my $buffer = '';
	##### read_client

	my $bytes = sysread($sock, $buffer, $len);
	#print "read $bytes/$len bytes from " . $sock->peerhost . ":" . $sock->peerport . "\n";
	if (!$bytes) {
		#warn "read nothing from sock in read_client\n";
		clean_socket($sock);
		_exit("exit for client disconnected\n");
		return;
	}
	if ($bytes < $len) {
		$buffer .= read_client($sock, $len - $bytes);
	}

	return $buffer;
}

sub check_socket {
	my ($sock, $mode, $tm) = @_;
	return if !$sock || !$sock->connected;
	my $ism = $dispatch->init_ioselect() || die "Cannot create io::select obj!\n";
	$tm ||= 0.1;
	#warn "enter check_socket: tm=$tm, fileno=" . fileno($sock) . "\n";
	##### check_socket ...
	#warn "host: " . $sock->peerhost . "\n";
	$ism->add($sock);
	my @ready;
	my $start = time;
	#warn "startime: " . $start . " and tm is $tm\n";
	if ($mode) {
		@ready = $ism->can_write($tm);
	} else {
		@ready = $ism->can_read($tm);
	}
	#warn "endtime: " . time . '   ' . $tm . '/' . (time-$start) . "\n";
	$ism->remove;
	return @ready;
}

sub send_reg_socket {
	my $group = shift;
	my $event = shift;

	log_debug(5, $event);
	my @EP = qw /action queue_admin inbound/;
	return unless $dispatch_IsLogin;
	for (@EP) {
		if ($dispatch_privillege eq $_) {
			send_response($dispatch_sock, justify_head($event));
			log_debug(5, "Send Event to " . $dispatch_sock->peerhost . ":" .
						$dispatch_sock->peerport . ' --- ' . $event);
			last;
		}
	}

}

### hash to str
sub get_str {
	my $h = shift || return;
	my $str = '';

	while (my($k, $v) = each %$h) {
		$str .= "<$k>$v</$k>";
	}
	return $str;
}

### assume the callerid of a channel

sub get_astvalue {
	my $str = shift || return;
	my ($v) = ($str =~ /Value: (.*)$/m);
	return $v;
}

sub _channel {
	my $ext = shift || return;
	my $l   = shift || 'l';
	if ($ext =~ /(.+?)#$/) {
		return "Local/$1\@from-internal/n";
	}
	if ($l) {
		return "Local/$ext\@from-internal/n"
	}

	return "SIP/$ext";
}

### try best to get the channel from its callerid
### if callerid is an extension in system,match SIP/xxxx-yyyy
### if callerid is an external number,

sub intial_channelspool {
	my $Aid	 = getid();
	my %resp = sendcommand({Action => 'Command', Command => 'core show channels concise', ActionID => $Aid}, 1);
	my @lines= split /\n/, $resp{Response};

	for my $l (@lines) {
		next if !$l || $l =~ /^local/i; #ignore local channel
		my @fields = split /!/, $l;
		next unless $fields[1];
		my $cid	   = '';
		if ($fields[1] eq 'from-sip-external') {
			$cid   = $fields[7];
		} else {
			#SIP/2007-09233ba0
			($cid) = $fields[0] =~ m/^(?:sip|ooh323|h323|zap)\/(\d+?)\-/i;
		}

		set_channeltable("ChannelSpool", $fields[0], $cid);
		set_channeltable("ChannelSpoolReverse", $cid, $fields[0]);
	}
}


sub getchannels {
	my $number  = shift || return;
	my $verbose = shift;

	my $chan = get_channeltable("ChannelSpoolReverse", $number);
	return  $chan if $chan && (not defined $verbose);

	my $Aid  = getid();
	my %resp = sendcommand({Action => 'Command', Command => 'core show channels concise', ActionID => $Aid}, 1);
	my @lines   = split /\n/, $resp{Response};

	for my $l (@lines) {
		next if $l =~ /^local/i; #ignore local channel
		my @fields = split /!/, $l;
		if ($fields[1] =~ /external/i) {
			if (!compare_callerid($number, $fields[7], $fields[0])) {
				next;
			} else {
				return @fields if $verbose;
				return $fields[0];
			}
		}

		if ($fields[0] =~ m{^(?:sip|ooh323|h323|zap)/$number}i) {
			return @fields if $verbose;
			return $fields[0];
		}
	}

	return;
}

sub getcallsbyacct {
	my $acct = shift || return 0;
	my $cnt  = 0;
	my $Aid  = getid();
	my %resp = sendcommand({Action => 'Command', Command => 'core show channels concise', ActionID => $Aid}, 1);
	my @lines   = split /\n/, $resp{Response};

	for my $l (@lines) {
		next if $l =~ /^local/i; #ignore local channel
		my @fields = split /!/, $l;
		if ($fields[0] =~ m{^(?:sip|ooh323|h323|zap)/}i) {
			$cnt++ if $fields[8] eq $acct;
		}
	}

	return $cnt;
}

sub getconference {
	my $Aid = getid();
	for (800 .. 820) {
		my $Aid = getid();
		my %resp = sendcommand({Action => 'Command', Command => "meetme list $_", ActionID => $Aid}, 1);
		warn "getconference: $resp{Response}\n";
		return $_ if $resp{Response} =~ /No (?:active|such) conference/;
	}
	return;
}

sub getconfinfo {
	my $confid = shift || return;
	log_debug(5, "confid: $confid\n");
	my $userid = shift;

	my $Aid   = getid();
	my %resp  = sendcommand({Action => 'Command', Command=> "Meetme list $confid concise", ActionID => $Aid}, 1);
	my @lines = split /\n/, $resp{Response};
	###print "meetme   " . Dump(\@lines);
	my @info;
	for my $l (@lines) {
		##line: $l;
		my @fields = split /!/, $l;
		if ($userid) {
			return @fields if $fields[3] && $fields[3] =~ /SIP\/$userid-/;
		} else {
			push @info, \@fields;
		}
	}
	return @info;
}

sub getuserconf {
	my $user = shift || return;

	my @chan_attribs = getchannels($user, 1);

	#print join "!", @chan_attribs, "\n";
	my ($app, $arg) = @chan_attribs[5..6];
	#warn "app: $app ==== arg: $arg\n";
	if (defined $app && $app !~ /meetme/i) {
		return;
	}

	return if !$arg;

	my ($confid) = ($arg =~ /(\d+)/);
	my @info = getconfinfo($confid, $user);

	return if !$info[0];
	return($confid, $info[0]);
}

sub chan2cid {
	my $chan = shift || return '';
	my $mode = shift;

	warn "try to get cid for $chan";
	my ($lchan, $tag) = parse_localchannel($chan);
	$chan = $lchan if $lchan;

	my $cid = get_channeltable("ChannelSpool", $chan);
	return $cid if $cid;

	my $Aid     = getid();
	my %resp    = sendcommand({Action => 'Command', Command => 'core show channels concise', ActionID => $Aid}, 1);
	my @lines   = split /\n/, $resp{Response};

	if ($lchan) {
		warn "try to get cid from $chan";
		for my $l (@lines) {
			next unless $l =~ /^$chan/;
			my @fields = split /!/, $l;
			my $tmpchan = $fields[$#fields];
			warn "$tmpchan - $l\n";
			if ($tmpchan =~ m{(?:sip|zap|h323|ooh323)/}i) {
				warn "get normal chan=$tmpchan for $chan";
				$chan = $tmpchan;
				$cid = get_channeltable("ChannelSpool", $chan);
				return $cid if $cid;
			}
		}
	}


	for my $l (@lines) {
		next unless $l =~ /^$chan/;
		my @fields = split /!/, $l;
		warn "try to get cid for chan: $chan from $l\n";

		if ($fields[7]) {
			set_channeltable("ChannelSpool", $chan, $fields[7]);
			set_channeltable("ChannelSpoolReverse", $fields[7], $chan);
			return $fields[7];
		}
	}
	return;
}

sub getcidgroup {
    my $cid = shift || return;
	return 1;
	return $ExtenSpool{$cid} if exists $ExtenSpool{$cid};
	while (my($k, $v) = each %GroupInfor) {
		for (@{$v->{extensions}}) {
			 if ($cid eq $_) {
				 $ExtenSpool{$cid} = $v->{id};
				 return $v->{id};
			 }
		}
	}
	return;

}

sub compare_callerid {
	my $number = shift || return;
	my $cid	   = shift || return;
	my $chan   = shift;

	return ($number eq $cid ? 1 : 0);
}

sub parse_localchannel {
	my $chan = shift;
	my ($lchan, $tag) = $chan =~ m{(Local\/.+),([12])$}i;

	if ($lchan && $tag) {
		return ($lchan, $tag);
	} else {
		return('', '');
	}
}

sub get_cidfromlocalchan {
	my $chan  = shift || return;
	my ($cid) = $chan =~ m{Local\/(.+?)\@};

	return $cid || '';
}

sub get_channeltable {
	my $item = shift || return;
	my $key  = shift || return;

	if (not exists $ChannelTable{$item}) {
		$ChannelTable{$item} = {};
		return;
	} else {
		return $ChannelTable{$item}->{$key};
	}
}

sub del_channeltable {
	my $item = shift || return;
	my $key  = shift || return;

	if (not exists $ChannelTable{$item}) {
		$ChannelTable{$item} = {};
		return;
	} else {
		delete $ChannelTable{$item}->{$key};
	}
}

sub set_channeltable {
	my $item = shift || return;
	my $key  = shift || return;
	my $val	 = shift;

	warn "set $item->$key: $val";
	if (not exists $ChannelTable{$item}) {
		$ChannelTable{$item} = {};
	}
	$ChannelTable{$item}->{$key} = (defined $val ? $val : '');
}

sub check_privilege {
    my $action = shift || 'action';
	return 1 if $action eq 'system';
    return 1 if $dispatch_privillege eq $action;
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

sub usage {
	my $txt =<<"EOC";
h: print this
s: shutdown center2.pl
d: run center2.pl in daemon module
EOC
	print $txt;
}

sub shutdown_center {
	my $name   = shift;
	warn "try to shutdown $name";
	die "script name must be center[X]|autodial[X]" unless $name =~ /(?:center|autodial|mazal\.pl|mazal)(?:\d|)$/i;
	#my $script = "mazal.pl";
	#warn "try to shutdown $script";
	my $ps	   = `ps aux | grep -w '$name ' | grep -v grep | grep -v ' -s'`;
	#print $ps;
	for (my @lines =  split "\n", $ps) {
		warn $_, "\n";
		my @fields = split /\s+/, $_;
		kill 2, $fields[1];
	}
}

sub dehash {
	my $hash = shift || return;
	my $result = "";
	while (my($key, $val) = each %$hash) {
		$result .= "<$key>$val</$key>";
	}
	return $result;
}

sub hash2line {
	my $hash = shift;

	my $line = '';
	while (my ($k, $v) = each %$hash) {
		$line .= "$k: $v\r\n";
	}
	$line .= "\r\n";
	return $line;
}

sub is_true {
	return $dispatch->is_true(@_);
}

sub show_version {
	my ($ver) = $VERSION =~ /\$Rev: (\d+) \$$/;
	print $ver;
}

#release all opened file descriptor in %ClientSpool
sub _release {
	my $cp = shift;

	while (my ($k, $v) = each %$cp) {
		my $sock = $cp->{$k}{sock};
		if ($sock && $sock->connected) {
			$sock->close();
		}
		delete $cp->{$k};
	}
}

sub _stop_server {
    log_debug(1, "** catch INT SIGNAL, server will quit!");
    if (-e $sock_file) {
		unlink $sock_file;
    }
	log_debug(1, "** Server stop!!![OK]");
    exit 0;
}

sub _reaper {
	my $pid;
	while (($pid = waitpid(-1, WNOHANG)) > 0) {
		if ($ClientSpool{$pid}) {
			my $sock = $ClientSpool{$pid}->{sock};
			if ($sock && $sock->connected) {
				$sock->close();
			}
			delete $ClientSpool{$pid};
		}
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
	$dispatch->del_child($$);
	log_debug(3, "child $$ quit normally: $msg!\n");
	unlink 'broadcast_lock';
	exit 0;
}

