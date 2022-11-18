


/* Dropping existing tables */
/* DROP TABLE IF EXISTS Lieux;
DROP TABLE IF EXISTS Playlists; 
DROP TABLE IF EXISTS Est_en_relation;
DROP TABLE IF EXISTS Inclure;
DROP TABLE IF EXISTS Posseder;
DROP TABLE IF EXISTS sont_enregistres;
DROP TABLE IF EXISTS Enregistrer;
DROP TABLE IF EXISTS Faire_partie;
DROP TABLE IF EXISTS
DROP TABLE IF EXISTS Albums_Live;
DROP TABLE IF EXISTS Albums_Compilation;
DROP TABLE IF EXISTS Albums_Studio;
DROP TABLE IF EXISTS Musiciens; 
DROP TABLE IF EXISTS Versions;
DROP TABLE IF EXISTS Albums; 
DROP TABLE IF EXISTS Groupes_de_musique;
DROP TABLE IF EXISTS Chansons;
DROP TABLE IF EXISTS Albums;  
 REFAIRE TOUT CA DANS LE BON ORDRE*/




CREATE TABLE Groupes_de_musique(
   NomG VARCHAR(50),
   DateFormation YEAR,
   DateSeparation YEAR,
   PRIMARY KEY(NomG)
);

CREATE TABLE Chansons(
   IdC INT AUTO_INCREMENT,
   TitreC VARCHAR(50),
   Genre VARCHAR(50),
   DateCreation DATE,
   NomG VARCHAR(50) NOT NULL,
   PRIMARY KEY(IdC),
   FOREIGN KEY(NomG) REFERENCES Groupes_de_musique(NomG)
);

CREATE TABLE Versions(
   IdC INT AUTO_INCREMENT,
   NumeroV INT ,
   DureeV FLOAT UNSIGNED,
   DateV DATE,
   NomFichier VARCHAR(50),
   PRIMARY KEY(IdC, NumeroV),
   FOREIGN KEY(IdC) REFERENCES Chansons(IdC)
);

CREATE TABLE Albums(
   TitreA VARCHAR(50),
   DateSortie DATE,
   NomG VARCHAR(50) NOT NULL,
   Producteur VARCHAR(50),
   PRIMARY KEY(TitreA),
   FOREIGN KEY(NomG) REFERENCES Groupes_de_musique(NomG)
);

CREATE TABLE Musiciens(
   NomScene VARCHAR(50),
   NomM VARCHAR(50),
   PrenomM VARCHAR(50),
   PRIMARY KEY(NomScene)
);

CREATE TABLE Albums_Studio(
   TitreA VARCHAR(50),
   NomInge VARCHAR(50),
   PRIMARY KEY(TitreA),
   FOREIGN KEY(TitreA) REFERENCES Albums(TitreA)
);

CREATE TABLE Albums_Compilation(
   TitreA VARCHAR(50),
   Description VARCHAR(100),
   PRIMARY KEY(TitreA),
   FOREIGN KEY(TitreA) REFERENCES Albums(TitreA)
);

CREATE TABLE Albums_Live(
   TitreA VARCHAR(50),
   PRIMARY KEY(TitreA),
   FOREIGN KEY(TitreA) REFERENCES Albums(TitreA)
);

CREATE TABLE Lieux(
   IdL INT AUTO_INCREMENT,
   NomL VARCHAR(50),
   Latitude DECIMAL(15,2),
   Longitude DECIMAL(15,2),
   PRIMARY KEY(IdL)
);

CREATE TABLE Playlists(
   IdP INT AUTO_INCREMENT,
   TitreP VARCHAR(50),
   DateP DATE,
   PRIMARY KEY(IdP)
);

CREATE TABLE Interpreter(
   NomG VARCHAR(50),
   IdC INT,
   NumeroV INT,
   PRIMARY KEY(NomG, IdC, NumeroV),
   FOREIGN KEY(NomG) REFERENCES Groupes_de_musique(NomG),
   FOREIGN KEY(IdC, NumeroV) REFERENCES Versions(IdC, NumeroV)
);

CREATE TABLE Enregistrer(
   NomG VARCHAR(50),
   TitreA VARCHAR(50),
   PRIMARY KEY(NomG, TitreA),
   FOREIGN KEY(NomG) REFERENCES Groupes_de_musique(NomG),
   FOREIGN KEY(TitreA) REFERENCES Albums(TitreA)
);

CREATE TABLE Participer(
   NomG VARCHAR(50),
   TitreA VARCHAR(50),
   NomScene VARCHAR(50),
   CommentaireP VARCHAR(100),
   PRIMARY KEY(NomG, TitreA, NomScene),
   FOREIGN KEY(NomG) REFERENCES Groupes_de_musique(NomG),
   FOREIGN KEY(TitreA) REFERENCES Albums(TitreA),
   FOREIGN KEY(NomScene) REFERENCES Musiciens(NomScene)
);

CREATE TABLE Faire_partie(
   NomG VARCHAR(50),
   NomScene VARCHAR(50),
   DateDebut DATE,
   DateFin DATE,
   Role VARCHAR(50),
   isMembreFondateur BOOLEAN,
   PRIMARY KEY(NomG, NomScene),
   FOREIGN KEY(NomG) REFERENCES Groupes_de_musique(NomG),
   FOREIGN KEY(NomScene) REFERENCES Musiciens(NomScene)
);

CREATE TABLE sont_enregistres(
   TitreA VARCHAR(50),
   IdL INT,
   PRIMARY KEY(TitreA, IdL),
   FOREIGN KEY(TitreA) REFERENCES Albums_Live(TitreA),
   FOREIGN KEY(IdL) REFERENCES Lieux(IdL)
);

CREATE TABLE Posseder(
   IdC INT,
   NumeroV INT,
   TitreA VARCHAR(50),
   NumeroPiste SMALLINT,
   PRIMARY KEY(IdC, NumeroV, TitreA),
   FOREIGN KEY(IdC, NumeroV) REFERENCES Versions(IdC, NumeroV),
   FOREIGN KEY(TitreA) REFERENCES Albums(TitreA)
);

CREATE TABLE Inclure(
   IdC INT,
   NumeroV INT,
   IdP INT,
   PRIMARY KEY(IdC, NumeroV, IdP),
   FOREIGN KEY(IdC, NumeroV) REFERENCES Versions(IdC, NumeroV),
   FOREIGN KEY(IdP) REFERENCES Playlists(IdP)
);

CREATE TABLE Est_en_relation(
   IdC INT,
   IdC_1 INT,
   TypeRelation VARCHAR(50),
   PRIMARY KEY(IdC, IdC_1),
   FOREIGN KEY(IdC) REFERENCES Chansons(IdC),
   FOREIGN KEY(IdC_1) REFERENCES Chansons(IdC)
);
