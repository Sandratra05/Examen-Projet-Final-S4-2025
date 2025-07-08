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


-- Insertion des remboursements mensuels pour le prêt ID 1
INSERT INTO ef_remboursement (id_pret, date, montant_payer, ammortisement, interet) VALUES
-- Année 2024
(1, '2024-03-31', 372.86, 297.86, 75.00),
(1, '2024-04-30', 372.86, 298.98, 73.88),
(1, '2024-05-31', 372.86, 300.10, 72.76),
(1, '2024-06-30', 372.86, 301.23, 71.63),
(1, '2024-07-31', 372.86, 302.36, 70.50),
(1, '2024-08-31', 372.86, 303.49, 69.37),
(1, '2024-09-30', 372.86, 304.63, 68.23),
(1, '2024-10-31', 372.86, 305.78, 67.08),
(1, '2024-11-30', 372.86, 306.93, 65.93),
(1, '2024-12-31', 372.86, 308.08, 64.78),

-- Année 2025
(1, '2025-01-31', 372.86, 309.24, 63.62),
(1, '2025-02-28', 372.86, 310.40, 62.46),
(1, '2025-03-31', 372.86, 311.57, 61.29),
(1, '2025-04-30', 372.86, 312.74, 60.12),
(1, '2025-05-31', 372.86, 313.91, 58.95),
(1, '2025-06-30', 372.86, 315.09, 57.77),
(1, '2025-07-31', 372.86, 316.27, 56.59),
(1, '2025-08-31', 372.86, 317.46, 55.40),
(1, '2025-09-30', 372.86, 318.65, 54.21),
(1, '2025-10-31', 372.86, 319.84, 53.02),
(1, '2025-11-30', 372.86, 321.04, 51.82),
(1, '2025-12-31', 372.86, 322.24, 50.62),

-- Année 2026
(1, '2026-01-31', 372.86, 323.45, 49.41),
(1, '2026-02-28', 372.86, 324.66, 48.20),
(1, '2026-03-31', 372.86, 325.87, 46.99),
(1, '2026-04-30', 372.86, 327.09, 45.77),
(1, '2026-05-31', 372.86, 328.31, 44.55),
(1, '2026-06-30', 372.86, 329.54, 43.32),
(1, '2026-07-31', 372.86, 330.77, 42.09),
(1, '2026-08-31', 372.86, 332.00, 40.86),
(1, '2026-09-30', 372.86, 333.24, 39.62),
(1, '2026-10-31', 372.86, 334.48, 38.38),
(1, '2026-11-30', 372.86, 335.73, 37.13),
(1, '2026-12-31', 372.86, 336.98, 35.88),

-- Année 2027
(1, '2027-01-31', 372.86, 338.24, 34.62),
(1, '2027-02-28', 372.86, 339.50, 33.36),
(1, '2027-03-31', 372.86, 340.77, 32.09),
(1, '2027-04-30', 372.86, 342.04, 30.82),
(1, '2027-05-31', 372.86, 343.31, 29.55),
(1, '2027-06-30', 372.86, 344.59, 28.27),
(1, '2027-07-31', 372.86, 345.88, 26.98),
(1, '2027-08-31', 372.86, 347.17, 25.69),
(1, '2027-09-30', 372.86, 348.46, 24.40),
(1, '2027-10-31', 372.86, 349.76, 23.10),
(1, '2027-11-30', 372.86, 351.06, 21.80),
(1, '2027-12-31', 372.86, 352.37, 20.49),

-- Année 2028
(1, '2028-01-31', 372.86, 353.68, 19.18),
(1, '2028-02-29', 372.86, 355.00, 17.86),
(1, '2028-03-31', 372.86, 356.32, 16.54),
(1, '2028-04-30', 372.86, 357.65, 15.21),
(1, '2028-05-31', 372.86, 358.98, 13.88),
(1, '2028-06-30', 372.86, 360.32, 12.54),
(1, '2028-07-31', 372.86, 361.66, 11.20),
(1, '2028-08-31', 372.86, 363.01, 9.85),
(1, '2028-09-30', 372.86, 364.36, 8.50),
(1, '2028-10-31', 372.86, 365.72, 7.14),
(1, '2028-11-30', 372.86, 367.08, 5.78),
(1, '2028-12-31', 372.86, 368.45, 4.41),

-- Année 2029 (derniers mois)
(1, '2029-01-31', 372.86, 369.83, 3.03),
(1, '2029-02-28', 372.86, 371.21, 1.65),
(1, '2029-03-31', 28.14, 28.14, 0.00);  -- Dernier remboursement (solde restant)