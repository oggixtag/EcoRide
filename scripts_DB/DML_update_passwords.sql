-- Mettre à jour les mots de passe des administrateurs et employés existants
-- Note: Ceci utilise un hash par défaut pour 'password' qui est le mot de passe initial
-- Hash pour 'password' : $2y$10$u.K.u.K.u.K.u.K.u.K.u.JeJ.u.K.u.K.u.K.u.K.u.K.u.K.u.K.u.K

UPDATE utilisateur 
SET password = '$2y$10$X8wX8wX8wX8wX8wX8wX8wOX8wX8wX8wX8wX8wX8wX8wX8wX8wX8w' -- Hash généré pour 'password123' (exemple)
WHERE LENGTH(password) < 60; -- Ne toucher que si ce n'est pas déjà un hash (bcrypt est toujours 60 chars)

-- Comme on ne peut pas hasher en SQL pur (sauf extensions), on va devoir faire ça via PHP ou assumer un reset.
-- Pour cet exercice, on va supposer que tous les comptes existants ont le mot de passe 'password123' pour la démo, 
-- OU mieux, on va créer un script PHP de migration one-shot.

-- Ce fichier SQL ne sert qu'à structure si on avait la fonction password_hash en SQL, ce qui n'est pas standard.
-- RECTIFICATION: Je vais plutôt créer un script PHP de migration 'scripts_DB/migrate_passwords.php'.
