Installation:

Webserver->

run git clone https://github.com/Nanowork/SymfonyProject.git command

run composer install command

run npm install command to install frontend dependencies

add a database on your mysql server

run php bin/console doctrine:migrations:migrate to add the database tables

run php bin/console doctrine:fixtures:load to load the default products,payments and shipments code data


TO DO:

Have to add an admin panel for shipment and payment options(add/edit/delete).

Have to put my forms in folders.

Have to add adresses to the User Profile.

Fix details button.

Make it so that when u delete a product it doesnt delete from the database and moves to a table (is_deleted) so it can still show up in order(details) in the User Profile.

