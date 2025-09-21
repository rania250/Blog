# 🔧 Guide d'activation PostgreSQL pour XAMPP

## 📍 ÉTAPES POUR ACTIVER POSTGRESQL :

### 1. Ouvrir le fichier php.ini
- Chemin : `C:\xampp\php\php.ini`
- Ouvrez avec un éditeur de texte (Notepad++, VS Code)

### 2. Chercher et décommenter ces lignes :
Trouvez ces lignes (vers la ligne 900) et retirez le ; au début :

```ini
# AVANT :
;extension=pdo_pgsql
;extension=pgsql

# APRÈS :
extension=pdo_pgsql
extension=pgsql
```

### 3. Sauvegarder et redémarrer Apache
- Sauvegardez le fichier php.ini
- Dans XAMPP Control Panel → Arrêtez Apache
- Redémarrez Apache

### 4. Vérifier l'installation
```bash
php -m | grep -i pdo
```

## 🎯 ALTERNATIVE PLUS SIMPLE : Rester sur MySQL

Si vous préférez éviter les complications PostgreSQL, vous pouvez :

### Option 1 : Utiliser Railway (MySQL + 5$ gratuit)
1. Allez sur railway.app
2. Créez un service MySQL
3. Gardez votre code actuel (pas de changement)

### Option 2 : Utiliser FreeSQLDatabase (MySQL gratuit 5MB)
1. Allez sur freesqldatabase.com
2. Créez une base MySQL gratuite
3. Utilisez votre code existant

## 🤔 QUE RECOMMANDEZ-VOUS ?

1. **Si vous voulez apprendre PostgreSQL** → Suivez les étapes ci-dessus
2. **Si vous voulez du simple et rapide** → Utilisez Railway (MySQL)

Railway est plus simple car :
- Aucune modification de PHP nécessaire
- Votre code fonctionne directement
- Interface très simple
- 5$ gratuit/mois (largement suffisant)