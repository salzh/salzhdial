package Dialer;
#AutoDial.pm - Mazal AutoDial Application
#2010-4-6
#salzh@mazal.net.cn

use Encode;
use vars qw/$AUTOLOAD/;
use constant {
    MANAGERPING	   => 30,
	AUTODIALINT	   => 60,
	PINGTIMEOUT	   => 180,
    DEFAULTTHREADS => 1,
    ISBROADCAST    => 1,
    ISAUTOFAX      => 2,
    SURVEYNO       => 0,
    SURVEYOK       => 1,
    SURVEYBUSY     => 2,
    SURVEYREFUSE   => 3,
    SURVEYNOANSWER => 4,
    SURVEYNULL     => 5,
    SURVEYSCHEDULE => 6,
    MAXCALLS       => 89,
    BRANCHCONF     => 'autodial_branch.conf',
	MAXSPOOLSIZE   => 1000
};
use strict;
use warnings;
use File::Copy;
use Time::HiRes qw/usleep time/;
use LWP::Simple;

my %jobLockSpool    = ();
my $AutoDialOffset  = 0;
my $LastDialPhone   = '';
my @NoRedialStatus  = (28);
$main::dispatch_conf{FILEPATH}  ||= '/opt/lampp/htdocs';
$main::dispatch_conf{CENTERETC} ||= '/etc';
$main::dispatch_conf{AREACODE8} ||= '0852 0853 010 020 021 022 023 024 025 027 028 029 ' .
                                    '0311 0371 0377 0379 0411 0431 0451 0510 0511 0512 ' .
                                    '0513 0514 0515 0516 0517 0518 0519 0523 0527 0531 ' .
                                    '0532 0571 0573 0574 0575 0576 0577 0579 0591 0595 ' .
                                    '0731 0754 0755 0757 0760 0769 0898';

my $manager       = '';
my $manager2	  = ''; #manager2 is for get real current calls
my $firststart    = 1;
my %VSTATUS = (
    
);

#table jobLock and jobLockSpool
=pod
create table jobLock (
    id int not null auto_increment,
    jobid int not null,
    lock_time,
    primary key id
)

create table jobLockSpool (
    id int not null auto_increment,
    jobid int not null,
    
)
=cut

sub CHECK_TIMER {
    my $c   = shift;
	return unless defined $main::dispatch_privillege && $main::dispatch_privillege eq 'queue_admin_daemon';
	if ($firststart) {
		#do_push_spool($c);
		$firststart = 0;
		#return;
	} else {
		
	}
	
    my $dbh = $c->dbh_dialer;
    ###check pending job
    if (!$manager || !$manager->connect) {
		$manager = new CCCenter::Manager;
		$manager->user('dispatch');
		$manager->secret('dispatch123');	
		$manager->host('127.0.0.1');
		
		if (!$manager->connect) {
			log_debug(2, "Could not connect to " . $manager->host . "!\n");
			return;
		}
	}
	my $limit	  = 3*$main::dispatch_conf{MAXCALLS} || 60;
    my $sql       = "select t_work_detail.id id,telno,t_work_detail.userid,username,voicemoney,workid,workstate,sendtimetype,fixedtime,ifendtime,endtime,voicefile,VoiceTemplateId,workcount,feerate
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

	$sth_count->execute();
	my $row    = $sth_count->fetchrow_hashref;
	my $total  = $row->{total} || 0;
	my %t_work = ();
	my %t_ivrargs = ();
	my $start  = time();
	my $j	   = 1;
	while (1) {
		if ($j >= $total || time() - $start >= AUTODIALINT) {
			$start   = time();
			$sth_count->execute();
			my $row    = $sth_count->fetchrow_hashref;
			$total  = $row->{total};
			$j		= 1;
			warn "update total: $total";
			
			my $cnt  = getcurrentcalls();
			#my $Aid  = getid();
			#my $l = $manager->sendcommand(Action => 'Command', Command => 'core show channels', ActionID => $Aid, 1);
			#$cnt = $l =~ s{SIP/OUT1\-}{}g;
			my $ccalls = $cnt;
			
			$dbh->prepare("update t_livetable set livecalls=?,maxcalls=? where host=?")
				->execute($ccalls, $main::dispatch_conf{MAXCALLS}, $main::dispatch_conf{BRANCHNAME});
			if (!$total) {
				warn "total becomes 0, update t_work.workstate=3 - " . join(",", keys %t_work);
				$dbh->prepare("update t_livetable set livecalls=?,maxcalls=? where host=?")
					->execute(0, 0, 'sending');

				my $sth1 = $dbh->prepare("update t_work set workstate=3 where id=? and workstate=1");
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
		
		$sth   -> execute();
		
		my @spool = ();
		while (my $row = $sth->fetchrow_hashref()) {
			push @spool, $row;
			$sth_update->execute($row->{workid}) if $row->{workstate} == 0;			
			
			$t_work{$row->{workid}} = 1  unless $t_work{$row->{workid}};
		}
				
		if (@spool) {
			for my $row (@spool) {
				my $Aid          = getid();
				my $dialednumber = $row->{telno};
				my $workid 		 = $row->{workid};
				my $userid		 = $row->{userid};
				my $username 	 = $row->{username};
				my $feerate		 = $row->{feerate} || '0.03,6';
				
				my $callerid     = get_callerid($workid, $userid, $dialednumber);
				log_debug(3, $j++ . "/$total ($workid|$username) Dial $dialednumber");
			
				$dbh->prepare("update t_livetable set livecalls=?,maxcalls=? where host=?")
					->execute($j, $total, 'sending');
				
				for (1..60) {				
					my $cnt  = getcurrentcalls();
					#my $Aid  = getid();
					#my $l = $manager->sendcommand(Action => 'Command', Command => 'core show channels', ActionID => $Aid, 1);
					#$cnt = $l =~ s{SIP/OUT1\-}{}g;
					my $ccalls = $cnt;
					
					warn "ccall: $ccalls";
					if ( $ccalls >= $main::dispatch_conf{MAXCALLS} ) {
						warn "current calls=$ccalls is more than " . $main::dispatch_conf{MAXCALLS}  . " sleep!!!";
						sleep 5;
					} else {
						last;			
					}
				}
				
				if (!$t_ivrargs{$workid}) {
					$sql   = "select IfVoiceTemplate,VoiceTemplateId,VoiceType,VoiceFile,IfClick,RepeatNum,ReturnNum,ComplainNum,ComplainAgents,ReturnVoiceFile from t_work" .
							" where Id='$workid'";
							
					warn $sql;
					$sth = $dbh->prepare($sql);
					$sth->execute();
					
					my $work_detail = $sth->fetchrow_hashref;
					if ($work_detail->{VoiceTemplateId}) {
						$sql = "select VoiceType,VoiceFile,IfClick,RepeatNum,ReturnNum,ComplainNum,ComplainAgents,ReturnVoiceFile from t_voice_template " .
								"where id='$work_detail->{VoiceTemplateId}'";
						log_debug(5, "DEBUG", $sql);
						
						$sth = $dbh->prepare($sql);
						$sth->execute();
						$work_detail = $sth->fetchrow_hashref();
					}
					
					my $prompt = $work_detail->{VoiceFile};
					if ($prompt && !-e "/var/lib/cccenter/sounds/$prompt") {
						my $MAINIP = $main::dispatch_conf{MAINIP} || '61.152.175.238';
						warn "$prompt not exists, download it\n";
						warn "download: http://$MAINIP/evoice/$prompt";

						getstore("http://$MAINIP/evoice/$prompt", "/var/lib/cccenter/sounds/$prompt");
					}
					
					$prompt = $work_detail->{ReturnVoiceFile};
					if ($prompt && !-e "/var/lib/cccenter/sounds/$prompt") {
						my $MAINIP = $main::dispatch_conf{MAINIP} || '61.152.175.238';
						warn "$prompt not exists, download it\n";
						warn "download: http://$MAINIP/evoice/$prompt";
						getstore("http://$MAINIP/evoice/$prompt", "/var/lib/cccenter/sounds/$prompt");
					}
					
					$t_ivrargs{$workid}=$work_detail->{IfClick} . "," .
										$work_detail->{RepeatNum} . "," .
										$work_detail->{ComplainNum} . "," .
										$work_detail->{ReturnNum} . "," .
										$work_detail->{VoiceFile} . "," .
										$work_detail->{ReturnVoiceFile} . "," .
										$work_detail->{ComplainAgents};
				}
				
				$sth_update_detail->execute($row->{id});
				$manager -> sendcommand(Action => 'Originate', Channel => _channel($dialednumber, 'l'),
						  Async => 1, ActionID => $Aid, Timeout => 500000,
						  Variable => "DETAILID=$row->{id}|DIALEDNUMBER=$dialednumber|feerate=$feerate|userid=$userid|workid=$workid|ivragrs=$t_ivrargs{$workid}",
						  Context => 'dialer', CallerID => "$callerid <$callerid>",
						  Exten	 => 'handler', Priority => 1, 1);
				usleep 100;
			}
		} else {
			$total = 0; $j = 1;
		}
		
		usleep 100;
	} 
	
}


sub do_push_spool {
	my $c	= shift;
	if ($main::dispatch_conf{BRANCHNAME} && $main::dispatch_conf{BRANCHNAME} eq 'MAIN') {
		return;
	}
	
	my $dbh = $c->dbh_dialer_force();

	my $sql = "select t_user.id userid,t_work.id workid,AddressGroupid,VoiceTemplateId,username,voiceMoney," .
			  "SendTimeType,FixedTime,IfEndTime,EndTime,VoiceFile,workcount " .
              "from t_work left join t_user on t_work.Userid=t_user.Id where WorkState IN (0) and assignedbranch='$main::dispatch_conf{BRANCHNAME}'";
    
	warn $sql;
    my $sth = $dbh->prepare($sql);
    $sth   -> execute();
    
	warn "rows: " . $sth->rows();
    while (my $row = $sth->fetchrow_hashref) {
        unless ($row->{userid} && $row->{workid} && $row->{username}) {
            warn "t_user.id or t_work.id or username is null: IGNORE!";
            next;
        }
        

        my $workid = $row->{workid};
        my $userid = $row->{userid};
        my $username = $row->{username};
        
        if ($jobLockSpool{$workid}) {
            next;
        }

        warn "check jobid=$workid for $username";
        
       
        unless ($row->{voiceMoney}) {
            warn "VoiceMony is less than 0: IGNORE!";
            next;
        }
        
        unless ($row->{VoiceFile} || $row->{VoiceTemplateId}) {
            warn "VoiceFile /  VoiceTemplateId is null: IGNORE!";
            next;
        }
 
        my $fixedtime = $row->{FixedTime} || '';
       
        if ($row->{SendTimeType} == 2) {
            my $now = $c->center_time2str();
            if ($fixedtime gt $now) {
                warn "$fixedtime doenst arrive: IGNORE!";
                next;
            }
        }
        
        if ($row->{IfEndTime} == 2) {
            my $now = $c->center_time2str();
			my $endtime = $row->{EndTime};
            if ($endtime && $endtime ne '0000-00-00 00:00:00' && $endtime lt $now) {
                warn "$endtime arrive: IGNORE!";
                next;
            }
        }
		
		my $workcount = $row->{workcount};
        $dbh->prepare("update t_work set WorkState=1,SendTime=CURRENT_TIMESTAMP where Id='$workid'")->execute();
        
        my $f = fork();
        if ($f == 0) {
			my $start = time;
			my $loop  = 360;
			for (1..$loop) {
				my $sth    = $dbh->prepare("select  WorkCount count from t_work where id='$workid'");	
				$sth      -> execute();
				my $count  = $sth->fetchrow_hashref;
				
				if (!$count->{count} && $start -time() < $loop*10) {
					warn "$count->{count} is 0, sleep 10s";
					sleep 10;
					next;
				} else {
					start_job($c, $workid, $userid, $username, $row->{AddressGroupid},
				      $row->{VoiceMoney}, $row->{VoiceFile}, $row->{SendTimeType}, $fixedtime, $row->{EndTime});
					last;
				}
			}
			exit 0;
        }        
    }
    
    sleep AUTODIALINT;    
}

sub CHECK_TIMER2 {
    my $c   = shift;
	return unless defined $main::dispatch_privillege && $main::dispatch_privillege eq 'queue_admin_daemon';
    my $dbh = $c->dbh_dialer;
    ###check pending job
    $manager = new CCCenter::Manager;
	$manager->user('dispatch');
	$manager->secret('dispatch123');	
	$manager->host('127.0.0.1');
	
	if ($firststart) {
		$dbh->prepare("update t_work Set WorkState=0 where WorkState=1")->execute();
		$firststart = 0;
	}
	
    my $sql = "select t_user.id userid,t_work.id workid,AddressGroupid,VoiceTemplateId,username,voiceMoney," .
			  "SendTimeType,FixedTime,IfEndTime,EndTime,VoiceFile,workcount " .
              "from t_work left join t_user on t_work.Userid=t_user.Id where WorkState IN (0) and assignedbranch='$main::dispatch_conf{BRANCHNAME}'";
    
	warn $sql;
    my $sth = $dbh->prepare($sql);
    $sth   -> execute();
    
	warn "rows: " . $sth->rows();
    while (my $row = $sth->fetchrow_hashref) {
        unless ($row->{userid} && $row->{workid} && $row->{username}) {
            warn "t_user.id or t_work.id or username is null: IGNORE!";
            next;
        }
        

        my $workid = $row->{workid};
        my $userid = $row->{userid};
        my $username = $row->{username};
        
        if ($jobLockSpool{$workid}) {
            next;
        }

        warn "check jobid=$workid for $username";
        
       
        unless ($row->{voiceMoney}) {
            warn "VoiceMony is less than 0: IGNORE!";
            next;
        }
        
        unless ($row->{VoiceFile} || $row->{VoiceTemplateId}) {
            warn "VoiceFile /  VoiceTemplateId is null: IGNORE!";
            next;
        }
 
        my $fixedtime = $row->{FixedTime} || '';
       
        if ($row->{SendTimeType} == 2) {
            my $now = $c->center_time2str();
            if ($fixedtime gt $now) {
                warn "$fixedtime doenst arrive: IGNORE!";
                next;
            }
        }
        
        if ($row->{IfEndTime} == 2) {
            my $now = $c->center_time2str();
			my $endtime = $row->{EndTime};
            if ($endtime && $endtime ne '0000-00-00 00:00:00' && $endtime lt $now) {
                warn "$endtime arrive: IGNORE!";
                next;
            }
        }
		
		my $workcount = $row->{workcount};
        $dbh->prepare("update t_work set WorkState=1,SendTime=CURRENT_TIMESTAMP where Id='$workid'")->execute();
        
        my $f = fork();
        if ($f == 0) {
			my $start = time;
			my $loop  = 360;
			for (1..$loop) {
				my $sth    = $dbh->prepare("select  WorkCount count from t_work where id='$workid'");	
				$sth      -> execute();
				my $count  = $sth->fetchrow_hashref;
				
				if (!$count->{count} && $start -time() < $loop*10) {
					warn "$count->{count} is 0, sleep 10s";
					sleep 10;
					next;
				} else {
					start_job($c, $workid, $userid, $username, $row->{AddressGroupid},
				      $row->{VoiceMoney}, $row->{VoiceFile}, $row->{SendTimeType}, $fixedtime, $row->{EndTime});
					last;
				}
			}
			exit 0;
        }        
    }
    
    sleep AUTODIALINT;    
}

sub start_job {
    my ($c, $workid, $userid,$username,$addressgroupid,$voicemoney,$voicefile,$sendtimetype,$fixedtime,$endtime) = @_;    
	my $dbh = $c->dbh_dialer_force();

	
    log_debug(4, "jobid=$workid for $username start!!");
    my $sql = "select id,userid,telno,sendnum from t_work_detail where workid = '$workid' and (sendTime='' or sendTime IS NULL or sendtime='0000-00-00 00:00:00')";
	warn $sql;
    my $sth = $dbh->prepare($sql);
    $sth   -> execute();
    my @spool = ();
	my $i     = 0;
    while (my $row = $sth->fetchrow_hashref) {
        push @spool, $row; $i++;
    }
    
	
	log_debug(4, "total=$i will be dialed!!!");
    my $start    = time;
	my $endstate = '3';
	my $j		 = 1;
	my $pause	 = 0;
	my $sql_spool_size     = "select count(*) size from t_work_detail_spool";
	my $sth_spool_size	   = $dbh->prepare($sql_spool_size);
	
	my $sql_insert_spool   = "insert into t_work_detail_spool (userid,workid,telno) values (?,?,?)";
	warn $sql_insert_spool; 
	my $sth_insert_spool = $dbh->prepare($sql_insert_spool);
	
	my $sql_get_spool	  = "select * from t_work_detail where workid=?";
	my $sth_get_spool	  = $dbh->prepare($sql_get_spool);
	
	my $gospoolcount  = 0;
	my %control_infor = (gospoolindex => 0);
	
	if ($control_infor{gospoolindex} >= $#spool) {
		$control_infor{gospoolover}   = 1;
	}
	
	if (!$control_infor{gospoolover} && time() - $control_infor{gospooltime} >= AUTODIALINT) {
		$control_infor{gospooltime} = time();
		my $row  = $sth_spool_size->execute();
		my $currentsize = $row->{size} || 0;
		my $gospoolsize = MAXSPOOLSIZE - $currentsize - 1;
		
		$sth_insert_spool->bind_param_array(1, $userid);
		$sth_insert_spool->bind_param_array(1, $workid);
		$sth_insert_spool->bind_param_array(1, map($_->{telno}, @spool[$control_infor{gospoolindex} .. $control_infor{gospoolindex}+$gospoolsize]));

	}
	
	if (!$control_infor{getspoolover} && time() - $control_infor{getspooltime} >= AUTODIALINT) {
		$control_infor{getspooltime} = time();
		
		
		my $row  = $sth_spool_size->execute();
		my $currentsize = $row->{size} || 0;
		my $gospoolsize = MAXSPOOLSIZE - $currentsize - 1;
		
		$sth_insert_spool->bind_param_array(1, $userid);
		$sth_insert_spool->bind_param_array(1, $workid);
		$sth_insert_spool->bind_param_array(1, map($_->{telno}, @spool[$control_infor{gospoolindex} .. $control_infor{gospoolindex}+$gospoolsize]));

	}

    for my $row (@spool) {
		if (!$pause) {			
			if ($j < $gospoolcount) {
				$sth_insert_spool->execute($row->{userid}, $row->{workid}, $row->{telno})
			}
		}
		
		if (time() - $start >= AUTODIALINT) {
			$start = time();
			my $sql = "select WorkState,voiceMoney,unix_timestamp(EndTime) endtime from t_work left join t_user on t_work.Userid=t_user.Id where t_work.Id='$workid'";
			$sth = $dbh->prepare($sql);
			$sth->execute();
			my $work=$sth->fetchrow_hashref();
			if ($sth->rows < 1) {
				log_debug(4, "jobid=$workid for $username stop - no phone!!");
				last;
			} elsif	($work->{WorkState} == 4) {
				log_debug(4, "jobid=$workid for $username stop - state is 4!!");
				$pause = 1;
				redo;
			} elsif ($work->{WorkState} == 1) {
				$pause = 0;
			} elsif ($work->{WorkState} != 1) {
				log_debug(4, "jobid=$workid for $username stop - state is not 1!!");				
				last;
			} elsif	($endtime && $endtime < time) {
				log_debug(4, "jobid=$workid for $username stop - endtime reached: $endtime;!!");
				last;
			} elsif ($work->{voiceMoney} < 2) {
				log_debug(4, "jobid=$workid for $username stop - no credit: $work->{voiceMoney}!!");
				$endstate = 0;
				last;
			}
		}
			
		usleep 1000;
    }
    
	if ($endstate) {
		$dbh->prepare("update t_work set WorkState='$endstate' where Id='$workid' and WorkState=1")->execute();
	}
	
	$manager->disconnect;
}

sub getcurrentcalls {
	my $cnt  = 0;
	my $host = '127.0.0.1';
	$manager2 = $manager;
	if ($main::dispatch_conf{BRANCHNAME} eq 'zhonglian250			') {
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
	my $l = $manager2->sendcommand(Action => 'Command', Command => 'core show channels', ActionID => $Aid, 1);
	if ($main::dispatch_conf{BRANCHNAME} eq 'zhonglian250') {
		$cnt = $l =~ s{SS7/siuc/}{}g;
	} else {
		$cnt = $l =~ s{SIP/OUT1\-}{}g
	}
	return $cnt || 0;
}

sub get_callerid {
    return "02166778899";
}

sub _channel {
    my $number = shift;
    return "local/$number\@dialer/n";    
}

sub AUTOLOAD {
    my $name = $AUTOLOAD;
    $name   =~ s/.*://;
    no strict "refs";
    my $func = "main::" . $name;
    if (defined &$func) {
        &$func(@_);
    } else {
        die "undefined $AUTOLOAD\n";
    }
}

1;

=pod
sub check_autodial {
    my $c   = shift;
    my $now = time;

    my $w   = $AutoDialSpool{which} || 0;
	$AutoDialSpool{last_check_time} = $now;

	my $ccalls  = get_current_calls() || 0;
    my $avagents= get_avail_agents($c, $main::dispatch_username);
    warn "ccalls=$ccalls, agent=$avagents";
    return if $ccalls  >= $AutoDialSpool{number} || $avagents < 1;

    if ($w == 1 || $w == 2) {
         if ($AutoDialOffset >= $AutoDialSpool{total}) {
            send_reg_socket(4, "<event>AutoDialStatus</event><status>1</status>");
            %AutoDialSpool         = ();
            $AutoDialOffset		   = 0;
            return 1;
        }

        my $cid  = $AutoDialSpool{spool}->[$AutoDialOffset];
        my $dbh  = $c->dbh();
        my $sth  = $dbh->prepare("select telephone,mobile from clients where id=?");
        $sth    -> execute($cid);
        my $row  = $sth->fetchrow_hashref();
        my $phone= ($w == 2 ?  $row->{mobile} : $row->{telephone});
        my $qid  = $main::dispatch_username;
        my $dialednumber = get_dialednumber($row->{telephone}, $row->{mobile}, $w == 2);

        if ($phone && check_dialed($c, $cid, $phone, $w)) {
            do_locked($c, $cid, $phone);
            my $Aid    = getid();
            log_debug(3, "Dial $dialednumber - $phone");
            sendcommand({Action => 'Originate', Channel => _channel($dialednumber, 'l'),
                      Async => 1, ActionID => $Aid, Timeout => 300000,
                      Variable => "QID=$qid|CID=$phone",
                      Context => 'mycustom-dispatch', CallerID => "$qid <$qid>",
                      Exten	 => 'dqueue', Priority => 1}, 1);
            #sendcommand({Action => 'Originate', Channel => _channel($dialednumber, 'l'),
            #         Async => 1, ActionID => $Aid, Timeout => 300000,
            #          Variable => "QID=$qid|CID=$phone",
            #          Context => 'mycustom-ivr', CallerID => "$qid <$qid>",
            #          Exten	 => 's', Priority => 1}, 1);
            $LastDialPhone  = $phone;
        } else {
            log_debug(4, "cid=$cid, phone=$phone check dialed fail: IGNORE!!!");
        }
    } else {
        if ($AutoDialOffset >= 2 * $AutoDialSpool{total}) {
            send_reg_socket(4, "<event>AutoDialStatus</event><status>1</status>");
            %AutoDialSpool         = ();
            $AutoDialOffset		   = 0;
            return 1;
        }

        my $isn  = $AutoDialOffset >= $AutoDialSpool{total} ? 1 : 0;
        my $i    = ($isn ? $AutoDialOffset - $AutoDialSpool{total} : $AutoDialOffset);
        my $cid  = $AutoDialSpool{spool}->[$i];
        my $dbh  = $c->dbh();
        my $sth  = $dbh->prepare("select telephone,mobile from clients where id=?");
        $sth    -> execute($cid);
        my $row  = $sth->fetchrow_hashref();
        my $phone= ($isn ? $row->{mobile} : $row->{telephone});
        my $qid  = $main::dispatch_username;
        my $dialednumber = get_dialednumber($row->{telephone}, $row->{mobile}, $isn);

        if ($phone && check_dialed($c, $cid, $phone, $i == $AutoDialOffset ? 1 : 2)) {
            do_locked($c, $cid, $phone);
            my $Aid    = getid();
            log_debug(3, "Dial $dialednumber - $phone");
            sendcommand({Action => 'Originate', Channel => _channel($dialednumber, 'l'),
                      Async => 1, ActionID => $Aid, Timeout => 300000,
                      Variable => "QID=$qid|CID=$phone",
                      Context => 'mycustom-dispatch', CallerID => "$qid <$qid>",
                      Exten	 => 'dqueue', Priority => 1}, 1);
            $LastDialPhone  = $phone;
        } else {
            log_debug(4, "cid=$cid, phone=$phone check dialed fail: IGNORE!!!");
        }
    }

    $AutoDialOffset++;
}

sub check_broadcast {
    my $c   = shift;
    my $now = time;

    my $w   = $AutoDialSpool{which} || 0;
	$AutoDialSpool{last_check_time} = $now;

	my $ccalls  = get_current_calls() || 0;
    #my $avagents= get_avail_agents($c, $main::dispatch_username);
    warn "ccalls=$ccalls";
    return if $ccalls  >= $AutoDialSpool{number};

    if ($w == 1 || $w == 2) {
         if ($AutoDialOffset >= $AutoDialSpool{total}) {
            send_reg_socket(4, "<event>BroadcastStatus</event><status>1</status>");
            %AutoDialSpool         = ();
            $AutoDialOffset		   = 0;
            return 1;
        }

        my $cid  = $AutoDialSpool{spool}->[$AutoDialOffset];
        my $dbh  = $c->dbh();
        my $sth  = $dbh->prepare("select telephone,mobile from clients where id=?");
        $sth    -> execute($cid);
        my $row  = $sth->fetchrow_hashref();
        my $phone= ($w == 2 ?  $row->{mobile} : $row->{telephone});
        my $qid  = $main::dispatch_username;
        my $dialednumber = get_dialednumber($row->{telephone}, $row->{mobile}, $w == 2);

        if ($phone && check_dialed($c, $cid, $phone, $w)) {
            do_locked($c, $cid, $phone);
            my $Aid    = getid();
            log_debug(3, "Dial $dialednumber - $phone");
            sendcommand({Action => 'Originate', Channel => _channel($dialednumber, 'l'),
                         Context => 'mycustom-dispatch', Exten => 'audio',
                         Variable => "AUDIOID=$AutoDialSpool{audioid}",
                         CallerID => "$qid <$qid>", Async => 1, ActionID => $Aid, Timeout => 300000}, 1);

            $LastDialPhone  = $phone;
        } else {
            log_debug(4, "cid=$cid, phone=$phone check dialed fail: IGNORE!!!");
        }
    } else {
        if ($AutoDialOffset >= 2 * $AutoDialSpool{total}) {
            send_reg_socket(4, "<event>AutoDialStatus</event><status>1</status>");
            %AutoDialSpool         = ();
            $AutoDialOffset		   = 0;
            return 1;
        }

        my $isn  = $AutoDialOffset >= $AutoDialSpool{total} ? 1 : 0;
        my $i    = ($isn ? $AutoDialOffset - $AutoDialSpool{total} : $AutoDialOffset);
        my $cid  = $AutoDialSpool{spool}->[$i];
        my $dbh  = $c->dbh();
        my $sth  = $dbh->prepare("select telephone,mobile from clients where id=?");
        $sth    -> execute($cid);
        my $row  = $sth->fetchrow_hashref();
        my $phone= ($isn ? $row->{mobile} : $row->{telephone});
        my $qid  = $main::dispatch_username;
        my $dialednumber = get_dialednumber($row->{telephone}, $row->{mobile}, $isn);

        if ($phone && check_dialed($c, $cid, $phone, $i == $AutoDialOffset ? 1 : 2)) {
            do_locked($c, $cid, $phone);
            my $Aid    = getid();
            log_debug(3, "Dial $dialednumber - $phone");
            sendcommand({Action => 'Originate', Channel => _channel($dialednumber, 'l'),
                         Context => 'mycustom-dispatch', Exten => 'audio',
                         Variable => "AUDIOID=$AutoDialSpool{audioid}",
                         CallerID => "$qid <$qid>", Async => 1, ActionID => $Aid, Timeout => 300000}, 1);
            $LastDialPhone  = $phone;
        } else {
            log_debug(4, "cid=$cid, phone=$phone check dialed fail: IGNORE!!!");
        }
    }

    $AutoDialOffset++;
}
=cut
sub do_login {
    my $c         = shift;
	my $buffer    = shift || return {status => 0, message => 'buffer is null'};

	my $username  = getvalue('username', $buffer);
	my $callerid  = getvalue('callerid', $buffer) || $username;
	my $privilege = getvalue('privilege', $buffer) || 'action';
    my $infor;

	if (!$username) {
		return {status => 0, message => 'username is null'}
	}

	my $password = getvalue('password', $buffer) || '';
	return {status => 0, message => 'username/password is null'} if !$username || !$password;
    my $conf     =
    my $dbh		 = $c->dbh;

	if ($privilege eq 'queue_admin' || $privilege eq 'queue_admin_daemon') {
        my $sth = $dbh->prepare("select * from queues_config where extension=?");
        $sth   -> execute($username);
        return {status => 0, message => "Authen Fail"} if $sth->rows != 1;

		$sth	= $dbh->prepare("select * from dispatch_status where status='1' and privilege='queue_admin'" .
								" and username=?");
		$sth   -> execute($username);
		return {status => 0, message => "QueueAdmin already Logged in"} if $sth->rows > 0;

        $sth    = $dbh->prepare("select * from center_secret where username=? and privilege=? limit 1");
        $sth   -> execute($username, $privilege);
        if ($sth->rows < 1) {
            log_debug(3, "warning: qid=$username not in center_secret!!!");
            $dbh->prepare("insert into center_secret (username,secret,privilege) values (?, ?, ?)")
                ->execute($username, $username, 'queue_admin');
        } else {
            my $row = $sth->fetchrow_hashref();
            return {status => 0, message => 'Authen Fail'} if $row->{secret} && $row->{secret} ne $password;
        }

        $infor->{id} = $username;
    } elsif ($privilege eq 'action') {
        my $sth = $dbh->prepare("select * from sip where id=? and keyword='secret' and data=?");
        $sth   -> execute($username, $password);
        return {status => 0, message => 'Authen Fail'} if $sth->rows != 1;
    } else{
        my $sth = $dbh->prepare("select * from center_secret where username=? and secret=? and privilege=?");
        $sth   -> execute($username, $password, $privilege);
        return {status => 0, message => 'Authen Fail'} if $sth->rows < 1;
    }

    return {status => 1};
}
