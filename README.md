# photobooth+
A Photobooth webinterface for Raspberry Pi and Windows, based on [photobooth](https://github.com/andreknieriem/photobooth) by Andre Rinas.

I've extended the original [photobooth](https://github.com/andreknieriem/photobooth) with a print feature, so you can print newly taken pictures or any picture in the gallery. Photobooth uses the command line to print the picture. The command can be modified in ```config.inc.php```.

Modifications and new features:
- Pictures can be printed directly after they were taken or later from the gallery
- Moved a lot of parameters and settings into the ```config.inc.php```
- Changed the ```data.txt``` from a line seperated database into a JSON structure
- The images are now processed with GD/ImageMagick rather than avconv
- Now works on Windows and Linux
- Added [digiCamControl](http://digicamcontrol.com/) by Duka Istvan to control the camera and to take pictures under Windows
- Photobooth caches all generated QR-Codes, Thumbnails and Prints
- All directories are not automatically created if they doesn't exist
- The gallery can now be ordered ascending oder descending by picture age (see ```$config['gallery']['newest_first']``` in ```config.inc.php```)

### Prerequisites
- gphoto2 installed, if used on a Raspberry for DSLR control
- digiCamControl, if used unter Windows for DSLR control
- Apache

### Installazione
Su Raspberry:
```
  sudo apt-get update
  sudo apt-get dist-upgrade
  sudo apt-get install apache2 -y
  sudo apt-get install php -y
  sudo apt-get install php-gd -y
  sudo apt-get install gphoto2 -y
  sudo apt-get install libav-tools -y
  cd /var/www/
  sudo rm -r html/
  sudo git clone https://github.com/davide92br/photobooth-1.git
  sudo mv photobooth html
  sudo chown -R pi: /var/www/
  sudo chmod -R 777 /var/www
  sudo nano /etc/sudoers
--> Add the following:
  www-data ALL=(ALL) NOPASSWD: ALL
  sudo reboot
```
**Creo il punto di mount del disco fat32 dove salvare le foto**
```
sudo fdisk -l
sudo blkid /dev/sda1
sudo mkdir /var/www/html/usbhdd
sudo chown -R pi:pi /var/www/html/usbhdd
sudo chmod 777 /var/www/html/usbhdd
sudo nano /etc/fstab
UUID=UUIDTROVATO /var/www/html/usbhdd auto defaults,auto,umask=000,users,rw,uid=pi,gid=pi 0 0
sudo mount /var/www/html/usbhdd
sudo sh -c 'echo "KERNEL==\"sda\", RUN+=\"/bin/mount /var/www/html/usbhdd\"" >> /etc/udev/rules.d/99-mount.rules'
sudo reboot
```
**Ensure that the camera trigger works:**
```
  sudo rm /usr/share/dbus-1/services/org.gtk.vsf.GPhoto2VolumeMonitor.service
  sudo rm /usr/share/gvfs/mounts/gphoto2.mount
  sudo rm /usr/share/gvfs/remote-volume-monitors/gphoto2.monitor
  sudo rm /usr/lib/gvfs/gvfs-gphoto2-volume-monitor
```

**Canon SELPHY CP1200/CP1300 printer
Add buster/testing repository
We need Gutenprint 5.2.13 or newer, unfortunately Raspbian Stretch includes only Gutenprint 5.2.11. Luckily, the next distro (Buster) includes an up-to-date version and thus we can install that one instead of compiling the drivers ourselves.

For that, we add the buster repositories with a lower priority (to avoid an upgrade of all packages) and select them later manually, when installing the drivers.

Create file /etc/apt/preferences.d/stretch.pref with content

Package: *
Pin: release n=stretch
Pin-Priority: 900
Create file /etc/apt/preferences.d/buster.pref with content

Package: *
Pin: release n=buster
Pin-Priority: 750
Add the following line to /etc/apt/sources.list:

deb http://mirrordirector.raspbian.org/raspbian/ buster main contrib non-free rpi
Install Gutenprint printer drivers
With the up-to-date drivers available, we install them with the following command:

apt update
apt install printer-driver-gutenprint -t buster
Add user pi to group lpadmin
To allow the current user to modify printer settings we must add it to the group lpadmin:

sudo usermod -a -G lpadmin pi
Plug in printer to USB port and add in CUPS
Plug in the printer.
Open http://localhost:631 on the Raspberry Pi.
Select 'Add Printer' in the Tab 'Administration'
When asked, enter credentials for user pi
The printer should be offered somewhere close to the top of the list in the section 'Local Printers'. Select it and click 'Continue'.
If you wish, you can specify a name in the next step.
In the last step, select the appropriate model in the list. For the Canon SELPHY CP1300 the printer for the CP1200 works fine as the CP1300 is the same printer with a larger screen and some smartphone baublery.
Click "Add Printer". This concludes the installation.
In the following dialogue, you can modify the default settings.
Select default printer
It is important that you set the printer as the default printer. For that, go to the CUPS administration interface (http://localhost:631), open the list of printers and select your printer. In the drop-down menu 'Administration' select 'Set as Server Default'.**
Open the IP address of your raspberry pi in a browser

- Change the styling to your needs
On Windows
    - Download [digiCamControl](http://digicamcontrol.com/) and extract the archive into ```digicamcontrol``` in the photobooth root, e.g. ```D:\xampp\htdocs\photobooth\digicamcontrol```

### Change Labels
There are two label files in the lang folder, one for de and one for en. The de lang-file is included at the bottom of the index.php.
If you want the english labels, just change it to en.js.
If you want to change the labels just change the de.js or en.js

### Changelog
- 1.3.2: Bugfix for QR Code on result page
- 1.3.1: Merged pull-request #6,#15 and #16
- 1.3.0: Option for QR and Print Butons, code rework, gulp-sass feature enabled
- 1.2.0: Printing feature, code rework, bugfixes
- 1.1.1: Bugix - QR not working on touch devices
- 1.1.0: Added QR Code to Gallery
- 1.0.0: Initial Release

### Tutorial
[Raspberry Pi Weddingphotobooth (german)](https://www.andrerinas.de/tutorials/raspberry-pi-einen-dslr-weddingphotobooth-erstellen.html)

### Thanks to
- [dimsemenov](https://github.com/dimsemenov/photoswipe) for photoswipe
- [t0k4rt](https://github.com/t0k4rt/phpqrcode) for phpqrcode
