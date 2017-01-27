# Embryo
[![PHP](https://img.shields.io/badge/php->%3D%205.4-blue.svg?style=flat-square&colorB=8892BF)](https://secure.php.net/) [![Packagist](https://img.shields.io/packagist/v/davidecesarano/embryo-framework.svg?style=flat-square)](https://packagist.org/packages/davidecesarano/embryo-framework) [![Dependency Status](https://www.versioneye.com/user/projects/5814b830d33a7126ff24ee66/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5814b830d33a7126ff24ee66) [![Travis](https://img.shields.io/travis/davidecesarano/Embryo-Framework.svg?style=flat-square)](https://travis-ci.org/davidecesarano/Embryo-Framework)

### Cos'è Embryo?
Embryo è un framework MVC per lo sviluppo di applicazioni web in PHP.

### Requisiti
Per eseguire _web application_ scritte con Embryo, è necessario disporre di:
* **Apache HTTP Server**
* **PHP 5.4 o superiore** 
* Modulo **mod_rewrite** attivo. 

Per gli sviluppatori che desiderano utilizzare Embryo, avere conoscenze di programmazione orientata agli oggetti (Object Oriented Programming - OOP) è necessaria.

### Installazione

#### Packagist
E' possibile installare il framework attraverso [Packagist](https://packagist.org/packages/davidecesarano/embryo-framework) lanciando il comando 

`composer create-project davidecesarano/embryo-framework foldername 1.* -s dev`

Dove _foldername_ è il nome della cartella dove verranno scaricati i file.

##### Download
L'installazione di Embryo mediante download si sviluppa nei seguenti passi:

1. [Scaricare il framework](https://github.com/davidecesarano/Embryo-Framework/archive/master.zip).
2. Scompattare il pacchetto in una cartella in locale.
3. Eseguire [composer](https://getcomposer.org/) nella cartella in cui è presente il progetto e lanciare il comando `composer install`. A questo punto verrà creata la cartella `vendor` con le dipendenze e l'autoloading dei file.

### Documentazione
La documentazione ufficiale e alcuni esempi pratici sono disponibili nel [Wiki](https://github.com/davidecesarano/Embryo-Framework/wiki).
