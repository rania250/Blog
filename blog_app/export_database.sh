#!/bin/bash

echo "🗄️ Export de la base de données MySQL locale..."
echo

# Créer le dossier de sauvegarde
mkdir -p database_backup

# Export de la structure et des données
echo "📊 Export de la structure et des données..."
mysqldump -u root -p --single-transaction --routines --triggers blog > database_backup/blog_complete.sql

# Export des données seulement (pour fixtures)
echo "📋 Export des données uniquement..."
mysqldump -u root -p --no-create-info --single-transaction blog > database_backup/blog_data_only.sql

# Export de la structure seulement
echo "🏗️ Export de la structure uniquement..."
mysqldump -u root -p --no-data blog > database_backup/blog_structure_only.sql

echo
echo "✅ Export terminé ! Fichiers créés dans database_backup/"
echo
echo "Fichiers créés:"
echo "- blog_complete.sql (structure + données)"
echo "- blog_data_only.sql (données uniquement)"
echo "- blog_structure_only.sql (structure uniquement)"
echo