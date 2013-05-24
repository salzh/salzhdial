#!/opt/lampp/bin/perl
#Center.pm
#Since 2010-2-1
#Author by Salzh
#$Id: salzh $

package Center;

#use YAML;
use Smart::Comments;
use Log::Handler;
use strict;
use warnings;
use CCCenter::Manager2;
use CCCenter::Manager;
use DBI;
use HTTP::Date;
use IO::Socket::INET;
use IO::Socket::UNIX;
use Encode;

#use GD;
use YAML;
use constant {
    DBH_RECONNECT => 3600,
    RELOAD        => ".reload"
};


sub new {
	my $class = shift;

	my %h = @_;
    $h{'ami_user'} ||= 'dispatch';
    $h{'ami_pass'} ||= 'dispatch123';
    $h{'ami_host'} ||= '127.0.0.1';
    $h{'ami_port'} ||= '5038';
    $h{'back'} ||= 0;
    $h{'ami_event_user'} ||= 'admin';
    $h{'ami_event_pass'} ||= 'amp111';
    $h{'server_port'} ||= 26668;
    $h{'logfile'} ||= '/var/log/cccenter/center.log';
    my $log = Log::Handler->new();
	$log->add(file => {
			filename => $h{'logfile'},
			mode	 => 'append',
			maxlevel => 'debug',
			minlevel => 'critical',
			newline => 1,
			timeformat => "%Y-%m-%d %H:%M:%S",
            message_layout  => "%T [%L] [%P]: %m",
			die_on_errors => 0
			}
	);

	#$self->{log} = $log;
    $h{log} = $log;

	return bless(\%h, $class);
}

sub init_manager {
	my $self = shift;
    my ($user, $secret, $host, $port) = @_;
	my $ami  = new CCCenter::Manager2;
    $user   ||= $self->{ami_user};
    $secret ||= $self->{ami_pass};
    $host   ||= $self->{ami_host};
    $port   ||= $self->{ami_port};


	$ami->user($user);
	$ami->secret($secret);
	$ami->host($host);
    $ami->port($port);

	$ami->connect || return;
	warn "Success Connect to CCCenter manager: " . $user . "\n";

	$self->{ami} = $ami;


	return $ami;
}

sub init_manager_event {
	my $self = shift;
    my ($user, $secret, $host, $port) = @_;
	my $ami_event = new CCCenter::Manager2;
    $user   ||= $self->{ami_event_user};
    $secret ||= $self->{ami_event_pass};
    $host   ||= $self->{ami_host};
    $port   ||= $self->{ami_port};

	$ami_event->user($user);
	$ami_event->secret($secret);
    $ami_event->host($host);
    $ami_event->port($port);
	$ami_event->connect || return;

	warn "Success Connect to CCCenter manager for event: " . $user . "\n";
	$self->{ami_event} = $ami_event;
	return $ami_event;
}

sub init_event {
	my $self = shift;
    my ($path, $blocking) = @_;
    $path     ||= "/tmp/center.sock";
    $blocking ||= 0;

	my $sock = new IO::Socket::UNIX->new (
                        Peer    => $path,
                        Type    => SOCK_STREAM,
                        Timeout => 5
  	);

    if (!$sock) {
	    $self->log_debug(2, "Fail to create server socket at path=$path: $!");
        die "Fail to create server socket at path=$path: $!";
        return;
    }
    $sock->autoflush(1);
	return $sock;
}

sub init_manager1 {
    my $self = shift;
    my ($user, $secret, $host, $port) = @_;
    my $ami = new CCCenter::Manager;
    $user   ||= $self->{ami_user};
    $secret ||= $self->{ami_secret};
    $host   ||= $self->{ami_host};
    $port   ||= $self->{ami_port};


    $ami->user($self->ami_user);
    $ami->secret($self->ami_pass);
    $ami->host($self->ami_host);
    $ami->host($host);
    $ami->port($port);
    $ami->connect || return;

    return $ami;
}


sub init_dbh {
	my $self = shift;
    my $db   = shift || 'cccenter';
    my $host = shift || '127.0.0.1';
    my $user = shift || 'cccenteruser';
    my $pass = shift || 'amp109';

	my $dbh = DBI->connect("DBI:mysql:database=$db;host=$host",
						  "$user", "$pass", {RaiseError => 0, AutoCommit => 1});
    if ($db eq 'cccenter') {
        $self->{dbh} = $dbh;
        $self->{dbh_starttime} = time;
    }
    $dbh->{mysql_auto_reconnect} = 1;

	$self->log_debug(2, "Fail to connect database" . $DBI::errstr) if !$dbh;
	return $dbh;
}

sub init_server {
	my $self    =  shift;
    my ($port, $blocking) = @_;
    $port     ||= $self->server_port;
    $blocking ||= $self->server_blocking;

	my $sd  = new IO::Socket::INET(
   		Listen    => 1,
    	LocalPort => $port,
   		ReuseAddr => 1,
   		Blocking  => $blocking
  	);

    return if !$sd;

	$self->{sd} = $sd;
	return $sd;
}

sub init_server_unix {
    my $self = shift;
    my ($path, $blocking) = @_;
    $path     ||= "/tmp/center.sock";
    $blocking ||= 0;

    unlink $path;
    my $sdu = IO::Socket::UNIX->new(
                 Local => $path,
                 Type      => SOCK_STREAM,
                 Listen    => 1,
                 Blocking  => $blocking);
   if (!$sdu) {
        warn "Fail to create unix server socket path=$path: $@";
        $self->log_debug(2, "Fail to create unix server socket path=$path: $!");
        return;
   }
   $self->{sdu} = $sdu;
   return $sdu;
}

sub init_sock {
    my $self = shift;
    my $sock = new IO::Socket::INET(
            PeerAddr => $_[0],
            PeerPort => $_[1],
            Proto => $_[2] || 'tcp',
            Blocking => $_[3] || 1
    );

    return $sock;
}

sub init_ioselect {
	my $self = shift;

	my $is = IO::Select->new() || return;

	$self->{is} = $is;
	return $is;
}

sub ami_user {
	my $self = shift;
	if (@_ < 1) {
		return $self->{ami_user};
	} else {
		$self->{ami_user} = $_[0];
	}

}

sub dbh {
    my $self = shift;
    if ($self->{dbh} && exists $self->{dbh_starttime} &&
        time - $self->{dbh_starttime} < DBH_RECONNECT) {
        return $self->{dbh};
    }
    my $dbh = $self->init_dbh();
    return $dbh;
}

sub dbh_phonebook {
    my $self = shift;
    my $dbh = shift;
    if ($dbh) {
        $self->{dbh_phonebook} = $dbh;
    }
    return $self->{dbh_phonebook};
}

sub dbh_cdr {
    my $self = shift;
    my $dbh = shift;
    if ($dbh) {
        $self->{dbh_cdr} = $dbh;
        return;
    } else {
        return $self->{dbh_cdr} if $self->{dbh_cdr};
        my $dbh =  $self->init_dbh('cccentercdrdb');
        $self->{dbh_cdr} = $dbh;
        return $self->{dbh_cdr};
    }
}

sub dbh_dialer {
	my $self = shift;
    my $dbh = shift;
    if ($dbh) {
        $self->{dbh_dialer} = $dbh;
        return;
    } else {
        return $self->{dbh_dialer} if $self->{dbh_dialer};
        my $dbh =  $self->init_dbh('evoice');
        $self->{dbh_dialer} = $dbh;
        return $self->{dbh_dialer};
    }
}

sub dbh_dialer_force {
	my $self = shift;
   
	my $dbh =  $self->init_dbh('evoice');
	$self->{dbh_dialer} = $dbh;
	return $self->{dbh_dialer};
   
}

sub dbh_crm {
    my $self = shift;
    my $dbh  = shift;
    if ($dbh) {
        $self->{dbh_crm} = $dbh;
        return;
    } else {
        return $self->{dbh_crm} if $self->{dbh_crm};
        my $dbh =  $self->init_dbh('mazalcrm', '', 'mazalcrm', 'mazalcrm');
        $self->{dbh_crm} = $dbh;
        return $self->{dbh_crm};
    }
}

sub ami_pass {
	my $self = shift;
	if (@_ < 1) {
		return $self->{ami_pass};
	} else {
		$self->{ami_pass} = $_[0];
	}
}

sub ami_host {
	my $self = shift;

	if (@_ < 1) {
		return $self->{ami_host};
	} else {
		$self->{ami_host} = $_[0];
	}
}

sub server_port {
	my $self = shift;
	if (@_ < 1) {
		return $self->{server_port};
	} else {
		$self->{server_port} = $_[0];
	}
    #warn "server_port: " . $self->{server_port} . "\n";
}

sub server_blocking {
    my $self = shift;
	if (@_ < 1) {
		return (exists $self->{server_blocking}) && $self->{server_blocking} ? 1 : 0 ;
	} else {
		$self->{server_blocking} = $_[0];
	}
}

sub log_debug {
	my $self  = shift;
	my $level = shift;
	my $body  = shift;

	if ($level <= 1) {
		$self->{log}->critical($body);
	} elsif ($level == 2) {
		$self->{log}->error($body);
	} elsif ($level == 3) {
		$self->{log}->notice($body);
	} elsif ($level == 4) {
		$self->{log}->info($body);
	} elsif ($level == 5) {
        $self->{log}->warning($body);
    } else {
		$self->{log}->debug($body);
	}

}

sub log_event {
    my $self = shift;
    my $content = shift || '';

    my $dbh = $self->dbh;
    my $sth = $dbh->prepare("insert into dispatch_log (content, actiontime) values(?, ?)");
    $sth->execute($content, $self->dispatch_time());

    return 1;
}

sub dispatch_time {

	my @today = localtime;
	return sprintf( "%04d-%02d-%02d %02d:%02d:%02d",
				 $today[5]+1900, $today[4]+1, $today[3], $today[2], $today[1], $today[0])

}

sub do_login {
    my ($self, $user, $pass, $p) = @_;

    return {status => 0, message => 'username/password is null'} if !$user || !$pass;
    #return if $user ne '2002' || $pass ne '2002';
    my $infor;
    my $dbh = $self->dbh;
    if ($p eq 'queue_admin') {
        my $sth = $dbh->prepare("select * from queues_config where extension=?");
        $sth->execute($user);
        return {status => 0, message => "Queue Authen Fail"} if $sth->rows != 1;
        $infor->{id} = $user;
        $sth = $dbh->prepare("select * from queues_details where id=?");
        $sth   -> execute($user);
        while (my $row = $sth->fetchrow_hashref()) {
            next unless $row->{keyword} eq 'member';
            next unless $row->{data} =~ /^local/i;

            my ($exten, $tag) = $self->getcidfromlocalchan($row->{data});
            push @{$infor->{extensions}}, $exten if $exten;
        }

    } else {
        my $sth = $dbh->prepare("select * from sip where id=? and keyword='secret' and data=?");
        $sth->execute($user, $pass);
        return {status => 0, message => 'Authen Fail'} if $sth->rows != 1;

        $sth = $dbh->prepare("select groupid from dispatch_groupuser where extension=? and is_admin=1 limit 1");
        $sth->execute($user);
        my @rows = $sth->fetchrow_array();
        return {status => 0, message => 'user is not an admin of any group'} if !@rows;
        my $groupid = $rows[0];

        $sth = $dbh->prepare("select * from dispatch_groups, dispatch_roles where dispatch_groups.admin_grade=dispatch_roles.grade and id=?");
        $sth->execute($groupid);
        $infor = $sth->fetchrow_hashref || return {status => 0, message => 'No admin grade'};

        $infor->{extensions} = [];
        $sth = $dbh->prepare("select extension from dispatch_groupuser where groupid=?");
        $sth->execute($groupid);

        while(my @rows = $sth->fetchrow_array()) {
            push @{$infor->{extensions}}, $rows[0];
        }
    }

    $infor->{status} = 1;
    return $infor;
}

sub get_admin {
    my $self = shift;
    my $user = shift || return;

    my $dbh  = $self->dbh;
    my $sth  = $dbh->prepare("select * from queues_details where keyword='member' and data like '%$user%' limit 1");
    $sth    -> execute();

    my $q   = $sth->fetchrow_hashref;
    return $q->{id} || '';
}

sub add_child {
    my $self = shift;
    return if @_ < 3;
    warn join ",", @_, "\n";
    my $dbh = $self->dbh;
    my $sth = $dbh->prepare("insert into dispatch_status (pid,fdno,domain,status,privilege,starttime,endtime) ".
                            "values (?,?,?,'1','unknown',?,'-')");
    $sth->execute($_[0], $_[1], $_[2], $self->_time2str());
    return 1;
}

sub set_privilege {
    my $self = shift;
    my $pid  = shift || return;
    my $privilege = shift || return;
    my $username  = shift || '';
    my $callerid  = shift || '';

    my $dbh = $self->dbh;
    my $sth = $dbh->prepare("update dispatch_status set privilege=?, username=?, callerid=? where pid=?");
    $sth->execute($privilege, $username, $callerid, $pid);
    return 1;
}

sub del_child {
    my $self = shift;
    my $pid = shift || return;

    my $dbh = $self->dbh;
    my $sth = $dbh->prepare("update dispatch_status set status='0', endtime=? where pid=?");
    $sth->execute($self->_time2str(), $pid);
    return 1;
}

sub clear_status {
    my $self = shift;

    my $dbh = $self->dbh;
    my $sth = $dbh->prepare("update dispatch_status set status='0', endtime=? where status='1'");
    $sth->execute($self->_time2str());
    return 1;
}

sub compile_condition {
    my $self = shift;
    my $offset = shift || 0;
    my $limit = shift || 0;
    my $rawcond = shift;
    my $map = shift;

    my $condition = '';
    for my $cond (split ',', $rawcond) {
        my ($f, $o, $v) = split ';', $cond;
        next unless $f && $o;
        $condition .= " and " if $condition;
        if ($map && $map->{alias}{$f}) {
            $condition .= " " . $map->{alias}{$f} . " ";
        } else {
            $condition .= " $f ";
        }

        if ($o eq 'eq') {
            $condition .= '=';
        } elsif ($o eq 'le') {
            $condition .= '<';
        } elsif ($o eq 'gt') {
            $condition .= '>';
        } elsif ($o eq 'ne') {
            $condition .= '<>';
        } elsif ($o eq 'like') {
            $condition .= 'like';
        } elsif ($o eq 'gte') {
            $condition .= '>=';
        } elsif ($o eq 'lee') {
            $condition .= '<=';
        } else {
            $condition .= '=';
        }
        if ($o eq 'like') {
            $condition .= " '%$v%' ";
        } else {
            $condition .= " '$v' ";
        }
    }
    if ($map) {
        while (my($k, $v) = each %{$map->{table_fields}}) {
            $condition .= " and ";
            $condition .= "$k=$v";
        }
    }

    return $condition;
 }

sub get_reg_sockets {
    my $self = shift;
    my $dbh  = $self->dbh;

    my $sth  = $dbh->prepare("select * from dispatch_status where status='1'");
    $sth     ->execute();

    my $results = [];
    while (my $row = $sth->fetchrow_hashref) {
        push @$results, $row;
    }

    return $results;
}

sub in_array {
    my $self = shift;
    my $arr = shift || return;
    my $e = shift || '';

    for (@$arr) {
        return 1 if $e eq $_;
    }
    return;
}

sub _time2str {
    my $self = shift;
    my $time = shift;
    my $mode = shift;

    my @args = ();
    if (defined $time && $time ne '0') {
        if ($time =~ /\d-\d/) {
            return $time;
        }
        @args = localtime $time;
    } else {
        @args = localtime;
    }
    if (!$mode) {
        return sprintf("%02d-%02d-%02d %02d:%02d:%02d",
                $args[5]+1900, $args[4]+1, $args[3], $args[2], $args[1], $args[0]);
    } else {
        return sprintf("%02d-%02d-%02d", $args[5]+1900, $args[4]+1, $args[3]);
    }
}


sub _tts {
    my $self = shift;
    my $outfile = shift || return;
    my $content = shift || return;

    my $textfile = "/tmp/" . time . ".txt";
	open FH, "> $textfile";
	print FH $content;
	close FH;
	if (system("/usr/local/bin/tts $textfile $outfile") != 0) {
		warn "tts error: $!\n";
		return;
	}
	if (!-e $outfile) {
		warn "$outfile not generated!\n";
		return;
	}
    return 1;
}

sub add_recording {
    my $self = shift;
    my $name = shift || 'uname';
    my $content = shift || '';

    my $audioid = shift || time;
	my $filename = shift;
    if ($content) {
        my $recfile = "";
        if (!$filename) {
            $filename = "custom/$audioid";
        }
        $recfile = "/var/lib/cccenter/sounds/$filename.wav";
        $self->_tts($recfile, $content) || return {status=> 0, message => 'tts error'};
    }
    if (!$filename && !$content) {
        return {status => 0, message => 'both recfile and content are null'};
    }

    my $dbh = $self->dbh();
    my $sth = $dbh->prepare("insert into recordings (displayname,filename,content) values (?, ?,?)");
    $sth->execute($name, $filename, $content);
    return {status => 1, message => 'ok', id => $dbh->last_insert_id(undef, undef, 'recordings', 'id')};
}

sub send_audio {
    my $self     = shift;
    my $caller   = shift || return;
    my $audioid  = shift || return;
    my $callerid = shift || '';

    my $Aid      = main::getid();

    $self->{ami}->sendcommand({Action => 'Originate', Channel => "Local/$caller\@from-internal/n", Async => 1,
                     ActionID => $Aid, Timeout => 300000, Variable => "AUDIOID=$audioid",
                     Context => 'mycustom-dispatch', CallerID => "$callerid <$callerid>",
                     Exten => 'audio', Priority => 1}, 1);
    my %resp    = main::getres($Aid);
    return ($resp{Response} =~ /error/i ? 0 : 1);
}

sub send_email {
    my $self = shift;

    my $title = shift || '无标题';
    my $body = shift || '内容为空';

    my $to = shift || return {status => 0, message => 'address is null'};
    my $cc = shift || '';
    my $bcc = shift || '';
    my $attachment = shift || '';


    my $cmd = "mutt -s \"$title\" " .
              ($cc ? "-c \"$cc\" " : '') .
              ($bcc ? "-b \"$bcc\" " : '') .
              ($attachment ? "-a $attachment " : '').
              "-s \"$title\" " . $to;
    $cmd = "echo \"$body\" | $cmd";
    print $cmd;
    warn "Can't fork for send email" unless defined (my $child = fork);
    if ($child == 0) {
        system($cmd);
        exit 0;
    } else {
        return {status => 1, message => "OK"};
    }
}

sub send_fax {
}

sub send_sms {
}

sub need_reload {
    my $self = shift;

    system("touch " . RELOAD) == 0 || warn "fail to create " . RELOAD . ": $!\n";
    return 1;
}

sub is_reload {
    my $self = shift;
    return (-e RELOAD ? 1 : 0);
}

sub apply_cccenter_conf {
    my $self = shift;
    my $md   = $self->{ami};
    $md      = $self->init_manager if !$md;
    my $cmd  = "/var/lib/cccenter/bin/retrieve_conf";
    system("$cmd > /dev/null 2>&1");
    $md->sendcommand({Action => 'Command', Command => 'reload', ActionID => 66667},1);
    unlink RELOAD;
    return 1;
}

sub build_string {
    my $self = shift;
	my $size = int scalar @_;
	return if $size <= 0 && $size / 2 != 0;

	my $str = "<actionid>" . (int rand (899999) + 100000) . "</actionid>";
	for (my $i = 0; $i < $size; $i += 2) {
		$str .= "<$_[$i]>" . (defined $_[$i+1] ? $_[$i+1] : '') . "</$_[$i]>";
	}

	return sprintf("<head>%07d</head>$str", length $str);
}

sub parse_string {
    my $self = shift;

    my $str = shift || return;
    my $hash = {};
    while ($str =~ /\<(.+?)\>(.*?)\<\/(.+?)\>/g) {
        next unless $1 eq $3;
        $hash->{$1} = (defined $2 ? $2 : '');
    }
    return $hash;
}

sub parse_msg_file {
    my $self = shift;
    my $file = shift || return;

    my $struct = {};
    my $section = '';
    my $s = 1;
    open FH, $file || return;
    while (<FH>) {

        next if /^\s*$/ || /^;/;
        chomp;
        if ($s == 1) {
            next unless /^\s*\[(.+?)\]\s*$/;
            $section = $1;
            $s = 2;
        } elsif ($s == 2) {
            if (/^\s*\[(.+?)\]\s*$/) {
                $s = 1;
                redo;
            }
            my ($key, $val) = split '=';
            #warn "$key $val\n";
            next if !$key;
            $struct->{$section}{$key} = $val;
            #warn "$section == $key === $val\n";
       }
   }
   close FH;
   return $struct;
}

#loop sql sth handle
sub loop_sth {
    my $self = shift;
    my $sth = shift || return '';
    my $name = shift;
    my $h = shift || return '';
    my $map = shift || {};
    my $filter = shift || {};
    my $count  = shift;
    my $isc    = $count;
    my $str = '';
    my $size = int scalar @$h;
    while (my $row = $sth->fetchrow_hashref) {
        $str .= "<$name>" if $name;
        for (my $i=0;$i < $size; $i+=2) {
            my ($name, $dv) = ($h->[$i], $h->[$i+1]);
            my $nm = $name;
            $nm    = $map->{$name} if exists $map->{$name};
            my $val=$row->{$name};
            if ($val && $filter->{$name}) {
                if ($filter->{$name} eq 'str2time') {
                    $val = $self->center_str2time($val);
                } elsif ($filter->{$name} eq 'time2str') {
                    $val = $self->center_time2str($val);
               }
            }
            $str  .= "<$nm>" . (defined $val ? $val : $dv) . "</$nm>";
        }
        $str .= "</$name>" if $name;

        last if $isc && !--$count;
   }
   return $str;
}

#loop hash ref
sub loop_sth2 {
    my $self = shift;
    my $row = shift || return '';
    my $name = shift;
    my $h = shift || return '';
    my $map = shift || {};
    my $str = '';
    my $size = int scalar @$h;
    $str .= "<$name>" if $name;
    for (my $i=0;$i < $size; $i+=2) {
        my ($name, $dv) = ($h->[$i], $h->[$i+1]);
        my $nm = $name;
        $nm    = $map->{$name} if exists $map->{$name};
        $str  .= "<$nm>" . (defined $row->{$name} ? $row->{$name} : $dv) . "</$nm>";
    }
    $str .= "</$name>" if $name;
   return $str;
}

sub global_variable {

}

sub parse_params {
    my $self   = shift;
    my $params = shift || return;
    my $h      = {};
    for my $pair (split '&', $params) {
        my ($key, $val) = split '=', $pair, 2;
        next if !$key;
        $h->{$key} = (defined $val ? $val : '');
    }

    return $h;
}

#xxx-xx-xx xx:xx:xx to time (\d\d\d\d\d)
sub center_str2time {
    my $self = shift;
    my $date = shift || return '';
    my $zone = shift || "+0800";

    return str2time($date, $zone) || 0;
}
#time(\d\d\d\d) to xxxx-xx-xx xx:xx:xx
sub center_time2str {
    my $self = shift;
    my $time = shift;
    my $mode = shift;
    return $self->_time2str($time, $mode);
}

#time(\d\d\d\d) to xxxxaabbccddee
sub center_time2str2 {
    my $self = shift;
    my $time = shift;
    my $mode = shift;

    my @args = ();
    if (defined $time) {
        if ($time =~ /\d-\d/) {
            return $time;
        }
        @args = localtime $time;
    } else {
        @args = localtime;
    }

    return sprintf("%04d%02d%02d%02d%02d%02d",
            $args[5]+1900, $args[4]+1, $args[3], $args[2], $args[1], $args[0]);

}

sub getcidfromlocalchan {
    my $self = shift;
    my $chan = shift;

    my ($cid) = $chan =~ m{^Local\/(.+)\@}i;
    return $cid || '';
}

sub sendtail {
    my $self = shift;
    my $file = shift || return ('', '');
    my $size = shift;
    my $pid  = shift;
    my $t    = shift || 'u';

    $pid     = 0 if $pid =~ /\D/;
    if (!$size) {
        $size = -s $file;
        return ($size, '');
    }

    my $string  = "";
	my $newsize = -s $file;
	if ($newsize < $size) {
		$string = "$file truncated!\n";
	} elsif ($newsize > $size) {
		open FH, $file || die "fail to open $file for reading: $!\n";
		seek FH, $size, 0;
		while(<FH>) {
			next if $pid && $_ !~ /\[$pid\]/;
            my $txt  = $_;
            $txt     = encode("gbk", decode("utf8", $txt)) if $t eq 'w';
            $string .= $txt . "\r\n";
		}
	}
	$size = $newsize;
    return ($size, $string);
}

sub dumpbin {
    my $self = shift;
    my $buf  = shift;
    my $len  = length $buf;
    my $out  = '';
    for (my $i=0; $i < $len; $i++) {
        $out .= ord(substr $buf, $i, 1) . " ";
    }
    return $out;
}

sub is_true {
    my $self = shift;
    my $val  = shift;

    return if !$val;
    return 1 if $val eq '1';
    return 1 if $val =~ /^on$/i;
    return 1 if $val =~ /^yes$/i;
    return 1 if $val =~ /^enabled$/i;
    return 1 if $val =~ /^\d+$/;
    return;
}
1;

