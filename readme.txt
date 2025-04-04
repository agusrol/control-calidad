Los datos de esta base de datos fueron tomados de un Excel usado en CC.
Estos datos fueron levantados on un script de Python (pasar_postgresql.py) y subidos
a Postgresql versión 
Luego se crearon varios archivos php sin framework, excepto para la parte visual que
se utilizó Bootsrap en versión CDN (para verlo se requiere Internet).
Para levantarlo en un servidor utilicé LARAGON en su versión 6.0 220916 la cuál viene
nativa con php 8.1, pero para hacer la exportación a excel necesité ponerle PHP 8.3.19 (cli).
(ya que se hace con unas librerías en particular). Esto rompe un poco las bolas pero 
anda, es cuestión de darse mañana (agusrol97@gmail.com por consultas). 
Una vez hecho eso se levanta el servidor, luego de enlazar con la base de datos.