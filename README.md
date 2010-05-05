<img src="http://github.com/dentarg/TDDD26/raw/master/user-case.png" alt="user-case.png" />


User, Album, Photo


User will have fields
id
email (used for logging in as well)
password
nickname
status
friends

Album fields:
id, name, date, cover picture, author, access

Photo:
id, name, picture, date, album



*************************************************************************

AlbumController has actions:
show (indexAction)
create
update
delete

PhotoController:
show
create - implemented by create or update actions of album controller
update
delete
random (indexAction) - random public pictures on the frontpage

UserActions:
create
update
show (user profile)
index (users list)
login
friend