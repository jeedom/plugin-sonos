PROGRESS_FILE=/tmp/dependancy_camera_in_progress
if [ ! -z $1 ]; then
	PROGRESS_FILE=$1
fi
touch ${PROGRESS_FILE}
echo 0 > ${PROGRESS_FILE}
echo "Launch install of sonos dependancy"
sudo apt-get clean
echo 30 > ${PROGRESS_FILE}
sudo apt-get update
echo 50 > ${PROGRESS_FILE}
sudo apt-get install -y smbclient
echo 60 > ${PROGRESS_FILE}
sudo apt-get install -y php7.0-mbstring php7.0-soap
echo 75 > ${PROGRESS_FILE}
sudo apt-get install libsox-fmt-mp3 sox libttspico-utils
echo 100 > ${PROGRESS_FILE}
sudo systemctl reload apache2
echo "Everything is successfully installed!"
rm ${PROGRESS_FILE}
