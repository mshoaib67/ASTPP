#!/usr/bin/perl
#
# ASTPP - Open Source VoIP Billing
#
# Copyright (C) 2004/2013 www.astpp.org
#
# ASTPP Team <info@astpp.org>
#
# This program is Free Software and is distributed under the
# Terms of the GNU General Public License version 2.
############################################################

sub xml_process()
{
    my ($params,$ASTPP, $config) = @_;    
    my ($xml);    
    if($params->{key_value} eq 'acl.conf')
    {
	$xml = header( -type => 'text/plain' );
	$xml = $ASTPP->fs_configuration_xml_header(module=>'acl.conf',desc=>'Various Configuration',xml=>$xml);
	$xml = $ASTPP->acl(module=>$params->{key_value},xml=>$xml);
	$xml = $ASTPP->fs_configuration_xml_footer(xml=>$xml);
    }
    elsif($params->{key_value} eq 'sofia.conf')
    {
	$xml = header( -type => 'text/plain' );
	$xml = $ASTPP->fs_configuration_xml_header(module=>'sofia.conf',desc=>'Sip Profile',xml=>$xml);
	$xml = $ASTPP->sip_profile_gateway(module=>$params->{key_value},xml=>$xml);
	$xml = $ASTPP->fs_configuration_xml_footer(xml=>$xml);
    }
    elsif($params->{key_value} eq 'post_load_modules.conf')
    {
	$xml = header( -type => 'text/plain' );
	$xml = $ASTPP->fs_configuration_xml_header(module=>'post_load_modules.conf',desc=>'Post Load Modules',xml=>$xml);
	$xml = $ASTPP->post_load_modules(module=>$params->{key_value},xml=>$xml);
	$xml = $ASTPP->fs_configuration_xml_footer(xml=>$xml);
	print STDERR $xml;
    }
    elsif($params->{key_value} eq 'post_load_switch.conf')
    {
	$xml = header( -type => 'text/plain' );
	$xml = $ASTPP->fs_configuration_xml_header(module=>'post_load_modules.conf',desc=>'Post Load Modules',xml=>$xml);
	$xml = $ASTPP->post_load_switch(module=>$params->{key_value},xml=>$xml);
	$xml = $ASTPP->fs_configuration_xml_footer(xml=>$xml);
	print STDERR $xml;
    }
    else{
	$xml = header( -type => 'text/plain' );
	$xml = $ASTPP->fs_configuration_xml_header(module=>$params->{key_value},desc=>'Various Configuration',xml=>$xml);
	$xml = $ASTPP->fs_configuration_xml_footer(xml=>$xml);
    }
    print $xml;
}
1;