drop table if exists basket;
drop table if exists comment;
drop table if exists jersey;
drop table if exists league;
drop table if exists user;


CREATE TABLE IF NOT EXISTS league (
  `league_id` integer not null primary key auto_increment,
  `league_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS jersey (
  `jersey_id` integer not null primary key auto_increment,
  `jersey_name` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `jersey_desc` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `jersey_type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `jersey_team` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `jersey_price` int(10) NOT NULL,
  `jersey_image` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `jersey_league` integer NOT NULL,
  constraint fk_jer_lea foreign key(jersey_league) references league(league_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `user` (
  `user_id` integer not null primary key auto_increment,
  `user_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_mail` varchar(90) COLLATE utf8_unicode_ci NOT NULL ,

  `user_address` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `user_postal_code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `user_city` varchar(80) COLLATE utf8_unicode_ci NOT NULL,

  `user_password` varchar(88) COLLATE utf8_unicode_ci NOT NULL,
  `user_salt` varchar(23) COLLATE utf8_unicode_ci NOT NULL,
  `user_role` varchar(50) COLLATE utf8_unicode_ci NOT NULL,

  UNIQUE(user_mail)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS comment (
  com_id integer not null primary key auto_increment,
  com_content varchar(500) not null,
  jer_id integer not null,
  usr_id integer not null,
  constraint fk_com_jer foreign key(jer_id) references jersey(jersey_id),
  constraint fk_com_usr foreign key(usr_id) references  user(user_id)
) engine=innodb character set utf8 collate utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS basket (
  jersey_id integer not null, 
  user_id integer not null,
  quantite integer not null,  
  constraint ct_basket_fk_jersey FOREIGN KEY(jersey_id) REFERENCES jersey(jersey_id),
  constraint ct_basket_fk_user FOREIGN KEY(user_id) REFERENCES user(user_id),
  constraint ct_basket_pk PRIMARY KEY(jersey_id,user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- Grilles concours
drop table if exists user_grille;
drop table if exists matchs_grille;
drop table if exists grille;


CREATE TABLE IF NOT EXISTS grille (
  grille_id integer not null primary key auto_increment,
  league_id integer not null,
  date_fin date not null,
  nom_grille varchar(500) not null,
  constraint fk_grille_league foreign key(league_id) references league(league_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS matchs_grille (
  match_id integer not null primary key auto_increment,
  grille_id integer not null,
  domicile varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  exterieur varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  constraint fk_matchsg_grille foreign key(grille_id) references grille(grille_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS user_grille (
  user_id integer not null,
  match_id integer not null,
  result integer not null,
  constraint fk_userg_user foreign key(user_id) references user(user_id),
  constraint fk_userg_match foreign key(match_id) references matchs_grille(match_id),
  constraint ct_userg_pk PRIMARY KEY(user_id,match_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

