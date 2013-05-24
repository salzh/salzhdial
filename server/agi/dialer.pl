#!/usr/bin/env perl
BEGIN {
	push @INC, "/salzh/mazalcenter/lib";
}

use CCCenter::AGI;
use strict;
use warnings;
use DBI;
use LWP::Simple;
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

my $dbh = DBI->connect("DBI:mysql:database=$db;host=$host",
					  "$user", "$pass", {RaiseError => 0, AutoCommit => 1});

my $id = $agi->get_variable("DETAILID") || 1;

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
	
	if (!$result && $loop < MAXLOOP && $dialednumber) {
		$agi->set_variable('LOOP', ++$loop);
		log_debug(3, "REDIAL", "Redial $dialednumber: $loop!");
		$agi->set_context("dialer");
		$agi->set_extension($dialednumber);
		$agi->set_priority(1);
		exit 0;
	}
	
	my $sql = sprintf("update t_work_detail set SendResult='%s',SendNum=1, TimeLength=TimeLength+%d,dialtime='$dt',dialstatus='$ds' where Id='%d' and SendResult!=1",
					  $result, $answeredtime, $id);
	
	
	log_debug(3, "SQL", $sql);
	my $sth = $dbh ->prepare($sql);
	my $rv  = $sth  ->execute();
	if ($rv < 1) {
		my $sql = sprintf("update t_his_work_detail set SendResult='%s',SendNum=1, TimeLength=TimeLength+%d,dialtime='$dt',dialstatus='$ds' where Id='%d' and SendResult!=1",
					  $result, $answeredtime, $id);
		log_debug(3, "DUPSQL", $sql);
		my $sth = $dbh ->prepare($sql);
		my $rv = $sth   -> execute();
	}
	
	update_money($id, $answeredtime);
	$sql = "update t_work set overcount=overcount+1 where id='$workid'";
	log_debug(3, "SQL", $sql);
	
	$dbh->prepare($sql)->execute();

	exit 0;
} elsif ($status eq 'COMPLAIN') {
	my $id = $agi->get_variable('ID');
	my $answeredtime = $agi->get_variable('ANSWEREDTIME') || 0;

	my $sql = sprintf("update t_work_detail set TimeLength=if(TimeLength,TimeLength,0)+%d where Id=%d",
					  $answeredtime, $id);
	log_debug(3, "SQL", $sql);
	my $sth = $dbh -> prepare($sql);
	my $rv  = $sth   -> execute();
	if ($rv < 1) {
		my $sql = sprintf("update t_his_work_detail set TimeLength=if(TimeLength,TimeLength,0)+%d where Id=%d",
					  $answeredtime, $id);
		log_debug(3, "DUPSQL", $sql);
		my $sth = $dbh ->prepare($sql);
		my $rv = $sth   -> execute();
	}
	update_money($id, $answeredtime);

	exit 0;
} elsif ($status eq 'DIALERANSWER') {
	sleep 2;
	$agi->answer();
	my $sql = "select UserId,WorkId,SendNum from t_work_detail where Id='$id'";
	log_debug(5, "DEBUG", $sql);
	my $sth = $dbh->prepare($sql);
	$sth   -> execute();
	if ($sth->rows < 1) {
		my $sql = "select UserId,WorkId,SendNum from t_his_work_detail where Id='$id'";
		log_debug(5, "DUPSQL", $sql);
		
		$sth = $dbh->prepare($sql);
		$sth->execute();
	}
	my $detail = $sth->fetchrow_hashref();
	my $workid = $detail->{WorkId};
	
	$sql   = "select IfVoiceTemplate,VoiceTemplateId,VoiceType,VoiceFile,IfClick,RepeatNum,ReturnNum,ComplainNum,ComplainAgents,ReturnVoiceFile from t_work" .
			 " where Id='$detail->{WorkId}'";
	log_debug(5, "SQL", $sql);
	
	$sth = $dbh->prepare($sql);
	$sth->execute();
	if ($sth->rows < 1) {
		$sql   = "select IfVoiceTemplate,VoiceTemplateId,VoiceType,VoiceFile,IfClick,RepeatNum,ReturnNum,ComplainNum,ComplainAgents,ReturnVoiceFile from t_his_work" .
			 " where Id='$detail->{WorkId}'";
		log_debug(5, "DUPSQL", $sql);
		
		$sth = $dbh->prepare($sql);
		$sth->execute();
	}
	
	my $work_detail = $sth->fetchrow_hashref;
	if ($work_detail->{VoiceTemplateId}) {
		$sql = "select VoiceType,VoiceFile,IfClick,RepeatNum,ReturnNum,ComplainNum,ComplainAgents,ReturnVoiceFile from t_voice_template " .
				"where id='$work_detail->{VoiceTemplateId}'";
		log_debug(5, "DEBUG", $sql);
		
		$sth = $dbh->prepare($sql);
		$sth->execute();
		$work_detail = $sth->fetchrow_hashref();
	}
	
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
		$sth   = $dbh->prepare($sql);
		my $rv = $sth->execute();
		if ($rv < 1) {
			$sql = "update t_his_work_detail set KeyPress='$digit' where Id='$id'";
			log_debug(3, "DUPSQL", $sql);
			my $sth = $dbh ->prepare($sql);
			my $rv = $sth   -> execute();
		}
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
	
	#DEFINE feeRate for t_user
	my $sql = "select t_user.Id userid,feeRate from t_work_detail,t_work,t_user where t_work_detail.WorkId=t_work.Id and t_work.UserId=t_user.Id " .
			  " and t_work_detail.Id='$id'";
	log_debug(5, "DEBUG", $sql);

	my $sth	= $dbh->prepare($sql);
	$sth   -> execute();
	
	if ($sth->rows < 1) {
		my $sql = "select t_user.Id userid,feeRate from t_his_work_detail,t_his_work,t_user where t_his_work_detail.WorkId=t_his_work.Id and t_his_work.UserId=t_user.Id " .
				 " and t_his_work_detail.Id='$id'";
		log_debug(5, "DUP", $sql);
	
		my $sth	= $dbh->prepare($sql);
		$sth   -> execute();
	}
	
	my $infor = $sth->fetchrow_hashref;
	
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
	$sth   = $dbh -> prepare($sql);
	my $rv = $sth -> execute();
	if ($rv < 1) {
		$sql  = sprintf("update t_his_work_detail set Money=Money+%.2f  where Id=%d", $money, $id);
		log_debug(3, "DUPSQL", $sql);
		my $sth = $dbh ->prepare($sql);
		my $rv = $sth   -> execute();
	}
	$sql  = sprintf("update t_user set voiceMoney=voiceMoney-%.2f where Id=%d", $money, $infor->{userid});
	log_debug(3, "SQL", $sql);
	$sth  = $dbh -> prepare($sql);
	$sth -> execute() || log_debug(3, "SQLRES", "Error: $sql: " . $sth->errstr);
}

sub log_debug {
	my $level = shift || 5;
	my $type  = shift;
	my $body  = shift || return;
	if ($level > 0 && $level < 5) {
		my $sth = $dbh->prepare("insert into t_log (type,body) values (?,?)");
		$sth   -> execute($type, $body);
	}
	
	$agi->verbose("[$type] - $body", $level);
}