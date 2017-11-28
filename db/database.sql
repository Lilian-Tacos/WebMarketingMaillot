create database if not exists newsoccerjersey character set utf8 collate utf8_unicode_ci;
use newsoccerjersey;

grant all privileges on newsoccerjersey.* to 'nsj_user'@'localhost' identified by 'secret';