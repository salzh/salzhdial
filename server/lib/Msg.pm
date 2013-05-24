package Msg;

use strict;
use warnings;

sub new {
	my $class = shift;
	my %h = @_;
	$h{event_tmp}		= '';
	$h{msg_tmp}			= '';
	$h{event_msg_spool} = [];
	$h{msg_spool}		= [];
	
   	return bless(\%h, $class);
}

sub store {
	my $self   = shift;
    my $buffer = shift;
    return {status => 0, message => "buffer is null"} if not defined $buffer;
    my $head   = shift || 20;
    warn "--buffer: $buffer\n";
    if ($buffer =~ /^<head>(\d+)<\/head>/) {
        my $explen = $1;
        my $gotlen = length($buffer) - $head;
        warn "explen: $explen, gotlen=$gotlen\n";
        $self->{msg_tmp} = '';
        if ($explen == $gotlen) {
            my $msg = substr $buffer, $head;
            push @{$self->{msg_spool}}, $msg;
            return {status => 1}
        } elsif ($explen > $gotlen) {
            $self->{msg_tmp} = $buffer;
            return {status => 1};
        } else {
            my $msg = substr $buffer, $head, $explen;
            push @{$self->{msg_spool}}, $msg;

            return $self->store(substr($buffer, $head+$explen));
        }

    } else {
        $buffer = $self->{msg_tmp} . $buffer;
        if (length($buffer) >= $head) {
            if ($buffer =~ /^<head>(\d+)<\/head>/) {
                return $self->store($buffer)
            } else {
                $self->{msg_tmp} .= '';
                return {status => 0, message => "buffer: $buffer [discard]"};
            }
        }
        $self->{msg_tmp} = $buffer;
        return {status => 1};
    }
}

sub fetch {
    my $self = shift;
    my $msg  = shift @{$self->{msg_spool}};

    return $msg;
}

sub store2 {
    my $self   = shift;
    my $buffer = shift;
    return {status => 0, message => "buffer is null"} if not defined $buffer;

    my $ok     = 0;
    my $string = $self->{msg_tmp};
    if (length($buffer) == 1 && ord($buffer) == 8) {
        $self->{msg_tmp} = substr $string, 0, -1;
        return {status   => 1};
    }

    $string   .= $buffer;
    $self     -> {msg_tmp} = '';
    $ok        = 1 if $string =~ /\n$/;

    my @cmds   = split /\r\n|\n/, $string;
    if (!$ok) {
        $self->{msg_tmp} = pop @cmds;
    }
    push @{$self->{msg_spool}}, @cmds;
    return {status => 1};
}

sub store3 {
    my $self   = shift;
    my $buffer = shift;
    return {status => 0, message => "buffer is null"} if not defined $buffer;

    my $ok     = 0;
    my $string = $self->{msg_tmp};
    if (length($buffer) == 1 && ord($buffer) == 8) {
        $self->{msg_tmp} = substr $string, 0, -1;
        return {status   => 1};
    }

    $string   .= $buffer;
    $self     -> {msg_tmp} = '';
    $ok        = 1 if $string =~ /\n$/;

    my @cmds   = split /\r\n|\n/, $string;
    if (!$ok) {
        $self->{msg_tmp} = pop @cmds;
    }
    push @{$self->{msg_spool}}, @cmds;
    return {status => 1};
}

1;
