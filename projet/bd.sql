-- Création de la table "utilisateur"
CREATE TABLE utilisateur (
    nom_util VARCHAR(255) PRIMARY KEY,
    nom VARCHAR(255),
    prenom VARCHAR(255),
    ddn DATE,
    adresse_mail VARCHAR(255),
    mot_de_passe VARCHAR(255)
);

-- Insertion des données dans la table "utilisateur"
INSERT INTO utilisateur (nom_util, nom, prenom, ddn, adresse_mail, mot_de_passe) VALUES
('test', 'test', 'test', '2000-05-31', 'test@test', 'test');

-- Création de la table "livres"
CREATE TABLE livres (
    titre VARCHAR(255) PRIMARY KEY,
    auteur VARCHAR(255),
    editeur VARCHAR(255),
    parution DATE,
    categorie VARCHAR(255),
    stock INT,
    chemin_image VARCHAR(255)
);

-- Insertion des données dans la table "livres"
INSERT INTO livres (titre, auteur, editeur, parution, categorie, stock, chemin_image) VALUES
('1984', 'George Orwell', 'Secker & Warburg', '1949-06-08', 'Dystopie', 16, 'images/orwell.jpg'),
('Anna Karénine', 'Léon Tolstoï', 'The Russian Messenger', '1877-01-01', 'Roman romantique', 20, 'images/anna.jpg'),
('Crime et Châtiment', 'Fiodor Dostoïevski', 'The Russian Messenger', '1866-01-01', 'Roman psychologique', 19, 'images/crime.jpg'),
('Harry Potter à l''école des sorciers', 'J.K. Rowling', 'Bloomsbury', '1997-06-26', 'Fantaisie', 19, 'images/harry.jpg'),
('L''Étranger', 'Albert Camus', 'Gallimard', '1942-06-03', 'Roman philosophique', 16, 'images/etranger.jpg'),
('La Guerre et la Paix', 'Léon Tolstoï', 'The Russian Messenger', '1869-01-01', 'Roman historique', 16, 'images/La-Guerre-et-la-Paix.jpg'),
('La Peste', 'Albert Camus', 'Gallimard', '1947-06-10', 'Roman philosophique', 20, 'images/lapeste.jpg'),
('Le Nom du vent', 'Patrick Rothfuss', 'DAW Books', '2007-03-27', 'Fantaisie', 20, 'images/vent.jpg'),
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Gallimard', '1943-04-06', 'Conte philosophique', 20, 'images/prince.jpg'),
('Le Seigneur des anneaux', 'J.R.R. Tolkien', 'George Allen & Unwin', '1954-07-29', 'Fantaisie', 20, 'images/anneaux.jpg'),
('Le Tour du monde en quatre-vingts jours', 'Jules Verne', 'Pierre-Jules Hetzel', '1873-01-30', 'Aventure', 20, 'images/tdmonde.jpg'),
('Les Misérables', 'Victor Hugo', 'A. Lacroix, Verboeckhoven & Cie', '1862-03-15', 'Roman historique', 20, 'images/miserable.jpg'),
('Orgueil et Préjugés', 'Jane Austen', 'T. Egerton', '1813-01-28', 'Roman romantique', 20,'images/orgeuil.jpg');

-- Création de la table "emprunts"
CREATE TABLE emprunts (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    nom_util VARCHAR(255),
    titre VARCHAR(255),
    date_emprunt DATE,
    date_rendu DATE,
    qte INT,
    FOREIGN KEY (nom_util) REFERENCES utilisateur(nom_util),
    FOREIGN KEY (titre) REFERENCES livres(titre)
);

-- Insertion des données dans la table "emprunts"
INSERT INTO emprunts (nom_util, titre, date_emprunt, date_rendu, qte) VALUES
('test', '1984', '2024-04-30', '2024-05-14', 1),
('test', 'Crime et Châtiment', '2024-04-30', '2024-05-14', 1);

CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nom_admin VARCHAR(255) NOT NULL,
    mot_de_passe_admin VARCHAR(255) NOT NULL
);
INSERT INTO admin (nom_admin, mot_de_passe_admin) VALUES ('test', 'test');
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    status ENUM('unread', 'read') NOT NULL DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
