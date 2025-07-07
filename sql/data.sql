-- ef_type_transaction (virement, injection de capitale, pret)
INSERT INTO ef_type_transaction (nom_type_transaction) VALUES
('virement'),
('injection de capitale'),
('pret');

-- ef_type_mvt (entree, sortie)
INSERT INTO ef_type_mvt (nom_type_mvt) VALUES
('entree'),
('sortie');

-- ef_etat_pret
INSERT INTO ef_etat_pret (nom_etat) VALUES
('en attente'),
('approuvé'),
('refusé'),
('remboursé');

-- ef_type_compte
INSERT INTO ef_type_compte (nom_type, decouvert) VALUES
('Compte Courant', 1),
('Compte Épargne', 0),
('Compte Entreprise', 1);

-- ef_type_pret
INSERT INTO ef_type_pret (nom_type_pret) VALUES
('Prêt Personnel'),
('Prêt Immobilier'),
('Prêt Automobile');

-- ef_taux_pret
INSERT INTO ef_taux_pret (taux, date_taux, id_type_pret) VALUES
(4.5, '2024-01-01 00:00:00', 1),
(2.8, '2024-01-01 00:00:00', 2),
(3.2, '2024-01-01 00:00:00', 3);

-- ef_mvt_type_pret
INSERT INTO ef_mvt_type_pret (montant_min, montant_max, date_mvt, id_type_pret) VALUES
(1000, 50000, '2024-01-01 00:00:00', 1),
(50000, 500000, '2024-01-01 00:00:00', 2),
(10000, 100000, '2024-01-01 00:00:00', 3);

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