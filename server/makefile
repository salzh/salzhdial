AGIBIN = /var/lib/cccenter/agi-bin
COREAGI = /opt/lampp/htdocs/admin/modules/core/agi-bin
SVNROOT = /salzh/Dispatch
WEBROOT = /opt/lampp/htdocs
PPERL	= /salzh/perl510/bin/perl
PP	    = /salzh/perl510/bin/pp

all: recording perl-lib link log ivr sql sql-patch files scripts mis etc conf
	echo "Mazal Center installed"
recording:
	-ln -s /var/spool/cccenter/ /opt/lampp/htdocs/spool
unrecording:
	rm /opt/lampp/htdocs/spool -f
clean: unrecording unlink
	echo "Mazal Center cleaned"
perl-lib:
	echo "GD is needed"
link:
	-rm /opt/lampp/htdocs/ivr.pl -f
	-ln -s `pwd`/ivr.pl /opt/lampp/htdocs/ivr.pl
unlink:
	rm -f /opt/lampp/htocs/ivr.pl 
log:
	-chmod a+w /var/log/cccenter/center.log
ivr:
	-rm $(WEBROOT)/ivr -rf
	mkdir -p $(WEBROOT)/ivr && chmod a+w $(WEBROOT)/ivr
univr: 
	rm -f /opt/lampp/htdocs/ivr && rm -f ivr -rf
sql:
	-mysql cccenter < sql/ss_config.sql
	-mysql cccenter < sql/dispatch_status.sql
	-mysql cccenter < sql/dispatch_log.sql
	-mysql cccenter < sql/dispatch_reload.sql
	-mysql cccenter < sql/dispatch_configs.sql
	-mysql cccenter < sql/dispatch_configs2.sql
	-mysql cccenter < sql/dispatch_definegroup.sql
	-mysql cccenter < sql/dispatch_roles.sql
	-mysql cccenter < sql/member.sql
	-mysql cccenter < sql/example.sql
	-mysql cccenter < sql/define_template.sql
	-mysql cccenter < sql/define_playHistory.sql
	-mysql cccenter < sql/notice.sql
	-mysql cccenter < sql/alarm.sql
	-mysql cccenter < sql/center_secret.sql
	-echo "alter table recordings add content varchar(1024)" | mysql cccenter

sql-patch:
	-echo "alter table dispatch_status add username varchar(20)" | mysql cccenter
	-echo "alter table dispatch_status add callerid varchar(20)" | mysql cccenter

unsql:
	echo "drop table if exists ss_config" | mysql cccenter
	echo "drop table if exists h323" | mysql cccenter
	echo "alter table recordings drop content" | mysql cccenter
bin:
	chmod a+x bin/* -R && cp -p bin/* /var/lib/cccenter/bin
files: ivr
	-rm -rf $(WEBROOT)/files
	-mkdir -p $(WEBROOT)/files; chmod a+w $(WEBROOT)/files
unfiles:
	-/bin/rm -rf $(WEBROOT)/files
	
scripts:
	-ln -s `pwd`/scripts /opt/lampp/htdocs/scripts
unscripts:
	-/bin/rm -f /opt/lampp/htdocs/scripts
etc:
	-/bin/cp etc/muttrc ~/.muttrc
	-/bin/cp etc/center /etc/logrotate.d -f
	-/bin/cp etc/my.cnf /opt/lampp/etc; service lampp reloadmysql
	-/bin/cp etc/crontab /etc/ -f
	-/bin/cp etc/backup /etc/cron.hourly -p

dist:
	-rm ../MazalCenter2.tar.gz -f; ./clearz
	date > buildtime && cd .. && tar --exclude='.svn' --exclude='AgentSystem2' --exclude='tmp' --exclude='TODO' --exclude='ivr' --exclude='flex' -cvzf MazalCenter.tar.gz mazalcenter
	
autodial-dist:
	-rm ../autodial.tar.gz -f; ./clearz
	date > buildtime && cd .. && tar --exclude='.svn' --exclude='AgentSystem2' --exclude='messages*' --exclude='note' --exclude='tmp' --exclude='TODO' --exclude='ivr' --exclude='lib' --exclude='*.pl' --exclude='alarm' --exclude='center2' -cvzf MazalCenter2.tar.gz MazalCenter2

mis:
	/bin/cp -pf agi/dialparties.agi $(AGIBIN)
	-/bin/cp -pf agi/dialparties.agi $(COREAGI)
	echo 'cp lampp and make it as service'
	-cp -pf lampp /etc/init.d && chkconfig --add lampp
	/bin/cp -pf etc/* /etc/cccenter -rf
	-mkdir -p /var/lib/cccenter/sounds/dispatch
	-rm /opt/lampp/htdocs/dispatch /opt/lampp/htdocs/dispatch2 /opt/lampp/htdocs/meeting /opt/lampp/htdocs/dipaptch3 -rf
	chmod a+x dispatch-server
	-/bin/cp patch/components.class.php $(WEBROOT)/admin/ -f
	-/bin/cp patch/functions.inc.php $(WEBROOT)/admin/modules/core -f
	-/bin/cp -pf retrieve_* /var/lib/cccenter/bin
	-/bin/cp -pf crossdomain.xml $(WEBROOT)
autodial: all autodial_patch
	-mysql cccenter < sql/autodial.sql
	echo "AUTODIAL" > /etc/mazal.conf
fetchexe:
	-wget http://192.168.1.190/center2 -O center2 && chmod a+x center2
	-wget http://192.168.1.190/autodial2 -O autodial2 && chmod a+x autodial2
	-wget http://192.168.1.190/alarm -O alarm && chmod a+x alarm

center2: mazal.pl
	-export MAKEPP=1 && cp -f center.conf center.conf.default && $(PP) -x mazal.pl -o center2 
autodial2: mazal.pl
	-export MAKEPP=1 && cp -f autodial.conf center.conf.default && $(PP) -x mazal.pl -o autodial2
alarm:
	-export MAKEPP=1 && $(PP) -x alarm.pl -o alarm	

conf:
	echo "WARNING: set value for center.conf, autodial.conf and alarm.conf"
test:
	echo $@
touch:
	./ctouch mazal.pl
autodial_patch:
	-echo 'alter table clients add column note varchar(1024)' | mysql cccenter
	-cp -f patch/functions.inc.php /opt/lampp/htdocs/admin/modules/core
	-echo "update globals set value='20' where VARIABLE='RINGTIMER'" | mysql cccenter
	-/var/lib/cccenter/bin/retrieve_conf; cccenter -rx "dialplan reload"
	-echo "alter table surquest add column tag tinyint" | mysql cccenter
broadcast: all
	-echo 'install module - broadcast'
	-mysql cccenter < sql/broadcast.sql
	echo "BROADCAST" > /etc/mazal.conf
	-mkdir -p $(WEBROOT)/broadcast
	-/bin/cp -p scripts/syncbroadcastcdr.pl /etc/cron.hourly
broadcast-patch1:
	-/bin/cp -p scripts/syncbroadcastcdr.pl /etc/cron.hourly
broadcast-patch2: broadcast-patch1
	-echo "alter table broadcastcdr add column clid varchar(8)" | mysql cccenter

haikang:
	-mysql cccenter < sql/haikang.sql
	-mkdir $(WEBROOT)/haikang -p
itx: autodial
	-mysql cccenter < itx.sql
	-mkidr $(WEBROOT)/itx -p
tvsale: sql/tvsale.sql
	-mysql cccenter < sql/tvsale.sql
groupnotice:
	-mysql cccenter < sql/groupnotice.sql
	-cp -pf agi/groupnotice.agi $(AGIBIN)
	-/bin/cp -pf gn/* $(WEBROOT)/groupnotice

