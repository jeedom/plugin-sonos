touch /tmp/dependancy_sonos_in_progress
echo 0 > /tmp/dependancy_sonos_in_progress
echo "Launch install of sonos dependancy"
sudo apt-get clean
echo 30 > /tmp/dependancy_sonos_in_progress
sudo apt-get update
echo 50 > /tmp/dependancy_sonos_in_progress
sudo apt-get install -y smbclient
echo 100 > /tmp/dependancy_sonos_in_progress
echo "Everything is successfully installed!"
rm /tmp/dependancy_sonos_in_progress