#!/usr/bin/perl
# ASTPP - Open Source Voip Billing
#
# Copyright (C) 2004, Aleph Communications
#
# ASTPP Team (info@astpp.org)
#
# This program is Free Software and is distributed under the
# terms of the GNU General Public License version 2.
#
#  minbalance should be specified in dollars.
#
# @daily /usr/local/astpp/astpp-low-balance.pl minbalance=5
#
###############################################################################
use LWP::Simple qw(!head);
use POSIX;
use POSIX qw(strftime);
use DBI;
use CGI;
use CGI qw/:standard Vars/;
use Getopt::Long;
use Locale::Country;
use Locale::gettext_pp qw(:locale_h);
use Data::Dumper;
use ASTPP;
use strict;

use lib './lib', '../lib';
require "/usr/local/astpp/astpp-common.pl";
$ENV{'LANGUAGE'} = "en";    # de, es, br - whatever
print STDERR "Interface language is set to: $ENV{'LANGUAGE'}\n";
bindtextdomain( "ASTPP", "/var/locale" );
textdomain("ASTPP");
use vars qw($config $astpp_db $osc_db $agile_db $cdr_db
  @output @cardlist $config $params $ASTPP);
@output = ( "STDOUT", "LOGFILE" );
$ASTPP     = ASTPP->new;
$ASTPP->set_verbosity(4);    #Tell ASTPP debugging how verbose we want to be.

sub initialize() {
    $config     = &load_config();
    $astpp_db   = &connect_db( $config, @output );
    $config     = &load_config_db($astpp_db,$config);
}

###########################################
# Program Starts Here
###########################################
foreach my $param ( param() ) {
    $params->{$param} = param($param);
    print STDERR "$param $params->{$param}\n";
}

&initialize();

@cardlist = &list_accounts($astpp_db);
foreach my $card (@cardlist) {
    my $cardinfo = &get_account( $astpp_db,  $card );
    my $balance = (-1 * $cardinfo->{balance}) + ($cardinfo->{posttoexternal} * $cardinfo->{credit_limit});
    if ( ($balance * -1) <= $params->{minbalance} && $cardinfo->{posttoexternal} == 0 )    
    {
        print "\n Card Number: $card Balance: $balance\n";	
	&email_low_balance( $config, $cardinfo->{email},$balance );
    }
}
