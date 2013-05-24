#!/usr/bin/env perl
BEGIN {
	push @INC, "/salzh/mazalcenter/lib";
}

use CCCenter::AGI;
use strict;
use warnings;
use DBI;
use LWP::Simple;
use IO::Socket::UNIX;

use constant {
	MAXLOOP => 1
};

my $MAINIP  = '61.152.175.238';
my $agi		= new CCCenter::AGI;

my %input   = $agi->ReadParse();
my $status  = shift;
my $start	= time;

my $db   = 'evoice';
my $host = '127.0.0.1';
my $user = 'cccenteruser';
my $pass = 'amp109';
my $price = 0.1;

my $path = '/tmp/dialer.sock';
my $sock = new IO::Socket::UNIX->new (
                        Peer    => $path,
                        Type    => SOCK_STREAM,
                        Timeout => 5
  	);

if (!$sock) {
    $agi->verbose(1, "Fail to create server socket at path=$path: $!");
	exit 0;
}
$sock->autoflush(1);


my $id = $agi->get_variable("DETAILID") || 1;
my $userid = $agi->get_variable("userid");
my $workid = $agi->get_variable("workid");
my $feerate = $agi->get_variable("feerate");

if ($status eq 'DIALERHANGUP') {
	my $channel = $input{channel};
	log_debug(5, "channel: $channel");
	if ($channel =~ /Local.+,1/) {
		log_debug(5, "DEBUG", "exit for channel=$channel");
		exit 0;
	}
	my $answeredtime = $agi->get_variable('ANSWEREDTIME') || 0;
	my $ds			 = $agi->get_variable('DIALSTATUS');
	my $dt			 = $agi->get_variable('DIALEDTIME');
	my $loop		 = $agi->get_variable('LOOP') || 1;
	my $workid		 = $agi->get_variable('workid');
	
	my $dialednumber = $agi->get_variable('DIALEDNUMBER');
	my $result 		 =  $ds eq 'ANSWER' ? 1 : 0;

	
	my $sql = sprintf("update t_work_detail set SendResult='%s',SendNum=1, TimeLength=TimeLength+%d,dialtime='$dt',dialstatus='$ds' where Id='%d' and SendResult!=1",
					  $result, $answeredtime, $id);
	
	
	log_debug(3, "SQL", $sql);
	
	async_execute_sql($sql);
	
	
	
	update_money($id, $answeredtime);
	
	$sql = "update t_work set overcount=overcount+1 where id='$workid'";
	log_debug(3, "SQL", $sql);
	
	#$dbh->prepare($sql)->execute();
	
	async_execute_sql($sql);
	exit 0;
} elsif ($status eq 'COMPLAIN') {
	my $id = $agi->get_variable('ID');
	my $answeredtime = $agi->get_variable('ANSWEREDTIME') || 0;

	my $sql = sprintf("update t_work_detail set TimeLength=if(TimeLength,TimeLength,0)+%d where Id=%d",
					  $answeredtime, $id);
	log_debug(3, "SQL", $sql);
	
	
	update_money($id, $answeredtime);

	exit 0;
} elsif ($status eq 'DIALERANSWER') {
	sleep 2;
	$agi->answer();
	my $sql = '';
	my $work_detail = {};
	$agi->verbose(4, $agi->get_variable('ivragrs'));
	my @ivrargs = split ',', $agi->get_variable('ivragrs'), 7;
	$work_detail = {IfClick   => $ivrargs[0],
					RepeatNum => $ivrargs[1],
					ComplainNum => $ivrargs[2],
					VoiceFile   => $ivrargs[3],
					ReturnNum   => $ivrargs[4],
					ReturnVoiceFile => $ivrargs[5],
					ComplainAgents     => $ivrargs[6]
				   };
	my $flags = "";
	if ($work_detail->{IfClick}) {
		$flags = $work_detail->{RepeatNum} . $work_detail->{ComplainNum} . $work_detail->{ReturnNum} ;
	}
	
	PLAY:
	my $digit = asterisk_collect_digit ($work_detail->{VoiceFile}, $flags);
	log_debug(5, "DEBUG", "client press key: $digit");
	if (defined $digit) {
		$sql = "update t_work_detail set KeyPress='$digit' where Id='$id'";
		log_debug(3, "SQL", $sql);
		#$sth   = $dbh->prepare($sql);
		async_execute_sql($sql);
	}
	
	if (defined $work_detail->{RepeatNum} && $work_detail->{RepeatNum} eq $digit) {
		goto PLAY;
	}
	
	if (defined $work_detail->{ReturnNum} && $work_detail->{ReturnNum} eq $digit) {
		asterisk_collect_digit($work_detail->{ReturnVoiceFile});
	}
	
	
	
	if (defined $work_detail->{ComplainNum} && $work_detail->{ComplainNum} eq $digit) {
		my $agents = $work_detail->{ComplainAgents};
		my $dialstr = '';
		for (split ',', $agents) {
			next unless $_;
			$dialstr .= '&' if $dialstr;
			$dialstr .= "SIP/OUT1/$_";
		}
		asterisk_collect_digit("tranfer");	
		$agi->set_variable("DIALSTR", $dialstr);
		$agi->set_variable("ID", $id);
		$agi->set_context("dialer-complain");
		$agi->set_extension('DIAL');
		$agi->set_priority(1);
		exit 0;
	}
	
} else {
	log_debug(5, "DEBUG", "No Define Status");
	exit 0;
}

sub asterisk_collect_digit {
        my ($prompt,$flags)=@_;
		my $nowait = 1 if !$flags;
        my ($digit,$digit_code,$tmp);
		log_debug(5, "DEBUG", "play $prompt");
		$prompt =~ s/^(.+)\.(\w+)$/$1/;
		log_debug(5, "DEBUG", "play: $prompt");
		$flags ||= '#';
        if ($prompt ne "") {
			if (!-e "/var/lib/cccenter/sounds/$prompt.wav") {
				getstore("http://$MAINIP/evoice/$prompt.wav", "/var/lib/cccenter/sounds/$prompt.wav");
			}
			
			my $digit_code = $agi->stream_file($prompt, $flags);
			
			if (!$nowait && $flags && $digit_code eq 0) {
                $digit_code = $agi->wait_for_digit('25000');
            }
			$digit = chr($digit_code);
        }
      
        return $digit;
}

sub update_money {
	my $id = shift;
	my $duration = shift || return;
	
	my $sql = '';
	my $infor = {feerate => $feerate, userid => $userid};
	
	my $feerate = $infor->{feeRate} || '0.03,6';
	my ($fee, $step) = split /,/, $feerate;
	$fee  = 0.03 unless $fee;
	$step = 6 unless $step;
	my $ms  = int $duration / $step;
	$ms	   += 1 if $duration % $step;	
	my $money = $ms * $fee;
	log_debug(5, "DEBUG", "$duration|$step|$fee|$money");

	$sql  = sprintf("update t_work_detail set Money=Money+%.2f  where Id=%d", $money, $id);
	log_debug(3, "SQL", $sql);
	
	async_execute_sql($sql);
	
	$sql  = sprintf("update t_user set voiceMoney=voiceMoney-%.2f where Id=%d", $money, $infor->{userid});
	log_debug(3, "SQL", $sql);
	
	async_execute_sql($sql);
}

sub log_debug {
	my $level = shift || 5;
	my $type  = shift;
	my $body  = shift || return;
	if ($level > 0 && $level < 5) {
		#my $sth = $dbh->prepare("insert into t_log (type,body) values (?,?)");
		#$sth   -> execute($type, $body);
	}
	
	$agi->verbose("[$type] - $body", $level);
}

sub async_execute_sql {
	my $sql  = shift || return;
	log_debug(5, 'SAVESQL', $sql);
	
	syswrite($sock, "$sql;");
}