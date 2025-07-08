
-- ef_type_transaction (virement, injection de capitale, pret)
INSERT INTO ef_type_transaction (nom_type_transaction) VALUES
('virement'),
('injection de capitale'),
('pret');

-- ef_type_mvt (entree, sortie)
INSERT INTO ef_type_mvt (nom_type_mvt) VALUES
('entree'),
('sortie');


INSERT INTO ef_type_pret (nom_type_pret) VALUES 
('Prêt personnel'),
('Prêt immobilier');

INSERT INTO ef_type_compte (nom_type, decouvert) VALUES 
('Compte Courant', TRUE),
('Compte Épargne', FALSE),
('Compte Jeune', FALSE),
('Compte Professionnel', TRUE);

INSERT INTO ef_client (nom_client, email, coordonnees, CIN, date_naissance) VALUES 
('Jean Dupont', 'jean.dupont@email.com', '12 Rue de Paris, 75001', 'AB123456', '1985-05-15'),
('Marie Martin', 'marie.martin@email.com', '34 Avenue des Champs, 69002', 'CD789012', '1990-08-22'),
('Pierre Durand', 'pierre.durand@email.com', '56 Boulevard Central, 13003', 'EF345678', '1978-11-30'),
('Sophie Lambert', 'sophie.lambert@email.com', '78 Rue Principale, 31000', 'GH901234', '1995-03-10'),
('Thomas Leroy', 'thomas.leroy@email.com', '90 Chemin des Bois, 59000', 'IJ567890', '1982-07-25');

INSERT INTO ef_compte (date_creation, mot_de_passe, solde_compte, id_client, id_type_compte) VALUES 
('2023-01-10 09:15:00', 'mdp123', 1500.50, 1, 1),
('2023-02-15 14:30:00', 'secret456', 3200.75, 2, 2),
('2023-03-20 10:45:00', 'monmotdepasse', 500.00, 3, 1),
('2023-04-05 16:20:00', 'epargne789', 7500.25, 4, 3),
('2023-05-12 11:10:00', 'pro1234', -200.00, 5, 4),
('2023-06-18 13:25:00', 'mdpjeune', 1200.00, 4, 3),
('2023-07-22 08:40:00', 'duranp', 4500.60, 3, 1);

INSERT INTO ef_etat_pret (nom_etat) VALUES 
('En attente'),       -- Prêt en cours de traitement
('Approuvé'),        -- Prêt validé par la banque
('Simule'),          -- Prêt refusé
('Rejete');      -- Prêt clôturé (termes respectés)


INSERT INTO ef_taux_pret (taux, date_taux, id_type_pret,taux_assurance) VALUES
-- Prêts personnels (type 1) - Taux élevés
(18.50, '2023-01-01 00:00:00', 1,3),
(17.75, '2023-04-15 00:00:00', 1,3),
(16.90, '2023-07-20 00:00:00', 1,3),

-- Crédits renouvelables (type 2) - Taux très élevés
(12.00, '2023-01-10 00:00:00', 2,3);

-- ef_etablissement_financier
INSERT INTO ef_etablissement_financier (nom_etablissement, solde_etablissement) VALUES
-- ('Banque Principale', 10000000.00),
('BNI', 5000000.00);

-- ef_client
INSERT INTO ef_client (nom_client, email, coordonnees, CIN, date_naissance) VALUES
('Martin Dupont', 'martin.dupont@email.com', 'Paris', 'AB123456', '1985-05-15'),
('Sophie Lambert', 'sophie.lambert@email.com', 'Lyon', 'CD789012', '1990-11-22'),
('Thomas Moreau', 'thomas.moreau@email.com', 'Marseille', 'EF345678', '1978-03-08'),
('Emma Petit', 'emma.petit@email.com', 'Bordeaux', 'GH901234', '1995-07-30');

-- ef_compte
INSERT INTO ef_compte (date_creation, mot_de_passe, solde_compte, id_client, id_type_compte) VALUES
(NOW(), 'pass123', 2500.00, 1, 1),
(NOW(), 'secret', 15000.50, 2, 2),
(NOW(), 'mdp456', 8500.75, 3, 1),
(NOW(), 'secure', 42000.25, 4, 3);

-- ef_pret
INSERT INTO ef_pret (date_pret, montant, duree_remboursement, id_type_pret, id_compte) VALUES
('2024-03-01 10:00:00', 20000.00, 60, 1, 1),
('2024-02-15 14:30:00', 150000.00, 240, 2, 2),
('2024-01-10 09:15:00', 30000.00, 84, 3, 3);

-- ef_mvt_solde
INSERT INTO ef_mvt_solde (montant, date_mvt, id_type_transaction, id_type_mvt, id_compte) VALUES
(500.00, NOW(), 1, 1, 1),   -- Virement entrant
(200.00, NOW(), 1, 2, 2),   -- Virement sortant
(10000.00, NOW(), 2, 1, 3), -- Injection de capital
(1200.00, NOW(), 3, 2, 4);  -- Prêt (sortie)

-- ef_type_pret_compte
INSERT INTO ef_type_pret_compte (id_type_compte, id_type_pret, date_pret_compte) VALUES
(1, 1, NOW()),
(1, 3, NOW()),
(2, 1, NOW()),
(3, 2, NOW());

-- ef_pret_etat
INSERT INTO ef_pret_etat (id_pret, id_etat_pret, date_pret_etat) VALUES
(1, 2, '2024-03-02 11:00:00'),
(2, 1, '2024-02-16 10:00:00'),
(3, 3, '2024-01-12 09:00:00');


-- INSERT INTO ef_taux_pret (taux, date_taux, id_type_pret, taux_assurance) VALUES
-- (8.50, '2000-07-01 00:00:00', 1, 0.80),
-- (5.20, '2000-07-01 00:00:00', 2, 0.50),
-- (6.75, '2000-07-01 00:00:00', 3, 0.70),
-- (4.00, '2000-07-01 00:00:00', 4, 0.30),
-- (12.00, '2000-07-01 00:00:00', 5, 1.20),
-- (6.20, '2000-07-01 00:00:00', 6, 0.60),
-- (7.80, '2000-07-01 00:00:00', 7, 0.90),
-- (10.00, '2000-07-01 00:00:00', 8, 1.00),
-- (5.50, '2000-07-01 00:00:00', 9, 0.40),
-- (6.00, '2000-07-01 00:00:00', 10, 0.50);



INSERT INTO ef_mvt_type_pret (montant_min, montant_max, date_mvt, id_type_pret) VALUES
(100000.00, 5000000.00, '2023-01-01 00:00:00', 1),  -- Prêt personnel
(2000000.00, 200000000.00, '2023-01-10 00:00:00', 2);  -- Prêt immobilier
-- (500000.00, 40000000.00, '2000-07-01 00:00:00', 3),  -- Prêt automobile
-- (100000.00, 3000000.00, '2000-07-01 00:00:00', 4),  -- Prêt étudiant
-- (50000.00, 1500000.00, '2000-07-01 00:00:00', 5),  -- Crédit renouvelable
-- (200000.00, 10000000.00, '2000-07-01 00:00:00', 6),  -- Prêt travaux
-- (1000000.00, 50000000.00, '2000-07-01 00:00:00', 7),  -- Prêt professionnel
-- (50000.00, 2000000.00, '2000-07-01 00:00:00', 8),  -- Microcrédit
-- (1000000.00, 30000000.00, '2000-07-01 00:00:00', 9),  -- Prêt relais
-- (1000000.00, 25000000.00, '2000-07-01 00:00:00', 10);  -- Crédit-bail
