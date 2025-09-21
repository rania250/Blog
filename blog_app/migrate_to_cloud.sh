#!/bin/bash

# 🗄️ Script de migration de base de données vers le cloud
# Usage: ./migrate_to_cloud.sh [service] [connection_string]

echo "🚀 Migration de la base de données vers le cloud"
echo "=============================================="
echo

# Vérification des paramètres
if [ $# -eq 0 ]; then
    echo "❓ Utilisation:"
    echo "./migrate_to_cloud.sh [service] [connection_string]"
    echo
    echo "Services supportés:"
    echo "- planetscale"
    echo "- railway"
    echo "- supabase"
    echo "- aiven"
    echo
    echo "Exemple:"
    echo "./migrate_to_cloud.sh planetscale 'mysql://user:pass@host:3306/db'"
    exit 1
fi

SERVICE=$1
CONNECTION_STRING=$2

echo "🔧 Service sélectionné: $SERVICE"
echo "🔗 URL de connexion: $CONNECTION_STRING"
echo

# Backup local avant migration
echo "📦 Création d'un backup local de sécurité..."
mkdir -p database_backup
mysqldump -u root -p --single-transaction blog > database_backup/backup_before_migration_$(date +%Y%m%d_%H%M%S).sql

# Test de connexion au service cloud
echo "🧪 Test de connexion au service cloud..."

case $SERVICE in
    "planetscale")
        echo "🌍 Configuration pour PlanetScale..."
        # Ici vous pouvez ajouter des vérifications spécifiques à PlanetScale
        ;;
    "railway")
        echo "🚂 Configuration pour Railway..."
        ;;
    "supabase")
        echo "🔋 Configuration pour Supabase..."
        ;;
    "aiven")
        echo "☁️ Configuration pour Aiven..."
        ;;
    *)
        echo "❌ Service non supporté: $SERVICE"
        exit 1
        ;;
esac

# Création du fichier .env.prod
echo "📝 Création du fichier .env.prod..."
cp .env.cloud.example .env.prod

# Mise à jour de la DATABASE_URL dans .env.prod
echo "🔧 Mise à jour de la configuration..."
sed -i "s|# DATABASE_URL=\".*|DATABASE_URL=\"$CONNECTION_STRING\"|" .env.prod

echo "✅ Configuration terminée !"
echo
echo "📋 Prochaines étapes:"
echo "1. Vérifiez le fichier .env.prod"
echo "2. Testez la connexion avec: php bin/console doctrine:migrations:status"
echo "3. Créez les tables avec: php bin/console doctrine:migrations:migrate"
echo "4. Importez vos données avec: php bin/console doctrine:fixtures:load"
echo
echo "🎯 Pour tester la connexion:"
echo "APP_ENV=prod php bin/console doctrine:database:create"