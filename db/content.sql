INSERT INTO league (league_name) VALUES ('Ligue 1'), ('Liga BBVA'),('Bundesliga'),('Serie A'),('Première Ligue');

INSERT INTO `jersey` (`jersey_name`, `jersey_desc`, `jersey_type`, `jersey_team`, `jersey_price`, `jersey_image`, `jersey_league`) VALUES
('OL domicile', 'Maillot domicile 2017/2018', 'Domicile', 'Olympique Lyonnais', 60, 'OL-domicile.jpg', 1),
('PSG domicile', 'Maillot domicile 2017/2018', 'Domicile', 'Paris Saint Germain', 120, 'PSG-domicile.jpg', 1),
('Real domicile', 'Maillot domicile 2017/2018', 'Domicile', 'Real Madrid', 50, 'Real-domicile.jpg', 2),
('Barcelone domicile','Maillot domicile 2017/2018','Domicile','FC Barcelone',70,'Barca-domicile.jpg',2),
('Bayern Munich domicile','Maillot domicile 2017/2018','Domicile','FC Bayern Munich',60,'Munich-domicile.jpg',3),
('Borussia Dortmund domicile','Maillot domicile 2017/2018','Domicile','BV 09 Borussia Dortmund',55,'Dortmund-domicile.jpg',3),
('Juventus Turin domicile','Maillot domicile 2017/2018','Domicile','Juventus Turin',50,'Juventus-domicile.jpg',4),
('Milan AC domicile','Maillot domicile 2017/2018','Domicile','Milan AC',45,'MilanAc-domicile.jpg',4),
('Leicester City domicile','Maillot domicile 2017/2018','Domicile','Leicester City',45,'Leicester-domicile.jpg',5),
('West Ham domicile','Maillot domicile 2017/2018','Domicile','West Ham',45,'WestHam-domicile.jpg',5);



/* raw password is 'john' */
INSERT INTO user (user_name, user_last_name, user_mail, user_address, user_postal_code, user_city, user_password, user_salt, user_role) VALUES 
('John', 'Doe', 'john@doe.com', '30 rue des utilisateurs', '69000', 'Lyon', 'L2nNR5hIcinaJkKR+j4baYaZjcHS0c3WX2gjYF6Tmgl1Bs+C9Qbr+69X8eQwXDvw0vp73PrcSeT0bGEW5+T2hA==', 'YcM=A$nsYzkyeDVjEUa7W9K', 'ROLE_USER');
/* raw password is 'jane' */
INSERT INTO user (user_name, user_last_name, user_mail, user_address, user_postal_code, user_city, user_password, user_salt, user_role) VALUES 
('Jane', 'Doe', 'jane@doe.com', '30 rue des utilisateurs', '69000', 'Lyon', 'EfakNLxyhHy2hVJlxDmVNl1pmgjUZl99gtQ+V3mxSeD8IjeZJ8abnFIpw9QNahwAlEaXBiQUBLXKWRzOmSr8HQ==', 'dhMTBkzwDKxnD;4KNs,4ENy', 'ROLE_USER');
/* raw password is '@dm1n' */
INSERT INTO user (user_name, user_last_name, user_mail, user_address, user_postal_code, user_city, user_password, user_salt, user_role) VALUES 
('admin', 'istrateur', 'admin@admin.com', '20 rue des admins', '75010', 'Paris', 'gqeuP4YJ8hU3ZqGwGikB6+rcZBqefVy+7hTLQkOD+jwVkp4fkS7/gr1rAQfn9VUKWc7bvOD7OsXrQQN5KGHbfg==', 'EDDsl&fBCJB|a5XUtAlnQN8', 'ROLE_ADMIN');


-- Grilles
