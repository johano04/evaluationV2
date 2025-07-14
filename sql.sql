


CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre ENUM('H', 'F'),
    email VARCHAR(150) UNIQUE,
    ville VARCHAR(100),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);


CREATE TABLE categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100)
);

CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);


CREATE TABLE images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
);


CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);



SET FOREIGN_KEY_CHECKS=0;


CREATE TABLE IF NOT EXISTS membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    date_naissance DATE,
    genre ENUM('H', 'F'),
    email VARCHAR(150) UNIQUE,
    ville VARCHAR(100),
    mdp VARCHAR(255),
    image_profil VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
        ON DELETE CASCADE ON UPDATE CASCADE
);

SET FOREIGN_KEY_CHECKS=1;

INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '1990-05-12', 'F', 'alice@mail.com', 'Antananarivo', 'alice123', NULL),
('Bob', '1985-11-23', 'H', 'bob@mail.com', 'Toamasina', 'bob123', NULL),
('Clara', '1992-07-01', 'F', 'clara@mail.com', 'Fianarantsoa', 'clara123', NULL),
('David', '1988-09-15', 'H', 'david@mail.com', 'Mahajanga', 'david123', NULL);

INSERT INTO categorie_objet (nom_categorie) VALUES
('esthétique'),
('bricolage'),
('mécanique'),
('cuisine');

INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Parfum floral', 1, 1),
('Vernis à ongles', 1, 1),
('Tournevis', 2, 1),
('Marteau', 2, 1),
('Clé à molette', 3, 1),
('Pince multiprise', 3, 1),
('Robot culinaire', 4, 1),
('Poêle antiadhésive', 4, 1),
('Four à micro-ondes', 4, 1),
('Couteau de cuisine', 4, 1),
('Fond de teint', 1, 2),
('Lisseur à cheveux', 1, 2),
('Perceuse électrique', 2, 2),
('Scie sauteuse', 2, 2),
('Pompe à huile', 3, 2),
('Compresseur', 3, 2),
('Mixeur plongeant', 4, 2),
('Grille-pain', 4, 2),
('Bouilloire électrique', 4, 2),
('Moule à gâteau', 4, 2),
('Rouge à lèvres', 1, 3),
('Masque facial', 1, 3),
('Clé plate', 3, 3),
('Clé à pipe', 3, 3),
('Perceuse manuelle', 2, 3),
('Tournevis électrique', 2, 3),
('Casserole', 4, 3),
('Spatule en bois', 4, 3),
('Balance de cuisine', 4, 3),
('Fouet', 4, 3),
('Crème hydratante', 1, 4),
('Brosse à cheveux', 1, 4),
('Pince coupante', 3, 4),
('Clé dynamométrique', 3, 4),
('Marteau-piqueur', 2, 4),
('Niveau à bulle', 2, 4),
('Machine à café', 4, 4),
('Cafetière italienne', 4, 4),
('Mixeur', 4, 4),
('Planche à découper', 4, 4);

INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-06-01', '2025-06-10'),
(3, 3, '2025-06-05', NULL),
(5, 1, '2025-05-20', '2025-06-01'),
(7, 4, '2025-06-07', NULL),
(9, 2, '2025-04-15', '2025-04-25'),
(11, 1, '2025-06-01', '2025-06-05'),
(13, 3, '2025-05-01', '2025-05-10'),
(15, 4, '2025-06-09', NULL),
(17, 2, '2025-06-10', '2025-06-12'),
(19, 1, '2025-06-11', NULL);
