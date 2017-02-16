Arkadiusz Mazur
email:
arkadiusz.mazur@hotmail.com
skype:
programista.php.sql
repo:
https://github.com/ArkadiuszMazur/a_mazur_task_recruitment
https://github.com/ArkadiuszMazur/a_mazur_task_recruitment/compare/master@%7B1day%7D...master

Instructions to the application (ENGLISH)

- The application is made in Zend 1.8.4.
- As a graphic template I used Bootstrap.
- The application allows you to add and remove items. Security XSS are included in JS and PHP code
Zend provides for SQL Injection.
- Queries to the database are located in:
application/configs/database_queries.txt
- To log protocol is used OAuth2.
To log on you have to use an account gmail:
	login:
	amazur.edukey
	password:
	nx.media.pl
It is not possible to log in via another account.
Path to class to login by gmail:
application\models\googleapiModel.php
First is achieved url google account by entering google.clientId.
After this occurs redirect to login to the address indicated.
After logging you return to the site of application.
There is extracted token using google.secretKey and then are collected user data.
In our case, we are interested in user's email address.
After properly logging you can add or remove items.

--------------------------------

Instrukcja do aplikacji (POLISH)

- Aplikacja jest wykonana w Zend 1.8.4.
- Jako szaty graficznej użyto szablonu Bootstrap.
- Aplikacja pozwala na dodawanie i odejmowanie pozycji. Zabezpieczenia XSS są na poziomie kodu JS/PHP,
obsługę SQL Injection zapewnia Zend.
- Zapytania do bazy danych znajdują się w:
application/configs/database_queries.txt
- Do zalogowania wykorzystywany jest protokół oAuth2.
Aby się zalogować, należy użyć konta gmail:
login:
amazur.edukey
hasło:
nx.media.pl
Nie jest możliwe zalogowanie się poprzez inne konto.
Obsługa logowania przez gmail znajduje się w:
application\models\googleapiModel.php
Najpierw uzyskiwany jest adres url konta google przez podanie google.clientId.
Robione jest przekierowanie do logowania na wskazany adres.
Po zalogowaniu następuje powrót na stronę z aplikacją.
Tam pobieramy token przy podaniu google.secretKey a następnie interesujące nas dane.
W naszym przypadku interesuje nas adres email użytkownika.
Po zalogowaniu użytkownik może dodawać lub usuwać pozycje.

