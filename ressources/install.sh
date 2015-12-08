touch /tmp/dependancy_sonos_in_progress
echo "Lancement de l'installation/mise à jour des dépendances sonos"

sudo apt-get clean
sudo apt-get update
sudo apt-get install -y smbclient

if [ $? -ne 0 ]; then
    echo "could not install smbclient - abort"
    exit 1
fi

echo "Everything is successfully installed!"
rm /tmp/dependancy_sonos_in_progress