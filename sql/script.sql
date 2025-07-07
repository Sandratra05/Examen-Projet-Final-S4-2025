
DROP DATABASE db_s2_ETU003197;
CREATE DATABASE db_s2_ETU003197 CHARACTER SET utf8mb4;

USE db_s2_ETU003197;

CREATE TABLE ef_client (
   id_client INT AUTO_INCREMENT,
   nom_client VARCHAR(50),
   email VARCHAR(100),
   coordonnees VARCHAR(50),
   CIN VARCHAR(50),
   date_naissance DATE,
   PRIMARY KEY(id_client)
);

CREATE TABLE ef_type_compte (
   id_type_compte INT AUTO_INCREMENT,
   nom_type VARCHAR(50),
   decouvert BOOLEAN,
   PRIMARY KEY(id_type_compte)
);

CREATE TABLE ef_type_pret (
   id_type_pret INT AUTO_INCREMENT,
   nom_type_pret VARCHAR(50),
   PRIMARY KEY(id_type_pret)
);

CREATE TABLE ef_taux_pret (
   id_taux_pret INT AUTO_INCREMENT,
   taux DECIMAL(15,2),
   date_taux DATETIME,
   id_type_pret INT NOT NULL,
   PRIMARY KEY(id_taux_pret),
   FOREIGN KEY(id_type_pret) REFERENCES ef_type_pret(id_type_pret)
);

ALTER TABLE ef_taux_pret
ADD COLUMN taux_assurance DECIMAL(5,2);

CREATE TABLE ef_mvt_type_pret (
   id_mvt_type_pret INT AUTO_INCREMENT,
   montant_min DECIMAL(15,2),
   montant_max DECIMAL(15,2),
   date_mvt DATETIME,
   id_type_pret INT NOT NULL,
   PRIMARY KEY(id_mvt_type_pret),
   FOREIGN KEY(id_type_pret) REFERENCES ef_type_pret(id_type_pret)
);

CREATE TABLE ef_type_mvt (
   id_type_mvt INT AUTO_INCREMENT,
   nom_type_mvt VARCHAR(50),
   PRIMARY KEY(id_type_mvt)
);

CREATE TABLE ef_etat_pret (
   id_etat_pret INT AUTO_INCREMENT,
   nom_etat VARCHAR(50),
   PRIMARY KEY(id_etat_pret)
);

CREATE TABLE ef_type_transaction (
   id_type_transaction INT AUTO_INCREMENT,
   nom_type_transaction VARCHAR(50),
   PRIMARY KEY(id_type_transaction)
);

CREATE TABLE ef_etablissement_financier (
   id_etablissement_financier INT AUTO_INCREMENT,
   nom_etablissement VARCHAR(50),
   solde_etablissement DECIMAL(15,2),
   PRIMARY KEY(id_etablissement_financier)
);

CREATE TABLE ef_compte (
   id_compte INT AUTO_INCREMENT,
   date_creation DATETIME,
   mot_de_passe VARCHAR(50),
   solde_compte DECIMAL(15,2),
   id_client INT NOT NULL,
   id_type_compte INT NOT NULL,
   PRIMARY KEY(id_compte),
   FOREIGN KEY(id_client) REFERENCES ef_client(id_client),
   FOREIGN KEY(id_type_compte) REFERENCES ef_type_compte(id_type_compte)
);

CREATE TABLE ef_pret (
   id_pret INT AUTO_INCREMENT,
   date_pret DATETIME,
   montant DECIMAL(15,2),
   duree_remboursement INT,
   id_type_pret INT NOT NULL,
   id_compte INT NOT NULL,
   PRIMARY KEY(id_pret),
   FOREIGN KEY(id_type_pret) REFERENCES ef_type_pret(id_type_pret),
   FOREIGN KEY(id_compte) REFERENCES ef_compte(id_compte)
);

CREATE TABLE ef_mvt_solde (
   id_mvt_solde INT AUTO_INCREMENT,
   montant DECIMAL(15,2),
   date_mvt DATETIME,
   id_type_transaction INT NOT NULL,
   id_type_mvt INT NOT NULL,
   id_compte INT NOT NULL,
   PRIMARY KEY(id_mvt_solde),
   FOREIGN KEY(id_type_transaction) REFERENCES ef_type_transaction(id_type_transaction),
   FOREIGN KEY(id_type_mvt) REFERENCES ef_type_mvt(id_type_mvt),
   FOREIGN KEY(id_compte) REFERENCES ef_compte(id_compte)
);

CREATE TABLE ef_type_pret_compte (
   id_type_compte INT,
   id_type_pret INT,
   date_pret_compte DATETIME,
   PRIMARY KEY(id_type_compte, id_type_pret),
   FOREIGN KEY(id_type_compte) REFERENCES ef_type_compte(id_type_compte),
   FOREIGN KEY(id_type_pret) REFERENCES ef_type_pret(id_type_pret)
);

CREATE TABLE ef_pret_etat (
   id_pret INT,
   id_etat_pret INT,
   date_pret_etat DATETIME,
   PRIMARY KEY(id_pret, id_etat_pret),
   FOREIGN KEY(id_pret) REFERENCES ef_pret(id_pret),
   FOREIGN KEY(id_etat_pret) REFERENCES ef_etat_pret(id_etat_pret)
);

