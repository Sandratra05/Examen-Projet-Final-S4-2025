INSERT INTO ef_type_pret (nom_type_pret) VALUES 
('Prêt personnel'),
('Prêt immobilier'),
('Prêt automobile'),
('Prêt étudiant'),
('Crédit renouvelable'),
('Prêt travaux'),
('Prêt professionnel'),
('Microcrédit'),
('Prêt relais'),
('Crédit-bail');

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
('Rejeté'),          -- Prêt refusé
('Débloqué'),        -- Montant versé au client
('En remboursement'), -- Remboursement en cours
('Remboursé'),       -- Prêt intégralement remboursé
('En défaut'),       -- Retards de paiement
('Clôturé');         -- Prêt clôturé (termes respectés)


INSERT INTO ef_taux_pret (taux, date_taux, id_type_pret) VALUES
-- Prêts personnels (type 1) - Taux élevés
(18.50, '2023-01-01 00:00:00', 1),
(17.75, '2023-04-15 00:00:00', 1),
(16.90, '2023-07-20 00:00:00', 1),

-- Crédits renouvelables (type 2) - Taux très élevés
(12.00, '2023-01-10 00:00:00', 2);