#!/usr/bin/env perl
BEGIN {
	push @INC, "/salzh/mazalcenter/lib";
}

use CCCenter::AGI;
use strict;
use warnings;
use DBI;
use LWP::Simple;
my $MAINIP  = '61.152.175.238';
my $agi		= new CCCenter::AGI;

my %input   = $agi->ReadParse();
my $status  = shift;

for (1..15) {
my $digit = asterisk_collect_digit ('uploadimg/1358846147', '2');
$agi->say_digits($digit);
}

sub asterisk_collect_digit {
        my ($prompt,$flags)=@_;
		my $nowait   = 1 if !$flags;
		my $tmpflags = $flags ? '0123456789*#' : '#'; 
        my ($digit,$digit_code,$tmp);
		#log_debug(5, "DEBUG", "play $prompt");
		$prompt =~ s/^(.+)\.(\w+)$/$1/;
		#log_debug(5, "DEBUG", "play: $prompt");
		#$flags ||= '#';
        if ($prompt ne "") {
			if (!-e "/var/lib/cccenter/sounds/$prompt.wav") {
				getstore("http://$MAINIP/evoice/$prompt.wav", "/var/lib/cccenter/sounds/$prompt.wav");
			}
			my $digit_code = '';
			for (1..10)	{		
				$digit_code = $agi->stream_file($prompt, $tmpflags);
				$digit      = chr($digit_code);
				if (!$flags || index($flags, $digit) != -1) {
					#log_debug(5, "DEBUG", "get digit: $digit");
					last;
				}
			}
			
			if (!$nowait && $flags && $digit_code eq 0) {
                $digit_code = $agi->wait_for_digit('25000');
            }
			$digit = chr($digit_code);
        }
      
        return $digit;
}

sub asterisk_collect_digit2 {
        my ($prompt,$flags)=@_;
		my $nowait = 1 if !$flags;
        my ($digit,$digit_code,$tmp);
		$prompt =~ s/^(.+)\.(\w+)$/$1/;
		$flags ||= '#';
        if ($prompt ne "") {
			if (!-e "/var/lib/cccenter/sounds/$prompt.wav") {
				getstore("http://$MAINIP/evoice/$prompt.wav", "/var/lib/cccenter/sounds/$prompt.wav");
			}
			
			my $digit_code = $agi->stream_file($prompt, '0123456789*#');
			
			if (!$nowait && $flags && $digit_code eq 0) {
                $digit_code = $agi->wait_for_digit('25000');
            }
			$digit = chr($digit_code);
        }
      
        return $digit;
}

