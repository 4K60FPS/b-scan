#Pasii pentru configurarea unui nou Scanner

!!! Inainte de a urma pasii aveti nevoie de apache2 sau httpd (depinde de distributie) si PHP instalat pe server-ul pe care vreti sa instalati aceasta interfata.

1. Aflati cine proceseaza comenzile (accesand whoami.php)

2. Adaugati privilegii utilizatorului de la pasul 1 accesand /etc/sudoers

#In cazul unei distributii ubuntu
www-data ALL=(ALL) NOPASSWD: ALL

#In cazul undei distributii centOS
apache2 ALL=(ALL) NOPASSWD: ALL

3. Adaugati script-ul de scan

4. In directorul in care aveti toate fisierele de la script-ul de scan scrieti urmatoarea comanda:
chmod +x *

5. Acum puteti sa-l configurati dupa placul vostru.