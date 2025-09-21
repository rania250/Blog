-- Script SQL pour créer des données de test dans Supabase

-- 1. Créer un utilisateur
INSERT INTO "user" (full_name, email, password, roles, created_at) 
VALUES 
('Admin Jstore', 'admin@jstore.com', '$2y$13$dummy.hash.for.testing', '[]', NOW());

-- 2. Créer des catégories
INSERT INTO category (name, description, image_url, slug, created_at) 
VALUES 
('Technologie', 'Articles sur la technologie', '/images/tech.jpg', 'technologie', NOW()),
('Science', 'Articles scientifiques', '/images/science.jpg', 'science', NOW());

-- 3. Créer des articles (remplacez author_id par l'ID du user créé)
INSERT INTO article (title, slug, content, image_url, author_id, created_at) 
VALUES 
('Premier Article', 'premier-article', 'Contenu du premier article de test avec beaucoup de texte pour remplir...', '/images/article1.jpg', 1, NOW()),
('Deuxième Article', 'deuxieme-article', 'Contenu du deuxième article avec des informations intéressantes...', '/images/article2.jpg', 1, NOW() - INTERVAL '1 day'),
('Troisième Article', 'troisieme-article', 'Un article passionnant sur les nouvelles technologies...', '/images/article3.jpg', 1, NOW() - INTERVAL '2 days'),
('Guide Complet', 'guide-complet', 'Un guide complet pour bien commencer dans le développement...', '/images/guide.jpg', 1, NOW() - INTERVAL '3 days'),
('Article Innovation', 'article-innovation', 'Les dernières innovations dans le monde du web...', '/images/innovation.jpg', 1, NOW() - INTERVAL '4 days');