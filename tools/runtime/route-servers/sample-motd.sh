#! /bin/sh

# Here is a sample motd (message of the day) that sysadmins at INEX see when 
# logging into a route server.
#
# Place this file at /etc/update-motd.d/99-ixp-manager-motd
#

cat <<END_MOTD

========================== Route Server #1 ===============================

To start / reconfigure all Bird BGP daemons, execute:

sudo /usr/local/sbin/reconfigure-rs1-all.sh

To start / reconfigure a single instance:

sudo /usr/local/sbin/reconfigure-rs1.sh -h [handle]

where handle is rs1-lan[12]ipv[46] (e.g. rs1-lan2-ipv6)

Bird control:

sudo birdc  -s /var/run/bird/bird-rs1-lan1-ipv4.ctl
sudo birdc6 -s /var/run/bird/bird-rs1-lan1-ipv6.ctl
sudo birdc  -s /var/run/bird/bird-rs1-lan2-ipv4.ctl
sudo birdc6 -s /var/run/bird/bird-rs1-lan2-ipv6.ctl

===========================================================================

END_MOTD
